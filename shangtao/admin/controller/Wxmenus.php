<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\Wxmenus as M;
/**
 * 微信自定义列表控制器
 */
class Wxmenus extends Base{

    public function index(){
    	return $this->fetch("list");
    }
    
    /**
     * 获取分页
     */
    public function pageQuery(){
    	$m = new M();
    	return $m->pageQuery();
    }
    
    /**
     * 获取列表
     */
    public function listQuery(){
    	$m = new M();
    	return $m->listQuery();
    }
    
    /**
     * 与微信菜单同步
     */
    public function synchroWx(){
    	$m = new M();
    	return $m->synchroWx();
    }
    
    /**
     * 同步到微信菜单
     */
    public function synchroAd(){
    	$m = new M();
    	return $m->synchroAd();
    }
    
    /**
     * 跳去新增/编辑页面
     */
    public function toEdit(){
    	$menuId = Input("get.menuId/d",0);
    	$parentId = Input("get.parentId/d",0);
    	$m = new M();
    	if($menuId>0){
    		$object = $m->getById($menuId);
    	}else{
    		$object = $m->getEModel('wx_menus');
    	}
    	$this->assign('menuId',$menuId);
    	$this->assign('parentId',$parentId);
    	$this->assign('object',$object);
    	return $this->fetch("edit");
    }
    
    /**
     * 新增
     */
    public function add(){
    	$m = new M();
    	return $m->add();
    }
    
    /**
     * 编辑
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
