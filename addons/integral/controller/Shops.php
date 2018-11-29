<?php
namespace addons\integral\controller;

use think\addons\Controller;
use addons\integral\model\Integrals as M;
/**
 * 积分商城插件
 */
class Shops extends Controller{
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
	
	/**
	 * 搜索商品
	 */
	public function searchGoods(){
		$m = new M();
		return $m->searchGoods();
	}

	public function getShopCats(){
		$m = new M();
		$parentId = (int)input('post.parentId/d');
    	$list = $m->getShopCats($parentId);
    	$rs = array();
    	$rs['status'] = 1;
    	$rs['list'] = $list;
    	return $rs;
	}

	
}