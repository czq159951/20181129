<?php
namespace addons\pintuan\controller;
use think\addons\Controller;
use addons\pintuan\model\Pintuans as M;
use addons\pintuan\model\Weixinpays as WM;
/**
 * 拼团插件定时任务
 */
class Cron extends Controller{
	protected $addonStyle = 'default';
	public function __construct(){
		parent::__construct();
	}
    
    /**
     * 取消拼单
     */
    public function tuanRefund(){
    	$m = new M();
    	$rs = $m->tuanRefund();
        $m->batchRefund();
    	echo json_encode($rs);
    }

    public function tuanNotify(){
        $m = new WM();
        $m->tuanNotify ();
    }
}