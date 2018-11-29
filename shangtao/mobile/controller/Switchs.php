<?php
namespace shangtao\mobile\controller;
/**
 * 关闭提示处理控制器
 */
use think\Controller;
class Switchs extends Controller{
	public function __construct(){
		parent::__construct();
		WSTConf('CONF',WSTConfig());
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPcStyleId'));
	}
	protected function fetch($template = '', $vars = [], $replace = [], $config = []){
		$style = WSTConf('CONF.wstmobileStyle')?WSTConf('CONF.wstmobileStyle'):'default';
		$replace['__MOBILE__'] = str_replace('/index.php','',\think\facade\Request::instance()->root()).'/shangtao/mobile/view/'.$style;
		return $this->view->fetch($style."/".$template, $vars, $replace, $config);
	}
    public function index(){
        return $this->fetch('error_switch');
    }
}
