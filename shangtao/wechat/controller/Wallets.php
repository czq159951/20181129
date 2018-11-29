<?php
namespace shangtao\wechat\controller;
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
        $data['userId'] = (int)session('WST_USER.userId');
        $this->assign('data',$data);
		$m = new OM();
		$rs = $m->getOrderPayInfo($data);
		
		$list = $m->getByUnique();
		$this->assign('rs',$list);
		if(empty($rs)){
			$this->assign('type','');
			return $this->fetch("users/orders/orders_list");
		}else{
			$this->assign('needPay',$rs['needPay']);
			//获取用户钱包
			$user = model('users')->getFieldsById($data['userId'],'userMoney,payPwd');
			$this->assign('userMoney',$user['userMoney']);
        	$payPwd = $user['payPwd'];
        	$payPwd = empty($payPwd)?0:1;
			$this->assign('payPwd',$payPwd);
	    }
	    return $this->fetch('users/orders/orders_pay_wallets');
	}
	/**
	 * 钱包支付
	 */
	public function payByWallet(){
		$m = new OM();
		return $m->payByWallet();
	}
}
