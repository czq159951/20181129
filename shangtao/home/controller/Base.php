<?php
namespace shangtao\home\controller;
/**
 * 基础控制器
 */
use think\Controller;
class Base extends Controller {
	public function __construct(){
		parent::__construct();
        WSTSwitchs();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wsthomeStyleId'));
		$this->view->filter(function($content){
            $style = WSTConf('CONF.wsthomeStyle')?WSTConf('CONF.wsthomeStyle'):'default';
            return str_replace("__STYLE__",str_replace('/index.php','',$this->request->root()).'/shangtao/home/view/'.$style,$content);
        });
		hook('homeControllerBase');
		
		if(WSTConf('CONF.seoMallSwitch')==0){
			$this->redirect('home/switchs/index');
			exit;
		}
	}

	protected function fetch($template = '', $vars = [], $config = [])
    {
    	$style = WSTConf('CONF.wsthomeStyle')?WSTConf('CONF.wsthomeStyle'):'default';   
        return $this->view->fetch($style."/".$template, $vars, $config);
    }

	/**
	 * 上传图片
	 */
	public function uploadPic(){
		return WSTUploadPic(0);
	}
	/**
    * 编辑器上传文件
    */
    public function editorUpload(){
           return WSTEditUpload(0);
    }
	
	/**
	 * 获取验证码
	 */
	public function getVerify(){
		WSTVerify();
	}

	// 登录验证方法--用户
    protected function checkAuth(){
       	$USER = session('WST_USER');
        if(empty($USER)){
        	if(request()->isAjax()){
        		die('{"status":-999,"msg":"您还未登录"}');
        	}else{
        		$this->redirect('home/users/login');
        		exit;
        	}
        }
    }
    //登录验证方法--商家
    protected function checkShopAuth(){
       	$USER = session('WST_USER');
        if(empty($USER) || $USER['userType']!=1){
        	if(request()->isAjax()){
        		die('{"status":-999,"msg":"您还未登录"}');
        	}else{
        		$this->redirect('home/shops/login');
        		exit;
        	}
        }
    }

}