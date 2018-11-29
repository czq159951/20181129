<?php
namespace shangtao\weapp\model;
use think\Db;
/**
 * 购物车业务处理类
 */

class Carts extends Base{
	protected $pk = 'cartId';
	/**
	 * 加入购物车
	 */
	public function addCart(){
		$userId = $this->getUserId();
		$goodsId = (int)input('post.goodsId');
		$goodsSpecId = (int)input('post.goodsSpecId');
		$cartNum = (int)input('post.buyNum',1);
		$cartNum = ($cartNum>0)?$cartNum:1;
		$type = (int)input('post.types');
		if($userId==0)return jsonReturn('加入购物车失败，请先登录');
		//验证传过来的商品是否合法
		$chk = $this->checkGoodsSaleSpec($goodsId,$goodsSpecId);
		if($chk['status']==-1)return $chk;
		//检测库存是否足够
		if($chk['data']['stock']<$cartNum)return jsonReturn("加入购物车失败，商品库存不足", -1);
		//添加实物商品
		if($chk['data']['goodsType']==0){
			$goodsSpecId = $chk['data']['goodsSpecId'];
			$goods = $this->where(['userId'=>$userId,'goodsId'=>$goodsId,'goodsSpecId'=>$goodsSpecId])->select()->toArray();
			if(empty($goods)){
				$data = array();
				$data['userId'] = $userId;
				$data['goodsId'] = $goodsId;
				$data['goodsSpecId'] = $goodsSpecId;
				$data['isCheck'] = 1;
				$data['cartNum'] = $cartNum;
				$rs = $this->save($data);
			}else{
				$rs = $this->where([['userId','=',$userId],['goodsId','=',$goodsId],['goodsSpecId','=',$goodsSpecId]])->setInc('cartNum',$cartNum);
			}
			if(false !==$rs){
				if($type==1){
					$cartId = $this->where(['userId'=>$userId,'goodsId'=>$goodsId,'goodsSpecId'=>$goodsSpecId])->value('cartId');
					$this->where('cartId','<>',$cartId)->where('userId','=',$userId)->setField('isCheck',0);
				}
				return jsonReturn("添加成功", 1);
			}
		}else{
			//非实物商品
			$carts = [];
			$carts['goodsId'] = $goodsId;
			$carts['cartNum'] = $cartNum;
			session('TMP_CARTS',$carts);
			return jsonReturn("添加成功", 1,['forward'=>'quickSettlement']);
		}
		return jsonReturn("加入购物车失败", -1);
	}
	/**
	 * 验证商品是否合法
	 */
	public function checkGoodsSaleSpec($goodsId,$goodsSpecId){
		$goods = model('Goods')->where(['goodsStatus'=>1,'dataFlag'=>1,'isSale'=>1,'goodsId'=>$goodsId])->field('goodsId,isSpec,goodsStock,goodsType')->find();
		if(empty($goods))return WSTReturn("添加失败，无效的商品信息", -1);
		$goodsStock = (int)$goods['goodsStock'];
		//有规格的话查询规格是否正确
		if($goods['isSpec']==1){
			$specs = Db::name('goods_specs')->where(['goodsId'=>$goodsId,'dataFlag'=>1])->field('id,isDefault,specStock')->select();
			if(count($specs)==0){
				return WSTReturn("添加失败，无效的商品信息", -1);
			}
			$defaultGoodsSpecId = 0;
			$defaultGoodsSpecStock = 0;
			$isFindSpecId = false;
			foreach ($specs as $key => $v){
				if($v['isDefault']==1){
					$defaultGoodsSpecId = $v['id'];
					$defaultGoodsSpecStock = (int)$v['specStock'];
				}
				if($v['id']==$goodsSpecId){
					$goodsStock = (int)$v['specStock'];
					$isFindSpecId = true;
				}
			}
			
			if($defaultGoodsSpecId==0)return WSTReturn("添加失败，无效的商品信息", -1);//有规格却找不到规格的话就报错
			if(!$isFindSpecId)return WSTReturn("", 1,['goodsSpecId'=>$defaultGoodsSpecId,'stock'=>$defaultGoodsSpecStock,'goodsType'=>$goods['goodsType']]);//如果没有找到的话就取默认的规格
			return WSTReturn("", 1,['goodsSpecId'=>$goodsSpecId,'stock'=>$goodsStock,'goodsType'=>$goods['goodsType']]);
		}else{
			return WSTReturn("", 1,['goodsSpecId'=>0,'stock'=>$goodsStock,'goodsType'=>$goods['goodsType']]);
		}
	}
	/**
	 * 删除购物车里的商品
	 */
	public function delCart(){
		$userId = $this->getUserId();
		$id = input('post.id');
		$id = explode(',',WSTFormatIn(",",$id));
		$id = array_filter($id);
		$this->where("userId = ".$userId." and cartId in(".implode(',', $id).")")->delete();
		return jsonReturn("删除成功", 1);
	}
	/**
	 * 取消购物车商品选中状态
	 */
	public function disChkGoods($goodsId,$goodsSpecId,$userId){
		$this->save(['isCheck'=>0],['userId'=>$userId,'goodsId'=>$goodsId,'goodsSpecId'=>$goodsSpecId]);
	}

