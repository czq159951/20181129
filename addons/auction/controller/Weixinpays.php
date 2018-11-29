<?php
namespace addons\auction\controller;
use think\addons\Controller;
use think\Loader;
use Env;
use shangtao\common\model\Payments as PM;
use addons\auction\model\Auctions as AM;

/**
 * 微信支付控制器
 */
class Weixinpays extends Controller{
	public function __construct(){
		parent::__construct();
		$m = new AM();
		$data = $m->getConf('Auction');
		$this->assign("seoAuctionKeywords",$data['seoAuctionKeywords']);
		$this->assign("seoAuctionDesc",$data['seoAuctionDesc']);
	}
	
	/**
	 * 初始化
	 */
	private $wxpayConfig;
	private $wxpay;
	public function initialize() {
		header ("Content-type: text/html; charset=utf-8");
		require Env::get('root_path') . 'extend/wxpay/WxPayConf.php';
		require Env::get('root_path') . 'extend/wxpay/WxQrcodePay.php';
		
		$this->wxpayConfig = array();
		$m = new PM();
		$this->wxpay = $m->getPayment("weixinpays");
		$this->wxpayConfig['appid'] = $this->wxpay['appId']; // 微信公众号身份的唯一标识
		$this->wxpayConfig['appsecret'] = $this->wxpay['appsecret']; // JSAPI接口中获取openid
		$this->wxpayConfig['mchid'] = $this->wxpay['mchId']; // 受理商ID
		$this->wxpayConfig['key'] = $this->wxpay['apiKey']; // 商户支付密钥Key
		$this->wxpayConfig['notifyurl'] = addon_url("auction://weixinpays/wxNotify","",true,true);
		$this->wxpayConfig['curl_timeout'] = 30;
		$this->wxpayConfig['returnurl'] = "";
		// 初始化WxPayConf_pub
		new \WxPayConf($this->wxpayConfig);
	}
	
	/**
	 * 获取微信URL
	 */
	public function getWeixinPaysURL(){
		$am = new AM();
		$payObj = input("payObj/s");
		$payFrom = (int)input("payFrom");//0:PC 1:微信
		$pkey = "";
		$data = array();
		$auctionId = input("auctionId/d",0);
		$userId = (int)session('WST_USER.userId');
		if($payObj=="bao"){
			$auction = $am->getUserAuction($auctionId);
			$orderAmount = $auction["cautionMoney"];
			
			if($auction["userId"]>0){
				$data["status"] = -1;
				$data["msg"] = "您已缴保证金";
			}else{
				$data["status"] = $orderAmount>0?1:-1;
				$data["msg"] = ($data["status"]==-1)?"无需支付保证金":"";
				$pkey = $payObj."@".$userId."@".$auctionId;
			}
		}else{
			$auction = $am->getAuctionPay($auctionId);
			if($auction["endPayTime"]<date("Y-m-d H:i:s")){
				$data["status"] = -1;
				$data["msg"] = "您已过拍卖支付货款期限";
			}else{
				$orderAmount = $auction["payPrice"];
				if($auction["isPay"]==1){
					$data["status"] = -1;
					$data["msg"] = "您已缴成拍卖货款";
				}else{
					$data["status"] = $orderAmount>0?1:-1;
					$data["msg"] = ($data["status"]==-1)?"无需支付拍卖货款":"";
					$pkey = $payObj."@".$userId."@".$auctionId;
				}
			}
		}
		if($payFrom==1){//微信
			$data["url"] = addon_url('auction://weixinpays/createQrcode',array("pkey"=>base64_encode($pkey)));
		}else{
			$data["url"] = addon_url('auction://weixinpays/createQrcode',array("pkey"=>base64_encode($pkey)));
		}
		return $data;
	}
	
