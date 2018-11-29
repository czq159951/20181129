<?php
namespace shangtao\home\controller;
use shangtao\home\model\ShopFreights as M;
use shangtao\home\model\Areas;
use shangtao\home\model\Shops;
/**
 * 运费控制器
 */
class Shopfreights extends Base{
	protected $beforeActionList = ['checkShopAuth'];
    /**
    * 查看运费设置
    */
	public function index(){
		$shops = new Shops();
		$shopId = session('WST_USER.shopId');
		$shFreight =  $shops->getShopsFreight($shopId);
		$this->assign('shFreight',$shFreight);//默认运费
		return $this->fetch('shops/freights/list');
	}
	/**
	 * 运费列表
	 */
	public function listProvince(){
		$m = new M();
		return $m->listProvince();
	}

    /**
     * 编辑
     */
    public function edit(){
    	$m = new M();
    	$rs = $m->edit();
    	return $rs;
    }
}
