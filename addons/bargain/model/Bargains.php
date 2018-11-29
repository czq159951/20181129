<?php
namespace addons\bargain\model;
use think\addons\BaseModel as Base;
use shangtao\common\model\GoodsCats;
use think\Db;
/**
 * 全民砍价活动插件
 */
class Bargains extends Base{
	protected $pk = 'bargainId';
	/***
     * 安装插件
     */
    public function install(){
    	Db::startTrans();
		try{
			$hooks = ['wechatDocumentUserIndexTools'];
			$this->bindHoods("Bargain", $hooks);
			//管理员后台
			$rs = Db::name('menus')->insert(["parentId"=>93,"menuName"=>"全民砍价","menuSort"=>1,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"bargain"]);
			if($rs!==false){
				$datas = [];
				$parentId = Db::name('menus')->getLastInsID();
				$datas[] = ["menuId"=>$parentId,"privilegeCode"=>"BARGAIN_QMKJ_00","privilegeName"=>"查看全民砍价","isMenuPrivilege"=>1,"privilegeUrl"=>"/addon/bargain-admin-index","otherPrivilegeUrl"=>"/addon/bargain-admin-pageQuery,/addon/bargain-admin-joins,/addon/bargain-admin-pageyByJoins,/addon/bargain-admin-showHelps,/addon/bargain-pageByHelps","dataFlag"=>1,"isEnable"=>1];
				$datas[] = ["menuId"=>$parentId,"privilegeCode"=>"BARGAIN_QMKJ_04","privilegeName"=>"砍价商品操作","isMenuPrivilege"=>0,"privilegeUrl"=>"/addon/bargain-admin-allow","otherPrivilegeUrl"=>"/addon/bargain-admin-illegal","dataFlag"=>1,"isEnable"=>1];
				$datas[] = ["menuId"=>$parentId,"privilegeCode"=>"BARGAIN_QMKJ_03","privilegeName"=>"删除砍价商品","isMenuPrivilege"=>0,"privilegeUrl"=>"/addon/bargain-admin-del","otherPrivilegeUrl"=>"","dataFlag"=>1,"isEnable"=>1];
				Db::name('privileges')->insertAll($datas);
			}
			
			$now = date("Y-m-d H:i:s");
			//商家中心
			Db::name('home_menus')->insert(["parentId"=>77,"menuName"=>"全民砍价","menuUrl"=>"addon/bargain-shops-index","menuOtherUrl"=>"addon/bargain-shops-pageQuery,addon/bargain-shops-toEdit,addon/bargain-shops-add,addon/bargain-shops-edit,addon/bargain-shops-del,addon/bargain-shops-searchGoods,addon/bargain-shops-joins,addon/bargain-shops-pageByJoins,addon/bargain-shops-showHelps,addon/bargain-shops-pageByHelps,addon/bargain-shops-orders,addon/bargain-shops-pageByOrders","menuType"=>1,"isShow"=>1,"menuSort"=>1,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"bargain"]);
			installSql("bargain");
			$this->addMobileBtn();
			Db::commit();
			return true;
		}catch (\Exception $e) {
	 		Db::rollback();
	  		return false;
	   	}
    }

	/**
	 * 删除菜单
	 */
	public function uninstall(){
		Db::startTrans();
		try{
			$hooks = ['wechatDocumentUserIndexTools'];
			$this->unbindHoods("Bargain", $hooks);
			Db::name('menus')->where("menuMark",'=',"bargain")->delete();
			Db::name('home_menus')->where("menuMark",'=',"bargain")->delete();
			Db::name('privileges')->where("privilegeCode","like","BARGAIN_%")->delete();
            //删除微信参数数据
			$tplMsgIds = Db::name('template_msgs')->where([['tplCode','in',explode(',','BARGAIN_GOODS_ALLOW,BARGAIN_GOODS_REJECT,WX_BARGAIN_GOODS_ALLOW,WX_BARGAIN_GOODS_REJECT,BARGAIN_USER_RESULT,BARGAIN_SHOP_RESULT,WX_BARGAIN_USER_RESULT,WX_BARGAIN_SHOP_RESULT')]])
			  ->column('id');
			if((int)WSTConf('CONF.wxenabled')==1)Db::name('wx_template_params')->where('parentId','in',$tplMsgIds)->delete();
			uninstallSql("bargain");//传入插件名
			$this->delMobileBtn();
			Db::commit();
			return true;
		}catch (\Exception $e) {
	 		Db::rollback();
	  		return false;
	   	}
	}

