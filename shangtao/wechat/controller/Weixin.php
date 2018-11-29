<?php
namespace shangtao\wechat\controller;
use think\Controller;
/**
 * 微信接入接口控制器
 */
class Weixin extends Controller{
    public function index(){
        if(isset($_GET['echostr'])){
		    $this->first();
		}else{
		    $wechat = new \wechat\WSTWechat(WSTConf('CONF.wxAppId'),WSTConf('CONF.wxAppKey'));
		    $wechat->responseMsg();
		}
    }

    public function first()
    {
        $echoStr = input("echostr");
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }	
	private function checkSignature()
	{	
        $signature = input("signature");
        $timestamp = input("timestamp");
        $nonce = input("nonce");

		$token = WSTConf('CONF.wxAppCode');

		$tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}

	/******************** 调用模板消息接口 *************************/
	public function getTemplates(){
		$wechat = new \wechat\WSTWechat(WSTConf('CONF.wxAppId'),WSTConf('CONF.wxAppKey'));
		$rs = $wechat->getTemplates();
		dump(json_decode($rs,true));
	}
	public function sendTemplate(){
		$wechat = new \wechat\WSTWechat(WSTConf('CONF.wxAppId'),WSTConf('CONF.wxAppKey'));
		$wechat->sendTemplate();
	}
}
