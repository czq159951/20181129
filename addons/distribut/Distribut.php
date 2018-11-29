<?php
namespace addons\distribut;  


use think\addons\Addons;
use addons\distribut\model\Distribut as DM;

/**
 * 分销插件
 * @author shangtao
 */
class Distribut extends Addons{
    // 该插件的基础信息
    public $info = [
        'name' => 'Distribut',   // 插件标识
        'title' => '分销商品',  // 插件名称
        'description' => '分销插件，更好帮助推广商城！',    // 插件简介
        'status' => 0,  // 状态
        'author' => 'shangtao',
        'version' => '1.0.1'
    ];

	
    /**
     * 插件安装方法
     * @return bool
     */
    public function install(){
    	$m = new DM();
    	$flag = $m->installMenu();
    	WSTClearHookCache();
    	cache('hooks',null);
        return $flag;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall(){
    	$m = new DM();
    	$flag = $m->uninstallMenu();
    	WSTClearHookCache();
    	cache('hooks',null);
        return $flag;
    }
    
	/**
     * 插件启用方法
     * @return bool
     */
    public function enable(){
    	$m = new DM();
    	$flag = $m->toggleShow(1);
    	WSTClearHookCache();
    	cache('hooks',null);
        return $flag;
    }
    
    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable(){
    	$m = new DM();
    	$flag = $m->toggleShow(0);
    	WSTClearHookCache();
    	cache('hooks',null);
    	return $flag;
    }

    /**
     * 插件设置方法
     * @return bool
     */
    public function saveConfig(){
    	WSTClearHookCache();
    	cache('hooks',null);
    	return true;
    }
    
    /**
     * 编辑商品页加分销设置【home】
     */
    public function homeDocumentShopEditGoods($params){
    	$m = new DM();
    	$this->assign("addonParams",$params);
    	$distObj = $m->getGoodsDistribut($params['goodsId']);
    	$conf = $m->getDistributCfg();
    	if($conf["isDistribut"]==1){
    		$this->assign("distributType",$conf["distributType"]);
    		$this->assign("distObj",$distObj);
    		return $this->fetch('view/home/shops/goods_edit');
    	}
    }
    
    /**
     * 商品详情页显示分销提示【home】
     */
    public function homeDocumentGoodsDetail($params){
        if(!(Request()->isSsl())){
        	$m = new DM();
        	$this->assign("addonParams",$params);
        	$addonConfig = $m->getAddonConfig();
        	$this->assign("addonConfig",$addonConfig);
        	if($params['goods']["isDistribut"]==1){
        		self::setShareUser($params);
        		return $this->fetch('view/home/index/goods_detail');
        	}
        }
    }
    
    /**
     * 商品详情页显示分销提示【home】
     */
    public function homeDocumentShopHomeHeader($params){
        if(!(Request()->isSsl())){
        	$m = new DM();
        	$this->assign("addonParams",$params);
        	$conf = $m->getDistributCfg($params['shop']['shopId']);
    		$addonConfig = $m->getAddonConfig();
    		$this->assign("addonConfig",$addonConfig);
    		if($conf["isDistribut"]==1){
    			self::setShareUser($params);
    			return $this->fetch('view/home/index/shop_home');
    		}
        }
    }
    
    /**
     * 购物车结算【home】
     */
    public function homeControllerCartsSettlement($params){
    	$m = new DM();
    	if(isset($params["carts"]["carts"])){
	    	$carts = $params["carts"]["carts"];
	    	$flag = $m->checkPayments($carts);
	    	if($flag){
	    		unset($params["payments"][0]);
	    	}
    	}
    }
    
    /**
     * 跳转商品详情前【mobile】
     */
    public function mobileControllerGoodsIndex($params){
    	self::setShareUser($params);
    }
    
    /**
     * 跳转商城首页前【mobile】
     */
    public function mobileControllerIndexIndex($params){
    	self::setShareUser($params);
    }
    
    /**
     * 用户“我的”【mobile】
     */
    public function mobileDocumentUserIndex(){
    	$m = new DM();
    	$user = $m->getUser();
		$this->assign("user",$user);
		return $this->fetch('view/mobile/users/index');
    }
    
    /**
     * 商品详情页提示【mobile】
     */
    public function mobileDocumentGoodsDetailTips($params){
        if(!(Request()->isSsl())){
        	if($params['goods']["isDistribut"]==1){
        		echo '<div style="color:#d82a2e;font-size: 0.15rem;position: absolute;top:0.1rem;right:0.1rem;" onclick="shareTips();">'.
                     '分享可获佣金<img src="'.WSTDomain().'/addons/distribut/view/images/icon_tstb.png" onclick="showTips()" class="showTips" style="height:18px;vertical-align:middle;position:relative;top:-3px;cursor:pointer;"/>'.
                     '</div>';
        	}
        }
    }
    
    /**
     * 商品详情页加分享JS【mobile】
     */
    public function mobileDocumentGoodsDetail($params){
        if(!(Request()->isSsl())){
        	$m = new DM();
    		$cfg = $m->getAddonConfig();
    		//分享信息
    		$shareUrl = url('mobile/goods/detail',array('goodsId'=>$params['goods']['goodsId'],'shareUserId'=>base64_encode((int)session('WST_USER.userId'))),true,true);
    		$this->assign('shareUrl', $shareUrl);
    		$this->assign('shareTitle', $params['goods']['goodsName']);
    		$this->assign('shareSummary', $cfg["goodsShareTitle"]);
    		$this->assign('shareImg', WSTDomain()."/".$params['goods']['goodsImg']);
            $this->assign("addonConfig",$cfg);
    		self::setShareUser($params);
    		return $this->fetch('view/mobile/share');
        }
    }
    
    /**
     * 购物车结算【mobile】
     */
    public function mobileControllerCartsSettlement($params){
    	$m = new DM();
    	if(isset($params["carts"]["carts"])){
	    	$carts = $params["carts"]["carts"];
	    	$flag = $m->checkPayments($carts);
	    	if($flag){
	    		unset($params["payments"][0]);
	    	}
    	}
    }
    
    /**
     * 跳转商城首页前【wechat】
     */
    public function wechatControllerIndexIndex($params){
    	self::setShareUser($params);
    }
    
    /**
     * 用户“我的”【mobile】
     */
    public function wechatDocumentUserIndex(){
    	$m = new DM();
    	$user = $m->getUser();
    	$this->assign("user",$user);
    	return $this->fetch('view/wechat/users/index');
    }
    
    /**
     * 跳转商品详情页前【wechat】
     */
    public function wechatControllerGoodsIndex($params){
    	self::setShareUser($params);
    }
    
	/**
     * 跳转商品详情页前【wechat】
     */
    public function wechatDocumentGoodsDetailTips($params){
    	if($params['goods']["isDistribut"]==1){
    		echo '<div style="color:#d82a2e;font-size: 0.15rem;position: absolute;top:0.1rem;right:0.1rem;" onclick="shareTips();">'.
                 '分享可获佣金<img src="'.WSTDomain().'/addons/distribut/view/images/icon_tstb.png" onclick="showTips()" class="showTips" style="height:18px;vertical-align:middle;position:relative;top:-3px;cursor:pointer;"/>'.
                 '</div>';
    	}
    }
    
    /**
     * 跳转商品详情页前【wechat】
     */
    public function wechatDocumentGoodsDetail($params){
		$m = new DM();
		$conf = $m->getDistributCfg($params['goods']['shopId']);		
		if($conf["isDistribut"]==1){
			$cfg = $m->getAddonConfig();
			//分享信息
			$shareInfo= array(
				'title'=>$params['goods']['goodsName'],
				'desc'=>$cfg["goodsShareTitle"],
				'link'=>url('wechat/goods/detail',array('goodsId'=>$params['goods']['goodsId'],'shareUserId'=>base64_encode((int)session('WST_USER.userId'))),true,true),
				'imgUrl'=>WSTDomain()."/".$params['goods']["goodsImg"]
			);
			$this->assign('datawx', $params['datawx']);
			$this->assign('shareInfo', $shareInfo);
            $this->assign("addonConfig",$cfg);
			self::setShareUser($params);
			return $this->fetch('view/wechat/share');
		}
    }
    
    /**
     * 购物车结算【wechat】
     */
    public function wechatControllerCartsSettlement($params){
    	$m = new DM();
    	if(isset($params["carts"]["carts"])){
	    	$carts = $params["carts"]["carts"];
	    	$flag = $m->checkPayments($carts);
	    	if($flag){
	    		unset($params["payments"][0]);
	    	}
    	}
    }
    
    /**
     * 加载首页执行
     */
    public function setShareUser($params){
		if(isset($params["getParams"]["shareUserId"])){
			session("WST_shareUserId",(int)base64_decode($params["getParams"]["shareUserId"]));
		}
    }
    
	/**
     * 编辑商品前执行
     */
    public function beforeEidtGoods($params){
    	$m = new DM();
		$conf = $m->getDistributCfg();
		if($conf["isDistribut"]==1){
			if($conf["distributType"] == 2){
				 $params["data"]["isDistribut"] = 1;
				 $params["data"]["commission"] = 0;
			}else{
				if($params["data"]["isDistribut"]==1){
					if($params["data"]["commission"]<=0){
						exit(json_encode(WSTReturn('佣金必须大小0',-1)));
					}
					if($params["data"]["commission"]>$params["data"]["shopPrice"] ){
						exit(json_encode(WSTReturn('佣金不能大于商品金额',-1)));
					}
				}else{
					$params["data"]["isDistribut"] = 0;
					$params["data"]["commission"] = 0;
				}
			}
		}
    }
    
    /**
     * 用户注册后执行
     */
    public function afterUserRegist($params){
    	$m = new DM();
    	$m->userRegist($params["user"]['userId']);
    }
    
	/**
     * 提交订单后执行
     */
    public function afterSubmitOrder($params){
    	$m = new DM();
    	$m->setOrderDistribut($params['orderId']);
    }
    
    /**
     * 确认收货后执行
     */
    public function afterUserReceive($params){
    	$m = new DM();
    	$m->userReceive($params['orderId']);
    }
    
    
    
    /**
     * 确认收货前执行
     */
    public function beforeSubmitOrder($params){
    	$m = new DM();
    	if(isset($params["carts"]["carts"])){
    		$payType = $params["payType"];
	    	$carts = $params["carts"]["carts"];
	    	$flag = $m->checkPayments($carts);
	    	if($flag && $payType==0){
	    		exit(json_encode(WSTReturn("请选择在线支付！")));
	    	}
    	}
    }
    
    public function initConfigHook($params){
    	self::setShareUser($params);
    }
    
}