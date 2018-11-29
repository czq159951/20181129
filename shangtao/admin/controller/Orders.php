<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\Orders as M;
/**
 * 订单控制器
 */
class Orders extends Base{
	/**
	 * 订单列表
	 */
    public function index(){
    	$areaList = model('areas')->listQuery(0); 
    	$this->assign("areaList",$areaList);
    	$this->assign("userId",(int)input('userId'));
    	return $this->fetch("list");
    }
    /**
     * 获取分页
     */
    public function pageQuery(){
        $m = new M();
        return WSTGrid($m->pageQuery((int)input('orderStatus',10000)));
    }
   /**
    * 获取订单详情
    */
    public function view(){
        $m = new M();
        $rs = $m->getByView(Input("id/d",0));
        $this->assign("object",$rs);
        $this->assign("from",input("from/d",0));
        return $this->fetch("view");
    }
    /**
     * 导出订单
     */
    public function toExport(){
    	$m = new M();
    	$rs = $m->toExport();
    	$this->assign('rs',$rs);
    }
}
