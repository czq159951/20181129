<?php
namespace addons\auction\controller;

use think\addons\Controller;
use addons\auction\model\Auctions as M;
use shangtao\common\model\Payments;

class Auction extends Controller{
	public function __construct(){
		parent::__construct();
		$m = new M();
		$data = $m->getConf('Auction');
		$this->assign("seoAuctionKeywords",$data['seoAuctionKeywords']);
		$this->assign("endPayDate",(int)$data['endPayDate']);
		$this->assign("seoAuctionDesc",$data['seoAuctionDesc']);
	}

	/**
	 * 拍卖商品
	 */
	public function addAcution(){
		 $m = new M();
		 return $m->addAcution();
	}

	/**
	 * 获取拍卖纪录
	 */
	public function pageQueryByAuctionLog(){
		$m = new M();
		return $m->pageQueryByAuctionLog((int)input('id'));
	}
    
	/**
	 * 去支付保证金
	 */
	public function toPay(){
		$m = new M();
		$rs = $m->getPayInfo((int)input('auctionId/d',0),1);
		if($rs['status']==-1){
			session('0001',$rs['msg']);
        	$this->redirect('home/error/message',['code'=>'0001']);
		}
		$this->assign("object",$rs['data']);
		$this->assign("payObj","bao");
		return $this->fetch("/home/index/pay_step1");
	}
	/**
	 * 下单
	 */
	public function submit(){
		$m = new M();
		return $m->submit((int)input('orderSrc'));
	}

	
	/**
	 * 微信在线支付方式
	 */
	public function wxsucceed(){
		//获取支付方式
		$m = new M();
		$pa = new Payments();
		$payments = $pa->getByGroup('3');
		$this->assign('payments',$payments);
		$rs = $m->getPayInfo((int)input('auctionId/d',0),1);
		$this->assign("object",$rs['data']['auction']);
		$this->assign("payObj",'bao');
		return $this->fetch("/wechat/index/pay_list");
	}
	public function numberOrder(){
		$m = new M();
		$payObj = input("payObj/s");
		$pkey = "";
		$data = array();
		$data['status'] = 1;
		$auctionId = input("auctionId/d",0);
		if($payObj=="bao"){
			$auction = $m->getUserAuction($auctionId);
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
			$auction = $m->getAuctionPay($auctionId);
			$orderAmount = $auction["payPrice"];
			$userId = (int)session('WST_USER.userId');
			if($auction["isPay"]==1){
				$data["status"] = -1;
				$data["msg"] = "您已缴成拍卖交金";
			}else{
				$data["status"] = $orderAmount>0?1:-1;
				$data["msg"] = ($data["status"]==-1)?"无需支付拍卖交金":"";
				$pkey = $payObj."@".$auctionId;
			}
		}
		$orderNo = WSTOrderNo();
		$base64 = new \org\Base64();
        $key = WSTBase64url($base64->encrypt($pkey, "shangtao"));
        $data['key'] = $key;
		return $data;
	}
	
	/**
	 * 手机在线支付方式
	 */
	public function mosucceed(){
		//获取支付方式
		$m = new M();
		$pa = new Payments();
		$payments = $pa->getByGroup('2');
		$this->assign('payments',$payments);
		$rs = $m->getPayInfo((int)input('auctionId/d',0),1);
		$this->assign("object",$rs['data']['auction']);
		$this->assign("payObj",'bao');
		return $this->fetch("/mobile/index/pay_list");
	}
}