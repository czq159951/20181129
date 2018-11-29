<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\DataCats as M;
/**
 * 系统数据分类控制器
 */
class Datacats extends Base{
    /**
    * 根据
    */
    public function listQuery(){
        $m = new M();
        return $m->listQuery((int)Input("post.id",-1));
    }
    /**
     * 根据catId获取数据分类
     */
    public function get(){
    	$m = new M();
    	return $m->getById((int)Input("post.id"));
    }
    /**
     * 新增数据分类
     */
    public function add(){
    	$m = new M();
    	return $m->add();
    }
    /**
     * 编辑数据分类
     */
    public function edit(){
    	$m = new M();
    	return $m->edit();
    }
    /**
     * 删除数据分类
     */
    public function del(){
    	$m = new M();
    	return $m->del();
    }
}