	/**
	 * 获取session中购物车列表
	 */
	public function getQuickCarts(){
		// 根据传递过来的tokenId获取用户Id
		$userId = $this->getUserId();
		$tmp_carts = session('TMP_CARTS');
		$where = [];
		$where['goodsId'] = $tmp_carts['goodsId'];
		$rs = Db::name('goods')->alias('g')
		           ->join('shops s','s.shopId=g.shopId','left')
		           ->where($where)
		           ->field('s.userId,s.shopId,s.shopName,g.goodsId,g.goodsName,g.shopPrice,g.goodsStock,g.goodsImg,g.goodsCatId')
		           ->find();
		if(empty($rs))return ['carts'=>[],'goodsTotalMoney'=>0,'goodsTotalNum'=>0]; 
		$rs['cartNum'] = $tmp_carts['cartNum'];
		$carts = [];
		$goodsTotalNum = 0;
		$goodsTotalMoney = 0;
		if(!isset($carts['goodsMoney']))$carts['goodsMoney'] = 0;
		$carts['shopId'] = $rs['shopId'];
		$carts['shopName'] = $rs['shopName'];
		$carts['userId'] = $rs['userId'];
		//判断能否购买，预设allowBuy值为10，为将来的各种情况预留10个情况值，从0到9
		$rs['allowBuy'] = 10;
		if($rs['goodsStock']<0){
			$rs['allowBuy'] = 0;//库存不足
		}else if($rs['goodsStock']<$tmp_carts['cartNum']){
			$rs['allowBuy'] = 1;//库存比购买数小
		}
		$carts['goodsMoney'] = $carts['goodsMoney'] + $rs['shopPrice'] * $rs['cartNum'];
		$goodsTotalMoney = $goodsTotalMoney + $rs['shopPrice'] * $rs['cartNum'];
		$rs['specNames'] = [];
		unset($rs['shopName']);
		$carts['goods'] = $rs;
		return ['carts'=>$carts,'goodsTotalMoney'=>$goodsTotalMoney,'goodsTotalNum'=>$goodsTotalNum]; 
	}
	
