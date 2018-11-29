<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\Menus as M;
/**
 * 菜单控制器
 */
class Menus extends Base{
	
    public function index(){
    	return $this->fetch("list");
    }
    
    /**
     * 获取菜单列表
     */
    public function listQuery(){
    	$m = new M();
    	return $m->listQuery((int)Input("post.id",-1));
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
