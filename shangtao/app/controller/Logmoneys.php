<?php
namespace shangtao\app\controller;
/**
 * 资金流水控制器
 */
class Logmoneys extends Base{
	// 前置方法执行列表
    protected $beforeActionList = [
        'checkAuth'
    ];
    /**
     * 充值页面
     */
    public function toRecharge(){
    	$data = array();
    	$data['payments'] = model('common/payments')->recharePayments('4');
    	$data['chargeItems'] = model('common/ChargeItems')->queryList();
    	return json_encode(WSTReturn('ok',1,$data));
    }
	/**
     * 查看用户资金流水
     */
	public function usermoneys(){
		$userId = model('app/users')->getUserId();
		$rs = model('Users')->getFieldsById($userId,['lockMoney','userMoney','payPwd','rechargeMoney']);
		$rs['withdrawMoney'] = (($rs['userMoney']-$rs['rechargeMoney'])>0)?sprintf('%.2f',($rs['userMoney']-$rs['rechargeMoney'])):0;
		unset($rs['rechargeMoney']);
		$rs['isSetPayPwd'] = ($rs['payPwd']=='')?0:1;
        unset($rs['payPwd']);
		$rs['num'] = count(model('cashConfigs')->listQuery(0,$userId));
		return json_encode(WSTReturn('success',1,$rs));
	}
	/**
	* 验证支付密码
	*/
	public function checkPayPwd(){
		$rs = model('app/users')->checkPayPwd();
		return json_encode($rs);
	}
	/**
	 * 资金流水
	 */
	public function record(){
		$userId = model('app/index')->getUserId();
		$rs = model('Users')->getFieldsById($userId,['lockMoney','userMoney']);
		return json_encode(WSTReturn('ok',1,$rs));
	}
	/**
	 * 列表
	 */
	public function pageQuery(){
		$userId = model('app/index')->getUserId();
		$data = model('LogMoneys')->pageQuery("",$userId);
		if(!empty($data['data'])){
			foreach($data['data'] as $k=>$v){
				// 删除无用字段
				unset($data['data'][$k]['dataFlag']);
				unset($data['data'][$k]['targetType']);
				unset($data['data'][$k]['targetId']);
				unset($data['data'][$k]['dataId']);
				unset($data['data'][$k]['dataSrc']);
				unset($data['data'][$k]['id']);
				unset($data['data'][$k]['payType']);
				unset($data['data'][$k]['tradeNo']);
			}
		}
		return json_encode(WSTReturn("ok", 1,$data));
	}
}
