<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\Express as M;
/**
 * 快递控制器
 */
class Express extends Base{
	
    public function index(){
    	return $this->fetch("list");
    }
    /**
     * 获取分页
     */
    public function pageQuery(){
        $m = new M();
        $rs = $m->pageQuery();
        return WSTGrid($rs);
    }
    /*
    * 获取数据
    */
    public function get(){
        $m = new M();
        $rs = $m->getById(Input("id/d",0));
        return $rs;
    }
    /**
     * 新增
     */
    public function add(){
        $m = new M();
        $rs = $m->add();
        return $rs;
    }
    /**
    * 修改
    */
    public function edit(){
        
        $m = new M();
        $rs = $m->edit();
        return $rs;
    }
    /**
     * 删除
     */
    public function del(){
        $m = new M();
        $rs = $m->del();
        return $rs;
    }

    
}
