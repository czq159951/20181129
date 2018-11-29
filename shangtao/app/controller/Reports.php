<?php
namespace shangtao\app\controller;
use shangtao\common\model\Reports as M;
/**
 * 报表控制器
 */
class Reports extends Base{
    protected $beforeActionList = ['checkShopAuth'];
	/**
     * 商品销售排行
     */
    public function getTopSaleGoods(){
        $shopId = $this->getShopId();
    	$m = new M();
        return json_encode($m->getTopSaleGoods($shopId));
    } 
    /**
     * 获取销售额
     */
    public function getStatSales(){
        $shopId = $this->getShopId();
    	$m = new M();
        return json_encode($m->getStatSales($shopId));
    }

    /**
     * 获取销售订单
     */
    public function getStatOrders(){
        $shopId = $this->getShopId();
        $m = new M();
        return json_encode($m->getStatOrders($shopId));
    }
}
