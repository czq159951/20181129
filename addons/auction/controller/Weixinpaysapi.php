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
class Weixinpaysapi extends Controller{
	/**
	* 微信支付
	* tokenId
	* auctionId
	* payObj
	*/
	public function toPay(){
		$payObj = input("payObj/s");
    	$am = new AM();
    	$obj = array();
    	$data = array();
    	$needPay = 0;
    	$auctionId = input("auctionId/d",0);
    	$userId = model('app/index')->getUserId();
        if($userId <= 0){
            return json_encode(WSTReturn('您还未登录',-999));
        }
    	$return_url = "";
    	
    	if($payObj=="bao"){//充值
    		$auction = $am->getUserAuction($auctionId);
    		$needPay = $auction["cautionMoney"];
    		if($auction["userId"]>0){
    			$data["status"] = -1;
    			$data['msg'] = '您已缴保证金';
    		}else{
    			$data["status"] = $needPay>0?1:-1;
    			$data["msg"] = ($data["status"]==-1)?"无需支付保证金":"";
    		}
    	}else{
    		$auction = $am->getAuctionPay($auctionId,$userId);
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
    		}
    	}
    	$notify_url = addon_url("auction://weixinpaysapi/notify","",true,true);
    	if($data["status"]==1){

    		$m = new PM();
	    	header ( "Content-type: text/html; charset=utf-8" );
	    	require Env::get('root_path') . 'extend/wxpay/WxPayConf.php';
	    	require Env::get('root_path') . 'extend/wxpay/WxJsApiPay.php';

			$total_fee = WSTBCMoney($needPay,0,2); // 1.需要支付金额
			// $payRand = $order["payRand"]; // 支付标记
			// $out_trade_no = $obj["orderNo"]."a".$payRand; // 支付流水号
			$out_trade_no = WSTOrderNo();

			$body = ($payObj=="bao")?"支付保证金":"支付拍卖成拍卖货款";
	    	$wxpay = $m->getPayment ( "app_weixinpays" );
	    	$wxpayConfig = array();
	    	$wxpayConfig ['appid'] = $wxpay ['appId']; // 微信公众号身份的唯一标识
	    	$wxpayConfig ['appsecret'] = $wxpay['appsecret']; // JSAPI接口中获取openid
	    	$wxpayConfig ['mchid'] = $wxpay['mchId']; // 受理商ID
	    	$wxpayConfig ['key'] = $wxpay['apiKey']; // 商户支付密钥Key
	    	$wxpayConfig ['curl_timeout'] = 30;
	    	$wxpayConfig ['notifyurl'] = addon_url("auction://weixinpaysapi/notify","",true,true); // 回调地址
	    	$wxpayConfig ['returnurl'] =  "";
	    	 
	    	// 初始化WxPayConf
	    	new \WxPayConf ( $wxpayConfig );
	    	//使用统一支付接口
	    	$unifiedOrder = new \UnifiedOrder();
	    	$unifiedOrder->setParameter("out_trade_no",$out_trade_no);//商户订单号
	    	$unifiedOrder->setParameter("notify_url",$wxpayConfig ['notifyurl']);//通知地址
	    	$unifiedOrder->setParameter("trade_type","APP");//交易类型
	    	// 	附加数据 给异步回调函数调用
	    	$attach = $payObj."@".$userId."@".$auctionId;
			$unifiedOrder->setParameter("attach",$attach);//附加数据
	    
	    	$unifiedOrder->setParameter("body",$body);//商品描述
	    	$needPay = WSTBCMoney($total_fee,0,2);
	    	$unifiedOrder->setParameter("total_fee", $needPay * 100);//总金额
	    	//dump($unifiedOrder);die;
	    	$prepay_id = $unifiedOrder->getPrepayId();

	    	$obj["prepayid"] = $prepay_id;
	    	$rs = $unifiedOrder->getParameters($obj);
	    	$data =array('msg'=>'success','status'=>1,'data'=>array($rs));
	    	echo json_encode($data);die;
		}else{
			return json_encode($data);
		}
		
	}
	
	public function notify() {

		$m = new PM();
    	header ( "Content-type: text/html; charset=utf-8" );
    	require Env::get('root_path') . 'extend/wxpay/WxPayConf.php';
    	require Env::get('root_path') . 'extend/wxpay/WxJsApiPay.php';
    	$wxpay = $m->getPayment ( "app_weixinpays" );
    	
    	$wxpayConfig = array();
    	$wxpayConfig ['appid'] = $wxpay ['appId']; // 微信公众号身份的唯一标识
    	$wxpayConfig ['appsecret'] = $wxpay['appsecret']; // JSAPI接口中获取openid
    	$wxpayConfig ['mchid'] = $wxpay['mchId']; // 受理商ID
    	$wxpayConfig ['key'] = $wxpay['apiKey']; // 商户支付密钥Key
    	$wxpayConfig ['curl_timeout'] = 30;
    	$wxpayConfig ['notifyurl'] = addon_url("auction://weixinpaysapi/notify","",true,true); // 回调地址
    	$wxpayConfig ['returnurl'] =  "";
    	// 初始化WxPayConf
    	new \WxPayConf ( $wxpayConfig );
    
    	// 使用通用通知接口
    	$notify = new \Notify();
    	// 存储微信的回调
    	$xml = $GLOBALS ['HTTP_RAW_POST_DATA'];
    	$notify->saveData ( $xml );
    	$notify->setReturnParameter ( "return_code", "SUCCESS" ); // 设置返回码
    
    	$returnXml = $notify->returnXml ();
    
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
		$obj["payFrom"] = 'app_weixinpays';
		// 支付成功业务逻辑
		$m = new AM();
		$rs = $m->complateCautionMoney ( $obj );

		return $rs;
		
	}

}
