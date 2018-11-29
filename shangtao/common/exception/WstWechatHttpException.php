<?php
namespace shangtao\common\exception;
use think\exception\Handle;
// 微信版异常处理类
class WstWechatHttpException extends Handle
{

    public function render(\Exception $e)
    {
    	if(config('app_debug')){
    		return parent::render($e);
    	}else{
    	    header("Location:".url('wechat/error/index'));
    	}
    }

}