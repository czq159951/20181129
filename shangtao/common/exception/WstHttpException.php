<?php
namespace shangtao\common\exception;

use think\exception\Handle;

class WstHttpException extends Handle
{

    public function render(\Exception $e)
    {
    	if(config('app_debug')){
    		return parent::render($e);
    	}else{
    		$request = request();
    		$isMobile = $request->isMobile();
			$isWeChat = (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false);
			$hasMobile = (WSTDatas('ADS_TYPE',3)!='')?true:false;
			$hasWechat = (WSTConf('CONF.wxenabled',2)==1)?true:false;
			if($isWeChat && $hasWechat){
				header("Location:".url('wechat/error/index'));
			}else if($isMobile && $hasMobile){
                header("Location:".url('mobile/error/index'));
			}else{
	    	    header("Location:".url('home/error/index'));
	    	}
    	}
    }

}