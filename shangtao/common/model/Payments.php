<?php
namespace shangtao\common\model;
use think\Db;
/**
 * 支付管理业务处理
 */
class Payments extends Base{
	/**
	 * 获取支付方式种类
	 *
	 * $isApp 如果是接口请求,则不返回payConfig数据
	 */
	public function getByGroup($payfor = '', $onlineType = -1, $isApp = false){
		$payments = ['0'=>[],'1'=>[]];
		$where = ['enabled'=>1];
		if(in_array($onlineType,[1,0]))$where['isOnline'] = $onlineType;
		$rs = $this->where($where)->where("find_in_set ($payfor,payFor)")->order('payOrder asc')->select();
		foreach ($rs as $key =>$v){
			if($v['payConfig']!='')$v['payConfig'] = json_decode($v['payConfig'], true);
			if($isApp)unset($v['payConfig']);
			$payments[$v['isOnline']][] = $v;
		}
		return $payments;
	}

	
	/**
	 * 获取支付信息
	 */
	public function getPayment($payCode){
		$payment = $this->where("enabled=1 AND payCode='$payCode' AND isOnline=1")->find();
		$payConfig = json_decode($payment["payConfig"]) ;
		foreach ($payConfig as $key => $value) {
			$payment[$key] = $value;
		}
		return $payment;
	}
	
	/**
	 * 获取在线支付方式
	 */
	public function getOnlinePayments(){
		//获取支付信息
		return $this->where(['isOnline'=>1,'enabled'=>1])->order('payOrder asc')->select();
	}
	/**
	 * 判断某种支付是否开启
	 */
	public function isEnablePayment($payCode){
        //获取支付信息
		return $this->where(['isOnline'=>1,'enabled'=>1,'payCode'=>$payCode])->Count();
	}
	
	public function recharePayments($payfor = ''){
		$rs = $this->where(['isOnline'=>1,'enabled'=>1])->where("find_in_set ($payfor,payFor)")->where("payCode!='wallets'")
			->field('id,payCode,payName,isOnline')->order('payOrder asc')->select();
		return $rs;
	}
}
