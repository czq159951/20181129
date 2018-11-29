<?php
namespace addons\distribut\controller;

use think\addons\Controller;
use addons\distribut\model\Distribut as M;

class Distribut extends Controller{
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
   
	
    /*************************用户中心*****************************/
    /**
     * 加载店铺分销设置
     */
    public function userDistributUsers(){

    	return $this->fetch("/home/users/user_list");
    }
    
    /**
     * 获取用户分成列表
     */
    public function queryMineUsers(){
    	$m = new M();
    	$rs = $m->queryMineUsers();
    	$rs['status'] = 1;
    	return $rs;
    }
    
    /**
     * 加载店铺分销设置
     */
    public function userDistributMoneys(){
    	 
    	return $this->fetch("/home/users/money_list");
    }
    
    /**
     * 获取用户分成列表
     */
    public function queryUserMoneys(){
    	$m = new M();
    	$rs = $m->queryUserMoneys();
    	$rs['status'] = 1;
    	return $rs;
    }
    
    /*************************商家中心*****************************/
    
    /**
     * 加载店铺分销设置
     */
    public function shopDistributCfg(){
    	$m = new M();
    	$rs = $m->getDistributCfg();
    	$this->assign("object",$rs);
    	return $this->fetch("/home/shops/distribut_cfg");
    }
    
    /**
     * 保存店铺设置
     */
    public function saveCfg(){
    	$m = new M();
    	$rs = $m->saveCfg();
    	return $rs;
    }
    
    /**
     * 获取店铺分销商品列表
     */
    public function shopDistributGoods(){
    	return $this->fetch("/home/shops/goods_list");
    }
    
    /**
     * 获取店铺分销商品列表
     */
    public function queryDistributGoods(){
    	$m = new M();
    	$rs = $m->querydistributgoods();
    	$rs['status'] = 1;
    	return $rs;
    }

    /**
     * 获取店铺分成列表
     */
    public function queryDistributMoneys(){
    	$m = new M();
    	$rs = $m->queryDistributMoneys();
    	$rs['status'] = 1;
    	return $rs;
    }
    
    /**
     * 获取店铺分销商品列表
     */
    public function shopDistributMoneys(){
    	return $this->fetch("/home/shops/money_list");
    }
    
    /*******************************admin*********************************/
    
    /**
     * 获取分销店铺列表
     */
    public function adminDistributShops(){
    	$this->checkAdminPrivileges();
    	return $this->fetch("/admin/shop_list");
    }
    
    /**
     * 获取分销店铺列表
     */
    public function queryAdminDistributShops(){
    	$this->checkAdminPrivileges();
        $m = new M();
        $rs = $m->queryAdminDistributShops();
        $rs['status'] = 1;
        return WSTGrid($rs);
    }
    
    /**
     * 获取分销商品列表
     */
    public function adminDistributGoods(){
    	$this->checkAdminPrivileges();
    	return $this->fetch("/admin/goods_list");
    }
    
    /**
     * 获取分销商品列表
     */
    public function queryAdminDistributGoods(){
    	$this->checkAdminPrivileges();
    	$m = new M();
    	$rs = $m->queryAdminDistributGoods();
    	$rs['status'] = 1;
    	return WSTGrid($rs);
    }
    
    /**
     * 获取分销佣金列表
     */
    public function adminDistributMoneys(){
    	$this->checkAdminPrivileges();
    	return $this->fetch("/admin/money_list");
    }
    
    /**
     * 获取分销佣金列表
     */
    public function queryAdminDistributMoneys(){
    	$this->checkAdminPrivileges();
    	$m = new M();
    	$rs = $m->queryAdminDistributMoneys();
    	$rs['status'] = 1;
    	return WSTGrid($rs);
    }
    
    /**
     * 获取店铺分销商品列表
     */
    public function adminDistributUsers(){
    	$this->checkAdminPrivileges();
    	return $this->fetch("/admin/user_list");
    }
    
    /**
     * 获取分销佣金列表
     */
    public function queryAdminDistributUsers(){
    	$this->checkAdminPrivileges();
    	$m = new M();
    	$rs = $m->queryAdminDistributUsers();
    	$rs['status'] = 1;
    	return WSTGrid($rs);
    }
    
