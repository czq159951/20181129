<?php
namespace shangtao\mobile\controller;
/**
 * 错误处理控制器
 */
class Error extends Base{
    public function index(){
    	header("HTTP/1.0 404 Not Found");
        return $this->fetch('error_sys');
    }
    public function message(){
    	$code = input('code');
    	if($code !== null && session($code)!=''){
    		$this->assign('message',session($code));
    	}else{
    		$this->assign('message','操作错误，请联系商城管理员');
    	}
    	return $this->fetch('error_lost');
    }
}
