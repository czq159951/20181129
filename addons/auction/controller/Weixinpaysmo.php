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
class Weixinpaysmo extends Controller{
	/**
	 * 初始化
	 */
	private $wxpayConfig;
	private $wxpay;
	public function initialize() {
		header ("Content-type: text/html; charset=utf-8");
		require Env::get('root_path') . 'extend/wxpay/WxPayConf.php';
		require Env::get('root_path') . 'extend/wxpay/WxJsApiPay.php';
		
		$this->wxpayConfig = array();
		$m = new PM();
		$this->wxpay = $m->getPayment("weixinpays");
		$this->wxpayConfig['appid'] = $this->wxpay['appId']; // 微信公众号身份的唯一标识
		$this->wxpayConfig['appsecret'] = $this->wxpay['appsecret']; // JSAPI接口中获取openid
		$this->wxpayConfig['mchid'] = $this->wxpay['mchId']; // 受理商ID
		$this->wxpayConfig['key'] = $this->wxpay['apiKey']; // 商户支付密钥Key
		$this->wxpayConfig['notifyurl'] = "";
		$this->wxpayConfig['curl_timeout'] = 30;
		$this->wxpayConfig['returnurl'] = "";
		// 初始化WxPayConf
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
			$needPay = $auction["cautionMoney"];
			
			if($auction["userId"]>0){
				$data["status"] = -1;
				$data["msg"] = "您已缴保证金";
			}else{
				$data["status"] = $needPay>0?1:-1;
				$data["msg"] = ($data["status"]==-1)?"无需支付保证金":"";
			}
		}else{
			$auction = $am->getAuctionPay($auctionId);
			$needPay = $auction["payPrice"];
			if($auction["isPay"]==1){
				$data["status"] = -1;
				$data["msg"] = "您已缴成拍卖货款";
			}else{
				$data["status"] = $needPay>0?1:-1;
				$data["msg"] = ($data["status"]==-1)?"无需支付拍卖货款":"";
			}
		}
		return $data;
	}
	
	public function toPay(){
		$payObj = input("payObj/s");
    	$am = new AM();
    	$obj = array();
    	$data = array();
    	$needPay = 0;
    	$auctionId = input("auctionId/d",0);
    	$userId = (int)session('WST_USER.userId');
    	$return_url = "";
    	
    	if($payObj=="bao"){//充值
    		$return_url = addon_url("auction://goods/modetail",array("id"=>$auctionId),true,true);
    		$auction = $am->getUserAuction($auctionId);
    		$needPay = $auction["cautionMoney"];
    		if($auction["userId"]>0){
    			header("Location:".$return_url);
				exit();
    		}else{
    			$data["status"] = $needPay>0?1:-1;
    			$data["msg"] = ($data["status"]==-1)?"无需支付保证金":"";
    		}
    		if($needPay==0){
				header("Location:".$return_url);
				exit();
			}
    	}else{
    		$auction = $am->getAuctionPay($auctionId);
    		if($auction["endPayTime"]<date("Y-m-d H:i:s")){
    			$data["status"] = -1;
    			$data["msg"] = "您已过拍卖支付货款期限";
    		}else{
	    		$needPay = $auction["payPrice"];
	    		if($auction["isPay"]==1){
	    			$data["status"] = -1;
	    			$data["msg"] = "您已缴成拍卖货款";
	    		}else{
	    			$data["status"] = $needPay>0?1:-1;
	    			$data["msg"] = ($data["status"]==-1)?"无需支付拍卖货款":"";
	    		}
	    		$return_url = addon_url("auction://users/mocheckPayStatus",array("id"=>$auctionId),true,true);
	    		if($needPay==0){
					header("Location:".$return_url);
					exit();
				}
    		}
    	}
    	$notify_url = addon_url("auction://weixinpaysmo/notify","",true,true);
    	if($data["status"]==1){
    		$openid = session('WST_USER.wxOpenId');
			$out_trade_no = WSTOrderNo();
			//使用统一支付接口
			$unifiedOrder = new \UnifiedOrder();

			$body = ($payObj=="bao")?"支付保证金":"支付拍卖成拍卖货款";
			
			//自定义订单号，此处仅作举例
			$unifiedOrder->setParameter("out_trade_no",$out_trade_no);//商户订单号
			$unifiedOrder->setParameter("notify_url",$notify_url);//通知地址
			$unifiedOrder->setParameter("trade_type","MWEB");//交易类型
		
			$unifiedOrder->setParameter("body",$body);//商品描述
			$needPay = WSTBCMoney($needPay,0,2);
			$unifiedOrder->setParameter("total_fee", $needPay * 100);//总金额

			$attach = $payObj."@".$userId."@".$auctionId;
			$unifiedOrder->setParameter("attach",$attach);//附加数据
			$wap_name = WSTConf('CONF.mallName');
			$unifiedOrder->setParameter("scene_info", "{'h5_info': {'type':'Wap','wap_url': '".$notify_url."','wap_name': '".$wap_name."'}}");

			if($payObj=='bao'){
				$rs = $am->getPayInfo($auctionId,1);
			}else{
				$rs = $am->getPayInfo($auctionId,2);
			}

			$this->assign('payObj',$payObj);
			$this->assign('object', $rs['data']['auction']);
			$this->assign('returnUrl',$return_url);
			$this->assign('needPay',$needPay);

			$wxResult = $unifiedOrder->getResult();
	    	$this->assign('mweb_url',$wxResult['mweb_url']."&redirect_url".urlencode($return_url));

			return $this->fetch('mobile/index/pay_weixin');
		}
		
	}
	
	public function notify() {
		// 使用通用通知接口
		$notify = new \Notify();
		// 存储微信的回调
		$xml = file_get_contents("php://input");
		$notify->saveData ( $xml );
		if ($notify->checkSign () == FALSE) {
			$notify->setReturnParameter ( "return_code", "FAIL" ); // 返回状态码
			$notify->setReturnParameter ( "return_msg", "签名失败" ); // 返回信息
		} else {
			$notify->setReturnParameter ( "return_code", "SUCCESS" ); // 设置返回码
		}
		$returnXml = $notify->returnXml ();
		if ($notify->checkSign () == TRUE) {
			if ($notify->data ["return_code"] == "FAIL") {
				// 此处应该更新一下订单状态，商户自行增删操作
			} elseif ($notify->data ["result_code"] == "FAIL") {
				// 此处应该更新一下订单状态，商户自行增删操作
			} else {
				$order = $notify->getData ();
				$rs = $this->process($order);
				if($rs["status"]==1){
					echo "SUCCESS";
				}else{
					echo "FAIL";
				}
			}
		}
	}
	
	//订单处理
	private function process($order) {
	
		$obj = array();
		$obj["trade_no"] = $order['transaction_id'];
		$obj["out_trade_no"] = $order['out_trade_no'];
		$obj["total_fee"] = (float)$order["total_fee"]/100;
		$extras =  explode ( "@", $order ["attach"] );
		
		$obj["payObj"] = $extras[0];
		$obj["userId"] = (int)$extras[1];
		$obj["auctionId"] = (int)$extras[2];
		$obj["payFrom"] = 'weixinpays';
		// 支付成功业务逻辑
		$m = new AM();
		$rs = $m->complateCautionMoney ( $obj );
		return $rs;
		
	}

}