	public function createQrcode() {
		$pkey = base64_decode(input("pkey"));
		$pkeys = explode("@", $pkey );
		$flag = true;
		$am = new AM();
		$needPay = 0;
		$out_trade_no = 0;
		$trade_no = 0;
		$auctionId = (int)$pkeys[2];
		if($pkeys[0]=="bao"){
			$auction = $am->getUserAuction($auctionId);
			$needPay = $auction["cautionMoney"];
			$body = "支付保证金";
		}else{
			$auction = $am->getAuctionPay($auctionId);
			$needPay = $auction["payPrice"];
			$body = "支付拍卖货款";
		}
		$out_trade_no = WSTOrderNo();
		$trade_no = $out_trade_no;
		if($needPay>0){
			// 使用统一支付接口
			$wxQrcodePay = new \WxQrcodePay ();
			$wxQrcodePay->setParameter ( "body", $body ); // 商品描述
			
			$wxQrcodePay->setParameter ( "out_trade_no", $out_trade_no ); // 商户订单号
			$wxQrcodePay->setParameter ( "total_fee", $needPay * 100 ); // 总金额
			$wxQrcodePay->setParameter ( "notify_url", $this->wxpayConfig['notifyurl'] ); // 通知地址
			$wxQrcodePay->setParameter ( "trade_type", "NATIVE" ); // 交易类型
			$wxQrcodePay->setParameter ( "attach", "$pkey" ); // 附加数据
			$wxQrcodePay->SetParameter ( "input_charset", "UTF-8" );
			// 获取统一支付接口结果
			$wxQrcodePayResult = $wxQrcodePay->getResult ();
			$code_url = '';
			// 商户根据实际情况设置相应的处理流程
			if ($wxQrcodePayResult ["return_code"] == "FAIL") {
				// 商户自行增加处理流程
				session('0001',"通信出错：" . $wxQrcodePayResult ['return_msg']);
        	    $this->redirect('home/error/message',['code'=>'0001']);
			} elseif ($wxQrcodePayResult ["result_code"] == "FAIL") {
				session('0001',"通信出错：" . "错误代码描述：" . $wxQrcodePayResult ['err_code_des']);
        	    $this->redirect('home/error/message',['code'=>'0001']);
			} elseif ($wxQrcodePayResult ["code_url"] != NULL) {
				// 从统一支付接口获取到code_url
				$code_url = $wxQrcodePayResult ["code_url"];
				// 商户自行增加处理流程
			}
			$this->assign('pkey',input("pkey"));
			$this->assign ( 'out_trade_no', $trade_no );
			$this->assign ( 'code_url', $code_url );
			$this->assign ( 'wxQrcodePayResult', $wxQrcodePayResult );
			$this->assign ( 'needPay', $needPay );
		}else{
			$flag = false;
		}
	
		if($flag){
			return $this->fetch('/home/index/pay_step2');
		}else{
			return $this->fetch('/home/index/pay_success');
		}

	}
	
	
	/**
	 * 检查支付结果
	 */
	public function getPayStatus() {
		$trade_no = input('trade_no');
		$total_fee = cache( $trade_no );
		$data = array("status"=>-1);
		if($total_fee>0){
			cache( $trade_no, null );
			$data["status"] = 1;
		}else{// 检查缓存是否存在，存在说明支付成功
			$data["status"] = -1;
		}
		return $data;
	}
	
	/**
	 * 微信异步通知
	 */
	public function wxNotify() {
		// 使用通用通知接口
		$wxQrcodePay = new \WxQrcodePay ();
		// 存储微信的回调
		$xml = file_get_contents("php://input");
		$wxQrcodePay->saveData ( $xml );
		// 验证签名，并回应微信。
		if ($wxQrcodePay->checkSign () == FALSE) {
			$wxQrcodePay->setReturnParameter ( "return_code", "FAIL" ); // 返回状态码
			$wxQrcodePay->setReturnParameter ( "return_msg", "签名失败" ); // 返回信息
		} else {
			$wxQrcodePay->setReturnParameter ( "return_code", "SUCCESS" ); //设置返回码
		}
		$returnXml = $wxQrcodePay->returnXml ();
		if ($wxQrcodePay->checkSign () == TRUE) {
			if ($wxQrcodePay->data ["return_code"] == "FAIL") {
				echo "FAIL";
			} elseif ($wxQrcodePay->data ["result_code"] == "FAIL") {
				echo "FAIL";
			} else {
				// 此处应该更新一下订单状态，商户自行增删操作
				$order = $wxQrcodePay->getData ();
				$trade_no = $order["transaction_id"];
				$total_fee = $order ["total_fee"];
				$pkey = $order ["attach"] ;
				$pkeys = explode ( "@", $pkey );
				$out_trade_no = 0;
				
				$out_trade_no = $order["out_trade_no"];
				$userId = (int)$pkeys [1];
				$auctionId = (int)$pkeys [2];
				$obj = array ();
				$obj["trade_no"] = $trade_no;
				$obj["out_trade_no"] = $out_trade_no;
				$obj["userId"] = $userId;
				$obj["auctionId"] = $auctionId;
				$obj["total_fee"] = (float)$total_fee/100;
				$obj["payFrom"] = 'weixinpays';
				$obj["payObj"] = $pkeys[0];
				// 支付成功业务逻辑
				$m = new AM();
				$rs = $m->complateCautionMoney ( $obj );
					
				if($rs["status"]==1){
					cache("$out_trade_no",$total_fee);
					echo "SUCCESS";
				}else{
					echo "FAIL";
				}
			}
		}else{
			echo "FAIL";
		}
	}

	/**
	 * 检查支付结果
	 */
	public function paySuccess() {
		$pkey = base64_decode(input("pkey"));
		$pkeys = explode("@", $pkey );
		$auctionId = (int)$pkeys[2];
		if($pkeys[0]=="bao"){
			return $this->fetch('/home/index/pay_success');
		}else{
			$this->redirect(addon_url("auction://users/checkPayStatus",array("id"=>$auctionId),true,true));
		}
	}

	
}
