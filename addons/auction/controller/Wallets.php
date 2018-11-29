<?php
namespace addons\auction\controller;
use think\addons\Controller;
use addons\auction\model\Auctions as AM;
use addons\auction\model\Auctions as M;
use shangtao\common\model\Users as UM;

/**
 * 余额控制器
 */
class Wallets extends Controller{
	public function __construct(){
		parent::__construct();
		$m = new M();
		$data = $m->getConf('Auction');
		$this->assign("seoAuctionKeywords",$data['seoAuctionKeywords']);
		$this->assign("seoAuctionDesc",$data['seoAuctionDesc']);
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
	/**
	 * 生成支付代码
	 */
	function getWalletsUrl(){
		$am = new AM();
		$payObj = input("payObj/s");
		$payFrom = (int)input("payFrom");//0:PC 1:手机 2:微信
		$pkey = "";
		$data = array();
		$data['status'] = 1;
		$auctionId = input("auctionId/d",0);
		if($payObj=="bao"){
			$auction = $am->getUserAuction($auctionId);
			$orderAmount = $auction["cautionMoney"];
			$userId = (int)session('WST_USER.userId');
			if($auction["userId"]>0){
				$data["status"] = -1;
				$data["msg"] = "您已缴保证金";
			}else{
				$data["status"] = $orderAmount>0?1:-1;
				$data["msg"] = ($data["status"]==-1)?"无需支付保证金":"";
				$pkey = $payObj."@".$auctionId;
			}
		}else{
			$auction = $am->getAuctionPay($auctionId);
			if($auction["endPayTime"]<date("Y-m-d H:i:s")){
				$data["status"] = -1;
				$data["msg"] = "您已过拍卖支付货款期限";
			}else{
				$orderAmount = $auction["payPrice"];
				$userId = (int)session('WST_USER.userId');
				if($auction["isPay"]==1){
					$data["status"] = -1;
					$data["msg"] = "您已缴拍卖货款";
				}else{
					$data["status"] = $orderAmount>0?1:-1;
					$data["msg"] = ($data["status"]==-1)?"无需支付拍卖货款":"";
					$pkey = $payObj."@".$auctionId;
				}
			}
		}
		$pkey .= "@".$payFrom;
		$orderNo = WSTOrderNo();
		$base64 = new \org\Base64();
        $key = WSTBase64url($base64->encrypt($pkey, "shangtao"));
		
		$data['url'] = addon_url('auction://wallets/payment','key='.$key,'',true,true);
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
		
		$key = input('key');
		$this->assign('paykey',$key);
        $key = WSTBase64url($key,false);
        $base64 = new \org\Base64();
        $key = $base64->decrypt($key,"shangtao");
        $key = explode('@',$key);
        $data = [];
        $auctionId = (int)$key[1];
        $payFrom = (int)$key[2];
        $data['auctionId'] = (int)$key[1];
        $data['userId'] = $userId;
        
        if((int)session('WST_USER.userId')==0){
        	session('payment','对不起，您尚未登录，请先登录!');
        	if($payFrom==1){//1:手机
        		$this->redirect('mobile/error/message',['code'=>'payment']);
        	}else if($payFrom==2){// 2:微信
        		$this->redirect('wechat/error/message',['code'=>'payment']);
        	}else{//0:PC 
                $this->redirect('home/error/message',['code'=>'payment']);
        	}
        }
        
		$m = new AM();
		$needPay = 0;
		$this->assign('payObj',$key[0]);
		$this->assign('auctionId',$auctionId);
		if($key[0]=="bao"){
			$auction = $m->getUserAuction($data['auctionId']);
			$needPay = $auction["cautionMoney"];
			$flag = (isset($auction["userId"]) && $auction["userId"]>0)?true:false;
		}else{
			$auction = $m->getAuctionPay($data['auctionId']);
			$needPay = $auction["payPrice"];
			$flag = ($auction["isPay"]==1)?true:false;
		}
		if($flag){
			session('payment','您已支付，请勿重复支付~');
			if($payFrom==0){//0:PC 
        		$this->redirect('home/error/message',['code'=>'payment']);
        	}
		}else{
			$this->assign('needPay',$needPay);
			//获取用户钱包
			$user = model('common/users')->getFieldsById($data['userId'],'userMoney,payPwd');
			$this->assign('userMoney',$user['userMoney']);
			$payPwd = $user['payPwd'];
			$payPwd = empty($payPwd)?0:1;
			$this->assign('payPwd',$payPwd);
			if($key[0]=='bao'){
				$rs = $m->getPayInfo($auctionId,1);
			}else{
				$rs = $m->getPayInfo($auctionId,2);
			}
			$this->assign("object",$rs['data']['auction']);
	        if($payFrom==1){//1:手机
	        	return $this->fetch('/mobile/index/pay_wallets');
	        }else if($payFrom==2){// 2:微信
	        	return $this->fetch('/wechat/index/pay_wallets');
	        }else{//0:PC
	        	 return $this->fetch('/home/index/pay_wallets');
	        }
	    }
	}

	/**
	 * 钱包支付
	 */
	public function payByWallet(){
		$m = new AM();
        return $m->payByWallet();
	}
	/**
	 * 检查支付结果
	 */
	public function paySuccess() {
		return $this->fetch('/home/index/pay_success');
	}
}