	public function addMobileBtn(){
		$data = array();
		$data["btnName"] = "全民砍价";
		$data["btnSrc"] = 1;
		$data["btnUrl"] = "/addon/bargain-goods-wxlists";
		$data["btnImg"] = "addons/bargain/view/wechat/index/img/bargain.png";
		$data["addonsName"] = "Bargain";
		$data["btnSort"] = 7;
		Db::name('mobile_btns')->insert($data);
	}
	
	public function delMobileBtn(){
		Db::name('mobile_btns')->where(["addonsName"=>"Bargain"])->delete();
	}
	
	/**
	 * 菜单显示隐藏
	 */
	public function toggleShow($isShow = 1){
		Db::startTrans();
		try{
			Db::name('menus')->where(["menuMark"=>"bargain"])->update(["isShow"=>$isShow]);
			Db::name('home_menus')->where(["menuMark"=>"bargain"])->update(["isShow"=>$isShow]);
			if($isShow==1){
				$this->addMobileBtn();
			}else{
				$this->delMobileBtn();
			}
			Db::commit();
			return true;
		}catch (\Exception $e) {
			Db::rollback();
			return false;
		}
	}
    
	/**
	 * 获取砍价列表
	 */
	public function pageQuery(){
		$goodsCatId = (int)input('catId');
		$goodsName = input('goodsName');
		$areaId = (int)input('areaId');
		$where = [];
		if($goodsCatId>0){
			$gc = new GoodsCats();
			$goodsCatIds = $gc->getParentIs($goodsCatId);
			$where[] = ['goodsCatIdPath','like',implode('_',$goodsCatIds).'_%'];
		}
		$where[] = ['b.endTime','>=',date('Y-m-d H:i:s')];
		if($goodsName!='')$where[] = ['goodsName','like','%'.$goodsName.'%'];
		$page = Db::name('bargains')->alias('b')->join('__GOODS__ g','b.goodsId=g.goodsId','inner')
		->where('g.dataFlag=1 and g.isSale=1 and g.goodsStatus=1 and b.dataFlag=1 and b.bargainStatus=1')
		->where($where)
		->field('g.goodsName,g.goodsImg,g.marketPrice,b.*')
		->order('b.updateTime desc,b.startTime asc')
		->paginate(input('pagesize/d'))->toArray();
		if(count($page)>0){
			$time = time();
			foreach($page['data'] as $key =>$v){
				$page['data'][$key]['goodsImg'] = WSTImg($v['goodsImg']);
				if(strtotime($v['startTime'])<=$time && strtotime($v['endTime'])>=$time){
					$page['data'][$key]['status'] = 1;
				}else if(strtotime($v['startTime'])>$time){
					$page['data'][$key]['status'] = 0;
				}else{
					$page['data'][$key]['status'] = -1;
				}
			}
		}
		return $page;
	}
	/**
	 * 获取砍价详情
	 */
	public function getBySale($bargainId){
		$key = input('key');
		$where = ['dataFlag'=>1,'bargainId'=>$bargainId];
		$gu = $this->where($where)->find();
		$viKey = WSTShopEncrypt($gu['shopId']);
		if($key!=''){
			if($viKey!=$key && $gu['bargainStatus']!=1)return [];
		}else{
			if($gu['bargainStatus']!=1)return [];
		}
		$goodsId = $gu['goodsId'];
		if(empty($gu))return [];
		$gu = $gu->toArray();
		$rs = Db::name('goods')->where(['goodsId'=>$goodsId,'dataFlag'=>1])->find();
		if(!empty($rs)){
			Db::name('goods')->where('goodsId',$goodsId)->setInc('visitNum',1);
			$rs = array_merge($rs,$gu);
			$time = time();
			if(strtotime($rs['startTime'])<=$time && strtotime($rs['endTime'])>=$time){
				$rs['status'] = 1;
			}else if(strtotime($rs['startTime'])>$time){
				$rs['status'] = 0;
			}else{
				$rs['status'] = -1;
			}
			$rs['read'] = false;
			//判断是否可以公开查看
			if($rs['isSale']==0 || $rs['goodsStatus']==0 )return [];
			if($key!='')$rs['read'] = true;
			//获取店铺信息
			$rs['shop'] = model('common/shops')->getBriefShop((int)$rs['shopId']);
	
			if(empty($rs['shop']))return [];
			$goodsCats = Db::name('cat_shops')->alias('cs')->join('__GOODS_CATS__ gc','cs.catId=gc.catId and gc.dataFlag=1','left')->join('__SHOPS__ s','s.shopId = cs.shopId','left')
			->where('cs.shopId',$rs['shopId'])->field('cs.shopId,s.shopTel,gc.catId,gc.catName')->select();
			$rs['shop']['catId'] = $goodsCats[0]['catId'];
			$rs['shop']['shopTel'] = $goodsCats[0]['shopTel'];
			$cat = [];
			foreach ($goodsCats as $v){
				$cat[] = $v['catName'];
			}
			$rs['shop']['cat'] = implode('，',$cat);
	
			$gallery = [];
			$gallery[] = $rs['goodsImg'];
			if($rs['gallery']!=''){
				$tmp = explode(',',$rs['gallery']);
				$gallery = array_merge($gallery,$tmp);
			}
			$rs['gallery'] = $gallery;
			if($rs['isSpec']==1){
				//获取销售规格
				$sales = Db::name('goods_specs')->where(['goodsId'=>$goodsId,'isDefault'=>1])->field('id,isDefault,productNo,specIds,marketPrice,specPrice,specStock')->find();
				$specIds = [];
				if(!empty($sales)){
					$str = explode(':',$sales['specIds']);
					foreach ($str as $skey => $sv) {
						if(!in_array($sv,$specIds))$specIds[] = $sv;
					}
					sort($str);
					unset($sales['specIds']);
					$rs['saleSpec'][implode(':',$str)] = $sales;
				}
				//获取默认规格值
				$specs = Db::name('spec_cats')->alias('gc')
				->join('__SPEC_ITEMS__ sit','gc.catId=sit.catId','inner')
				->where(['sit.goodsId'=>$goodsId,'gc.isShow'=>1,'sit.dataFlag'=>1])
				->field('gc.isAllowImg,gc.catName,sit.catId,sit.itemId,sit.itemName,sit.itemImg')
				->order('gc.isAllowImg desc,gc.catSort asc,gc.catId asc')->select();
				foreach ($specs as $key =>$v){
					if(in_array($v['itemId'],$specIds)){
						$rs['spec'][$v['catId']]['name'] = $v['catName'];
						$rs['spec'][$v['catId']]['list'][] = $v;
					}
				}
	
			}
			//获取商品属性
			$rs['attrs'] = Db::name('attributes')->alias('a')->join('goods_attributes ga','a.attrId=ga.attrId','inner')
			->where(['a.isShow'=>1,'dataFlag'=>1,'goodsId'=>$goodsId])->field('a.attrName,ga.attrVal')
			->order('attrSort asc')->select();
			//获取商品评分
			$rs['scores'] = Db::name('goods_scores')->where('goodsId',$goodsId)->field('totalScore,totalUsers')->find();
			$rs['scores']['totalScores'] = ($rs['scores']['totalScore']==0)?5:WSTScore($rs['scores']['totalScore'],$rs['scores']['totalUsers'],5,0,3);
			WSTUnset($rs, 'totalUsers');
			//关注
			$f = model('common/Favorites');
			$rs['favShop'] = $f->checkFavorite($rs['shopId'],1);
			$rs['favGood'] = $f->checkFavorite($goodsId,0);
			$conf=$this->getConf('Bargain');
			//获取砍价规则
			$article = Db::name('articles')->field('articleContent')->where(['isShow'=>1,'dataFlag'=>1,'articleId'=>(int)$conf['bargainArticleId']])->find();
			$rs['article'] = $article['articleContent'];
			//分享标题
			$rs['shareExplain'] = $conf['bargainShareExplain'];
		}
		return $rs;
	}
	/**
	 * 验证商品是否合法
	 */
	public function checkGoodsSaleSpec($bargainId){
		$userId = (int)session('WST_USER.userId');
		$rs = $this->checkBargain($userId,$bargainId);
		if($rs['orderId']>0)return WSTReturn("添加失败，你已经购买过了", -1);
		$goods = $this->alias('b')->join('__GOODS__ g','b.goodsId=g.goodsId','inner')
		->where(['g.goodsStatus'=>1,'g.dataFlag'=>1,'g.isSale'=>1,'b.dataFlag'=>1,'b.bargainId'=>$bargainId,'b.bargainStatus'=>1])
		->field('g.goodsId,isSpec,goodsType,b.goodsStock,b.orderNum,b.startTime,b.endTime')
		->find();
		if(empty($goods))return WSTReturn("添加失败，无效的商品信息", -1);
		//判断团购是否过期
		$time = time();
		if(strtotime($goods['startTime']) > $time)return WSTReturn('对不起，砍价活动尚未开始');
		if(strtotime($goods['endTime']) < $time)return WSTReturn('对不起，您来晚了，砍价活动已结束');
		$goodsId = $goods['goodsId'];
		$goodsStock = (int)$goods['goodsStock']-(int)$goods['orderNum'];
		//有规格的话查询规格是否正确
		if($goods['isSpec']==1){
			$specs = Db::name('goods_specs')->where(['goodsId'=>$goodsId,'dataFlag'=>1])->field('id,isDefault')->select();
			if(count($specs)==0){
				return WSTReturn("添加失败，无效的商品信息", -1);
			}
			$goodsSpecId = 0;
			foreach ($specs as $key => $v){
				if($v['isDefault']==1){
					$goodsSpecId = $v['id'];
					$isFindSpecId = true;
				}
			}
				
			if($goodsSpecId==0)return WSTReturn("添加失败，无效的商品信息", -1);//有规格却找不到规格的话就报错
			return WSTReturn("", 1,['goodsId'=>$goods['goodsId'],'goodsSpecId'=>$goodsSpecId,'stock'=>$goodsStock,'goodsType'=>$goods['goodsType']]);
		}else{
			return WSTReturn("", 1,['goodsId'=>$goods['goodsId'],'goodsSpecId'=>0,'stock'=>$goodsStock,'goodsType'=>$goods['goodsType']]);
		}
	}
	/**
	 * 砍价时间
	 */
	function checkTime($bargainId){
		$rs = $this->get(['bargainId'=>$bargainId]);
		$time = time();
		if(strtotime($rs['startTime'])<=$time && strtotime($rs['endTime'])>=$time){
			$rs = 1;
		}else if(strtotime($rs['startTime'])>$time){
			$rs = 0;
		}else{
			$rs = -1;
		}
		return $rs;
	}
	/**
	 * 是否已砍价
	 */
	function checkBargain($userId,$bargainId,$openId=''){
		if(!$openId){
			$rs = Db::name('bargain_users')->where(['userId'=>$userId,'bargainId'=>$bargainId])->find();
		}else{
			$rs = Db::name('bargain_users')->alias('bu')->join('__BARGAIN_HELPS__ bh','bh.bargainJoinId=bu.id','inner')
			->where(['bu.userId'=>$userId,'bu.bargainId'=>$bargainId,'bh.openId'=>$openId])
			->find();
		}
		return $rs;
	}
	/**
	 * 砍价
	 * total: 价格
	 * count: 数量
	 * type: 0 随机,1 平均
	 */
	function reckonPrice($total=0, $count=1, $type=0){
		if($type==0){
			$num = 0.01;
			if($total>9000)$num = 0.1;
			if($total>90000)$num = 1;
			if($total>900000)$num = 10;
			$input = range(0.01, $total, $num);
			if($count>1 && $total>0){
				$rand_keys = (array) array_rand($input, $count-1);
				$last    = 0;
				foreach($rand_keys as $i=>$key){
					$current  = $input[$key]-$last;
					$items[]  = $current;
					$last    = $input[$key];
				}
			}else{
				$items[] = $total;
			}
			$items[] = $total-array_sum($items);
		}else{
			if($count>1 && $total>0){
				$avg      = number_format($total/$count, 2);
				$i       = 0;
				while($i<$count){
					$items[]  = $i<$count-1?$avg:($total-array_sum($items));
					$i++;
				}
			}else{
				$items[] = $total;
			}
		}

		return $items;
	}
	/**
	 * 第一刀
	 */
	function firstKnife(){
		$userId = (int)session('WST_USER.userId');
		$bargainId = (int)input('id');
		$time = $this->checkTime($bargainId);
		if($time==0)return WSTReturn('砍价失败，活动还未开始');
		if($time==-1)return WSTReturn('砍价失败，活动已结束');
		$rs = $this->checkBargain($userId,$bargainId);
		if(empty($rs)){
			$bargain = $this->get(['bargainId'=>$bargainId]);
			//生成价格
			$price = $this->reckonPrice($bargain['startPrice']-$bargain['floorPrice'],$bargain['minusNum'],$bargain['minusType']);
			$price = $price[0];
			$currPrice = $bargain['startPrice'] - $price;
			//修改当前参与人数
			$bargain->joinNum = $bargain->joinNum + 1;
			$bargain->save();
			//添加砍价
			$data = [];
			$data['userId'] = $userId;
			$data['bargainId'] = $bargainId;
			$data['currPrice'] = $currPrice;
			$data['createTime'] = date('Y-m-d H:i:s');
			$data['helpNum'] = 0;
			$result = Db::name('bargain_users')->insert($data);
			if(false !== $result){
				$data = [];
				$data['bargainJoinId'] = Db::name('bargain_users')->getLastInsID();
				$data['openId'] = session('WST_USER.wxOpenId');
				$data['userName'] = session('WST_USER.userName')?session('WST_USER.userName'):session('WST_USER.loginName');
				$data['userPhoto'] = session('WST_USER.userPhoto');
				$data['bargainId'] = $bargainId;
				$data['minusMoney'] = $price;
				$data['createTime'] = date('Y-m-d H:i:s');
				$resultHelps = Db::name('bargain_helps')->insert($data);
				return WSTReturn('成功砍价',1,$price);
			}
		}
		return WSTReturn('你已经砍过，可邀请亲友帮忙');
	}
	/**
	 * 补刀
	 */
	function addKnife(){
		$userId = (int)session('WST_USER.userId');
		$bargainId = (int)input('id');
		$signType = input('signType',0);
		$openId = input('openId');
		$userName = input('userName');
		$userPhoto = input('userPhoto');
		$time = $this->checkTime($bargainId);
		if($time==0)return WSTReturn('砍价失败，活动还未开始');
		if($time==-1)return WSTReturn('砍价失败，活动已结束');
		$bargainUserId = (int)base64_decode(input('bargainUserId'));
		if($signType==0)return WSTReturn('请关注公众号，参与活动');
		if(!$openId)return WSTReturn('砍价失败');
		$rs = $this->checkBargain($bargainUserId,$bargainId,$openId);
		$userBargain = $this->checkBargain($bargainUserId,$bargainId);
		if($userBargain['orderId']>0 && $userBargain)return WSTReturn('不能再砍价了');
		if(empty($userBargain) && $userId!=$bargainUserId)return WSTReturn('该用户没有参与该活动');
		if(empty($rs) && $bargainUserId != $userId){
			$bargain = $this->get(['bargainId'=>$bargainId]);
			if(($bargain['minusNum']-$userBargain['helpNum']-1)==0)return WSTReturn('已经砍到低价，不能帮砍了');
			//生成价格
			$price = $this->reckonPrice($userBargain['currPrice']-$bargain['floorPrice'],$bargain['minusNum']-$userBargain['helpNum']-1,$bargain['minusType']);
			$price = $price[0];
			$currPrice = $userBargain['currPrice'] - $price;
			//修改砍价
			$data = [];
			$data['currPrice'] = $currPrice;
			$data['helpNum'] = $userBargain['helpNum']+1;
			$resultUsers = Db::name('bargain_users')->where(["userId"=>$bargainUserId,"bargainId"=>$bargainId])->update($data);
			if(false !== $resultUsers){
			    $data = [];
			    $data['bargainJoinId'] = $userBargain['id'];
			    $data['openId'] = $openId;
			    $data['userName'] = $userName;
			    $data['userPhoto'] = $userPhoto;
			    $data['bargainId'] = $bargainId;
			    $data['minusMoney'] = $price;
			    $data['createTime'] = date('Y-m-d H:i:s');
			    $resultHelps = Db::name('bargain_helps')->insert($data);
				return WSTReturn('成功砍价',1,$price);
			}
		}
		return WSTReturn('你已经砍过了');
	}
	/**
	 * 亲友团
	 */
	function helpsList($userId,$bargainUserId){
		$userId = ($bargainUserId>0)?$bargainUserId:$userId;
		$bargainId = (int)input('id');
		$page = Db::name('bargain_helps')->alias('bh')->join('__BARGAIN_USERS__ bu','bu.id=bh.bargainJoinId','inner')
		->where('bu.userId='.$userId.' and bh.bargainId='.$bargainId)
		->field('bh.userName,bh.userPhoto,bh.minusMoney,bh.bargainId,bh.createTime')
		->order('bh.createTime desc')
		->paginate(input('pagesize/d'))->toArray();
		if(count($page)>0){
			foreach($page['data'] as $key =>$v){
				$userPhoto = WSTUserPhoto($v['userPhoto']);
				$page['data'][$key]['userPhoto'] = (strpos($userPhoto,'ROOT')!=false)?url('/','','',true).substr($userPhoto,8):$userPhoto;
			}
		}
		return $page;
	}
	/**
	 * 获取用户砍价列表
	 */
	public function pageQueryByUser(){
		$page = Db::name('bargain_users')->alias('bu')
		->join('__BARGAINS__ b','bu.bargainId=b.bargainId','inner')
		->join('__GOODS__ g','b.goodsId=g.goodsId')
		->where(['bu.userId'=>(int)session('WST_USER.userId')])
		->field('b.bargainId,b.shopId,b.goodsId,g.goodsName,g.goodsImg,b.startPrice,b.floorPrice,b.goodsStock,b.startTime,b.endTime,bu.currPrice')
		->order('bu.createTime desc')
		->paginate(input('pagesize/d'))->toArray();
		if(count($page['data'])>0){
			$time = time();
			foreach ($page['data'] as $key => $v) {
				$page['data'][$key]['goodsImg'] = WSTImg($v['goodsImg']);
				if(strtotime($v['startTime'])<=$time && strtotime($v['endTime'])>=$time){
					$page['data'][$key]['status'] = 1;
				}else if(strtotime($v['startTime'])>$time){
					$page['data'][$key]['status'] = 0;
				}else{
					$page['data'][$key]['status'] = -1;
				}
			}
		}
		return WSTReturn('',1,$page);
	}
}
