<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\LogOperates as M;
/**
 * 操作日志控制器
 */
class Logoperates extends Base{
	
    public function index(){
    	return $this->fetch("list");
    }
    
    /**
     * 获取分页
     */
    public function pageQuery(){
    	$m = new M();
    	return WSTGrid($m->pageQuery());
    }
    /**
     * 获取指定记录
     */
    public function get(){
    	$m = new M();
    	return $m->getById(input('id/d',0));
    }
}
