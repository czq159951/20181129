<?php
namespace shangtao\home\controller;
use shangtao\common\model\Reports as M;
/**
 * 报表控制器
 */
class Reports extends Base{
    protected $beforeActionList = ['checkShopAuth'];
	/**
     * 商品销售排行
     */
    public function topSaleGoods(){
    	$this->assign("startDate",date('Y-m-d',strtotime("-1month")));
        $this->assign("endDate",date('Y-m-d'));
    	return $this->fetch('shops/reports/top_sale_goods');
    }
    public function getTopSaleGoods(){
    	$m = new M();
        return $m->getTopSaleGoods();
    } 
    /**
     * 获取销售额
     */
    public function statSales(){
    	$this->assign("startDate",date('Y-m-d',strtotime("-1month")));
        $this->assign("endDate",date('Y-m-d'));
        return $this->fetch('shops/reports/stat_sales');
    }
    public function getStatSales(){
    	$m = new M();
        return $m->getStatSales();
    }

    /**
     * 获取销售订单
     */
    public function statOrders(){
        $this->assign("startDate",date('Y-m-d',strtotime("-1month")));
        $this->assign("endDate",date('Y-m-d'));
        return $this->fetch('shops/reports/stat_orders');
    }
    public function getStatOrders(){
        $m = new M();
        return $m->getStatOrders();
    }
}
