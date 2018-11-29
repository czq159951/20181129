<?php
namespace shangtao\mobile\model;
use shangtao\common\model\Users as CUsers;
use think\Db;
/**
 * 用户类
 */
class Users extends CUsers{
	/**
	* 验证用户支付密码
	*/ 
	function checkPayPwd(){
		$payPwd = input('payPwd');
		$decrypt_data = WSTRSA($payPwd);
		if($decrypt_data['status']==1){
			$payPwd = $decrypt_data['data'];
		}else{
			return WSTReturn('验证失败');
		}
		$userId = (int)session('WST_USER.userId');
		$rs = $this->field('payPwd,loginSecret')->find($userId);
		if($rs['payPwd']==md5($payPwd.$rs['loginSecret'])){
			return WSTReturn('',1);
		}
		return WSTReturn('支付密码错误',-1);
	}
}
