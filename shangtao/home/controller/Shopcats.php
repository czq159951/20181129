<?php
namespace shangtao\home\controller;
use shangtao\common\model\ShopCats as M;
/**
 * 门店分类控制器
 */
class Shopcats extends Base{
    protected $beforeActionList = ['checkShopAuth'];

	/**
	 * 列表
	 */
	public function index(){
		$m = new M();
		$list = $m->getCatAndChild(session('WST_USER.shopId'),input('post.parentId/d'));
		$this->assign('list',$list);
		return $this->fetch("shops/shopcats/list");
	}
	
    /**
     * 修改名称
     */
    public function editName(){
    	$m = new M();
    	$rs = array();
    	if(input('post.id/d')>0){
    		$rs = $m->editName();
    	}
    	return $rs;
    }
    /**
     * 修改排序
     */
    public function editSort(){
    	$m = new M();
    	$rs = array();
    	if(input('post.id/d')>0){
    		$rs = $m->editSort();
    	}
    	return $rs;
    }
    /**
     * 批量保存商品分类
     */
    public function batchSaveCats(){
    	$m = new M();
    	$rs = $m->batchSaveCats();
    	return $rs;
    }
    /**
     * 删除操作
     */
    public function del(){
    	$m = new M();
    	$rs = $m->del();
    	return $rs;
    }
    
    /**
     * 列表查询
     */
    public function listQuery(){
    	$m = new M();
    	$list = $m->listQuery((int)session('WST_USER.shopId'),input('post.parentId/d'));
    	$rs = array();
    	$rs['status'] = 1;
    	$rs['list'] = $list;
    	return $rs;
    }
    
    public function changeCatStatus(){
    	$m = new M();
    	$rs = $m->changeCatStatus();
    	return $rs;
    }
	
}
