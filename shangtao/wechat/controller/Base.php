<?php
namespace shangtao\wechat\controller;
use think\Controller;
/**
 * 基础控制器
 */
class Base extends Controller {
	public function __construct(){
		parent::__construct();
		WSTConf('CONF',WSTConfig());
		WSTSwitchs();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wsthomeStyleId'));
		$this->view->filter(function($content){
            $style = WSTConf('CONF.wstwechatStyle')?WSTConf('CONF.wstwechatStyle'):'default';
            return str_replace("__WECHAT__",str_replace('/index.php','',$this->request->root()).'/shangtao/wechat/view/'.$style,$content);
        });
		if(!(request()->module()=="wechat" && request()->controller()=="Weixinpays" && request()->action()=="notify")){
			WSTIsWeixin();//检测是否在微信浏览器上使用
		}
		$state = input('param.state');
		if($state==WSTConf('CONF.wxAppCode')){
			$type = input('param.type');
			if($type=='1'){
				WSTBindWeixin(1);
			}else{
				WSTBindWeixin(0);
			}
		}
		if(WSTConf('CONF.seoMallSwitch')==0){
			$this->redirect('wechat/switchs/index');
			exit;
		}
	}
    // 权限验证方法
    protected function checkAuth(){
		$state = input('param.state');
		if($state==WSTConf('CONF.wxAppCode')){
			WSTBindWeixin(1);
		}
		$request = request();
       	$USER = session('WST_USER');
        if(empty($USER)){
        	if(request()->isAjax()){
        		die('{"status":-999,"msg":"还没关联帐号,正在关联帐号"}');
        	}else{
        		session('WST_WX_WlADDRESS',$request->url(true));
        		$url=urlencode($request->url(true));
        		$url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.WSTConf('CONF.wxAppId').'&redirect_uri='.$url.'&response_type=code&scope=snsapi_userinfo&state='.WSTConf('CONF.wxAppCode').'#wechat_redirect';
        		header("location:".$url);
        		exit;
        	}
        }
    }

    // 店铺权限验证方法
    protected function checkShopAuth($opt){
       	$shopMenus = WSTShopOrderMenus();
       	if($opt=="list"){
       		if(count($shopMenus)==0){
       			session('wxshoporder','对不起,您无权进行该操作');
       			$this->redirect('wechat/error/message',['code'=>'wxshoporder']);
		    	exit;
       		}
       	}else{
       		if(!array_key_exists($opt,$shopMenus)){
	       		if(request()->isAjax()){
		    		die('{"status":-1,"msg":"您无权进行该操作"}');
		    	}else{
		    		session('wxshoporder','对不起,您无权进行该操作');
		    		$this->redirect('wechat/error/message',['code'=>'wxshoporder']);
		    		exit;
		    	}
	       	}
       	}
    }

	protected function fetch($template = '', $vars = [], $config = []){
		$style = WSTConf('CONF.wstwechatStyle')?WSTConf('CONF.wstwechatStyle'):'default';
		return $this->view->fetch($style."/".$template, $vars, $config);
		
	}
	/**
	 * 上传图片
	 */
	public function uploadPic(){
		return WSTUploadPic(0);
	}
	/**
	 * 获取验证码
	 */
	public function getVerify(){
		WSTVerify();
	}
}