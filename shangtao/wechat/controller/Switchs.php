<?php
namespace shangta\wechat\controller;
/**
 * 关闭提示处理控制器
 */
use think\Controller;
class Switchs extends Controller{
	public function __construct(){
		parent::__construct();
		WSTConf('CONF',WSTConfig());
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPcStyleId'));
		if(WSTConf('CONF.wxenabled')==1){
			if(!(request()->module()=="wechat" && request()->controller()=="Weixinpays" && request()->action()=="notify")){
				WSTIsWeixin();//检测是否在微信浏览器上使用
			}
		}
	}
	protected function fetch($template = '', $vars = [], $replace = [], $config = []){
		$style = WSTConf('CONF.wstwechatStyle')?WSTConf('CONF.wstwechatStyle'):'default';
		$replace['__WECHAT__'] = str_replace('/index.php','',\think\facade\Request::instance()->root()).'/shangtao/wechat/view/'.$style;
		return $this->view->fetch($style."/".$template, $vars, $replace, $config);
	
	}
    public function index(){
        return $this->fetch('error_switch');
    }
}
