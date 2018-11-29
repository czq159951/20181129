<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\Datas as M;
/**
 * 系统数据控制器
 */
class Datas extends Base{
	
    public function index(){
    	return $this->fetch("list");
    }
    /**
    * 根据catId获取子数据
    */
    public function childQuery(){
        $m = new M();
        return WSTGrid($m->childQuery());
    }
    /**
     * 获取菜单列表
     */
    public function listQuery(){
    	$m = new M();
    	return $m->dataQuery((int)Input("post.id",-1));
    }
    /**
     * 获取菜单
     */
    public function get(){
    	$m = new M();
    	return $m->getById((int)Input("post.id"));
    }
    /**
     * 新增菜单
     */
    public function add(){
    	$m = new M();
    	return $m->add();
    }
    /**
     * 编辑菜单
     */
    public function edit(){
    	$m = new M();
    	return $m->edit();
    }
    /**
     * 删除菜单
     */
    public function del(){
    	$m = new M();
    	return $m->del();
    }
}
