<?php
namespace shangtao\home\controller;
use shangtao\home\model\ShopUsers as M;
/**
 * 门店角色控制器
 */
class Shopusers extends Base{
    protected $beforeActionList = ['checkShopAuth'];

	/**
	 * 列表
	 */
	public function index(){
		$m = new M();
		$list = $m->pageQuery();
		$this->assign('list',$list);
		return $this->fetch("shops/shopusers/list");
	}
	
    /**
    * 查询
    */
    public function pageQuery(){
        $m = new M();
        return $m->pageQuery();
    }
    
    /**
     * 新增店铺管理员
     */
    public function add(){
    	$m = new M();
    	$object = $m->getEModel('shop_roles');
        $roles = model("ShopRoles")->listQuery();
		$data = ['object'=>$object,"roles"=>$roles];
    	return $this->fetch('shops/shopusers/add',$data);
    }
	
	/**
     * 新增店铺管理员
     */
    public function toAdd(){
    	$m = new M();
    	return $m->add();
    }
	
    /**
     * 修改店铺管理员
     */
    public function edit(){
    	$m = new M();
    	$object = $m->getById(input('get.id'));
		$roles = model("ShopRoles")->listQuery();
        $data = ['object'=>$object,"roles"=>$roles];
    	return $this->fetch('shops/shopusers/edit',$data);
    }

	/**
     * 编辑店铺管理员
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
