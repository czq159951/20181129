<?php
namespace shangtao\weapp\controller;
use shangtao\common\model\Payments as PM;
/**
 * 支付控制器
 */
class Payments extends Base{
	// 前置方法执行列表
	protected $beforeActionList = [
			'checkAuth'
	];
	/**
	 * 在线支付方式
	 */
	public function index(){
		//获取支付方式
		$pa = new PM();
		$payments = $pa->getByGroup('3');
		return jsonReturn('success',1,$payments);
	}
}
