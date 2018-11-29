<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\ChargeItems as M;
/**
 * 充值项控制器
 */
class Chargeitems extends Base{
	
    public function index(){
    	return $this->fetch("list");
    }
    /**
     * 获取分页
     */
    public function pageQuery(){
        $m = new M();
        return WSTGrid($m->pageQuery());
    }
    /**
     * 跳去编辑页面
     */
    public function toEdit(){
        $m = new M();
        $assign = ['data'=>$m->getById(Input("get.id/d",0))];
        return $this->fetch("edit",$assign);
    }
    /**
    * 获取数据
    */
    public function get(){
        $m = new M();
        return $m->getById(Input("id/d",0));
    }
    /**
     * 新增
     */
    public function add(){
        $m = new M();
        return $m->add();
    }
    /**
    * 修改
    */
    public function edit(){
        $m = new M();
        return $m->edit();
    }
    /**
     * 删除
     */
    public function del(){
        $m = new M();
        return $m->del();
    }
   
}
