<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\Settlements as M;
/**
 * 结算控制器
 */
class Settlements extends Base{
    public function index(){
    	return $this->fetch('list');
    }

    /**
     * 获取列表
     */
    public function pageQuery(){
    	$m = new M();
    	return WSTGrid($m->pageQuery());
    }
    /**
     *  跳去结算页面
     */
    public function toHandle(){
    	$m = new M();
    	$object = $m->getById();
    	$this->assign("object",$object);
    	return $this->fetch('edit');
    }

    /**
     * 处理订单
     */
    public function handle(){
    	$m = new M();
    	return $m->handle();
    }
    /**
     *  跳去结算详情
     */
    public function toView(){
    	$m = new M();
    	$object = $m->getById();
    	$this->assign("object",$object);
    	return $this->fetch('view');
    }

    /**
     * 获取订单商品
     */
    public function pageGoodsQuery(){
        $m = new M();
        return WSTGrid($m->pageGoodsQuery());
    }

    /*************************************************
     *          以下是平台主动生成结算单
     ************************************************/
    /**
     * 进入平台结算野蛮
     */
    public function toShopIndex(){
        $this->assign("areaList",model('areas')->listQuery(0));
        return $this->fetch('list_shop');
    }

    /**
     * 获取待结算的商家列表
     */
    public function pageShopQuery(){
        $m = new M();
        return WSTGrid($m->pageShopQuery());
    }
    /**
     * 进入订单列表页面
     */
    public function toOrders(){
        $this->assign("id",(int)input('id'));
        return $this->fetch('list_order');
    }
    /**
     * 获取商家的待结算订单列表
     */
    public function pageShopOrderQuery(){
        $m = new M();
        return WSTGrid($m->pageShopOrderQuery());
    }
    /**
     * 生成结算单
     */
    public function generateSettleByShop(){
        $m = new M();
        return $m->generateSettleByShop();
    }
    /**
     * 导出
     */
    public function toExport(){
        $m = new M();
        $rs = $m->toExport();
        $this->assign('rs',$rs);
    }
}
