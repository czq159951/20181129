<?php
namespace shangtao\home\controller;
use shangtao\common\model\LogMoneys as M;
/**
 * 资金流水控制器
 */
class Logmoneys extends Base{
    protected $beforeActionList = [
       'checkAuth'=>['only'=>'usermoneys,pageuserquery,touserrecharge'],
       'checkShopAuth'=>['only'=>'shopmoneys,pageshopquery,torecharge']
    ];
    /**
     * 查看用户资金流水
     */
	public function usermoneys(){
		$rs = model('Users')->getFieldsById((int)session('WST_USER.userId'),['lockMoney','userMoney','rechargeMoney']);
		$this->assign('object',$rs);
		return $this->fetch('users/logmoneys/list');
	}
    /**
     * 查看用户资金流水
     */
    public function shopmoneys(){
        $rs = model('Shops')->getFieldsById((int)session('WST_USER.shopId'),['lockMoney','shopMoney','noSettledOrderFee','paymentMoney']);
        $this->assign('object',$rs);
        return $this->fetch('shops/logmoneys/list');
    }
    /**
     * 获取用户数据
     */
    public function pageUserQuery(){
        $userId = (int)session('WST_USER.userId');
        $data = model('logMoneys')->pageQuery(0,$userId);
        return WSTReturn("", 1,$data);
    }
    /**
     * 获取商家数据
     */
    public function pageShopQuery(){
        $shopId = (int)session('WST_USER.shopId');
        $data = model('logMoneys')->pageQuery(1,$shopId);
        return WSTReturn("", 1,$data);
    }
    
	/**
	 * 充值[商家]
	 */
    public function toRecharge(){
    	$payments = model('common/payments')->recharePayments('1');
    	$this->assign('payments',$payments);
        $chargeItems = model('common/ChargeItems')->queryList();
        $this->assign('chargeItems',$chargeItems);
    	return $this->fetch('shops/recharge/pay_step1');
    }
    
    /**
     * 充值[用户]
     */
    public function toUserRecharge(){
    	$payments = model('common/payments')->recharePayments('1');
    	$this->assign('payments',$payments);
    	$chargeItems = model('common/ChargeItems')->queryList();
    	$this->assign('chargeItems',$chargeItems);
    	return $this->fetch('users/recharge/pay_step1');
    }
}
