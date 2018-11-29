<?php
namespace addons\pintuan\controller;
use think\addons\Controller;
use addons\pintuan\model\Pintuans as M;
use shangtao\common\model\Users as UM;

/**
 * 余额控制器
 */
class Wallets extends Controller{
	protected $addonStyle = 'default';
	public function __construct(){
		parent::__construct();
		$m = new M();
        $data = $m->getConf('Pintuan');
        $this->addonStyle = ($data['addonsStyle']=='')?'default':$data['addonsStyle'];
        $this->assign("addonStyle",$this->addonStyle);

		$this->assign("seoPintuanKeywords",$data['seoPintuanKeywords']);
        $this->assign("seoPintuanDesc",$data['seoPintuanDesc']);
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
	/**
	 * 生成支付代码
	 */
	function getWalletsUrl(){
		$m = new M();
		$userId = (int)session('WST_USER.userId');
		$payFrom = (int)input("payFrom");//0:PC 1:手机 2:微信
		$orderNo = input("orderNo/s");
		$data = $m->getTuanPay($orderNo,$userId);
		$data['url'] = addon_url('pintuan://wallets/payment',array("orderNo"=>$orderNo,"payFrom"=>$payFrom),'',true,true);
		return $data;
	}
	
	/**
	 * 跳去支付页面
	 */
	public function payment(){
		$userId = (int)session('WST_USER.userId');
		$m = new UM();
		$user = $m->getFieldsById($userId,["payPwd"]);
		$this->assign('hasPayPwd',($user['payPwd']!="")?1:0);
		$payFrom = (int)input('payFrom/d');
		$orderNo = input('orderNo/s');
		$this->assign('orderNo',$orderNo);
       	$this->assign('payFrom',$payFrom);
        $data = [];
        $data['orderNo'] = $orderNo;
        $data['userId'] = $userId;
        
        if($userId==0){
        	session('payment','对不起，您尚未登录，请先登录!');
        	if($payFrom==1){//1:手机
        		$this->redirect('mobile/error/message',['code'=>'payment']);
        	}else if($payFrom==2){// 2:微信
        		$this->redirect('wechat/error/message',['code'=>'payment']);
        	}else{//0:PC 
                $this->redirect('home/error/message',['code'=>'payment']);
        	}
        }
        
		$m = new M();
		$data = $m->getTuanPay($orderNo,$userId);
		if($data["status"]==1){
			$this->assign('needPay',$data["data"]["needPay"]);
			//获取用户钱包
			$user = model('common/users')->getFieldsById($userId,'userMoney,payPwd');
			$this->assign('userMoney',$user['userMoney']);
			$payPwd = $user['payPwd'];
			$payPwd = empty($payPwd)?0:1;
			$this->assign('payPwd',$payPwd);


			$this->assign("object",$data['data']);
	        if($payFrom==1){//1:手机
	        	return $this->fetch($this->addonStyle.'/mobile/index/pay_wallets');
	        }else if($payFrom==2){// 2:微信
	        	return $this->fetch($this->addonStyle.'/wechat/index/pay_wallets');
	        }else{//0:PC
	        	 return $this->fetch($this->addonStyle.'/home/index/pay_wallets');
	        }
		}else{
			session('payment',$data["msg"]);
			if($payFrom==1){//1:手机
        		$this->redirect(addon_url('pintuan://pintuan/mopulist'));
        	}else if($payFrom==2){// 2:微信
        		$this->redirect(addon_url('pintuan://pintuan/wxpulist'));
        	}else{//0:PC 
                $this->redirect(addon_url('pintuan://pintuan/pulist'));
        	}
	    }
	}

	/**
	 * 钱包支付
	 */
	public function payByWallet(){
		$m = new M();
        return $m->payByWallet();
	}
	/**
	 * 检查支付结果
	 */
	public function paySuccess() {
		return $this->fetch('/home/index/pay_success');
	}
}
