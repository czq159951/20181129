<?php
namespace shangtao\home\controller;
/**
 * 错误处理控制器
 */
class Error extends Base{
    public function index(){
    	header("HTTP/1.0 404 Not Found");
        return $this->fetch('error_sys');
    }
    public function goods(){
    	$this->assign('message','很抱歉，您要找的商品已经找不到了~');
        return $this->fetch('error_msg');
    }
    public function shop(){
    	$this->assign('message','很抱歉，您要找的店铺已经找不到了~');
        return $this->fetch('error_msg');
    }
    public function message(){
        $code = input('code');
        if(!empty($code) && session($code)!=''){
            $this->assign('message',session($code));
        }else{
            $this->assign('message','操作错误，请联系商城管理员');
        }
        return $this->fetch('error_msg');
    }
}
