<?php
namespace addons\distribut\model;
use think\addons\BaseModel as Base;
use think\Db;
/**
 * 分销业务处理
 */
class Distribut extends Base{
	
	/**
	 * 添加菜单、权限
	 */
	public function installMenu(){
		Db::startTrans();
		try{
			
			$hooks = array("beforeSubmitOrder","mobileControllerCartsSettlement","wechatControllerCartsSettlement","homeControllerCartsSettlement","wechatDocumentGoodsDetail",
							"wechatDocumentGoodsDetailTips","wechatDocumentUserIndex","wechatControllerGoodsIndex","mobileDocumentGoodsDetail","mobileDocumentGoodsDetailTips",
							"mobileDocumentUserIndex","mobileControllerIndexIndex","wechatControllerIndexIndex","homeDocumentGoodsDetail","homeDocumentShopHomeHeader","loadHomePage",
							"beforeEidtGoods","afterUserReceive","afterSubmitOrder","afterUserRegist","mobileControllerGoodsIndex",
							"homeDocumentShopEditGoods","initConfigHook"
					);
			$this->bindHoods("Distribut", $hooks);
			
			
			//管理员后台
			$rs = Db::name('menus')->insert(["parentId"=>56,"menuName"=>"分销管理","menuSort"=>4,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"distribut"]);
			
			if($rs!==false){
				$parentId = Db::name('menus')->getLastInsID();
				Db::name('privileges')->insert(["menuId"=>$parentId,"privilegeCode"=>"DISTRIBUT_FXGL_00","privilegeName"=>"查看分销管理","isMenuPrivilege"=>1,"privilegeUrl"=>"","otherPrivilegeUrl"=>"","dataFlag"=>1,"isEnable"=>1]);
				
				Db::name('menus')->insert(["parentId"=>$parentId,"menuName"=>"分销商家列表","menuSort"=>1,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"distribut"]);
				$menuId = Db::name('menus')->getLastInsID();
				Db::name('privileges')->insert(["menuId"=>$menuId,"privilegeCode"=>"DISTRIBUT_FXSJ_00","privilegeName"=>"查看分销商家","isMenuPrivilege"=>1,"privilegeUrl"=>"/addon/distribut-distribut-admindistributshops","otherPrivilegeUrl"=>"","dataFlag"=>1,"isEnable"=>1]);
				
				
				Db::name('menus')->insert(["parentId"=>$parentId,"menuName"=>"分销商品列表","menuSort"=>1,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"distribut"]);
				$menuId = Db::name('menus')->getLastInsID();
				Db::name('privileges')->insert(["menuId"=>$menuId,"privilegeCode"=>"DISTRIBUT_FXSP_00","privilegeName"=>"查看分销商品","isMenuPrivilege"=>1,"privilegeUrl"=>"/addon/distribut-distribut-admindistributgoods","otherPrivilegeUrl"=>"","dataFlag"=>1,"isEnable"=>1]);
				
				Db::name('menus')->insert(["parentId"=>$parentId,"menuName"=>"佣金分成列表","menuSort"=>1,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"distribut"]);
				$menuId = Db::name('menus')->getLastInsID();
				Db::name('privileges')->insert(["menuId"=>$menuId,"privilegeCode"=>"DISTRIBUT_YJFC_00","privilegeName"=>"查看佣金分成","isMenuPrivilege"=>1,"privilegeUrl"=>"/addon/distribut-distribut-admindistributmoneys","otherPrivilegeUrl"=>"","dataFlag"=>1,"isEnable"=>1]);
				
				Db::name('menus')->insert(["parentId"=>$parentId,"menuName"=>"推广用户列表","menuSort"=>1,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"distribut"]);
				$menuId = Db::name('menus')->getLastInsID();
				Db::name('privileges')->insert(["menuId"=>$menuId,"privilegeCode"=>"DISTRIBUT_TGYH_00","privilegeName"=>"查看推广用户","isMenuPrivilege"=>1,"privilegeUrl"=>"/addon/distribut-distribut-admindistributusers","otherPrivilegeUrl"=>"","dataFlag"=>1,"isEnable"=>1]);
				
			}
			
			$now = date("Y-m-d H:i:s");
			//用户中心
			$rs = Db::name('home_menus')->insert(["parentId"=>100,"menuName"=>"分销管理","menuUrl"=>"#","menuOtherUrl"=>"","menuType"=>0,"isShow"=>1,"menuSort"=>1,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"distribut"]);
			if($rs!==false){
				$parentId = Db::name('home_menus')->getLastInsID();
				Db::name('home_menus')->insert(["parentId"=>$parentId,"menuName"=>"我的推广用户","menuUrl"=>"addon/distribut-distribut-userdistributusers","menuOtherUrl"=>"addon/distribut-distribut-querymineusers","menuType"=>0,"isShow"=>1,"menuSort"=>1,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"distribut"]);
				Db::name('home_menus')->insert(["parentId"=>$parentId,"menuName"=>"分成记录","menuUrl"=>"addon/distribut-distribut-userdistributmoneys","menuOtherUrl"=>"addon/distribut-distribut-queryusermoneys","menuType"=>0,"isShow"=>1,"menuSort"=>2,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"distribut"]);
			}
			
			//商家中心
			$rs = Db::name('home_menus')->insert(["parentId"=>76,"menuName"=>"分销管理","menuUrl"=>"#","menuOtherUrl"=>"","menuType"=>1,"isShow"=>1,"menuSort"=>1,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"distribut"]);
			if($rs!==false){
				$parentId = Db::name('home_menus')->getLastInsID();
				Db::name('home_menus')->insert(["parentId"=>$parentId,"menuName"=>"分销商品","menuUrl"=>"addon/distribut-distribut-shopdistributgoods","menuOtherUrl"=>"addon/distribut-distribut-querydistributgoods","menuType"=>1,"isShow"=>1,"menuSort"=>1,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"distribut"]);
				Db::name('home_menus')->insert(["parentId"=>$parentId,"menuName"=>"分成记录","menuUrl"=>"addon/distribut-distribut-shopdistributmoneys","menuOtherUrl"=>"addon/distribut-distribut-querydistributmoneys","menuType"=>1,"isShow"=>1,"menuSort"=>2,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"distribut"]);
				Db::name('home_menus')->insert(["parentId"=>$parentId,"menuName"=>"分销设置","menuUrl"=>"addon/distribut-distribut-shopdistributcfg","menuOtherUrl"=>"","menuType"=>1,"isShow"=>1,"menuSort"=>3,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"distribut"]);
			}
			
			installSql("distribut");//传入插件名
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
	public function uninstallMenu(){
		Db::startTrans();
		try{
			
			$hooks = array("beforeSubmitOrder","mobileControllerCartsSettlement","wechatControllerCartsSettlement","homeControllerCartsSettlement","wechatDocumentGoodsDetail",
							"wechatDocumentGoodsDetailTips","wechatDocumentUserIndex","wechatControllerIndexIndex","wechatControllerGoodsIndex","mobileDocumentGoodsDetail","mobileDocumentGoodsDetailTips",
							"mobileDocumentUserIndex","mobileControllerIndexIndex","homeDocumentGoodsDetail","homeDocumentShopHomeHeader","loadHomePage",
							"beforeEidtGoods","afterUserReceive","afterSubmitOrder","afterUserRegist","mobileControllerGoodsIndex",
							"homeDocumentShopEditGoods","initConfigHook"
					);
			$this->unbindHoods("Distribut", $hooks);
			
			Db::name('menus')->where("menuMark",'=',"distribut")->delete();
			Db::name('home_menus')->where("menuMark",'=',"distribut")->delete();
			Db::name('privileges')->where("privilegeCode","like","DISTRIBUT_%")->delete();

			uninstallSql("distribut");//传入插件名
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
		$data["btnName"] = "分销商品";
		$data["btnSrc"] = 0;
		$data["btnUrl"] = "/addon/distribut-distribut-mobiledistributgoods";
		$data["btnImg"] = "addons/distribut/view/images/distribut.png";
		$data["addonsName"] = "Distribut";
		$data["btnSort"] = 5;
		Db::name('mobile_btns')->insert($data);
		
		$data = array();
		$data["btnName"] = "分销商品";
		$data["btnSrc"] = 1;
		$data["btnUrl"] = "/addon/distribut-distribut-wechatdistributgoods";
		$data["btnImg"] = "addons/distribut/view/images/distribut.png";
		$data["addonsName"] = "Distribut";
		$data["btnSort"] = 5;
		Db::name('mobile_btns')->insert($data);

	}
	
	public function delMobileBtn(){
	
		Db::name('mobile_btns')->where(["addonsName"=>"Distribut"])->delete();
	
	}
	
	/**
	 * 菜单显示隐藏
	 */
	public function toggleShow($isShow = 1){
		Db::startTrans();
		try{
			Db::name('menus')->where("menuMark",'=',"distribut")->update(["isShow"=>$isShow]);
			Db::name('home_menus')->where("menuMark",'=',"distribut")->update(["isShow"=>$isShow]);
			Db::name('navs')->where(["navUrl"=>"addon/distribut-goods-glist"])->update(["isShow"=>$isShow]);
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
	 * 获取店铺分销配置
	 */
	public function getDistributCfg($shopId=0){
		if($shopId==0){
			$shopId = (int)session("WST_USER.shopId");
		}
		$conf = Db::name('shop_configs')->where("shopId", $shopId)->field("isDistribut,distributType,distributOrderRate")->find();
		return $conf;
	}
	
	/**
	 * 修改配置
	 */
	public function saveCfg(){
		$shopId = (int)session("WST_USER.shopId");
		$data = array();
		$data["isDistribut"] = input("isDistribut/d",0);
		$data["distributType"] = input("distributType/d",0);
		$orderNum = (int)input("orderNum/d",0);
		if($data["distributType"]==1){
			$data["distributOrderRate"] = 0;
		}else{
			if($orderNum<=0){
				return WSTReturn("订单佣金比例必须大于0%",-1);
			}else if($orderNum>=100){
				return WSTReturn("订单佣金比例必须小于100%",-1);
			}
			$data["distributOrderRate"] = $orderNum;
		}
		$data["distributOrderRate"] = $data["distributType"]==1?0:input("orderNum/d",0);
		$config = Db::name('shop_configs')->where("shopId", $shopId)->field("distributType")->find();
		$rs = Db::name('shop_configs')->where("shopId", $shopId)->update($data);
		if($data["isDistribut"]==0){
			Db::name('goods')->where(["shopId"=>$shopId,"dataFlag"=>1])->update(["isDistribut"=>0,"commission"=>0]);
		}else{
			if($data["distributType"]==2){////按订单分佣
				Db::name('goods')->where(["shopId"=>$shopId,"dataFlag"=>1])->update(["isDistribut"=>1,"commission"=>0]);
			}else{
				if($config["distributType"]==2){
					Db::name('goods')->where(["shopId"=>$shopId,"dataFlag"=>1])->update(["isDistribut"=>0,"commission"=>0]);
				}
			}
		}
		return WSTReturn("设置成功",1);
		
	}
	
	/**
	 * 获取商品分销设置
	 */
	public function getGoodsDistribut($goodsId){
		
		return Db::name('goods')->where("goodsId",$goodsId)->field("isDistribut,commission")->find();
	}
	
	/**
	 * 店铺分成记录
	 */
	public function queryDistributMoneys(){
		$shopId = (int)session('WST_USER.shopId');
		$where = array();
		$where[] = ["dm.shopId",'=',$shopId];
		$orderNo = input("orderNo");
		$userName = input("userName");
		if($orderNo!=""){
			$where[] = ["o.orderNo","like",$orderNo."%"];
		}
		if($userName!=""){
			$where[] = ["o.userName|o.loginName","like",$orderNo."%"];
		}
		$rs = Db::name('distribut_moneys dm')
				->join("__USERS__ u","u.userId=dm.userId")
				->join("__ORDERS__ o","o.orderId=dm.orderId")
				->where($where)
				->field("u.userId,u.userName,u.loginName,dm.moneyId,dm.money,dm.distributMoney,dm.remark,dm.createTime,dm.distributType,o.orderId,o.orderNo")
				->order('dm.moneyId', 'desc')
				->paginate(input('pagesize/d'))->toArray();
		return $rs;
	}
	
	/**
	 *  分销商品列表
	 */
	public function queryDistributGoods(){
		$shopId = (int)session('WST_USER.shopId');
		$shopConf = Db::name('shop_configs')->where("shopId",$shopId)->field("distributType,distributOrderRate")->find();
		$where = [];
		$where[] = ['shopId','=',$shopId];
		$where[] = ['goodsStatus','=',1];
		$where[] = ['dataFlag','=',1];
		$where[] = ['isSale','=',1];
		$where[] = ['isDistribut','=',1];
		$c1Id = (int)input('cat1');
		$c2Id = (int)input('cat2');
		$goodsName = input('goodsName');
		if($goodsName != ''){
			$where[] = ['goodsName','like',"%$goodsName%"];
		}
		if($c2Id!=0 && $c1Id!=0){
			$where[] = ['shopCatId2','=',$c2Id];
		}else if($c1Id!=0){
			$where[] = ['shopCatId1','=',$c1Id];
		}
		$where[] = ['shopId','=',$shopId];
		$rs = Db::name('goods')
			->where($where)
			->field('goodsId,goodsName,goodsImg,goodsSn,isSale,isBest,isHot,isNew,isRecom,goodsStock,saleNum,shopPrice,isSpec,commission')
			->order('saleTime', 'desc')
			->paginate(input('pagesize/d'))->toArray();
		foreach ($rs['data'] as $key => $v){
			$rs['data'][$key]['verfiycode'] = WSTShopEncrypt($shopId);
			$rs['data'][$key]['distributType'] = $shopConf["distributType"];
			$rs['data'][$key]['distributType'] = $shopConf["distributType"];
			$rs['data'][$key]['distributOrderRate'] = $shopConf["distributOrderRate"];
		}

		return $rs;
	}
	
	/**
	 * 用户注册设置
	 */
	public function userRegist($userId){
		
		$shareUserId = (int)session("WST_shareUserId");
		if($shareUserId>0){
			$addon = Db::name('addons')->where("name","Distribut")->field("config")->find();
			if($addon){
				$config = json_decode($addon["config"],true);
				$distributDeal = $config["distributDeal"];
				$sharer = Db::name('users')->where("userId",$shareUserId)->field("isBuyer")->find();
				if($distributDeal==1 || ($sharer["isBuyer"]==1 && $distributDeal==2)){
						
					$puser = Db::name('distribut_users')->where("userId",$shareUserId)->field("parentId")->find();
					$data = array();
					$data["grandpaId"] = isset($puser["parentId"])?$puser["parentId"]:0;
					$data["parentId"] = $shareUserId;
					$data["userId"] = $userId;
					$data["createTime"] = date("Y-m-d H:i:s");
					$data['ip'] = request()->ip();
					Db::name('distribut_users')->insert($data);
					session('WST_shareUserId', null);
				}
			}
		}
		return true;
	}
	
	/**
	 * 获取配置
	 */
	public function getAddonConfig(){
		$addon = Db::name('addons')->where("name","Distribut")->field("config")->find();
		$config = json_decode($addon["config"],true);
		return $config;
	}
	
	/**
	 * 设置订单分销信息
	 */
	public function setOrderDistribut($orderId){
		
		$order = Db::name('orders')->where("orderId",$orderId)->field("shopId,scoreMoney,realTotalMoney,userId")->find();
		$shopId = $order["shopId"];
		$userId = $order["userId"];
		
		$duser = Db::name('distribut_users')->where("userId",$userId)->field("parentId,userId")->find();
		$parentId = isset($duser["parentId"])?(int)$duser["parentId"]:0;
		$conf = self::getDistributCfg($shopId);
		
		$data = array();
		$data["distributOrderRate"] = $conf['distributOrderRate'];
		if($conf["isDistribut"]==1 && $parentId>0){
			$orderRealMoney = $order["scoreMoney"]+$order["realTotalMoney"];
			$data["distributType"] = $conf['distributType'];
			if($conf['distributType']==1){//按商品提成佣金
				$totalCommission = 0;//总佣金
				$where = array();
				$where["orderId"] = $orderId;
				$goodslist = Db::name('order_goods og')->join("__GOODS__ g","g.goodsId=og.goodsId")
							->where($where)
							->field("og.id,g.commission,og.goodsNum,og.goodsPrice")
							->select();

				foreach ($goodslist as $key=> $goods){
					$commission = ($goods["commission"]<$goods["goodsPrice"])?$goods["commission"]:0;
					Db::name('order_goods')->where("id",$goods["id"])->update(array("commission"=>$commission));
					$totalCommission = $totalCommission + round($goods["goodsNum"]*$commission,2);
				}
				if($orderRealMoney<$totalCommission){
					$data["totalCommission"] = $orderRealMoney;
				}else{
					$data["totalCommission"] = $totalCommission;
				}
			}else if($conf['distributType']==2){//按订单比例提成佣金
				$data["totalCommission"] = round(($order["scoreMoney"]+$order["realTotalMoney"])*$conf["distributOrderRate"]/100,2);
			}
		}else{
			$data["distributType"] = 0;
			$data["totalCommission"] = 0;
		}
		Db::name('orders')->where("orderId",$orderId)->update($data);
		
		return true;
		
	}
	
	/**
	 * 用户确认收货
	 */
	public function userReceive($orderId){
		$order = Db::name('orders')->where("orderId",$orderId)->find();
		$userId = $order["userId"];
		$shopId = $order["shopId"];
		$duser = Db::name('distribut_users')->where("userId",$userId)->field("grandpaId,parentId,userId")->find();
		
		Db::name('users')->where("userId",$userId)->update(["isBuyer"=>1]);
		
		if(!empty($duser)){
			$grandpaId = (int)$duser["grandpaId"];
			$parentId = (int)$duser["parentId"];
			$cfg = self::getAddonConfig();
			$thirdRate = $cfg["thirdRate"] ;
			$secondRate = $cfg["secondRate"];
			$buyerRate = $cfg["buyerRate"];
			if(($thirdRate+$secondRate+$buyerRate)!=100){
				return ;
			}
			$conf = self::getDistributCfg($shopId);
			if($conf["distributType"]==1){//按商品分成
				$goodslist = Db::name('order_goods')->where("orderId",$orderId)->field("goodsId,goodsName,goodsNum,goodsPrice,commission")->select();
				
				foreach ($goodslist as $key=> $goods){
					if($goods['commission']>0){
						//第三级
						$thirdMoney = 0;
						if($grandpaId>0){
							$thirdMoney = round(($goods['goodsNum']*$goods['commission']*$thirdRate/100),2);
							if($thirdMoney>0){
								$obj = array();
								$obj["shopId"] = $shopId;
								$obj["orderId"] = $orderId;
								$obj["userId"] = $grandpaId;
								$obj["buyerId"] = $userId;
								$obj["remark"] = "商品【".$goods["goodsName"]."】";
								$obj["distributType"] = 1;
								$obj["dataId"] = $goods["goodsId"];
								$obj["money"] = $goods["goodsPrice"]*$goods["goodsNum"];
								$obj["distributMoney"] = $thirdMoney;
								$obj["createTime"] = date("Y-m-d H:i:s");
								$obj["moneyType"] = 3;
								Db::name('distribut_moneys')->insert($obj);
								
								$data = array();
								$data["userMoney"] = array("exp","userMoney+".$thirdMoney);
								$data["distributMoney"] = array("exp","distributMoney+".$thirdMoney);
								Db::name('users')->where("userId",$grandpaId)->update($data);
								
								$data = array();
								$data["targetType"] = 0;
								$data["targetId"] = $grandpaId;
								$data["remark"] = "获得商品【".$goods["goodsName"]."】".$thirdRate."%的佣金 ¥".$thirdMoney;
								$data["dataSrc"] = 10000;
								$data["dataId"] = $goods["goodsId"];
								$data["money"] = $thirdMoney;
								$data["tradeNo"] = 0;
								$data["payType"] = 0;
								$data["moneyType"] = 1;
								$data["createTime"] = date('Y-m-d H:i:s');
								Db::name('log_moneys')->insert($data);
							}
						}else{
							$thirdRate = 0;
						}
						
						//第二级
						if($grandpaId==0){
							$secondRate = 100 - $thirdRate - $buyerRate;
						}
						$secondMoney = round(($goods['goodsNum']*$goods['commission']*$secondRate/100),2);
						if($secondMoney>0){
							$obj = array();
							$obj["shopId"] = $shopId;
							$obj["orderId"] = $orderId;
							$obj["userId"] = $parentId;
							$obj["buyerId"] = $userId;
							$obj["remark"] = "商品【".$goods["goodsName"]."】";
							$obj["distributType"] = 1;
							$obj["dataId"] = $goods["goodsId"];
							$obj["money"] = $goods["goodsPrice"]*$goods["goodsNum"];
							$obj["distributMoney"] = $secondMoney;
							$obj["createTime"] = date("Y-m-d H:i:s");
							$obj["moneyType"] = 2;
							Db::name('distribut_moneys')->insert($obj);
							
							$data = array();
							$data["userMoney"] = array("exp","userMoney+".$secondMoney);
							$data["distributMoney"] = array("exp","distributMoney+".$secondMoney);
							Db::name('users')->where("userId",$parentId)->update($data);
							
							$data = array();
							$data["targetType"] = 0;
							$data["targetId"] = $parentId;
							$data["remark"] = "获得商品【".$goods["goodsName"]."】".$secondRate."%的佣金 ¥".$secondMoney;
							$data["dataSrc"] = 10000;
							$data["dataId"] = $goods["goodsId"];
							$data["money"] = $secondMoney;
							$data["tradeNo"] = 0;
							$data["payType"] = 0;
							$data["moneyType"] = 1;
							$data["createTime"] = date('Y-m-d H:i:s');
							Db::name('log_moneys')->insert($data);
						
						}
						
						//购买者
						$buyerMoney = round(($goods['goodsNum']*$goods['commission']*$buyerRate/100),2);
						if($buyerMoney>0){
							$obj = array();
							$obj["shopId"] = $shopId;
							$obj["orderId"] = $orderId;
							$obj["userId"] = $userId;
							$obj["buyerId"] = $userId;
							$obj["remark"] = "商品【".$goods["goodsName"]."】";
							$obj["distributType"] = 1;
							$obj["dataId"] = $goods["goodsId"];
							$obj["money"] = $goods["goodsPrice"]*$goods["goodsNum"];
							$obj["distributMoney"] = $buyerMoney;
							$obj["createTime"] = date("Y-m-d H:i:s");
							$obj["moneyType"] = 1;
							Db::name('distribut_moneys')->insert($obj);
						
							$data = array();
							$data["userMoney"] = array("exp","userMoney+".$buyerMoney);
							$data["distributMoney"] = array("exp","distributMoney+".$buyerMoney);
							Db::name('users')->where("userId",$userId)->update($data);
							
							$data = array();
							$data["targetType"] = 0;
							$data["targetId"] = $userId;
							$data["remark"] = "获得商品【".$goods["goodsName"]."】".(100-$thirdRate-$secondRate)."%的佣金 ¥".$buyerMoney;
							$data["dataSrc"] = 10000;
							$data["dataId"] = $goods["goodsId"];
							$data["money"] = $buyerMoney;
							$data["tradeNo"] = 0;
							$data["payType"] = 0;
							$data["moneyType"] = 1;
							$data["createTime"] = date('Y-m-d H:i:s');
							Db::name('log_moneys')->insert($data);
							
						}
					}
				}
			}else if($conf["distributType"]==2){//按订单分成
				$totalCommission = $order["totalCommission"];
				
				//第三级
				$thirdMoney = 0;
				if($grandpaId>0){
					$thirdMoney = round(($totalCommission*$thirdRate/100),2);
					if($thirdMoney>0){
						$obj = array();
						$obj["shopId"] = $shopId;
						$obj["orderId"] = $orderId;
						$obj["userId"] = $grandpaId;
						$obj["buyerId"] = $userId;
						$obj["remark"] = "订单【".$order["orderNo"]."】";;
						$obj["distributType"] = 1;
						$obj["dataId"] = $orderId;
						$obj["money"] = $order["goodsMoney"];
						$obj["distributMoney"] = $thirdMoney;
						$obj["createTime"] = date("Y-m-d H:i:s");
						$obj["moneyType"] = 3;
						Db::name('distribut_moneys')->insert($obj);
						
						$data = array();
						$data["userMoney"] = array("exp","userMoney+".$thirdMoney);
						$data["distributMoney"] = array("exp","distributMoney+".$thirdMoney);
						Db::name('users')->where("userId",$grandpaId)->update($data);
							
						$data = array();
						$data["targetType"] = 0;
						$data["targetId"] = $grandpaId;
						$data["remark"] = "获得订单【".$order["orderNo"]."】".$thirdRate."%的佣金 ¥".$thirdMoney;
						$data["dataSrc"] = 10000;
						$data["dataId"] = $orderId;
						$data["money"] = $thirdMoney;
						$data["tradeNo"] = 0;
						$data["payType"] = 0;
						$data["moneyType"] = 1;
						$data["createTime"] = date('Y-m-d H:i:s');
						Db::name('log_moneys')->insert($data);
					}
				}else{
					$thirdRate = 0;
				}
				
				//第二级
				if($grandpaId==0){
					$secondRate = 100 - $thirdRate - $buyerRate;
				}
				$secondMoney = round(($totalCommission*$secondRate/100),2);
				if($secondMoney>0){
					$obj = array();
					$obj["shopId"] = $shopId;
					$obj["orderId"] = $orderId;
					$obj["userId"] = $parentId;
					$obj["buyerId"] = $userId;
					$obj["remark"] = "订单【".$order["orderNo"]."】";;
					$obj["distributType"] = 1;
					$obj["dataId"] = $orderId;
					$obj["money"] = $order["goodsMoney"];
					$obj["distributMoney"] = $secondMoney;
					$obj["createTime"] = date("Y-m-d H:i:s");
					$obj["moneyType"] = 2;
					Db::name('distribut_moneys')->insert($obj);
					$data = array();
					$data["userMoney"] = array("exp","userMoney+".$secondMoney);
					$data["distributMoney"] = array("exp","distributMoney+".$secondMoney);
					Db::name('users')->where("userId",$parentId)->update($data);
					$data = array();
					$data["targetType"] = 0;
					$data["targetId"] = $parentId;
					$data["remark"] = "获得订单【".$order["orderNo"]."】".$secondRate."%的佣金 ¥".$secondMoney;
					$data["dataSrc"] = 10000;
					$data["dataId"] = $orderId;
					$data["money"] = $secondMoney;
					$data["tradeNo"] = 0;
					$data["payType"] = 0;
					$data["moneyType"] = 1;
					$data["createTime"] = date('Y-m-d H:i:s');
					Db::name('log_moneys')->insert($data);
				}
				//购买者
				$buyerMoney = round(($totalCommission*$buyerRate/100),2);
				if($buyerMoney>0){
					$obj = array();
					$obj["shopId"] = $shopId;
					$obj["orderId"] = $orderId;
					$obj["userId"] = $userId;
					$obj["buyerId"] = $userId;
					$obj["remark"] = "订单【".$order["orderNo"]."】";;
					$obj["distributType"] = 1;
					$obj["dataId"] = $orderId;
					$obj["money"] = $order["goodsMoney"];
					$obj["distributMoney"] = $buyerMoney;
					$obj["createTime"] = date("Y-m-d H:i:s");
					$obj["moneyType"] = 1;
					Db::name('distribut_moneys')->insert($obj);
				
					$data = array();
					$data["userMoney"] = array("exp","userMoney+".$buyerMoney);
					$data["distributMoney"] = array("exp","distributMoney+".$buyerMoney);
					Db::name('users')->where("userId",$userId)->update($data);
				
					$data = array();
					$data["targetType"] = 0;
					$data["targetId"] = $userId;
					$data["remark"] = "获得订单【".$order["orderNo"]."】".(100-$thirdRate-$secondRate)."%的佣金 ¥".$buyerMoney;
					$data["dataSrc"] = 10000;
					$data["dataId"] = $orderId;
					$data["money"] = $buyerMoney;
					$data["tradeNo"] = 0;
					$data["payType"] = 0;
					$data["moneyType"] = 1;
					$data["createTime"] = date('Y-m-d H:i:s');
					Db::name('log_moneys')->insert($data);
				}
				
			}
		}
		//修改结算佣金
		$data = array();
		$data["commissionFee"] = array("exp","commissionFee+totalCommission");
		Db::name('orders')->where("orderId",$orderId)->update($data);
		return true;
		
	}
	
	
	/**
	 * 用户分成记录
	 */
	public function queryUserMoneys($uId=0){
		$userId = ($uId==0)?(int)session('WST_USER.userId'):$uId;
		$where = array();
		$where[] = ["dm.userId",'=',$userId];
		$orderNo = input("orderNo");
		$userName = input("userName");
		$type = input("type/d",0);
		if($orderNo!=""){
			$where[] = ["o.orderNo","like",$orderNo."%"];
		}
		if($userName!=""){
			$where[] = ["u.userName|u.loginName","like",$userName."%"];
		}
		if($type==1){
			$where[] = ["dm.moneyType",'=',1];
		}else if($type==2){
			$where[] = ["dm.moneyType","gt",1];
		}
		$rs = Db::name('distribut_moneys dm')
				->join("__USERS__ u","u.userId=dm.userId")
				->join("__ORDERS__ o","o.orderId=dm.orderId")
				->where($where)
				->field("u.userId,u.userName,u.loginName,dm.moneyId,dm.money,dm.distributMoney,dm.remark,dm.createTime,dm.distributType,o.orderId,o.orderNo")
				->order('dm.moneyId', 'desc')
				->paginate(input('pagesize/d'))->toArray();
		return $rs;
	}
	
	/**
	 * 我的推广用户
	 */
	public function queryMineUsers($uId=0){
		$userId = ($uId==0)?(int)session('WST_USER.userId'):$uId;
		$where = array();
		$where[] = ["dm.parentId",'=',$userId];
		$userName = input("userName");
		if($userName!=""){
			$where[] = ["u.userName|u.loginName","like",$userName."%"];
		}
		$rs = Db::name('distribut_users dm')
				->join("__USERS__ u","u.userId=dm.userId")
				->where($where)
				->field("u.userId,u.userName,u.loginName,u.userSex,u.createTime,u.userPhoto")
				->order('u.userId', 'desc')
				->paginate(input('pagesize/d'))->toArray();
		$userIds = array();
		foreach ($rs['data'] as $key => $v){
			$userIds[] = $v["userId"];
		}
		$where = array();
		$where[] = ["parentId","in",implode(",",$userIds)];
		$pusers = Db::name('distribut_users')->where($where)->field("parentId,count(parentId) userCnt")->group("parentId")->select();
		
		$ulist = array();
		foreach ($pusers as $key => $v){
			$ulist[$v["parentId"]] = $v["userCnt"];
		}
		foreach ($rs['data'] as $key => $v){
			$rs['data'][$key]['userCnt'] = isset($ulist[$v["userId"]])?$ulist[$v["userId"]]:0;
			$rs['data'][$key]['userPhoto'] = WSTUserPhoto($v['userPhoto']);
		}
		return $rs;
	}
	
	
	public function queryAdminDistributUsers(){
		
		$where = array();
		$where[] = ['dataFlag','=',1];
		$userId = input('userId/d');
		
		$lName = input('loginName');
		$phone = input('loginPhone');
		$email = input('loginEmail');
		if(!empty($lName))
			$where[] = ['loginName','like',"%$lName%"];
		if(!empty($phone))
			$where[] = ['userPhone','like',"%$phone%"];
		if(!empty($email))
			$where[] = ['userEmail','like',"%$email%"];
		
		$rs = Db::name('distribut_users dm')
			->join("__USERS__ u","u.userId=dm.parentId")
			->field("dm.parentId,u.userId,u.userName,u.loginName,u.userSex,u.createTime,u.userPhone,u.userEmail,u.userScore,u.userStatus,u.distributMoney,count(u.userId) userCnt")
			->where($where)
			->group("parentId")
			->order('u.userId', 'desc')
			->paginate(input('limit/d'))->toArray();

		$userIds = array();
		foreach ($rs['data'] as $key => $v){
			$userIds[] = $v["userId"];
		}
		
		return $rs;
	}
	
	public function queryAdminDistributChildUsers(){
	
		$where = array();
		$where[] = ['dataFlag','=',1];
		$userId = input('userId/d');
		$where[] = ["dm.parentId",'=',$userId];
	
		$lName = input('loginName');
		$phone = input('loginPhone');
		$email = input('loginEmail');
		if(!empty($lName))
			$where[] = ['loginName','like',"%$lName%"];
		if(!empty($phone))
			$where[] = ['userPhone','like',"%$phone%"];
		if(!empty($email))
			$where[] = ['userEmail','like',"%$email%"];
	
		$rs = Db::name('distribut_users dm')
		->join("__USERS__ u","u.userId=dm.userId")
		->field("dm.parentId,u.userId,u.userName,u.loginName,u.userSex,u.createTime,u.userPhone,u.userEmail,u.userScore,u.userStatus,u.distributMoney")
		->where($where)
		->order('u.userId', 'desc')
		->paginate(input('limit/d'))->toArray();
	
		$userIds = array();
		foreach ($rs['data'] as $key => $v){
			$userIds[] = $v["userId"];
		}
	
		return $rs;
	}
	
	public function queryAdminDistributShops(){
		
		$shopSn = input('get.shopSn');
		$shopName = input('get.shopName');
		$shopkeeper = input('get.shopkeeper');
		$where = array();
		if(!empty($shopSn))
			$where[] = ['shopSn','like',"%$shopSn%"];
		if(!empty($phone))
			$where[] = ['shopName','like',"%$shopName%"];
		if(!empty($email))
			$where[] = ['shopkeeper','like',"%$shopkeeper%"];
		
		$rs = Db::name('shops s')
				->join('__SHOP_CONFIGS__ sc','s.shopId=sc.shopId and sc.isDistribut=1')
				->join('__AREAS__ a2','s.areaId=a2.areaId','left')
				->where(['s.dataFlag'=>1,'s.dataFlag'=>1,'s.shopStatus'=>1])->where($where)
				->field('s.shopId,shopSn,shopName,a2.areaName,shopkeeper,telephone,shopAddress,shopCompany,shopAtive,shopStatus,distributType')
				->order('s.shopId desc')
				->paginate(input('limit/d'))->toArray();
		
		$cfg = self::getAddonConfig();
		$thirdRate = $cfg["thirdRate"] ;
		$secondRate = $cfg["secondRate"];
		$buyerRate = $cfg["buyerRate"];
		
		foreach ($rs['data'] as $key => $v){
			$rs['data'][$key]['thirdRate'] = $thirdRate."%";
			$rs['data'][$key]['secondRate'] = $secondRate."%";
			$rs['data'][$key]['buyerRate'] = $buyerRate."%";
		}
		return $rs;
	}
	
	public function queryAdminDistributGoods(){
		
		$where = array();
		$where[] = ['g.goodsStatus','=',1];
		$where[] = ['g.dataFlag','=',1];
		$where[] = ['g.isSale','=',1];
		$where[] = ['g.isDistribut','=',1];
		$goodsName = input('goodsName');
		$shopName = input('shopName');
		
		if($goodsName != '')$where[] = ['goodsName|goodsSn','like',"%$goodsName%"];
		if($shopName != '')$where[] = ['shopName|shopSn','like',"%$shopName%"];
		$keyCats = self::listKeyAll();
		
		$rs = Db::name('goods g')
				->join('__SHOPS__ s','g.shopId=s.shopId')
				->join('__SHOP_CONFIGS__ sc','sc.shopId=s.shopId')
				->where($where)
				->field('goodsId,goodsName,goodsSn,saleNum,shopPrice,g.shopId,goodsImg,s.shopName,goodsCatIdPath,commission,sc.distributType')
				->order('saleTime', 'desc')
				->paginate(input('limit/d'))->toArray();
		
		foreach ($rs['data'] as $key => $v){
			$rs['data'][$key]['verfiycode'] = WSTShopEncrypt($v['shopId']);
			$rs['data'][$key]['goodsCatName'] = self::getGoodsCatNames($v['goodsCatIdPath'],$keyCats);
		}
		return $rs;
		
	}
	
	public function listKeyAll(){
		$rs = Db::name('goods_cats')->field("catId,catName")->where(['dataFlag'=>1])->order('catSort asc,catName asc')->select();
		$data = array();
		foreach ($rs as $key => $cat) {
			$data[$cat["catId"]] = $cat["catName"];
		}
		return $data;
	}
	
	public function getGoodsCatNames($goodsCatPath, $keyCats){
		$catIds = explode("_",$goodsCatPath);
		$catNames = array();
		for($i=0,$k=count($catIds);$i<$k;$i++){
			if($catIds[$i]=='')continue;
			if(isset($keyCats[$catIds[$i]]))$catNames[] = $keyCats[$catIds[$i]];
		}
		return implode("→",$catNames);
	}
	
	
	/**
	 * 用户分成记录
	 */
	public function queryAdminDistributMoneys(){
		$where = array();
		$orderNo = input("orderNo");
		$userName = input("userName");
		if($orderNo!=""){
			$where[] = ["o.orderNo","like",$orderNo."%"];
		}
		if($userName!=""){
			$where[] = ["u.userName|u.loginName","like",$userName."%"];
		}
		$rs = Db::name('distribut_moneys dm')
				->join("__USERS__ u","u.userId=dm.userId")
				->join("__ORDERS__ o","o.orderId=dm.orderId")
				->where($where)
				->field("u.userId,u.userName,u.loginName,dm.moneyId,dm.money,dm.remark,dm.distributMoney,dm.createTime,dm.distributType,o.orderId,o.orderNo")
				->order('dm.moneyId', 'desc')
				->paginate(input('limit/d'))->toArray();
		foreach ($rs['data'] as $key => $v){
			$rs['data'][$key]['userName'] = $rs['data'][$key]['userName']!=""?$rs['data'][$key]['userName']:$rs['data'][$key]['loginName'];
		}
		return $rs;
	}
	
	/**
	 * 会员中心
	 */
	public function getUserInfo($uId=0){
		$userId = $uId==0?session('WST_USER.userId'):$uId;
		$user =  Db::name('users')->where(['userId'=>$userId])->find();
		$user['ranks'] = Db::name('user_ranks')->where(['dataFlag'=>1])->where('startScore','<=',$user['userTotalScore'])->where('endScore','>=',$user['userTotalScore'])->find();
		if($user['userName']=='')$user['userName']=$user['loginName'];
		$cnt = Db::name('distribut_users')->where(['parentId'=>$userId])->count();
		$user["userCnt"] = $cnt;
		return $user;
		
	}
	
	/**
	 * 会员中心
	 */
	public function getUser($uId=0){
		$userId = $uId==0?session('WST_USER.userId'):$uId;
		$user =  Db::name('users')->where(['userId'=>$userId])->field("distributMoney")->find();
		$cnt = Db::name('distribut_users')->where(['parentId'=>$userId])->count();
		$user["userCnt"] = $cnt;
		return $user;
	}

	/**
	 * 根据指定的商品分类获取其路径
	 */
	function WSTGoodsCat(){
		$rs = Db::table('__GOODS_CATS__')->where(['isShow'=>1,'dataFlag'=>1,'parentId'=>0])->field("parentId,catName,catId")->select();
		return $rs;
	}
	
	public function checkPayments($carts){
		foreach ($carts as $key1 => $shop){
			if($shop["list"]){
				for($i=0,$k=count($shop["list"]);$i<$k;$i++){
					$goods = $shop["list"][$i];
					$goodsId = $goods["goodsId"];
					$row = Db::name('goods')->where(['goodsId'=>$goodsId])->field("isDistribut")->find();
					if($row["isDistribut"]){
						return true;
					}
				}
			}
		}
		return false;
	}
}
