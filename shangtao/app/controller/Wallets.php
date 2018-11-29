<?php
namespace shangtao\app\controller;
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
        $data['userId'] = model('index')->getUserId();
        //$this->assign('data',$data); //订单号、用户id、isBatch
		$m = new OM();
		$rs = $m->getOrderPayInfo($data);// needPay、payRand
		// 订单信息
		$list = $m->getByUnique($data['userId']);// 根据订单唯一流水号 获取订单信息

		// 删除无用字段
		unset($list['payments']);
		
		if(empty($rs)){
			return json_encode(WSTReturn('订单已支付',-1));
			// 判断获取的需要支付信息为空，则说明已支付.跳转订单列表
			$this->assign('type','');
		}else{
			$this->assign('needPay',$rs['needPay']);
			//获取用户钱包
			$user = model('users')->getFieldsById($data['userId'],'userMoney');
			$list['userMoney'] = $user['userMoney'];// 用户钱包可用余额
	    }
	    // 域名,用于显示图片
	    $list['domain'] = $this->domain();
	    return json_encode(WSTReturn('请求成功', 1, $list));die;
	}
	/**
	 * 钱包支付
	 */
	public function payByWallet(){
		$m = new OM();
		$userId = (int)model('index')->getUserId();
		return json_encode($m->payByWallet($userId));
	}
}
