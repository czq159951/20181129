<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\Areas as M;
/**
 * 地区控制器
 */
class Areas extends Base{
	
    public function index(){
    	$m = new M();
    	$pArea=array('areaId'=>0,'parentId'=>0);
    	$parentId = Input("get.parentId/d",0);
    	if($parentId>0){
    		$pArea = $m->getFieldsById($parentId,['areaName,areaId,parentId']);
    	}
    	$this->assign("pArea",$pArea);
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
     * 设置是否显示/隐藏
     */
    public function editiIsShow(){
    	$m = new M();
    	$rs = $m->editiIsShow();
    	return $rs;
    }
    
    /**
     * 获取地区
     */
    public function get(){
    	$m = new M();
    	$rs = $m->getById((int)Input("post.id"));
    	return $rs;
    }
    
    /**
     * 排序字母
     */
    public function letterObtain(){
    	$m = new M();
    	$rs = $m->letterObtain();
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
     * 列表查询
     */
    public function listQuery(){
    	$m = new M();
    	$list = $m->listQuery(Input("post.parentId/d",0));
    	return WSTReturn("", 1,$list);
    }
}
