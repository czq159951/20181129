<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\LogSms as M;
/**
 * 登录日志控制器
 */
class Logsms extends Base{
	
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
}