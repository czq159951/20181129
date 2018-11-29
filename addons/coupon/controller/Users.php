<?php
namespace addons\coupon\controller;

use think\addons\Controller;
use addons\coupon\model\Coupons as M;
/**
 * 优惠券插件
 */
class Users extends Controller{
	protected $beforeActionList = ['checkAuth' ];
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
	/**
	 * 优惠券列表
	 */
	public function index(){
		$m = new M();
		$rs = $m->getCouponNumByUser();
		$this->assign("coupons",$rs);
    	return $this->fetch("/home/users/list");
	}
	/**
	 * 加载优惠券数据
	 */
	public function pageQuery(){
		$m = new M();
		return $m->pageQueryByUser();
	}
	/**************************************** 手机版 **************************************************/
	/**
	 * 手机优惠券列表
	 */
	public function moindex(){
		$this->assign('status',0);
		return $this->fetch("/mobile/users/list");
	}
	/**************************************** 微信版 **************************************************/
	/**
	 * 手机优惠券列表
	 */
	public function wxindex(){
		$this->assign('status',0);
		return $this->fetch("/wechat/users/list");
	}
}