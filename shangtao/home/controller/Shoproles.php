<?php
namespace shangtao\home\controller;
use shangtao\home\model\ShopRoles as M;
/**
 * 门店角色控制器
 */
class Shoproles extends Base{
    protected $beforeActionList = ['checkShopAuth'];

	/**
	 * 列表
	 */
	public function index(){
		$m = new M();
		$list = $m->pageQuery();
		$this->assign('list',$list);
		return $this->fetch("shops/shoproles/list");
	}
	
    /**
    * 查询
    */
    public function pageQuery(){
        $m = new M();
        return $m->pageQuery();
    }
    
    /**
     * 新增角色
     */
    public function add(){
    	$m = new M();
    	$object = $m->getEModel('shop_roles');
		$data = ['object'=>$object];
    	return $this->fetch('shops/shoproles/edit',$data);
    }
	
	/**
     * 新增角色
     */
    public function toAdd(){
    	$m = new M();
    	return $m->add();
    }
	
    /**
     * 修改角色
     */
    public function edit(){
    	$m = new M();
    	$object = $m->getById((int)input('get.id'));
		$data = ['object'=>$object];
    	return $this->fetch('shops/shoproles/edit',$data);
    }

	/**
     * 修改角色
     */
    public function toEdit(){
    	$m = new M();
    	return $m->edit();
    }
	
    /**
     * 删除操作
     */
    public function del(){
    	$m = new M();
    	$rs = $m->del();
    	return $rs;
    }
    
}
