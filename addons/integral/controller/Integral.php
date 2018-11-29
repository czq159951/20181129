<?php
namespace addons\integral\controller;

use think\addons\Controller;
use addons\integral\model\Integrals as M;
/**
 * 积分商城插件
 */
class Integral extends Controller{
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
}