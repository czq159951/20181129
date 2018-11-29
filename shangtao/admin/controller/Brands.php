<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\Brands as M;
/**
 * 品牌控制器
 */
class Brands extends Base{
	
    public function index(){
    	$g = model('GoodsCats');
    	$this->assign('gcatList',$g->listQuery(0));
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
    
    /**
     * 获取品牌
     */
    public function get(){
    	$m = new M();
    	$rs = $m->get((int)Input("post.id"));
    	return $rs;
    }
    
    /**
     * 跳去新增/编辑页面
     */
    public function toEdit(){
    	$id = Input("get.id/d",0);
    	$m = new M();
    	if($id>0){
    		$object = $m->getById($id);
    	}else{
    		$object = $m->getEModel('brands');
    	}
        $this->assign('object',$object);
        $this->assign('gcatList',model('GoodsCats')->listQuery(0));
        return $this->fetch("edit");
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
     * 编辑
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

    /**
    * 修改品牌排序
    */
    public function changeSort(){
        $m = new M();
        return $m->changeSort();
    }
   
}