    /**
     * 获取推广用户子列表
     */
    public function adminDistributChildUsers(){
    	$this->checkAdminPrivileges();
    	$this->assign("userId",input("userId/d"));
    	return $this->fetch("/admin/user_child_list");
    }
    
    /**
     * 获取推广用户子列表
     */
    public function queryAdminDistributChildUsers(){
    	$this->checkAdminPrivileges();
    	$m = new M();
    	$rs = $m->queryAdminDistributChildUsers();
    	$rs['status'] = 1;
    	return WSTGrid($rs);
    }
  
    /*******************************wechat*********************************/
    /**
     * 获取分销商品列表
     */
    public function wechatDistributGoods(){
    	$this->assign("keyword", input('keyword'));
    	return $this->fetch("/wechat/index/goods_list");
    }
    
    /**
     * 获取店铺分销商品列表
     */
    public function wechatDistributHome(){
    	$m = new M();
    	$user = $m->getUserInfo();
    	$cfg = $m->getAddonConfig();
    	//分享信息
		$shareInfo= array(
			'title'=>$cfg["mallShareTitle"],
			'desc'=>WSTConf('CONF.mallName'),
			'link'=>url('wechat/index/index',array('shareUserId'=>base64_encode((int)session('WST_USER.userId'))),true,true),
			'imgUrl'=>WSTConf('CONF.mallLogo')
		);
		$this->assign('shareInfo', $shareInfo);
		$this->assign('user', $user);
    	return $this->fetch("/wechat/users/distribut_home");
    }
    
    
    /**
     * 获取用户列表
     */
    public function wechatDistributUsers(){
    	$m = new M();
    	$user = $m->getUser();;
    	$this->assign('user', $user);
    	return $this->fetch("/wechat/users/user_list");
    }
    
    /**
     * 获取用户列表
     */
    public function queryWechatDistributUsers(){
    	$m = new M();
    	$rs = $m->queryMineUsers();
    	$rs['status'] = 1;
    	return $rs;
    }
    
    /**
     * 获取佣金列表
     */
    public function wechatDistributMoneys(){
    	$m = new M();
    	$user = $m->getUser();
    	$this->assign('user', $user);
    	return $this->fetch("/wechat/users/money_list");
    }
    
    /**
     * 获取佣金列表
     */
    public function queryWechatDistributMoneys(){
    	$m = new M();
    	$rs = $m->queryUserMoneys();
    	$rs['status'] = 1;
    	return $rs;
    }
    
    /*******************************mobile*********************************/
    /**
     * 获取分销商品列表
     */
    public function mobileDistributGoods(){
    	$this->assign("keyword", input('keyword'));
    	return $this->fetch("/mobile/index/goods_list");
    }
    
    /**
     * 获取店铺分销商品列表
     */
    public function mobileDistributHome(){
    	$m = new M();
    	$user = $m->getUserInfo();
    	$cfg = $m->getAddonConfig();
    	//分享信息
		$shareInfo= array(
			'title'=>$cfg["mallShareTitle"],
			'desc'=>WSTConf('CONF.mallName'),
			'link'=>url('mobile/index/index',array('shareUserId'=>base64_encode((int)session('WST_USER.userId'))),true,true),
			'imgUrl'=>WSTConf('CONF.mallLogo')
		);
		$this->assign('shareInfo', $shareInfo);
		$this->assign('user', $user);
    	return $this->fetch("/mobile/users/distribut_home");
    }
    
    
    /**
     * 获取用户列表
     */
    public function mobileDistributUsers(){
    	$m = new M();
    	$user = $m->getUser();;
    	$this->assign('user', $user);
    	return $this->fetch("/mobile/users/user_list");
    }
    
    /**
     * 获取用户列表
     */
    public function queryMobileDistributUsers(){
    	$m = new M();
    	$rs = $m->queryMineUsers();
    	$rs['status'] = 1;
    	return $rs;
    }
    
    /**
     * 获取佣金列表
     */
    public function mobileDistributMoneys(){
    	$m = new M();
    	$user = $m->getUser();
    	$this->assign('user', $user);
    	return $this->fetch("/mobile/users/money_list");
    }
    
    /**
     * 获取佣金列表
     */
    public function queryMobileDistributMoneys(){
    	$m = new M();
    	$rs = $m->queryUserMoneys();
    	$rs['status'] = 1;
    	return $rs;
    }
    
}