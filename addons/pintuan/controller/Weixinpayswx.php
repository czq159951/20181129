<?php
namespace addons\pintuan\controller;
use think\addons\Controller;
use think\Loader;
use Env;
use shangtao\common\model\Payments as PM;
use addons\pintuan\model\Pintuans as M;
use shangtao\common\model\Orders as OM;
use shangtao\common\model\LogMoneys as LM;
/**
 * 微信支付控制器
 */
class Weixinpayswx extends Controller{
	/**
	 * 初始化
	 */
	private $wxpayConfig;
	private $wxpay;
	protected $addonStyle = 'default';
	public function initialize() {
		header ("Content-type: text/html; charset=utf-8");
		require Env::get('root_path') . 'extend/wxpay/WxPayConf.php';
		require Env::get('root_path') . 'extend/wxpay/WxJsApiPay.php';
		$m = new M();
        $data = $m->getConf('Pintuan');
        $this->addonStyle = ($data['addonsStyle']=='')?'default':$data['addonsStyle'];
        $this->assign("addonStyle",$this->addonStyle);
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
		$m = new M();
		$orderNo = (int)input("orderNo/d",0);
		$userId = (int)session('WST_USER.userId');
		$rs = $m->getTuanPay($orderNo,$userId);
		return $rs;
	}
	
	public function toPay(){

    	$m = new M();
    	$data = array();
    	$orderNo = input("orderNo/d",0);
    	$userId = (int)session('WST_USER.userId');
    	$data = $m->getTuanPay($orderNo,$userId);
		$return_url = addon_url("pintuan://pintuan/wxpulist","",true,true);
    	$notify_url = addon_url("pintuan://weixinpayswx/notify","",true,true);
    	if($data["status"]==1){
    		$needPay =  $data["data"]["needPay"];
    		$openid = session('WST_USER.wxOpenId');
			$out_trade_no = $data["data"]["orderNo"];
			if($needPay==0){
				header("Location:".$return_url);
				exit();
			}
			//使用jsapi接口
			$jsApi = new \JsApi();
			//使用统一支付接口
			$unifiedOrder = new \UnifiedOrder();
			$unifiedOrder->setParameter("openid",$openid);//商品描述

			$body = "支付拼团费用";
			//自定义订单号，此处仅作举例
			$unifiedOrder->setParameter("out_trade_no",$out_trade_no);//商户订单号
			$unifiedOrder->setParameter("notify_url",$notify_url);//通知地址
			$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
			$unifiedOrder->setParameter("body",$body);//商品描述
			
			$unifiedOrder->setParameter("total_fee", $needPay * 100);//总金额
			$attach = $userId;
			$unifiedOrder->setParameter("attach",$attach);//附加数据
			$prepay_id = $unifiedOrder->getPrepayId();
			//=========步骤3：使用jsapi调起支付============
			$jsApi->setPrepayId($prepay_id);
			$jsApiParameters = $jsApi->getParameters();
			
			$addonPay = array();
			$addonPay["object"] = $data['data'];
			$addonPay["jsApiParameters"] = $jsApiParameters;
			$addonPay["returnUrl"] = $return_url;
			$addonPay["needPay"] = $needPay;
			$addonPay["showUrl"] = Env::get('root_path').'addons'.DS.'pintuan'.DS.'view'.DS.($this->addonStyle).DS.'wechat'.DS.'index'.DS.'pay_weixin.html';
			session("addonPay",$addonPay);
			$this->redirect('wechat/weixinpays/toaddonpay');
		}else{
			header("Location:".$return_url);
			exit();
		}
		
	}
	
	public function notify() {
		// 使用通用通知接口
		$notify = new \Notify();
		// 存储微信的回调
		$xml = file_get_contents('php://input');
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
		$userId =  (int)$order ["attach"];
		$obj["userId"] = $userId;
		$obj["payFrom"] = 'weixinpays';
		// 支付成功业务逻辑
		$m = new M();
		$rs = $m->complatePay ( $obj );
		return $rs;
	}

	

}
