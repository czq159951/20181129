<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\HomeMenus as M;
/**
 * 前台菜单控制器
 */
class Homemenus extends Base{
	
    public function index(){
    	return $this->fetch("list");
    }
    
    /**
     * 获取菜单列表
     */
    public function pageQuery(){
    	$m = new M();
    	return $m->pageQuery();
    }
    /**
     * 获取菜单
     */
    public function get(){
    	$m = new M();
    	return $m->getById((int)input("post.menuId"));
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
    
    /**
     * 显示隐藏
     */
    public function setToggle(){
    	$m = new M();
    	return $m->setToggle();
    }
    
    /**
    * 修改排序
    */ 
    public function changeSort(){
        $m = new M();
        return $m->changeSort();
    }
}
