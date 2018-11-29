<?php
namespace addons\pintuan\model;
use think\addons\BaseModel as Base;
use shangtao\common\model\GoodsCats;
use think\Db;
/**
 * 拼团插件
 */
class Pintuans extends Base{
	protected $pk = 'tuanId';
	public function getConfigs(){
		$data = cache('pintuan_sms');
		if(!$data){
			$rs = Db::name('addons')->where('name','Pintuan')->field('config')->find();
		    $data =  json_decode($rs['config'],true);
		    cache('pintuan_sms',$data,31622400);
		}
		return $data;
	}
    /***
     * 安装插件
     */
    public function installMenu(){
    	Db::startTrans();
		try{
			$hooks = ['beforeCancelOrder','wechatDocumentUserIndexTools'];
			$this->bindHoods("Pintuan", $hooks);
			//管理员后台
			$rs = Db::name('menus')->insert(["parentId"=>93,"menuName"=>"拼团","menuSort"=>1,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"pintuan"]);
			if($rs!==false){
				$datas = [];
				$parentId = Db::name('menus')->getLastInsID();
				$datas[] = ["menuId"=>$parentId,"privilegeCode"=>"PINTUAN_PTHD_00","privilegeName"=>"查看拼团","isMenuPrivilege"=>1,"privilegeUrl"=>"/addon/pintuan-goods-pageByAdmin","otherPrivilegeUrl"=>"/addon/pintuan-goods-pageQueryByAdmin,/addon/pintuan-goods-pageAuditQueryByAdmin","dataFlag"=>1,"isEnable"=>1];
				$datas[] = ["menuId"=>$parentId,"privilegeCode"=>"PINTUAN_PTHD_04","privilegeName"=>"拼团操作","isMenuPrivilege"=>0,"privilegeUrl"=>"","otherPrivilegeUrl"=>"/addon/pintuan-goods-allow,/addon/pintuan-goods-illegal","dataFlag"=>1,"isEnable"=>1];
				$datas[] = ["menuId"=>$parentId,"privilegeCode"=>"PINTUAN_PTHD_03","privilegeName"=>"删除拼团","isMenuPrivilege"=>0,"privilegeUrl"=>"/addon/pintuan-goods-delByAdmin","otherPrivilegeUrl"=>"","dataFlag"=>1,"isEnable"=>1];
				Db::name('privileges')->insertAll($datas);
			}
			
			$now = date("Y-m-d H:i:s");
			//商家中心
			Db::name('home_menus')->insert(["parentId"=>77,"menuName"=>"拼团","menuUrl"=>"addon/pintuan-shops-pintuan","menuOtherUrl"=>"addon/pintuan-shops-pintuan,addon/pintuan-shops-pageQuery,addon/pintuan-shops-searchGoods,addon/pintuan-shops-edit,addon/pintuan-shops-toEdit,addon/pintuan-shops-del","menuType"=>1,"isShow"=>1,"menuSort"=>1,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"pintuan"]);
			$this->addMobileBtn();
			installSql("pintuan");
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
	public function uninstallMenu(){
		Db::startTrans();
		try{
			$hooks = ['beforeCancelOrder','wechatDocumentUserIndexTools'];
			$this->unbindHoods("Pintuan", $hooks);
			Db::name('menus')->where(["menuMark"=>"pintuan"])->delete();
			Db::name('home_menus')->where(["menuMark"=>"pintuan"])->delete();
			Db::name('privileges')->where("privilegeCode","like","PINTUAN_%")->delete();
           
			//删除微信参数数据
			$tplMsgIds = Db::name('template_msgs')->where([['tplCode','in',explode(',','PINTUAN_GOODS_ALLOW,PINTUAN_GOODS_REJECT,WX_PINTUAN_GOODS_ALLOW,WX_PINTUAN_GOODS_REJECT,WX_PINTUAN_REFUND,WX_PINTUAN_SUCCESS')]])
			->column('id');
			if((int)WSTConf('CONF.wxenabled')==1)Db::name('wx_template_params')->where([['parentId','in',$tplMsgIds]])->delete();
			
			uninstallSql("pintuan");//传入插件名
			$this->delMobileBtn();
			Db::commit();
			return true;
		}catch (\Exception $e) {
	 		Db::rollback();
	  		return false;
	   	}
	}

	/**
	 * 菜单显示隐藏
	 */
	public function toggleShow($isShow = 1){
		Db::startTrans();
		try{
			Db::name('menus')->where(["menuMark"=>"pintuan"])->update(["isShow"=>$isShow]);
			Db::name('home_menus')->where(["menuMark"=>"pintuan"])->update(["isShow"=>$isShow]);
			Db::name('navs')->where(["navUrl"=>"index.php/addon/pintuan-goods-lists.html"])->update(["isShow"=>$isShow]);
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
	
	public function addMobileBtn(){
	
		$data = array();
		$data["btnName"] = "拼团";
		$data["btnSrc"] = 1;
		$data["btnUrl"] = "/addon/pintuan-goods-wxlists";
		$data["btnImg"] = "addons/pintuan/view/default/wechat/index/img/pintuan.png";
		$data["addonsName"] = "Pintuan";
		$data["btnSort"] = 6;
		Db::name('mobile_btns')->insert($data);

		// app端
		if(WSTDatas('ADS_TYPE',4)){
			$data = array();
			$data["btnName"] = "拼团";
			$data["btnSrc"] = 3;
			$data["btnUrl"] = "wst://PinTuan";
			$data["btnImg"] = "addons/pintuan/view/app/img/pintuan.png";
			$data["addonsName"] = "Pintuan";
			$data["btnSort"] = 5;
			Db::name('mobile_btns')->insert($data);
		}
	
	}
	
	public function delMobileBtn(){
		Db::name('mobile_btns')->where(["addonsName"=>"Pintuan"])->delete();
	
	}

	/**
	 * 改变拼团信息
	 */
	public function changePintuan($params){
		$goodsId = (int)$params['goodsId'];
		$date = date('Y-m-d H:i:s');
		Db::name('pintuan')
		  ->where(" endTime >='".$date."' and dataFlag=1 and goodsId=".$goodsId)
		  ->update(['pintuanStatus'=>0]);
	}


    /**
     * 商家获取拼团列表
     */
	public function pageQueryByShop(){
		$goodsName = input('goodsName');
		$shopId = (int)session('WST_USER.shopId');
		$where[] = ['g.shopId','=',$shopId];
		$where[] = ['p.dataFlag','=',1];
		if($goodsName !='')$where[] = ['g.goodsName','like','%'.$goodsName.'%'];
        $page =  $this->alias('p')
                      ->join('__GOODS__ g','g.goodsId=p.goodsId','left')
                      ->where($where)->order('p.createTime desc')
                      ->field('g.goodsName,g.goodsSn,g.shopPrice,g.goodsImg,p.*')
                      ->order('updateTime desc')
                      ->paginate(input('pagesize/d'))->toArray();
        if(count($page['data'])>0){
        	$tuanIds = [0];
        	foreach($page['data'] as $key =>$v){
        		$tuanIds[] = $page['data'][$key]['tuanId']; 
        	}
        	$olist = Db::name("pintuan_users")->where("tuanId","in",$tuanIds)
	        	->where(['isHead'=>1])
	        	->where("tuanStatus","in","-1,1,2")
	        	->field('tuanId,count(tuanId) openTuanCnt')
	        	->group('tuanId')
	        	->select();
	        $omap = [];
	        foreach($olist as $key =>$v){
        		$omap[$v['tuanId']] = $v['openTuanCnt']; 
        	}
        	foreach($page['data'] as $key =>$v){
        		$page['data'][$key]['openTuanCnt'] = isset($omap[$v['tuanId']])?$omap[$v['tuanId']]:0; 
        		$page['data'][$key]['goodsImg'] = WSTImg($v['goodsImg']); 
        	}
        }
        $page['status'] = 1;
        return $page;
	}

    /**
     * 搜索商品
     */
    public function searchGoods(){
    	$shopId = (int)session('WST_USER.shopId');
    	$shopCatId1 = (int)input('post.shopCatId1');
    	$shopCatId2 = (int)input('post.shopCatId2');
    	$goodsName = input('post.goodsName');
    	$where = [];
    	$where[] = ['goodsStatus','=',1];
    	$where[] = ['dataFlag','=',1];
    	$where[] = ['isSale','=',1];
    	$where[] = ['shopId','=',$shopId];
    	if($shopCatId1>0)$where[] = ['shopCatId1','=',$shopCatId1];
    	if($shopCatId2>0)$where[] = ['shopCatId2','=',$shopCatId2];
    	if($goodsName!='')$where[] = ['goodsName','like','%'.$goodsName.'%'];
    	$rs = Db::name('goods')->where($where)->field('goodsName,goodsId,marketPrice,shopPrice,goodsType')->select();
        return WSTReturn('',1,$rs);
    }
	/**
     * 获取商品类别
	 */
	public function getGoodsCats(){
		$rs = Db::name('goods_cats')
		        ->where(['dataFlag'=>1,'isShow'=>1])
		        ->order('catSort asc')
		        ->field('parentId pid,catId id,catName text')
		        ->select();
		return WSTReturn('',1,$rs);
	}
	/**
	 *  获取拼团
	 */
	public function getById($id){
		$where = [];
		$where['g.shopId'] = (int)session('WST_USER.shopId');
		$where['p.tuanId'] = $id;
		$where['p.dataFlag'] = 1;
		$where['g.dataFlag'] = 1;
		return $this->alias('p')->join('__GOODS__ g','g.goodsId=p.goodsId','left')->where($where)->field('g.goodsName,g.marketPrice,g.shopPrice,p.*')->find();
	}

	/**
	 * 新增拼团
	 */
	public function add(){
		$data = input('post.');
		$goods = model('common/Goods')->get((int)$data['goodsId']);
		if(empty($goods))return WSTReturn('商品不存在');
		if((float)$data['tuanPrice']<=0)return WSTReturn('拼团价格必须大于0');
		if((int)$data['tuanNum']<=0)return WSTReturn('拼团数量必须大于0');
		
		WSTUnset($data,'tuanId,cat_0,illegalRemarks');
		$specs = [];
		if($goods->isSpec==1){
			$specs = $this->getSpecs($goods->goodsId);
		}
		$data['shopId'] = (int)session('WST_USER.shopId');
		$data['goodsName'] = $goods->goodsName;
		$data['goodsImg'] = $goods->goodsImg;
		$data['goodsJson'] = json_encode(['gallery'=>$goods->gallery,'specs'=>$specs]);
		$data['dataFlag'] = 1;
		$data['orderNum'] = 0;
		$data['tuanStatus'] = 0;
		$data['updateTime'] = date('Y-m-d H:i:s');
		$data['createTime'] = date('Y-m-d H:i:s');
		$result = $this->allowField(true)->save($data);
		if(false !== $result){
			return WSTReturn('新增成功',1);
		}
		return WSTReturn('新增失败');
	}

	/**
	 * 编辑商品 
	 */
	public function edit(){
		$data = input('post.');
		$shopId = (int)session('WST_USER.shopId');
		$tuanId = (int)input('tuanId');
		$tuan = $this->get($tuanId);
		if($tuan->shopId!=$shopId)return WSTReturn('无效的拼团记录');

		//如果有改变商品则更新内容
		if($tuan->goodsId!=(int)$data['goodsId']){
			$goods = model('common/Goods')->get((int)$data['goodsId']);
			if(empty($goods))return WSTReturn('商品不存在');
		    if($goods->goodsStatus!=1 || $goods->goodsType!=0 || $goods->isSale!=1 || $goods->dataFlag!=1 || $goods->shopId != $shopId)return WSTReturn('无效的商品');
		    $specs = [];
			if($goods->isSpec==1){
				$specs = $this->getSpecs($goods->goodsId);
			}
			$data['goodsId'] = $goods->goodsId;
			$data['goodsName'] = $goods->goodsName;
		    $data['goodsImg'] = $goods->goodsImg;
		    $data['goodsJson'] = json_encode(['gallery'=>$goods->gallery,'specs'=>$specs]);
		}

		$goods = model('common/Goods')->get((int)$data['goodsId']);
		if(empty($goods))return WSTReturn('商品不存在');
		if((float)$data['tuanPrice']<=0)return WSTReturn('拼团价格必须大于0');
		if((int)$data['tuanNum']<=0)return WSTReturn('拼团数量必须大于0');
		WSTUnset($data,'tuanId,shopId,dataFlag,createTime,cat_0,illegalRemarks,orderNum');
		$data['tuanStatus'] = 0;
		$data['updateTime'] = date('Y-m-d H:i:s');
		$result = $this->allowField(true)->update($data,['tuanId'=>$tuanId,'shopId'=>$shopId]);
		if(false !== $result){
			return WSTReturn('编辑成功',1);
		}
		return WSTReturn('编辑失败');
	}

	/**
	 * 获取规格数组
	 */
	public function getSpecs($goodsId){
		$sales = Db::name('goods_specs')->where(['goodsId'=>$goodsId,'isDefault'=>1])->field('specIds')->find();
		$specIds = [];
		if(!empty($sales)){
			$specIds = explode(':',$sales['specIds']);
			sort($specIds);
		}
		$spec = [];
		//获取默认规格值
		$specs = Db::name('spec_cats')->alias('gc')
				   ->join('__SPEC_ITEMS__ sit','gc.catId=sit.catId','inner')
				   ->where(['sit.goodsId'=>$goodsId,'gc.isShow'=>1,'sit.dataFlag'=>1])
				   ->field('gc.isAllowImg,gc.catName,sit.catId,sit.itemId,sit.itemName,sit.itemImg')
				   ->order('gc.isAllowImg desc,gc.catSort asc,gc.catId asc')->select();                     
		foreach ($specs as $key =>$v){
			if(in_array($v['itemId'],$specIds)){
				$catId = $v['catId'];
				$spec[$catId]['name'] = $v['catName'];
				unset($v['catName'],$v['catId']);
				$spec[$catId]['list'][] = $v;
			}
		}
		return $spec;
	}

	/**
	 * 删除拼团
	 */
	public function del(){
		$id = (int)input('id');
		$shopId = (int)session('WST_USER.shopId');
		$data = [];
		$data['shopId'] = $shopId;
		$data['tuanId'] = $id;
		$data[] = ['tuanStatus','<>',1];
        $rs = $this->update(['dataFlag'=>-1],$data);
        if($rs){
        	return WSTReturn('删除成功',1);
        }else{
        	return WSTReturn('删除失败',-1);
        }
	}

	/**
	 * 下架拼团
	 */
	public function unSale(){
		$tuanId = (int)input('id');
		$shopId = (int)session('WST_USER.shopId');
		
        Db::startTrans();
		try{
			$data = [];
			$data['shopId'] = $shopId;
			$data['tuanId'] = $tuanId;
			$data['tuanStatus'] = 1;
	        $rs = $this->update(['dataFlag'=>-1],$data);

	        if(false !== $rs){
	        	$this->unSaleRefund($tuanId);
				Db::commit();
	        	return WSTReturn('下架成功',1);
	        }else{
	        	return WSTReturn('下架失败',-1);
	        }
		}catch (\Exception $e) {
	 		Db::rollback();
	  		return WSTReturn('下架失败',-1);
	   	}
	}
	/**
	 * 下架拼团退款
	 */
	public function unSaleRefund($tuanId){
		$pusers = Db::name('pintuan_users pu')->join("__USERS__ u","pu.userId=u.userId")
				->join("__PINTUANS__ p","p.tuanId=pu.tuanId","inner")
				->where(["pu.tuanStatus"=>1,"p.tuanId"=>$tuanId])
				->field("pu.id,pu.tuanId,pu.useScore, p.goodsName,u.userId,u.userName,u.loginName,u.userPhoto,pu.isHead,pu.tuanNo,pu.createTime,pu.realTotalMoney,pu.orderNo,pu.payType,pu.payFrom")
				->select();
		for($i=0,$j=count($pusers);$i<$j;$i++){
			$puser = $pusers[$i];
			if($puser['payType']==1 && ($puser['payFrom']=='wallets' || $puser['payFrom']=='others')){
	        	$rs = $this->saveTuanRefund($puser);
	        }else if($puser['payType']==1 && in_array($puser['payFrom'], ['weixinpays','app_weixinpays','alipays'])){
	        	Db::name('pintuan_users')->where(["id"=>$puser["id"],"tuanStatus"=>1])->update(["refundStatus"=>1]);
	        }
		}
	}

	/***
	 * 获取前台拼团列表
	 */
	public function pageQuery(){
		$goodsCatId = (int)input('catId');
		$goodsName = input('goodsName/s');
		$areaId = (int)input('areaId');
		$where = [];
		if($goodsCatId>0){
			$gc = new GoodsCats();
			$goodsCatIds = $gc->getParentIs($goodsCatId);
			$where[] = ['goodsCatIdPath','like',implode('_',$goodsCatIds).'_%'];
		}
		$where[] = ['p.goodsNum','>',0];
		if($goodsName!='')$where[] = ['p.goodsName','like','%'.$goodsName.'%'];
		$page = Db::name('pintuans')->alias('p')->join('__GOODS__ g','p.goodsId=g.goodsId','inner')
		          ->where('g.dataFlag=1 and g.isSale=1 and g.goodsStatus=1 and p.dataFlag=1 and p.tuanStatus=1')
		          ->where($where)
		          ->field('g.goodsName,g.goodsImg,g.marketPrice,g.goodsCatId,p.*')
		          ->order('p.updateTime desc,tuanId desc')
		          ->paginate(input('pagesize/d'))->toArray();
		if(count($page)>0){
			$time = time();
			foreach($page['data'] as $key =>$v){
				$page['data'][$key]['goodsImg'] = WSTImg($v['goodsImg']); 
				$page['data'][$key]['zhekou'] = round($v['tuanPrice']/$v['marketPrice']*10,1); 
        		if($v['orderNum']>=$v['tuanNum']){
        			$page['data'][$key]['status'] = -1;
        		}
			}
		}
		return $page;
	}

	/**
	 * 获取拼团详情
	 */
	public function getBySale($tuanId,$uId=0){
		$key = input('key');
		$where = ['tuanId'=>$tuanId];
		$gu = $this->where($where)->find();

		$viKey = WSTShopEncrypt($gu['shopId']);
        if($key!=''){	
            if($viKey!=$key && $gu['tuanStatus']!=1)return [];
        }else{
        	if($gu['tuanStatus']!=1)return [];
        }
		$goodsId = $gu['goodsId'];
		if(empty($gu))return [];
		$gu = $gu->toArray();
		$rs = Db::name('goods')->where(['goodsId'=>$goodsId,'dataFlag'=>1])->find();
		if(!empty($rs)){
			if($rs['isSpec']==1){
				$sales = Db::name('goods_specs')->where(['goodsId'=>$goodsId,'isDefault'=>1])->field('specIds')->find();
				$rs['goodsSpecId'] = $sales["specIds"];
			}
			
			Db::name('goods')->where('goodsId',$goodsId)->setInc('visitNum',1);
			$rs = array_merge($rs,$gu);

			$rs['status'] = 1;
        	if($gu['goodsNum']<=0)$rs['status'] = -1;
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
			$gu['goodsJson'] = json_decode($gu['goodsJson'],true);
			$gallery = [];
			$gallery[] = $rs['goodsImg'];
			if($gu['goodsJson']['gallery']!=''){
				$tmp = explode(',',$gu['goodsJson']['gallery']);
				$gallery = array_merge($gallery,$tmp);
			}

			$rs['gallery'] = $gallery;

			if(!empty($gu['goodsJson']['specs']))$rs['spec'] = $gu['goodsJson']['specs'];

			//获取销售规格
			$sales = Db::name('goods_specs')->where('goodsId',$goodsId)->field('id,isDefault,productNo,specIds,marketPrice,specPrice,specStock')->select();
			if(!empty($sales)){
				foreach ($sales as $key =>$v){
					$str = explode(':',$v['specIds']);
					sort($str);
					unset($v['specIds']);
					$rs['saleSpec'][implode(':',$str)] = $v;
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
			$rs['favShop'] = $f->checkFavorite($rs['shopId'],1,$uId);
			$rs['favGood'] = $f->checkFavorite($goodsId,0,$uId);


			// 获取一条商品评价
			$appr = model('app/GoodsAppraises')
								->alias('ga')
								->join('users U','ga.userId=U.userId')
								->field('U.loginName,U.userPhoto,ga.content')
								->where(['goodsId'=>$goodsId,'U.dataFlag'=>1,'ga.dataFlag'=>1])
								->find();
			if(!empty($appr)){
				// 若未设置头像,则取商城默认头像
				$appr['userPhoto'] = ($appr['userPhoto']!='')?$appr['userPhoto']:WSTConf('CONF.userLogo');
				// 过滤html标签
				$appr['content'] = strip_tags($appr['content']);
				// 处理匿名
				$start = floor((strlen($appr['loginName'])/2))-1;
				$appr['loginName'] = substr_replace($appr['loginName'],'**',$start,2);
			}
			$rs['goodsAppr'] = $appr;
		}
		if(!empty($rs)){
			$maxPuId = Db::name('pintuan_users')->where(["tuanId"=>$tuanId,"tuanStatus"=>1])->max('id');
			$rs["maxPuId"] = $maxPuId;
		}
		return $rs;
	}

	
	/**
     * 下单
     */
	public function addCart($uId=0){
		$userId = ($uId==0)?(int)session('WST_USER.userId'):$uId;
		if($userId==0)return WSTReturn('您尚未登录系统，请先登录系统');
		$id = (int)input('post.id');
		$tuanNo = (int)input('post.tuanNo');
		$tuanType = (int)input('post.tuanType');
		$cartNum = (int)input('post.buyNum',1);
		$cartNum = ($cartNum>0)?$cartNum:1;
		$goodsSpecId = 0;
		//验证传过来的商品是否合法
		$chk = $this->checkGoodsSaleSpec($id);
		if($chk['status']==-1)return $chk;
		//检测库存是否足够
		if($chk['data']['stock']<$cartNum)return WSTReturn("拼团失败，商品库存不足", -1);
        $carts = [];
        $carts['tuanId'] = $id;
        $carts['tuanNo'] = $tuanNo;
        $carts['cartNum'] = $cartNum;
        $carts['tuanType'] = $tuanType;
        session('PINTUAN_CARTS',$carts);
        return WSTReturn("", 1);
	}
	/**
	 * 验证商品是否合法
	 */
	public function checkGoodsSaleSpec($tuanId){
		$goods = $this->alias('p')->join('__GOODS__ g','p.goodsId=g.goodsId','inner')
		              ->where(['g.goodsStatus'=>1,'g.dataFlag'=>1,'g.isSale'=>1,'p.dataFlag'=>1,'p.tuanId'=>$tuanId,'p.tuanStatus'=>1])
		              ->field('g.goodsId,isSpec,goodsType,p.tuanNum,p.orderNum,p.goodsNum,p.saleNum')
		              ->find();
		if(empty($goods))return WSTReturn("添加失败，无效的商品信息", -1);
		//判断拼团是否过期
		$time = time();
		$goodsId = $goods['goodsId'];
		$goodsStock = (int)$goods['goodsNum'];
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
	 * 获取session中购物车列表
	 */
	public function getCarts($uId=0){
		$userId = ($uId==0)?(int)session('WST_USER.userId'):$uId;
		$tmp_carts = session('PINTUAN_CARTS');
		$where = [];
		$where['p.tuanId'] = $tmp_carts['tuanId'];
		$where['p.dataFlag'] = 1;
		$where['p.tuanStatus'] = 1;
		$where['g.goodsStatus'] = 1;
		$where['g.dataFlag'] = 1;
		$where['g.isSale'] = 1;
		$rs = $this->alias('p')->join('__GOODS__ g','p.goodsId=g.goodsId','inner')
		           ->join('__SHOPS__ s','s.shopId=p.shopId','left')
		           ->join('__GOODS_SPECS__ gs','g.goodsId=gs.goodsId and gs.isDefault','left')
		           ->where($where)
		           ->field('s.userId,s.shopId,s.shopName,g.goodsId,s.shopQQ,shopWangWang,g.goodsName,p.tuanPrice,g.shopPrice,p.goodsNum ,p.saleNum,g.goodsImg,g.goodsCatId,g.goodsType,gs.specIds,gs.id goodsSpecId,g.isFreeShipping')
		           ->find()->toArray();
		if(empty($rs))return ['carts'=>[],'goodsTotalMoney'=>0,'goodsTotalNum'=>0]; 
		// 确保goodsSpecId不为null.
		$rs['goodsSpecId'] = (int)$rs['goodsSpecId'];
		$rs['cartNum'] = $tmp_carts['cartNum'];
		$carts = [];
		$goodsTotalNum = 0;
		$goodsTotalMoney = 0;
		if(!isset($carts['goodsMoney']))$carts['goodsMoney'] = 0;
		$carts['isFreeShipping'] = ($rs['isFreeShipping']==1)?true:false;
		$carts['tuanId'] = $tmp_carts['tuanId'];
		$carts['shopId'] = $rs['shopId'];
		$carts['shopName'] = $rs['shopName'];
		$carts['shopQQ'] = $rs['shopQQ'];
		$carts['userId'] = $rs['userId'];
		$carts['shopWangWang'] = $rs['shopWangWang'];
		$rs['goodsStock'] = $rs['goodsNum'];
		//判断能否购买，预设allowBuy值为10，为将来的各种情况预留10个情况值，从0到9
		$rs['allowBuy'] = 10;
		if($rs['goodsStock']<=0){
			$rs['allowBuy'] = 0;//库存不足
		}else if($rs['goodsStock']<$tmp_carts['cartNum']){
			$rs['allowBuy'] = 1;//库存比购买数小
		}
		if($tmp_carts['tuanType']==1){
			$rs['shopPrice'] = $rs['tuanPrice'];
		}
		$carts['goodsMoney'] = $carts['goodsMoney'] + $rs['shopPrice'] * $rs['cartNum'];
		$goodsTotalMoney = $goodsTotalMoney + $rs['shopPrice'] * $rs['cartNum'];
		$goodsTotalNum = $rs['cartNum'];
		if($rs['specIds']!=''){
			//加载规格值
			$specs = DB::name('spec_items')->alias('s')->join('__SPEC_CATS__ sc','s.catId=sc.catId','left')
			           ->where(['s.goodsId'=>$rs['goodsId'],'s.dataFlag'=>1])
		               ->field('catName,itemId,itemName')
		           	   ->select();
		    if(count($specs)>0){ 
			    $specMap = [];
			    foreach ($specs as $key =>$v){
			    	$specMap[$v['itemId']] = $v;
			    }
				$strName = [];
				if($rs['specIds']!=''){
				    $str = explode(':',$rs['specIds']);
				    foreach ($str as $vv){
				    	if(isset($specMap[$vv]))$strName[] = $specMap[$vv];
				    }
				}
				$rs['specNames'] = $strName;
			}
		}
		unset($rs['shopName']);
		$carts['goods'] = $rs;
		return ['carts'=>$carts,'goodsType'=>$rs['goodsType'],'goodsTotalMoney'=>$goodsTotalMoney,'goodsTotalNum'=>$goodsTotalNum]; 
	}

	/**
	 * 计算订单金额
	 */
	public function getCartMoney($uId=0){
		$data = ['shops'=>[],'totalMoney'=>0,'totalGoodsMoney'=>0];
        $areaId = input('post.areaId2/d',-1);
		//计算各店铺运费及金额
		$deliverType = (int)input('deliverType');
		$carts = $this->getCarts();
		$deliverType = ($carts['goodsType']==1)?1:$deliverType;
		$shopFreight = 0;
		//判断是否包邮
		if($carts['carts']['isFreeShipping']){
			$shopFreight = 0;
		}else{
			if($areaId>0){
				$shopFreight = ($deliverType==1)?0:WSTOrderFreight($carts['carts']['shopId'],$areaId);
			}else{
				$shopFreight = 0;
			}
		}
		$data['shops']['freight'] = $shopFreight;
		$data['shops']['shopId'] = $carts['carts']['shopId'];
		$data['shops']['goodsMoney'] = $carts['carts']['goodsMoney'];
		$data['totalGoodsMoney'] = $carts['carts']['goodsMoney'];
		$data['totalMoney'] += $carts['carts']['goodsMoney'] + $shopFreight;
		$data['useScore'] = 0;
		$data['scoreMoney'] = 0;
		//计算积分
		$isUseScore = (int)input('isUseScore');
		if($isUseScore==1){
            $userId = ($uId==0)?(int)session('WST_USER.userId'):$uId;
			$useScore = (int)input('useScore');
			$user = model('common/users')->getFieldsById($userId,'userScore');
			if($useScore>$user['userScore'])$useScore = $user['userScore'];
			$moneyToScore = WSTScoreToMoney($data['totalGoodsMoney'],true);
			if($useScore>$moneyToScore)$useScore = $moneyToScore;
			$money = WSTScoreToMoney($useScore);
			$data['useScore'] = $useScore;
			$data['scoreMoney'] = $money;
		}
		$data['realTotalMoney'] = $data['totalMoney'] - $data['scoreMoney'];
		return WSTReturn('',1,$data);
	}

	/**
	 * 虚拟商品下单
	 */
	public function submitByVirtual($carts,$orderSrc = 0,$uId){
		$tuanId = (int)session('PINTUAN_CARTS.tuanId');
		$tuanType = (int)session('PINTUAN_CARTS.tuanType');
		$tuanNo = (int)session('PINTUAN_CARTS.tuanNo');
		$cartNum = (int)session('PINTUAN_CARTS.cartNum');
		$addressId = (int)input('post.s_addressId');
		$deliverType = ((int)input('post.deliverType')!=0)?1:0;
		$isInvoice = ((int)input('post.isInvoice')!=0)?1:0;
		$invoiceClient = ($isInvoice==1)?input('post.invoiceClient'):'';
		$payType = 1;
		$userId = ($uId==0)?(int)session('WST_USER.userId'):$uId;
		if($userId==0)return WSTReturn('您尚未登录系统，请先登录系统');
		$isUseScore = (int)input('isUseScore');
		$useScore = (int)input('useScore');
		$tuan = $this->where(["tuanId"=>$tuanId])->find();
		$goodsId = $tuan["goodsId"];
		//给用户分配卡券
		$cards = model('common/GoodsVirtuals')->where(['goodsId'=>$goodsId,'dataFlag'=>1,'isUse'=>0])->lock(true)->limit($cartNum)->select();
		if(count($cards)<$cartNum)return WSTReturn("拼团失败，商品库存不足");
		
		Db::startTrans();
		try{
			//修改库存
			Db::name('goods')->where('goodsId',$goodsId)->setDec('goodsStock',$cartNum);
			Db::name('goods')->where('goodsId',$goodsId)->setInc('saleNum',$cartNum);

			

			//计算出订单应该分配的金额和积分
			$scoreMoney = model('common/orders')->getOrderScoreMoney($isUseScore,$useScore,$uId);
			//生成订单
			$orderunique = WSTOrderQnique();
			$carts = $carts['carts'];
			$orderNo = WSTOrderNo(); 
			$orderScore = 0;
			//创建订单
			$puser = [];
			$puser['tuanId'] = $tuanId;
			$puser['goodsId'] = $tuan["goodsId"];
			$puser['goodsNum'] = $cartNum;
			$puser['tuanStatus'] = 0;
			if($tuanNo>0){
				$puser['isHead'] = 0;
				$puser['tuanNo'] = $tuanNo;
			}else{
				$puser['isHead'] = 1;
				$puser['tuanNo'] = $orderNo;
			}
			$puser['orderType'] = 1;
			$puser['areaId'] = 0;
			$puser['areaIdPath'] = 0;
			$puser['userName'] = "";
			$puser['userPhone'] = "";
			$puser['userAddress'] = "";
			$puser['orderNo'] = $orderNo;
			$puser['userId'] = $userId;
			$puser['shopId'] = $carts['shopId'];
			$puser['payType'] = $payType;
			$puser['goodsMoney'] = $carts['goodsMoney'];
			//计算运费和总金额
			$puser['deliverType'] = $deliverType;
			$puser['deliverMoney'] = 0;
			$puser['totalMoney'] = $puser['goodsMoney']+$puser['deliverMoney'];
            //积分支付-计算分配积分和金额
            $puser['scoreMoney'] = 0;
			$puser['useScore'] = 0;
			if($scoreMoney['useMoney']>0){
				$puser['scoreMoney'] = $scoreMoney['useMoney'];
				$puser['useScore'] = $scoreMoney['useScore'];
			}
			//实付金额要减去积分兑换的金额
			$puser['realTotalMoney'] = $puser['totalMoney'] - $puser['scoreMoney'];
			$needPay = $puser['realTotalMoney'];
			$puser['needPay'] = $needPay;
			

			$goods = Db::name('goods')->where('goodsId',$tuan["goodsId"])->field('goodsCatId')->find();

			$puser['commissionRate'] = WSTGoodsCommissionRate($goods['goodsCatId']);
			
			$commissionFee = 0;
			if((float)$puser['commissionRate']>0){
            	$commissionFee += round($tuan['tuanPrice']*$cartNum*$puser['commissionRate']/100,2);
            }
            $puser['commissionFee'] = $commissionFee;
            
            if($puser['needPay']>0){
			    $puser['isPay'] = 0; 
            }else{
			    $puser['isPay'] = 1; 
			    $puser['payFrom'] = 'others';
			    $puser["tuanStatus"] = 1;
            }
			//积分
			$orderScore = 0;
			//如果开启下单获取积分则有积分
			if(WSTConf('CONF.isOrderScore')==1){
				$orderScore = WSTMoneyGiftScore($puser['goodsMoney']);
			}
			$puser['orderScore'] = $orderScore;
			$puser['isInvoice'] = $isInvoice;
			$puser['invoiceClient'] = $invoiceClient;
			$puser['orderRemarks'] = input('post.remark_'.$carts['shopId']);
			$puser['orderunique'] = $orderunique;
			$puser['orderSrc'] = $orderSrc;
			$puser['payRand'] = 1;
			$puser['createTime'] = date('Y-m-d H:i:s');

			//标记虚拟卡券为占用状态
			$goodsCards = [];
		    foreach ($cards as $key => $card) {
			    $card->isUse = 1;
			    $card->orderId = 0;
			    $card->orderNo = $orderNo;
			    $card->save();
			    $goodsCards[] = ['cardId'=>$card->id];
		    }
		    $extraJson = json_encode($goodsCards);
		    $puser["extraJson"] = $extraJson;
			$puId = Db::name('pintuan_users')->insertGetId($puser);
			if($puId>0){
				$goods = $carts['goods'];
				//修改拼团数量
				$this->where('tuanId',$carts['tuanId'])->setInc('orderNum',$goods['cartNum']);
				//创建积分流水--如果有抵扣积分就肯定是开启了支付支付
				if($puser['useScore']>0){
					$score = [];
				    $score['userId'] = $userId;
					$score['score'] = $puser['useScore'];
					$score['dataSrc'] = 1;
					$score['dataId'] = $puId;
					$score['dataRemarks'] = "拼团订单【".$orderNo."】使用积分".$puser['useScore']."个";
					$score['scoreType'] = 0;
					model('common/UserScores')->add($score);
				}
				//已付款的虚拟商品
				if($puser['needPay']==0){
					$this->pinTuanSuccess($orderNo);
				}
				Db::commit();
			}else{
				 Db::rollback();
			}
			session('PINTUAN_CARTS',null);
			return WSTReturn("提交订单成功", 1,$orderNo);
		}catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('提交订单失败',-1);
        }
	}
	/**
	 * 实物商品下单
	 */
	public function submitByEntity($carts,$orderSrc = 0,$uId=0){
		$tuanId = (int)session('PINTUAN_CARTS.tuanId');
		$tuanType = (int)session('PINTUAN_CARTS.tuanType');
		$tuanNo = (int)session('PINTUAN_CARTS.tuanNo');
		$cartNum = (int)session('PINTUAN_CARTS.cartNum');
		$addressId = (int)input('post.s_addressId');
		$deliverType = ((int)input('post.deliverType')!=0)?1:0;
		$isInvoice = ((int)input('post.isInvoice')!=0)?1:0;
		$invoiceClient = ($isInvoice==1)?input('post.invoiceClient'):'';
		$payType = 1;
		$userId = ($uId==0)?(int)session('WST_USER.userId'):$uId;
		if($userId==0)return WSTReturn('您尚未登录系统，请先登录系统');
		$isUseScore = (int)input('isUseScore');
		$useScore = (int)input('useScore');
		if($deliverType==0){// 配送方式为快递，必须有用户地址
			//检测地址是否有效
			$address = Db::name('user_address')->where(['userId'=>$userId,'addressId'=>$addressId,'dataFlag'=>1])->find();
			if(empty($address)){
				return WSTReturn("无效的用户地址");
			}
		    $areaIds = [];
	        $areaMaps = [];
	        $tmp = explode('_',$address['areaIdPath']);
	        $address['areaId2'] = $tmp[1];//记录配送城市
	        foreach ($tmp as $vv){
	         	if($vv=='')continue;
	         	if(!in_array($vv,$areaIds))$areaIds[] = $vv;
	        }
	        if(!empty($areaIds)){
		         $areas = Db::name('areas')->where([['areaId','in',$areaIds],['dataFlag','=',1]])->field('areaId,areaName')->select();
		         foreach ($areas as $v){
		         	 $areaMaps[$v['areaId']] = $v['areaName'];
		         }
		         $tmp = explode('_',$address['areaIdPath']);
		         $areaNames = [];
			     foreach ($tmp as $vv){
		         	 if($vv=='')continue;
		         	 $areaNames[] = $areaMaps[$vv];
		         	 $address['areaName'] = implode('',$areaNames);
		         }
	        }
			$address['userAddress'] = $address['areaName'].$address['userAddress'];
		}else{
			$address = [];
			$address['areaId'] = 0;
			$address['userName'] = '';
			$address['userAddress'] = '';
			$address['areaIdPath'] = '';
			$address['userPhone'] = '';
		}
		//计算出订单应该分配的金额和积分
		$scoreMoney = model('common/orders')->getOrderScoreMoney($isUseScore,$useScore,$uId);
		//生成订单
		Db::startTrans();
		try{
			$tuan = $this->where(["tuanId"=>$tuanId])->find();
			$orderunique = WSTOrderQnique();
			$carts = $carts['carts'];
			$orderNo = WSTOrderNo(); 
			$orderScore = 0;
			//创建订单
			$puser = [];
			$puser['tuanId'] = $tuanId;
			$puser['goodsId'] = $tuan["goodsId"];
			$puser['goodsNum'] = $cartNum;
			$puser['tuanStatus'] = 0;
			if($tuanNo>0){
				$puser['isHead'] = 0;
				$puser['tuanNo'] = $tuanNo;
			}else{
				$puser['isHead'] = 1;
				$puser['tuanNo'] = $orderNo;
			}
			$puser['orderType'] = 0;
			$puser['areaId'] = $address['areaId'];
			$puser['areaIdPath'] = $address['areaIdPath'];
			$puser['userName'] = $address['userName'];
			$puser['userPhone'] = $address['userPhone'];
			$puser['userAddress'] = $address['userAddress'];
			$puser['orderNo'] = $orderNo;
			$puser['userId'] = $userId;
			$puser['shopId'] = $carts['shopId'];
			$puser['payType'] = $payType;
			$puser['goodsMoney'] = $carts['goodsMoney'];
			//计算运费和总金额
			$puser['deliverType'] = $deliverType;
			if($carts['isFreeShipping']){
                $puser['deliverMoney'] = 0;
			}else{
			    $puser['deliverMoney'] = ($deliverType==1)?0:WSTOrderFreight($carts['shopId'],$address['areaId2']);
			}
			$puser['totalMoney'] = $puser['goodsMoney']+$puser['deliverMoney'];
            //积分支付-计算分配积分和金额
            $puser['scoreMoney'] = 0;
			$puser['useScore'] = 0;
			if($scoreMoney['useMoney']>0){
				$puser['scoreMoney'] = $scoreMoney['useMoney'];
				$puser['useScore'] = $scoreMoney['useScore'];
			}
			//实付金额要减去积分兑换的金额
			$puser['realTotalMoney'] = $puser['totalMoney'] - $puser['scoreMoney'];
			$needPay = $puser['realTotalMoney'];
			$puser['needPay'] = $needPay;
			

			$goods = Db::name('goods')->where('goodsId',$tuan["goodsId"])->field('goodsCatId')->find();

			$puser['commissionRate'] = WSTGoodsCommissionRate($goods['goodsCatId']);
			
			$commissionFee = 0;
			if((float)$puser['commissionRate']>0){
            	$commissionFee += round($tuan['tuanPrice']*$cartNum*$puser['commissionRate']/100,2);
            }
            $puser['commissionFee'] = $commissionFee;
            
            if($puser['needPay']>0){
			    $puser['isPay'] = 0; 
            }else{
			    $puser['isPay'] = 1;
			    $puser['payFrom'] = 'others';
			    $puser["tuanStatus"] = 1;
            }
			//积分
			$orderScore = 0;
			//如果开启下单获取积分则有积分
			if(WSTConf('CONF.isOrderScore')==1){
				$orderScore = WSTMoneyGiftScore($puser['goodsMoney']);
			}
			$puser['orderScore'] = $orderScore;
			$puser['isInvoice'] = $isInvoice;
			$puser['invoiceClient'] = $invoiceClient;
			$puser['orderRemarks'] = input('post.remark_'.$carts['shopId']);
			$puser['orderunique'] = $orderunique;
			$puser['orderSrc'] = $orderSrc;
			$puser['payRand'] = 1;
			$puser['createTime'] = date('Y-m-d H:i:s');

			$result = Db::name('pintuan_users')->insert($puser);
			if(false !== $result){
				$orderId = $result;
				$goods = $carts['goods'];
				
				//修改拼团数量
				$this->where('tuanId',$carts['tuanId'])->setInc('orderNum',$goods['cartNum']);
				//创建积分流水--如果有抵扣积分就肯定是开启了支付支付
				if($puser['useScore']>0){
					$score = [];
				    $score['userId'] = $userId;
					$score['score'] = $puser['useScore'];
					$score['dataSrc'] = 1;
					$score['dataId'] = $orderId;
					$score['dataRemarks'] = "拼团订单【".$orderNo."】使用积分".$puser['useScore']."个";
					$score['scoreType'] = 0;
					model('common/UserScores')->add($score);
				}
				if($needPay==0){
					$this->pinTuanSuccess($orderNo);
				}
			}
			Db::commit();
			//删除session的购物车商品
			session('PINTUAN_CARTS',null);
			return WSTReturn("", 1,$orderNo);
		}catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('拼团失败',-1);
        }
	}
   
    /**
	 * 下单
	 */
	public function submit($orderSrc = 0,$uId=0){
		//检测购物车
		$tuanNo = (int)session('PINTUAN_CARTS.tuanNo');
		if($tuanNo>0){
			$rs = $this->checkTuanStatus($tuanNo);
			if($rs["status"]==-1){
				return $rs;
			}
		}
		
		$carts = $this->getCarts();
		if(empty($carts['carts']))return WSTReturn("请选择要购买的商品");
		$checkNum = $carts['carts']['goods']['goodsNum'];
		if($checkNum<$carts['goodsTotalNum'])return WSTReturn("拼团失败，商品剩余库存为".$checkNum);

		if($carts['goodsType']==1){
			return $this->submitByVirtual($carts,$orderSrc,$uId);
		}else{
			return $this->submitByEntity($carts,$orderSrc,$uId);
		}
	}

	public function checkTuanStatus($tuanNo){
		$tuan = $this->alias('p')->join('__PINTUAN_USERS__ pu','p.tuanId=pu.tuanId')
            		 ->where(["pu.tuanNo"=>$tuanNo,"pu.isHead"=>1])
            		 ->field("pu.id,pu.tuanStatus,pu.createTime,p.tuanTime")
            		 ->find();
        if(!empty($tuan)){
        	if($tuan["tuanStatus"]==2 || $tuan["tuanStatus"]==-1){
        		return WSTReturn("该拼团已结束",-1);
        	}else{
        		$now = date("Y-m-d H:i:s");
        		$cdate = $tuan['createTime'];
        		$tdate = date("Y-m-d H:i:s",strtotime("+".$tuan['tuanTime']." hours",WSTStrToTime($cdate)));
        		if($now>=$tdate){
        			return WSTReturn("该拼团已结束",-1);
        		}else{
        			return WSTReturn("该拼团进行中",1);
        		}
        	}
        }
        return WSTReturn("该拼团不存在",-1);
	}

	public function getTuanInfo($tuanNo, $uId=0){
		$userId = ($uId==0)?(int)session('WST_USER.userId'):$uId;
		$tuan = $this->alias('p')->join('__PINTUAN_USERS__ pu','p.tuanId=pu.tuanId')
            		 ->where(["pu.tuanNo"=>$tuanNo,"pu.isHead"=>1])
            		 ->field("pu.tuanNo,pu.orderNo,pu.tuanStatus,p.tuanId,p.tuanTime,p.tuanNum,p.goodsId,p.goodsName,p.orderNum,p.tuanPrice,p.goodsImg,pu.createTime")
            		 ->find();
        $cdate = $tuan['createTime'];
     	$endTime = date('Y-m-d H:i:s',strtotime('+'.$tuan['tuanTime'].' hours',WSTStrToTime($cdate)));

     	$tuan["endTime"] = $endTime;
     	$pnum = Db::name('pintuan_users')->where([["tuanNo",'=',$tuanNo],["tuanStatus","in","-1,1,2"]])->count();
     	$tuan["needNum"] = $tuan["tuanNum"] - $pnum;
     	$goods = Db::name('goods')->field("goodsId,goodsName,shopPrice,goodsCatId")->where(['goodsId'=>$tuan["goodsId"],'dataFlag'=>1])->find();
     	$tuan["economizePrice"] = WSTBCMoney($goods["shopPrice"],-$tuan["tuanNum"]);
     	$tuan["goodsCatId"] = $goods["goodsCatId"];

     	$pself = Db::name('pintuan_users pu')->join("__USERS__ u","pu.userId=u.userId")
									->where([["tuanNo",'=',$tuanNo],["pu.userId",'=',$userId]])
									->field("pu.id,pu.tuanStatus")
									->find();
		$tuan["pself"] = $pself;
     	$pusers = Db::name('pintuan_users pu')->join("__USERS__ u","pu.userId=u.userId")
									->where([["tuanNo",'=',$tuanNo],["tuanStatus","in","-1,1,2"]])
									->field("pu.id,u.userId,u.userName,u.loginName,u.userPhoto,pu.isHead,pu.createTime")
									->order("pu.createTime")
									->select();
		$remainderTime =  WSTStrToTime($endTime)-time();
		$tuan["ptime"] =  $remainderTime;
		$tuan["remainderTime"] =  WSTTimeToStr(WSTStrToTime($endTime)-time());
		$tuan["hasTuan"] = 0;
		for($i=0,$j=count($pusers);$i<$j;$i++){
			if($pusers[$i]["userId"]==$userId){
				$tuan["hasTuan"] = 1;
				break;
			}
		}
		$tuan["pusers"] = $pusers;
		$maxPuId = Db::name('pintuan_users')->where(["tuanId"=>$tuan['tuanId'],"tuanStatus"=>1])->max('id');
		$tuan["maxPuId"] = $maxPuId;
       	return $tuan;
	}




	public function getTuanPay($orderNo,$userId){
		$tuan = Db::name('pintuan_users')->where(["orderNo"=>$orderNo,"userId"=>$userId])->field("id,tuanStatus,orderNo,tuanNo,tuanId,needPay,goodsNum")->find();
		$tuanNo = $tuan["tuanNo"];
		$ptuan = array();
		
		$ptuan = Db::name('pintuans p')->join('__PINTUAN_USERS__ pu','p.tuanId=pu.tuanId')
        		 ->where(["pu.tuanNo"=>$tuanNo,"pu.isHead"=>1])
        		 ->field("p.tuanTime,p.goodsName,p.goodsImg,pu.createTime")
        		 ->find();

        if(!empty($tuan)){
        	$tuan = array_merge($tuan,$ptuan);
        	if($tuan["tuanStatus"]==2 || $tuan["tuanStatus"]==-1){
        		return WSTReturn("该拼团已结束",-1);
        	}else if($tuan["tuanStatus"]==1){
        		return WSTReturn("该拼团已支付",-1);
        	}else{
        		$now = date("Y-m-d H:i:s");
        		$cdate = $tuan['createTime'];
        		$tdate = date("Y-m-d H:i:s",strtotime("+".$tuan['tuanTime']." hours",WSTStrToTime($cdate)));
        	
        		if($now>=$tdate){
        			return WSTReturn("该拼团已结束".$tdate,-1);
        		}else{
        			return WSTReturn("该拼团进行中",1,$tuan);
        		}
        	}
        }
        return WSTReturn("该拼团不存在",-1);
	}

	/**
	 * 管理员查看拼团列表
	 */
	public function pageQueryByAdmin($tuanStatus){
		$goodsName = input('goodsName/s');
		$shopName = input('shopName/s');
		$areaIdPath = input('areaIdPath');
		$goodsCatIdPath = input('goodsCatIdPath');
		$where[] = ['gu.dataFlag','=',1];
		$where[] = ['tuanStatus','=',$tuanStatus];
		if($goodsName !='')$where[] = ['g.goodsName|g.goodsSn','like','%'.$goodsName.'%'];
		if($shopName !='')$where[] = ['s.shopName|s.shopSn','like','%'.$shopName.'%'];
		if($areaIdPath !='')$where[] = ['s.areaIdPath','like',$areaIdPath."%"];
		if($goodsCatIdPath !='')$where[] = ['g.goodsCatIdPath','like',$goodsCatIdPath."%"];
        $page =  $this->alias('gu')->join('__GOODS__ g','g.goodsId=gu.goodsId','inner')
                      ->join('__SHOPS__ s','s.shopId=gu.shopId','left')
                      ->where($where)->order('gu.createTime desc')->field('g.goodsName,g.shopPrice,g.goodsSn,gu.*,g.goodsImg,s.shopId,s.shopName')
                      ->order('gu.updateTime desc')
                      ->paginate(input('pagesize/d'))->toArray();
  
        if(count($page['data'])>0){
        	$tuanIds = [0];
        	foreach($page['data'] as $key =>$v){
        		$tuanIds[] = $page['data'][$key]['tuanId']; 
        	}
        	$olist = Db::name("pintuan_users")->where("tuanId","in",$tuanIds)
	        	->where(['isHead'=>1])
	        	->where("tuanStatus","in","-1,1,2")
	        	->field('tuanId,count(tuanId) openTuanCnt')
	        	->group('tuanId')
	        	->select();
	        $omap = [];
	        foreach($olist as $key =>$v){
        		$omap[$v['tuanId']] = $v['openTuanCnt']; 
        	}
        	foreach($page['data'] as $key =>$v){
        		$page['data'][$key]['openTuanCnt'] = isset($omap[$v['tuanId']])?$omap[$v['tuanId']]:0; 
        		$page['data'][$key]['goodsImg'] = WSTImg($v['goodsImg']);
        		$page['data'][$key]['verfiycode'] = WSTShopEncrypt($v['shopId']);
        	
        	}
        }
        return $page;
	}

	/**
	* 设置商品违规状态
	*/
	public function illegal(){
		$illegalRemarks = input('post.illegalRemarks');		
		$id = (int)input('post.id');
		if($illegalRemarks=='')return WSTReturn("请输入违规原因");
		//判断商品状态
		$rs = $this->alias('gu')
		           ->join('__GOODS__ g','gu.goodsId=g.goodsId','inner')
		           ->join('__SHOPS__ s','g.shopId=s.shopId','left')
		           ->where('tuanId',$id)
		           ->field('gu.tuanId,g.shopId,s.userId,g.goodsName,g.goodsSn,gu.tuanStatus,g.goodsId')->find();
		if((int)$rs['tuanId']==0)return WSTReturn("无效的商品");
		if((int)$rs['tuanStatus']==-1)return WSTReturn("操作失败，商品状态已发生改变，请刷新后再尝试");
		Db::startTrans();
		try{
			$res = $this->where('tuanId',$id)->update(['tuanStatus'=>-1,'illegalRemarks'=>$illegalRemarks]);
			if($res!==false){
				$this->unSaleRefund($id);
				//发送一条商家信息
				$tpl = WSTMsgTemplates('PINTUAN_GOODS_REJECT');
		        if($tpl['tplContent']!='' && $tpl['status']=='1'){
		            $find = ['${GOODS}','${GOODS_SN}','${TIME}','${REASON}'];
		            $replace = [$rs['goodsName'],$rs['goodsSn'],date('Y-m-d H:i:s'),$illegalRemarks];
		            
		            $msg = array();
		            $msg["shopId"] = $rs['shopId'];
		            $msg["tplCode"] = $tpl["tplCode"];
		            $msg["msgType"] = 1;
		            $msg["content"] = str_replace($find,$replace,$tpl['tplContent']);
		            $msg["msgJson"] = ['from'=>7,'dataId'=>$id];
		            model("common/MessageQueues")->add($msg);
		        } 
		        if((int)WSTConf('CONF.wxenabled')==1){
					$params = [];
					$params['GOODS'] = $rs['goodsName'];
					$params['GOODS_SN'] = $rs['goodsSn'];
					$params['TIME'] = date('Y-m-d H:i:s'); 
					$params['REASON'] = $illegalRemarks;          
					
					$msg = array();
					$tplCode = "WX_PINTUAN_GOODS_REJECT";
					$msg["shopId"] = $rs['shopId'];
		            $msg["tplCode"] = $tplCode;
		            $msg["msgType"] = 4;
		            $msg["paramJson"] = ['CODE'=>$tplCode,'params'=>$params];
		            $msg["msgJson"] = "";
		            model("common/MessageQueues")->add($msg);
				}
				Db::commit();
				return WSTReturn('操作成功',1);
			}
		}catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('操作失败',-1);
	}
   /**
	* 通过商品审核
	*/
	public function allow(){	
		$id = (int)input('post.id');
		//判断商品状态
		$rs = $this->alias('gu')
		           ->join('__GOODS__ g','gu.goodsId=g.goodsId','inner')
		           ->join('__SHOPS__ s','g.shopId=s.shopId','left')
		           ->where('tuanId',$id)
		           ->field('gu.tuanId,g.shopId,s.userId,g.goodsName,g.goodsSn,gu.tuanStatus,g.goodsId')->find();
		if((int)$rs['tuanId']==0)return WSTReturn("无效的商品");
		if((int)$rs['tuanStatus']!=0)return WSTReturn("操作失败，商品状态已发生改变，请刷新后再尝试");
		Db::startTrans();
		try{
			$res = $this->where('tuanId',$id)->update(['tuanStatus'=>1]);
			if($res!==false){
				//发送一条商家信息
				$tpl = WSTMsgTemplates('PINTUAN_GOODS_ALLOW');
		        if($tpl['tplContent']!='' && $tpl['status']=='1'){
		            $find = ['${GOODS}','${GOODS_SN}','${TIME}'];
		            $replace = [$rs['goodsName'],$rs['goodsSn'],date('Y-m-d H:i:s')];
		            
		            $msg = array();
		            $msg["shopId"] = $rs['shopId'];
		            $msg["tplCode"] = $tpl["tplCode"];
		            $msg["msgType"] = 1;
		            $msg["content"] = str_replace($find,$replace,$tpl['tplContent']);
		            $msg["msgJson"] = ['from'=>7,'dataId'=>$id];
		            model("common/MessageQueues")->add($msg);
		        } 
		        if((int)WSTConf('CONF.wxenabled')==1){
					$params = [];
					$params['GOODS'] = $rs['goodsName'];
					$params['GOODS_SN'] = $rs['goodsSn'];
					$params['TIME'] = date('Y-m-d H:i:s');          
					
					$msg = array();
					$tplCode = "WX_PINTUAN_GOODS_ALLOW";
					$msg["shopId"] = $rs['shopId'];
		            $msg["tplCode"] = $tplCode;
		            $msg["msgType"] = 4;
		            $msg["paramJson"] = ['CODE'=>$tplCode,'params'=>$params];
		            $msg["msgJson"] = "";
		            model("common/MessageQueues")->add($msg);
				}
				Db::commit();
				return WSTReturn('操作成功',1);
			}
		}catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('操作失败',-1);
	}

    /**
	 * 删除拼团
	 */
	public function delByAdmin(){
		$id = (int)input('id');
		$data = [];
		$data['tuanId'] = $id;
		Db::startTrans();
		try{
	        $rs = $this->update(['dataFlag'=>-1],$data);
	        $this->unSaleRefund($id);
	        Db::commit();
			return WSTReturn('操作成功',1);
		}catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('操作失败',-1);
        }
	}

	/**
	 * 查询商品订单
	 */
	public function pageQueryByGoods(){
	    $tuanId = (int)input('tuanId');
		$orderNo = input('post.orderNo');
		$payType = (int)input('post.payType');
		$deliverType = (int)input('post.deliverType');
		$shopId = (int)session('WST_USER.shopId');
		$where[] = ['shopId','=',$shopId];
		$where[] = ['dataFlag','=',1];
		$where[] = ['orderCode','=','pintuan'];
		$where[] = ['orderCodeTargetId','=',$tuanId];
		if($orderNo!=''){
			$where[] = ['orderNo','like',"%$orderNo%"];
		}
		if($payType > -1){
			$where[] = ['payType','=',$payType];
		}
		if($deliverType > -1){
			$where[] = ['deliverType','=',$deliverType];
		}
		$page = Db::name('orders')->alias('o')->where($where)
		      ->join('__ORDER_REFUNDS__ orf','orf.orderId=o.orderId and refundStatus=0','left')
		      ->field('o.orderId,orderNo,goodsMoney,totalMoney,realTotalMoney,orderStatus,deliverType,deliverMoney,isAppraise,isRefund
		              ,payType,payFrom,userAddress,orderStatus,isPay,isAppraise,userName,orderSrc,o.createTime,orf.id refundId')
			  ->order('o.createTime', 'desc')
			  ->paginate()->toArray();
	    if(count($page['data'])>0){
	    	 $orderIds = [];
	    	 foreach ($page['data'] as $v){
	    	 	 $orderIds[] = $v['orderId'];
	    	 }
	    	 $goods = Db::name('order_goods')->where([['orderId','in',$orderIds]])->select();
	    	 $goodsMap = [];
	    	 foreach ($goods as $v){
	    	 	 $v['goodsSpecNames'] = str_replace('@@_@@','、',$v['goodsSpecNames']);
	    	 	 $goodsMap[$v['orderId']][] = $v;
	    	 }
	    	 foreach ($page['data'] as $key => $v){
	    	 	 $page['data'][$key]['list'] = $goodsMap[$v['orderId']];
	    	 	 $page['data'][$key]['payTypeName'] = WSTLangPayType($v['payType']);
	    	 	 $page['data'][$key]['deliverType'] = WSTLangDeliverType($v['deliverType']==1);
	    	 	 $page['data'][$key]['status'] = WSTLangOrderStatus($v['orderStatus']);
	    	 }
	    }
	    return WSTReturn('',1,$page);
	}

	
	public function payByWallet($uId=0){
		$payPwd = input('payPwd');
		$decrypt_data = WSTRSA($payPwd);
		if($uId==0){// 大于0表示来自app端
			$decrypt_data = WSTRSA($payPwd);
			if($decrypt_data['status']==1){
				$payPwd = $decrypt_data['data'];
			}else{
				return WSTReturn('支付失败');
			}
		}
		$orderNo = input('orderNo/s');
		$userId = ($uId==0)?(int)session('WST_USER.userId'):$uId;
		//判断是否开启余额支付
		$isEnbalePay = model('common/Payments')->isEnablePayment('wallets');
		if($isEnbalePay==0)return WSTReturn('非法的支付方式',-1);
		$rs = $this->getTuanPay($orderNo,$userId);
		if($rs["status"]==-1){
			return $rs;
		}
		//判断订单状态
		$where = ["pu.userId"=>$userId,"pu.tuanStatus"=>0];
		$where['pu.orderNo'] = $orderNo;
		
		$ptuan = Db::name('pintuans p')
				->join('__PINTUAN_USERS__ pu','p.tuanId=pu.tuanId')
				->field('p.tuanNum,pu.tuanId,pu.isHead,pu.orderId,pu.orderNo,pu.tuanNo,pu.orderType,pu.needPay,pu.shopId,pu.payFrom,pu.realTotalMoney')
				->where($where)->find();
		if(empty($ptuan))return WSTReturn('您的订单已支付',-1);
		

		Db::startTrans();
		try{
			//判断订单金额是否正确
			$needPay = $ptuan["needPay"];
			$where = ["tuanNo"=>$ptuan["tuanNo"]];
			$where[] = ["tuanStatus",'>=',1];
			$pnum = Db::name('pintuan_users')->where($where)->lock(true)->count();
			$tuanNum = $ptuan["tuanNum"];
			//获取用户钱包
			$user = model('common/users')->get($userId);
			if($user->payPwd=='')return WSTReturn('您未设置支付密码，请先设置密码',-1);
			if($user->payPwd!=md5($payPwd.$user->loginSecret))return WSTReturn('您的支付密码不正确',-1);
			if($needPay > $user->userMoney)return WSTReturn('您的钱包余额不足',-1);
			$rechargeMoney = $user->rechargeMoney;
			$tmpNeedPay = $needPay;
            $lockCashMoney = ($rechargeMoney>$tmpNeedPay)?$tmpNeedPay:$rechargeMoney;
			$orderId = $ptuan["orderId"];
			$tuanNo = $ptuan["tuanNo"];
			$data = [];
			$needNum = 0;
			if($tuanNum<=$pnum){
				$data["tuanNo"] = $orderNo;
				$data["isHead"] = 1;
				$needNum = $tuanNum-1;
			}else{
				$needNum = $tuanNum-$pnum-1;
			}
			$data["needNum"] = $needNum;
			//处理订单信息
			$data["tuanStatus"] = 1;
			$data["isPay"] = 1;
			$data["needPay"] = 0;
			$data["payFrom"] = 'wallets';
			$data["lockCashMoney"] = $lockCashMoney;
			$result =  Db::name('pintuan_users')->where(["orderNo"=>$orderNo,"tuanStatus"=>0])->update($data);
			if(false != $result){
				Db::name('pintuan_users')->where(["tuanNo"=>$tuanNo])->update(["needNum"=>$needNum]);
				//创建一条支出流水记录
				$lm = [];
				$lm['targetType'] = 0;
				$lm['targetId'] = $userId;
				$lm['dataId'] = $orderId;
				$lm['dataSrc'] = 1;
				$lm['remark'] = '拼团订单【'.$orderNo.'】支出¥'.$tmpNeedPay;
				$lm['moneyType'] = 0;
				$lm['money'] = $tmpNeedPay;
				$lm['payType'] = 'wallets';
				model('common/LogMoneys')->add($lm);
				//修改用户充值金额
				model('common/users')->where(["userId"=>$userId])->setDec("rechargeMoney",$lockCashMoney);
				$this->pinTuanSuccess($tuanNo);
				Db::commit();
				return WSTReturn('支付成功',1);
			}
			
		}catch (\Exception $e) {
			Db::rollback();
			return WSTReturn('支付失败');
		}
		
	}


	public function pinTuanSuccess($tuanNo){
		$pu = Db::name('pintuans p')->join('__PINTUAN_USERS__ pu','p.tuanId=pu.tuanId')
									->field("pu.tuanStatus,pu.needNum,p.tuanPrice,p.goodsName,p.goodsImg")
									->where(["pu.tuanNo"=>$tuanNo,"pu.isHead"=>1])
									->find();
		$tuanStatus = $pu["tuanStatus"];
		$needNum = $pu["needNum"];
		if($tuanStatus==1 && $needNum==0){
			$tuanPrice = $pu["tuanPrice"];
			$goodsName = $pu["goodsName"];
			$goodsImg = $pu["goodsImg"];

			$pusers = Db::name('pintuan_users')->where(["tuanNo"=>$tuanNo,"tuanStatus"=>1])->select();
			for($i=0,$j=count($pusers);$i<$j;$i++){
				$data = $pusers[$i];
				unset($data['refundStatus']);
				unset($data['refundTime']);
				unset($data['refundTradeNo']);
				$tuanId = $data["tuanId"];
				$userId = $data["userId"];
				$orderNo = $data["orderNo"];
				$orderType = $data["orderType"];
				$extraJson = $data["extraJson"];
				
				$puId = $data["id"];
				$goodsId = $data["goodsId"];
				$goodsNum = $data["goodsNum"];
				$commissionRate = $data["commissionRate"];

				WSTUnset($data,'id,tuanId,goodsId,tuanNo,goodsNum,tuanStatus,isHead,needNum,commissionRate');
				$data["orderCode"] = "pintuan";
				$data["dataFlag"] = 1;
				$data["isPay"] = 1;
				$data["orderStatus"] = 0;
				$data["orderCode"] = "pintuan";
				Db::name('orders')->insert($data);
				$orderId = Db::name('orders')->getLastInsID();

				//创建订单商品记录
				$orderGgoods = [];
				$orderGoods['orderId'] = $orderId;
				$orderGoods['goodsId'] = $goodsId;
				$orderGoods['goodsNum'] = $goodsNum;
				$orderGoods['goodsPrice'] = $tuanPrice;
				$orderGoods['goodsSpecId'] = 0;
				$specNams = [];
				$specs = $this->getSpecs($goodsId);
				if(!empty($specs)){
					foreach($specs as $spkey =>$svv){
						$specNams[] = $svv['name'].":".$svv['list'][0]['itemName'];
					}
					$orderGoods['goodsSpecNames'] = implode('@@_@@',$specNams);
				}
				$orderGoods['goodsType'] = 1;
				$orderGoods['extraJson'] = $extraJson;
				$orderGoods['goodsName'] = $goodsName;
				$orderGoods['goodsImg'] = $goodsImg;
				$orderGoods['commissionRate'] = $commissionRate;
				Db::name('order_goods')->insert($orderGoods);
                Db::name('pintuan_users')->where(["id"=>$puId])->update(["tuanStatus"=>2,"orderId"=>$orderId]);
				if($orderType==1){//虚拟商品
					model('common/GoodsVirtuals')->where(['orderNo'=>$orderNo,'goodsId'=>$goodsId,'dataFlag'=>1,'isUse'=>1])->update(["orderId"=>$orderId]);
					model('common/orders')->handleVirtualGoods($orderId);
				}
                //修改库存
                Db::name('pintuans')->where(["tuanId"=>$tuanId])->setDec('goodsNum',$goodsNum);
                Db::name('pintuans')->where(["tuanId"=>$tuanId])->setInc('saleNum',1);

				 
			}

			//微信消息
		    if((int)WSTConf('CONF.wxenabled')==1){
		    	$members = Db::name('pintuan_users pu')->join("__USERS__ u","pu.userId=u.userId")
					->where(["tuanNo"=>$tuanNo,"tuanStatus"=>2])
					->field("pu.id,u.userId,u.userName,u.loginName")
					->order("pu.createTime")
					->select();
				$memberNames = array();
				for($m=0,$n=count($members);$m<$n;$m++){
					$memberNames[] = $members[$m]["userName"]?$members[$m]["userName"]:$members[$m]["loginName"];
				}
				$bnames = implode("、",$memberNames);
		    	for($i=0,$j=count($pusers);$i<$j;$i++){
					$data = $pusers[$i];
					$userId = $data["userId"];
				    $params = [];
				    $params['GOODS'] = $goodsName; 
			        $params['MEMBERS'] = WSTMSubstr($bnames,0,120); 
				    $url = addon_url('pintuan://pintuan/wxpulist',array(),true,true);
		            WSTWxMessage(['CODE'=>'WX_PINTUAN_SUCCESS','userId'=>$userId,'URL'=>$url,'params'=>$params]);
				}
		    	
		    }
		}
	}


	public function pulist($uId=0){
		$ftype = (int)input("ftype/d");
		$where = [];
		$where["pu.dataFlag"] = 1;
		if($ftype==1){//待付款
			$where["pu.tuanStatus"] = 0;
		}else if($ftype==2){//待成团
			$where["pu.tuanStatus"] = 1;
		}else if($ftype==3){//已成团
			$where["pu.tuanStatus"] = 2;
		}else if($ftype==4){//已付款
			$where["pu.tuanStatus"] = -1;
		}
		$userId = ($uId==0)?(int)session('WST_USER.userId'):$uId;
		$page = Db::name('pintuans p')->join('__PINTUAN_USERS__ pu','p.tuanId=pu.tuanId')
            		 ->where(["pu.userId"=>$userId])
            		 ->field("pu.id,pu.tuanStatus,pu.orderNo,pu.tuanNo,pu.tuanId,pu.needPay,pu.goodsNum,pu.needNum,p.tuanTime,p.tuanPrice,p.tuanNum,p.goodsName,p.goodsImg,pu.createTime")
            		 ->where($where)
            		 ->order("pu.id","desc")
            		 ->paginate(input('pagesize/d'))->toArray();
        if(count($page['data'])>0){
        	$time = time();
        	foreach($page['data'] as $key =>$v){
        		$page['data'][$key]['goodsImg'] = WSTImg($v['goodsImg']); 
        		$page['data'][$key]['ftype'] = $ftype; 
        	}
        }
        $page['status'] = 1;
        return $page;
	}
	
	public function complatePay($obj){
		
		$trade_no = $obj["trade_no"];
		$orderNo = $obj["out_trade_no"];
		$userId = (int)$obj["userId"];
		$payFrom = $obj["payFrom"];
		$payMoney = (float)$obj["total_fee"];
		if($payFrom!=''){
			$cnt = Db::name('pintuan_users')
				->where(['payFrom'=>$payFrom,"userId"=>$userId,"tradeNo"=>$trade_no])
				->count();
			if($cnt>0){
				return WSTReturn('拼团已支付',-1);
			}
		}
		$where = ["pu.userId"=>$userId,"pu.tuanStatus"=>0,"pu.isPay"=>0];
		$where[] = ["needPay",">",0];
		$where['pu.orderNo'] = $orderNo;
		$ptuan = Db::name('pintuans p')
				->join('__PINTUAN_USERS__ pu','p.tuanId=pu.tuanId')
				->field('p.tuanNum,pu.tuanId,pu.isHead,pu.orderId,pu.orderNo,pu.tuanNo,pu.orderType,pu.needPay,pu.shopId,pu.payFrom,pu.realTotalMoney')
				->where($where)->find();

	    if(empty($ptuan))return WSTReturn('无效的拼团信息',-1);
		$needPay = $ptuan["needPay"];
		if($needPay>$payMoney){
			return WSTReturn('支付金额不正确',-1);
		}
		Db::startTrans();
		try{
			$where = ["tuanNo"=>$ptuan["tuanNo"]];
			$where[] = ["tuanStatus",'>=',1];
			$pnum = Db::name('pintuan_users')->where($where)->lock(true)->count();
			$tuanNum = $ptuan["tuanNum"];

			$orderId = $ptuan["orderId"];
			$tuanNo = $ptuan["tuanNo"];
			$data = [];
			$needNum = 0;

			if($tuanNum<=$pnum){
				$data["tuanNo"] = $orderNo;
				$data["isHead"] = 1;
				$needNum = $tuanNum-1;
			}else{
				$needNum = $tuanNum-$pnum-1;
			}
			$data["needNum"] = $needNum;
			//处理订单信息
			$data["tuanStatus"] = 1;
			$data["isPay"] = 1;
			$data["needPay"] = 0;
			$data["payFrom"] = $payFrom;
			$data["tradeNo"] = $trade_no;
			$result =  Db::name('pintuan_users')->where(["orderNo"=>$orderNo,"tuanStatus"=>0])->update($data);
	
			if($needPay>0 && false != $result){
				Db::name('pintuan_users')->where(["tuanNo"=>$tuanNo])->update(["needNum"=>$needNum]);

				//新增订单日志
				$logOrder = [];
				$logOrder['orderId'] = $orderId;
				$logOrder['orderStatus'] = 0;
				$logOrder['logContent'] = "订单已支付,下单成功";
				$logOrder['logUserId'] = $userId;
				$logOrder['logType'] = 0;
				$logOrder['logTime'] = date('Y-m-d H:i:s');
				Db::name('log_orders')->insert($logOrder);
				//创建一条充值流水记录
				$lm = [];
				$lm['targetType'] = 0;
				$lm['targetId'] = $userId;
				$lm['dataId'] = $orderId;
				$lm['dataSrc'] = 1;
				$lm['remark'] = '拼团订单【'.$orderNo.'】充值¥'.$needPay;
				$lm['moneyType'] = 1;
				$lm['money'] = $needPay;
				$lm['payType'] = $payFrom;
				$lm['tradeNo'] = $trade_no;
				$lm['createTime'] = date('Y-m-d H:i:s');
				model('common/LogMoneys')->add($lm);
				//创建一条支出流水记录
				$lm = [];
				$lm['targetType'] = 0;
				$lm['targetId'] = $userId;
				$lm['dataId'] = $orderId;
				$lm['dataSrc'] = 1;
				$lm['remark'] = '拼团订单【'.$orderNo.'】支出¥'.$needPay;
				$lm['moneyType'] = 0;
				$lm['money'] = $needPay;
				$lm['payType'] = 0;
				$lm['createTime'] = date('Y-m-d H:i:s');
				model('common/LogMoneys')->add($lm);
				$this->pinTuanSuccess($tuanNo);
			}else{
				
				//创建一条充值流水记录
				$lm = [];
				$lm['targetType'] = 0;
				$lm['targetId'] = $userId;
				$lm['dataId'] = $orderNo;
				$lm['dataSrc'] = 1;
				$lm['remark'] = '拼团充值¥'.$payMoney;
				$lm['moneyType'] = 1;
				$lm['money'] = $payMoney;
				$lm['payType'] = $payFrom;
				$lm['tradeNo'] = $trade_no;
				$lm['createTime'] = date('Y-m-d H:i:s');
				model('common/LogMoneys')->add($lm);
			}
			Db::commit();
			return WSTReturn('支付成功',1);
		}catch (\Exception $e) {
			Db::rollback();
			return WSTReturn('操作失败',-1);
		}
	}

	/**
	 * 用户取消拼团
	 */
	public function delTuan($uId){
		$id = (int)input('id');
		$userId = ($uId==0)?(int)session('WST_USER.userId'):$uId;
		$data = [];
		$data['tuanStatus'] = 0;
		$data['userId'] = $userId;
		$data['id'] = $id;
        $rs = Db::name('pintuan_users')->where($data)->update(['dataFlag'=>-1]);
        if($rs){
        	return WSTReturn('操作成功',1);
        }else{
        	return WSTReturn('操作失败',-1);
        }
	}
	/**
	 * 获取最新拼团的记录
	 */
	public function getLastTuan(){
		//tuanId,currPuId,maxPuId
		$tuanId = (int)input('tuanId');
		$currPuId = (int)input('currPuId');
		$maxPuId = (int)input('maxPuId');
		$puser = array();
		$where = [];
		$where["tuanStatus"] = 1;
		if($tuanId>0){
			$where["tuanId"] = $tuanId;
		}
		$newMaxPuId = Db::name('pintuan_users')->where($where)->max('id');
		$data = array();
		if($newMaxPuId>$maxPuId){
			$puser = Db::name('pintuan_users pu')->join("__USERS__ u","pu.userId=u.userId")
					->where(["pu.id"=>$newMaxPuId,"tuanStatus"=>1])
					->field("pu.id,u.userId,u.userName,u.loginName,u.userPhoto,pu.isHead,pu.createTime")
					->find();
			$data['tflag'] = 1;//查到最新拼团
			$data['maxPuId'] = $newMaxPuId;
			$uname = $puser["userName"]?$puser["userName"]:$puser["loginName"];
			if($tuanId>0){
				$data['tmsg'] = $uname." 正在拼这个商品";
			}else{
				$data['tmsg'] = $uname." 正在发起拼团";
			}
		}else{
			$puser = Db::name('pintuan_users pu')->join("__USERS__ u","pu.userId=u.userId")
					->where([["pu.id","<",$currPuId],["tuanStatus",'=',1]])
					->field("pu.id,u.userId,u.userName,u.loginName,u.userPhoto,pu.isHead,pu.createTime")
					->order("pu.id desc")
					->find();
			if(!empty($puser)){
				$data['currPuId'] = $puser["id"];
				$uname = $puser["userName"]?$puser["userName"]:$puser["loginName"];
     			$vtime = time()-WSTStrToTime($puser['createTime']);
				$stime = $this->tuanTime($vtime);
				$data['tflag'] = 2;//向前已查到记录
				if($tuanId>0){
					$data['tmsg'] = $uname." ".$stime."前拼团了这个商品";
				}else{
					$data['tmsg'] = $uname." ".$stime."前发起了拼团";
				}
				
			}else{
				$data['tflag'] = 3;//向前已查到底了
			}
		}
		if(!empty($puser)){
			$data["puser"] = $puser;
			return WSTReturn('success',1,$data);
		}else{
			return WSTReturn('没有数据',-1);
		}
		
	}
	/**
	 * 获取当前最大拼团ID
	 */
	public function getMaxPuId(){
		$where = [];
		$where["tuanStatus"] = 1;
		$newMaxPuId = Db::name('pintuan_users')->where($where)->max('id');
		return $newMaxPuId;
	}
	/**
	 * 获取商品的当前正在拼团的列表
	 */
	public function getPulist(){
		$tuanId = (int)input("id");
		$where = ['dataFlag'=>1,'tuanId'=>$tuanId];
		$tuan = $this->where($where)->field("tuanTime,tuanNum")->find();
		$pusers = Db::name('pintuan_users pu')->join("__USERS__ u","pu.userId=u.userId")
				->where(["tuanId"=>$tuanId,"tuanStatus"=>1,"isHead"=>1])
				->where("date_add(pu.createTime,interval ".$tuan['tuanTime']." hour) > '".date('Y-m-d H:i:s')."'")
				->field("pu.id,u.userId,u.userName,u.loginName,u.userPhoto,pu.isHead,pu.tuanNo,pu.createTime")
				->order("pu.createTime")
				->limit(5)
				->select();
		for($i=0,$j=count($pusers);$i<$j;$i++){
			$puser = $pusers[$i];
			$puser["userName"] = $puser["userName"]?$puser["userName"]:$puser["loginName"];

			$cdate = $puser['createTime'];
	     	$endTime = date('Y-m-d H:i:s',strtotime('+'.$tuan['tuanTime'].' hours',WSTStrToTime($cdate)));

	     	$puser["endTime"] = $endTime;
	     	$tuanNo = $puser['tuanNo'];
	     	$pnum = Db::name('pintuan_users')->where(["tuanNo"=>$tuanNo,"tuanStatus"=>1])->count();
	     	$puser["needNum"] = $tuan["tuanNum"] - $pnum;

			$remainderTime =  WSTStrToTime($endTime)-time();
			$puser["ptime"] =  $remainderTime;
			$puser["remainderTime"] =  WSTTimeToStr(WSTStrToTime($endTime)-time());
			$puser["hasTuan"] = 0;
			$pusers[$i] = $puser;
		}
		return $pusers;
	}
	/**
	 * 拼团失败，退款
	 */
	public function tuanRefund(){
		$pusers = Db::name('pintuan_users pu')->join("__USERS__ u","pu.userId=u.userId")
				->where(["tuanStatus"=>1,"isHead"=>1])
				->field("pu.id,pu.useScore, pu.tuanId,u.userId,u.userName,u.loginName,u.userPhoto,pu.isHead,pu.tuanNo,pu.createTime,pu.realTotalMoney,pu.orderNo,pu.payType,pu.payFrom")
				->select();
		for($i=0,$j=count($pusers);$i<$j;$i++){
			Db::startTrans();
			try{
				$puser = $pusers[$i];
				$puId = $puser["id"];
				$tuanId = $puser["tuanId"];
				$where = ['dataFlag'=>1,'tuanId'=>$tuanId];
				$tuan = $this->where($where)->field("tuanTime,tuanNum,goodsName")->find();
				$puser["goodsName"] = $tuan["goodsName"];
				$cdate = $puser['createTime'];
		     	$endTime = date('Y-m-d H:i:s',strtotime('+'.$tuan['tuanTime'].' hours',WSTStrToTime($cdate)));
		     	$puser["endTime"] = $endTime;
		     	$tuanNo = $puser['tuanNo'];
		     	$pnum = Db::name('pintuan_users')->where(["tuanNo"=>$tuanNo,"tuanStatus"=>1])->count();
		     	$puser["needNum"] = $tuan["tuanNum"] - $pnum;
				$remainderTime =  WSTStrToTime($endTime)-time();
				
				if($remainderTime<0){
					$realTotalMoney = $puser["realTotalMoney"];
					//创建一条充值流水记录
					if($realTotalMoney>0){
						if($puser['payType']==1 && ($puser['payFrom']=='wallets' || $puser['payFrom']=='others')){
				        	$rs = $this->saveTuanRefund($puser);
				        }else if($puser['payType']==1 && in_array($puser['payFrom'], ['weixinpays','app_weixinpays','alipays'])){
				        	Db::name('pintuan_users')->where(["id"=>$puser["id"],"tuanStatus"=>1])->update(["refundStatus"=>1]);
				        }
 
					}
				}
				Db::commit();
			}catch (\Exception $e) {
		 		Db::rollback();
		  		return WSTReturn('退款失败',-1);
		   	}
		}
		return WSTReturn('退款成功',1);
	}

	public function  saveTuanRefund($puser){
		$realTotalMoney = $puser["realTotalMoney"];
		$useScore = $puser["useScore"];
		$puId = $puser["id"];
		$refundTime = date('Y-m-d H:i:s');
		Db::name('pintuan_users')->where(["id"=>$puId,"tuanStatus"=>1])->update(["tuanStatus"=>-1,"refundStatus"=>2,"refundTime"=>$refundTime]);
		//创建一条充值流水记录
		if($realTotalMoney>0 || $useScore>0){
			$userId = $puser["userId"];
			//退钱包金额
			if($realTotalMoney>0){
				$lm = [];
				$orderNo = $puser["orderNo"];
				$lm['targetType'] = 0;
				$lm['targetId'] = $userId;
				$lm['dataId'] = $orderNo;
				$lm['dataSrc'] = 1;
				$lm['remark'] = '拼团失败，退款 ¥'.$realTotalMoney;
				$lm['moneyType'] = 1;
				$lm['money'] = $realTotalMoney;
				$lm['createTime'] = date('Y-m-d H:i:s');
				model('common/LogMoneys')->add($lm);
			}
			//积分
			if($useScore>0){
				$score = [];
				$score['userId'] = $userId;
				$score['score'] = $useScore;
				$score['dataSrc'] = 4;
				$score['dataId'] = $orderNo;
				$score['dataRemarks'] = "返还拼团失败订单【".$orderNo."】积分".$useScore."个";
				$score['scoreType'] = 1;
				model('common/UserScores')->add($score);
			}
			//发送一条用户信息
			WSTSendMsg($userId,"您的订单【".$orderNo."】拼团失败，现已退款，请留意账户到账情况。",['from'=>1,'dataId'=>$puId]);
			
			//微信消息
		    if((int)WSTConf('CONF.wxenabled')==1){
			    $params = [];
			    $params['GOODS'] = $puser['goodsName'];
		        $params['TUAN_MONEY'] = $realTotalMoney;             
			    $params['FREUND_MONEY'] = $realTotalMoney;
	            WSTWxMessage(['CODE'=>'WX_PINTUAN_REFUND','userId'=>$userId,'URL'=>Url('wechat/users/index','',true,true),'params'=>$params]);
		    } 
	    }
	}

	function tuanTime($second){
		$day = floor($second/(3600*24));
		$second = $second%(3600*24);//除去整天之后剩余的时间
		$hour = floor($second/3600);
		$second = $second%3600;//除去整小时之后剩余的时间
		$minute = floor($second/60);
		$second = $second%60;//除去整分钟之后剩余的时间
		if($day>0){
			return $day."天";
		}else if($hour>0){
			return $hour."小时";
		}else if($minute>0){
			return $minute."分钟";
		}else if($second>0){
			return $second."秒";
		}
	}
	
	/**
	 * 取消拼团订单
	 */
	public function beforeCancelOrder($params){
		$order = DB::name('orders')->where('orderId',$params['orderId'])->field('orderCode')->find();
		if($order['orderCode']=='pintuan')die('{"status":-1,msg:"对不起，拼团订单不能取消"}');
	}

	/**
	 * 完成退款
	 */
	public function complateTuanRefund($obj){
		$orderNo = $obj['orderNo'];
		$userId = $obj['userId'];
		$refundTradeNo = $obj['refundTradeNo'];
		$puser = Db::name('pintuan_users pu')->join("__PINTUANS__ p","p.tuanId=pu.tuanId","inner")
				->where(['pu.orderNo'=>$orderNo,'pu.userId'=>$userId,'pu.refundStatus'=>1])
				->field("pu.id,pu.useScore,pu.realTotalMoney,p.goodsName")
				->find();

		if(!empty($puser)){
			$realTotalMoney = $puser["realTotalMoney"];
			$useScore = $puser["useScore"];
			$puId = $puser["id"];
			Db::startTrans();
			try{
				$refundTime = date('Y-m-d H:i:s');
				Db::name('pintuan_users')->where(["id"=>$puId,"tuanStatus"=>1])->update(["tuanStatus"=>-1,"refundStatus"=>2,"refundTime"=>$refundTime,"refundTradeNo"=>$refundTradeNo]);
				//创建一条充值流水记录
				if($realTotalMoney>0 || $useScore>0){
					//积分
					if($useScore>0){
						$score = [];
						$score['userId'] = $userId;
						$score['score'] = $useScore;
						$score['dataSrc'] = 4;
						$score['dataId'] = $orderNo;
						$score['dataRemarks'] = "返还拼团失败订单【".$orderNo."】积分".$useScore."个";
						$score['scoreType'] = 1;
						model('common/UserScores')->add($score);
					}
					//发送一条用户信息
					WSTSendMsg($userId,"您的订单【".$orderNo."】拼团失败，现已退款，请留意账户到账情况。",['from'=>1,'dataId'=>$puId]);
					//微信消息
				    if((int)WSTConf('CONF.wxenabled')==1){
					    $params = [];
					    $params['GOODS'] = $puser['goodsName'];
				        $params['TUAN_MONEY'] = $realTotalMoney;             
					    $params['FREUND_MONEY'] = $realTotalMoney;
			            WSTWxMessage(['CODE'=>'WX_PINTUAN_REFUND','userId'=>$userId,'URL'=>Url('wechat/users/index','',true,true),'params'=>$params]);
				    }
			    }
			    Db::commit();
			}catch (\Exception $e) {
		 		Db::rollback();
		  		return WSTReturn('退款失败',-1);
		   	}
		}
	}

	public function batchRefund(){
		$pusers = Db::name('pintuan_users')->where(["tuanStatus"=>1,'refundStatus'=>1])->select();
		$wm = new Weixinpays();
		$appWxM = null;
		$appAliM = null;
		for($i=0,$j=count($pusers);$i<$j;$i++){
			$puser = $pusers[$i];
			switch($puser['payFrom']){
				case 'app_weixinpays':
					if($appWxM==null)$appWxM = new \addons\pintuan\model\WeixinpaysApi();
					$appWxM->tuanRefund($puser);
				break;
				case 'alipays':
					if($appAliM==null)$appAliM = new \addons\pintuan\model\AlipaysApi();
					$appAliM->tuanRefund($puser);
				break;
				default:
					$wm->tuanRefund($puser);
				break;
			}
		}
	}

	public function pageQueryByTuan(){
		$tuanId =(int)input("tuanId/d");
		$tuanStatus = (int)input("tuanStatus/d");
		$where = [];
		if($tuanStatus==0){
			$where = ["tuanStatus","in","-1,1,2"];
		}else{
			$where = ["tuanStatus","=",$tuanStatus];
		}
		$page = Db::name('pintuan_users pu')->join("__USERS__ u","pu.userId=u.userId")
									->where([["tuanId","=",$tuanId],$where])
									->field("pu.tuanNo,pu.id,u.userId,u.userName,u.loginName,u.userPhoto,pu.isHead,pu.createTime,pu.tuanStatus")
									->order("pu.createTime")
									->paginate()->toArray();
		if(count($page['data'])>0){
        	foreach($page['data'] as $key =>$v){
        		$page['data'][$key]['userPhoto'] = WSTUserPhoto($v['userPhoto']);
        		if($v['loginName']){
        			$page['data'][$key]['userName'] = $v['loginName']; 
        		}
        	}
        }
		$page['status'] = 1;
		return $page;
	}

	public function tuanByAdminPageQuery(){
		$tuanId =(int)input("tuanId/d");
		$keyword =input("key/s");
		$tuanStatus = (int)input("tuanStatus/d");
		$where = [];
		$where[] = ["tuanId","=",$tuanId];
		if($tuanStatus==0){
			$where[] = ["tuanStatus","in","-1,1,2"];
		}else{
			$where[] = ["tuanStatus","=",$tuanStatus];
		}
		if($keyword!=""){
			$where[] = ["u.userName|u.loginName","like","%".$keyword."%"];
		}
		$page = Db::name('pintuan_users pu')->join("__USERS__ u","pu.userId=u.userId")
									->where($where)
									->field("pu.tuanNo,pu.id,u.userId,u.userName,u.loginName,u.userPhoto,pu.isHead,pu.createTime,pu.tuanStatus")
									->order("pu.createTime")
									->paginate()->toArray();
		if(count($page['data'])>0){
        	foreach($page['data'] as $key =>$v){
        		$page['data'][$key]['userPhoto'] = WSTUserPhoto($v['userPhoto']);
        		if($v['loginName']){
        			$page['data'][$key]['userName'] = $v['loginName']; 
        		}
        	}
        }
		$page['status'] = 1;
		return $page;
	}

	
}