	/**
	 * 获取购物车商品列表
	 */
	public function getCartInfo($isSettlement = false){
		$userId = $this->getUserId();
		$where = [];
		$where['c.userId'] = $userId;
		if($isSettlement)$where['c.isCheck'] = 1;
		$rs = $this->alias('c')->join('__GOODS__ g','c.goodsId=g.goodsId','inner')
		           ->join('__GOODS_SPECS__ gs','c.goodsSpecId=gs.id','left')
		           ->where($where)
		           ->field('c.goodsSpecId,c.cartId,g.goodsId,g.goodsName,g.shopPrice,g.goodsStock,g.isSpec,gs.specPrice,gs.specStock,g.goodsImg,c.isCheck,gs.specIds,c.cartNum')
		           ->select();
		$goodsIds = []; 
		$goodsTotalMoney = 0;
		$goodsTotalNum = 0;
		foreach ($rs as $key =>$v){
			if(!in_array($v['goodsId'],$goodsIds))$goodsIds[] = $v['goodsId'];
			$goodsTotalMoney = $goodsTotalMoney + $v['shopPrice'] * $v['cartNum'];
			$rs[$key]['goodsImg'] = WSTImg($v['goodsImg']);
		}
	    //加载规格值
		if(count($goodsIds)>0){
		    $specs = DB::name('spec_items')->alias('s')->join('__SPEC_CATS__ sc','s.catId=sc.catId','left')
		        ->where([['s.goodsId','in',$goodsIds],['s.dataFlag','=',1]])->field('itemId,itemName')->select();
		    if(count($specs)>0){ 
		    	$specMap = [];
		    	foreach ($specs as $key =>$v){
		    		$specMap[$v['itemId']] = $v;
		    	}
			    foreach ($rs as $key =>$v){
			    	$strName = [];
			    	if($v['specIds']!=''){
			    		$str = explode(':',$v['specIds']);
			    		foreach ($str as $vv){
			    			if(isset($specMap[$vv]))$strName[] = $specMap[$vv]['itemName'];
			    		}
			    	}
			    	$rs[$key]['specNames'] = $strName;
			    }
		    }
		}
		$goodsTotalNum = count($rs);
		return ['list'=>$rs,'goodsTotalMoney'=>sprintf("%.2f", $goodsTotalMoney),'goodsTotalNum'=>$goodsTotalNum];
	}
	
	/**
	 * 修改购物车商品状态
	 */
	public function changeCartGoods(){
		$isCheck = Input('post.isCheck/d',-1);
		$buyNum = Input('post.buyNum/d',1);
		if($buyNum<1)$buyNum = 1;
		$id = Input('post.id/d');
		
		$userId = $this->getUserId();
		$data = [];
		if($isCheck!=-1)$data['isCheck'] = $isCheck;
		$data['cartNum'] = $buyNum;
		$this->where(['userId'=>$userId,'cartId'=>$id])->update($data);
		return jsonReturn("操作成功", 1);
	}
	/**
	* 批量修改购物车选中状态
	*/
	public function batchSetIsCheck(){
		$id = Input('post.id');
		// 转数组
		$ids = explode(',',$id);
		$isCheck = (int)Input('post.isCheck');
		$userId = $this->getUserId();
		$data['isCheck'] = $isCheck;
		$where = [];
		$where[] = ['userId','=',$userId];
		$where[] = ['cartId','in',$id];
		$this->where($where)->update($data);
		return jsonReturn("操作成功", 1);
	}

	/**
	* 获取购物车总价格、及数量
	*/
	public function getCartTotalMoney(){
		$userId = $this->getUserId();
		$where = [];
		$where['c.userId'] = $userId;
		$rs = $this->alias('c')->join('__GOODS__ g','c.goodsId=g.goodsId','inner')
		           ->join('__SHOPS__ s','s.shopId=g.shopId','left')
		           ->join('__GOODS_SPECS__ gs','c.goodsSpecId=gs.id','left')
		           ->where($where)
		           ->field('c.goodsSpecId,c.cartId,s.userId,s.shopId,s.shopName,g.goodsId,s.shopQQ,shopWangWang,g.goodsName,g.shopPrice,g.goodsStock,g.isSpec,gs.specPrice,gs.specStock,g.goodsImg,c.isCheck,gs.specIds,c.cartNum,g.goodsCatId')
		           ->select();
		$goodsTotalNum = 0;
		$goodsTotalMoney = 0;
		foreach ($rs as $key =>$v){
			if($v['isCheck']==1){
				// 是否有规格值
				$_price = ($v['specPrice']>0)?$v['specPrice']:$v['shopPrice'];
				$goodsTotalMoney = $goodsTotalMoney + $_price * $v['cartNum'];
				$goodsTotalNum++;
			}
		}
		return ['goodsTotalMoney'=>$goodsTotalMoney,'goodsTotalNum'=>$goodsTotalNum];     
	}
	/**
	 * 获取购物车数量
	 */
	function cartNum(){
		$userId = $this->getUserId();
		$cartNum = $this->where(['userId'=>$userId])->field('cartId')->select();
		$count = count($cartNum);
		return $count;
	}
}
