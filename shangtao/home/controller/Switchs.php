<?php
namespace shangtao\home\controller;
/**
 * 关闭提示处理控制器
 */
use think\Controller;
class Switchs extends Controller{
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
	protected function fetch($template = '', $vars = [], $replace = [], $config = []){
		$style = WSTConf('CONF.wsthomeStyle')?WSTConf('CONF.wsthomeStyle'):'default';
		$replace['__STYLE__'] = str_replace('/index.php','',$this->request->root()).'/shangtao/home/view/'.$style;
		return $this->view->fetch($style."/".$template, $vars, $replace, $config);
	}
    public function index(){
        return $this->fetch('error_switch');
    }
}
