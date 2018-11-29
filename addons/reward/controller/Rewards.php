<?php
namespace addons\reward\controller;

use think\addons\Controller;
use addons\reward\model\Rewards as M;
/**
 * 满就送插件
 */
class Rewards extends Controller{
	public function __construct(){
		parent::__construct();
		$m = new M();
		$data = $m->getConf('Coupon');
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
		$this->assign("seoRewardsKeywords",$data['seoRewardsKeywords']);
        $this->assign("seoRewardsDesc",$data['seoRewardsDesc']);
	}
}