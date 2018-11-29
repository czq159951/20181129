<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\Reports as M;
/**
 * 报表控制器
 */
class Reports extends Base{
	/**
	 * 商品销售排行
	 */
	public function toTopSaleGoods(){
        $this->assign("startDate",date('Y-m-d',strtotime("-1month")));
        $this->assign("endDate",date('Y-m-d'));
		return $this->fetch("/reports/top_sale_goods");
	}
    /**
     * 获取商品排行数据
     */
    public function topSaleGoodsByPage(){
        $m = new M();
        return WSTGrid($m->topSaleGoodsByPage());
    }
	/**
     * 店铺销售排行
     */
    public function toTopShopSales(){
        $this->assign("startDate",date('Y-m-d',strtotime("-1month")));
        $this->assign("endDate",date('Y-m-d'));
        return $this->fetch("/reports/top_sale_shop");
    }
    /**
     * 获取店铺排行数据
     */
    public function topShopSalesByPage(){
        $m = new M();
        return WSTGrid($m->topShopSalesByPage());
    }
    /**
     * 获取销售额
     */
    public function toStatSales(){
        $this->assign("startDate",date('Y-m-d',strtotime("-1month")));
        $this->assign("endDate",date('Y-m-d'));
        return $this->fetch("/reports/stat_sales");
    }
    public function statSales(){
        $m = new M();
        return $m->statSales();
    }
    /**
     * 获取订单统计
     */
    public function toStatOrders(){
        $this->assign("startDate",date('Y-m-d',strtotime("-1month")));
        $this->assign("endDate",date('Y-m-d'));
        return $this->fetch("/reports/stat_orders");
    }
    /*
     * 
     */
    public function getOrders(){
    	$m = new M();
        return $m->getOrders();
    }
    public function statOrders(){
        $m = new M();
       return $m->statOrders();
    }


    /**
     * 获取每日新增用户
     */
    public function toStatNewUser(){
        $this->assign("startDate",date('Y-m-d',strtotime("-1month")));
        $this->assign("endDate",date('Y-m-d'));
        return $this->fetch("/reports/stat_new_user");
    }
    public function statNewUser(){
        $m = new M();
        return $m->statNewUser();
    }
    /*
     * 首页获取新增用户
     */
    public function getNewUser(){
    	$m = new M();
    	$data = cache('userNumData');
    	if(empty($data)){
    	  $rdata = $m->statNewUser();
    	  cache('userNumData',$rdata,7200);
    	}else{
    	  $rdata = cache('userNumData');
    	}
        return $rdata;
    }
    /**
     * 会员登录统计
     */
    public function toStatUserLogin(){
        $this->assign("startDate",date('Y-m-d',strtotime("-1month")));
        $this->assign("endDate",date('Y-m-d'));
        return $this->fetch("/reports/stat_user_login");
    }
    public function statUserLogin(){
        $m = new M();
        return $m->statUserLogin();
    }
}
