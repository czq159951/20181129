<?php
namespace addons\auction\controller;

use think\addons\Controller;
use addons\auction\model\Auctions as M;
use addons\auction\model\Weixinpays as WM;
use addons\auction\model\WeixinpaysApp as PM;

class Cron extends Controller{
	public function __construct(){
		parent::__construct();
		$m = new M();
		$data = $m->getConf('Auction');
	}
	/**
	 * 定时任务
	 */
	public function scanTask(){
		$m = new M();
		$rs = $m->scanTask();
		$m->batchRefund();
		return json($rs);
	}

	public function refundNotify(){
		$wm = new WM();
		$wm->auctionNotify();
	}

	public function refundAppNotify(){
		$wm = new PM();
		$wm->auctionNotify();
	}
}