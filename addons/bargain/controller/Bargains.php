<?php
namespace addons\bargain\controller;

use think\addons\Controller;
use addons\bargain\model\Bargains as M;
/**
 * 全民砍价活动插件
 */
class Bargains extends Controller{
	/**
	 * 第一刀
	 */
	public function firstKnife(){
		$m = new M();
		return $m->firstKnife();
	}
	/**
	 * 补刀
	 */
	public function addKnife(){
		$m = new M();
		return $m->addKnife();
	}
	/**
	 * 获取砍价人信息
	 */
	public function bargainInfo(){
		$m = new M();
		$userId = (int)session('WST_USER.userId');
		$bargainUserId = (int)base64_decode(input('bargainUserId'));
		$bargainId = input('id/d',0);
		$userIds = ($bargainUserId>0)?$bargainUserId:$userId;
		return $m->checkBargain($userIds,$bargainId);
	}
	/**
	 * 亲友团
	 */
	public function helpsList(){
		$m = new M();
		$userId = (int)session('WST_USER.userId');
		$bargainUserId = (int)base64_decode(input('bargainUserId'));
		return $m->helpsList($userId,$bargainUserId);
	}
}