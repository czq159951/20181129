<?php
namespace shangtao\wechat\controller;
use shangtao\common\model\OrderRefunds as M;
/**
 * 订单退款控制器
 */
class Orderrefunds extends Base{
    /**
	 * 用户申请退款
	 */
	public function refund(){
		$m = new M();
		$rs = $m->refund();
		return $rs;
	}
	/**
	 * 商家处理是否同意
	 */
	public function shopRefund(){
		$m = new M();
		$rs = $m->shopRefund();
		return $rs;
	}
}
