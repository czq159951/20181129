<?php
namespace shangtao\weapp\controller;
use shangtao\common\model\Orders as OM;
/**
 * 余额控制器
 */
class Wallets extends Base{
	// 前置方法执行列表
	protected $beforeActionList = [
			'checkAuth'
	];
	/**
	 * 跳去支付页面
	 */
	public function payment(){
        $data = [];
        $data['orderNo'] = input('orderNo');
        $data['isBatch'] = (int)input('isBatch');
        $data['userId'] = model('weapp/index')->getUserId();
		$m = new OM();
		$rs = $m->getOrderPayInfo($data);
		
		$list = $m->getByUnique($data['userId']);
		if(empty($rs)){
			return jsonReturn('订单已支付',-1,$list);
		}else{
			$list['totalMoney'] = sprintf("%.2f", $list['totalMoney']);
			$list['needPay'] = sprintf("%.2f", $rs['needPay']);
			//获取用户钱包
			$user = model('users')->getFieldsById($data['userId'],'userMoney,payPwd');
			$list['userMoney'] = $user['userMoney'];
        	$payPwd = $user['payPwd'];
        	$payPwd = empty($payPwd)?0:1;
			$this->assign('payPwd',$payPwd);
			$list['payPwd'] = $payPwd;
			return jsonReturn('success',1,$list);
	    }
	}
	/**
	 * 钱包支付
	 */
	public function payByWallet(){
		$m = new OM();
		$userId = model('weapp/index')->getUserId();
		$rs = $m->payByWallet($userId,1);
		return jsonReturn('success',1,$rs);
	}
}
