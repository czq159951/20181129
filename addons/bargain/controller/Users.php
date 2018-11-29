<?php
namespace addons\bargain\controller;

use think\addons\Controller;
use addons\bargain\model\Bargains as M;
/**
 * 全民砍价插件
 */
class Users extends Controller{
	/**
	 * 微信我的砍价页
	 */
	public function wxbargain(){
		return $this->fetch("/wechat/users/list");
	}
	/**
	 * 加载砍价数据
	 */
	public function pageQuery(){
		$m = new M();
		return $m->pageQueryByUser();
	}
}