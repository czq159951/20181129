<?php
namespace addons\pintuan\model;
use think\Loader;
use think\Db;
use Env;
use addons\pintuan\model\Pintuans as TM;
use shangtao\common\model\Payments as M;
use shangtao\common\model\LogPayParams as PM;
use think\addons\BaseModel as Base;
/**
 * 微信支付业务处理
 */
class Weixinpays extends Base{
	
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
		$m = new M();
		$this->wxpay = $m->getPayment("weixinpays");
		$this->wxpayConfig['appid'] = $this->wxpay['appId']; // 微信公众号身份的唯一标识
		$this->wxpayConfig['appsecret'] = $this->wxpay['appsecret']; // JSAPI接口中获取openid
		$this->wxpayConfig['mchid'] = $this->wxpay['mchId']; // 受理商ID
		$this->wxpayConfig['key'] = $this->wxpay['apiKey']; // 商户支付密钥Key
		$this->wxpayConfig['apiclient_cert'] = WSTRootPath().'/extend/wxpay/cert/apiclient_cert.pem'; // 商户支付证书
		$this->wxpayConfig['apiclient_key'] = WSTRootPath().'/extend/wxpay/cert/apiclient_key.pem'; // 商户支付证书
		$this->wxpayConfig['curl_timeout'] = 30;
		$this->wxpayConfig['notifyurl'] = addon_url("pintuan://cron/tuannotify","",true,true);
		$this->wxpayConfig['returnurl'] = "";
		// 初始化WxPayConf
		new \WxPayConf($this->wxpayConfig);
	}

	/**
	 * 退款
	 */
	public function tuanRefund($puser){
        $refund = new \Refund();
        $refund_no = $puser['orderNo'].$puser['userId'];
        $refund->setParameter("transaction_id",$puser['tradeNo']);//微信订单号
        $refund->setParameter("out_refund_no",$refund_no);//商户退款单号
        $refund->setParameter("total_fee",$puser['realTotalMoney']*100);//订单金额
        $refund->setParameter("refund_fee",$puser["realTotalMoney"]*100);//退款金额
        $refund->setParameter("refund_fee_type","CNY");//货币种类
        $refund->setParameter("refund_desc","拼团订单【".$puser['orderNo']."】退款");//退款原因
        $refund->setParameter("notify_url",$this->wxpayConfig['notifyurl']);//退款原因

        $payParams = [];
        $payParams["userId"] = (int)$puser['userId'];
        $payParams["orderNo"] = $puser['orderNo'];

        $pdata = array();
        $pdata["userId"] = $puser['userId'];
        $pdata["transId"] = $refund_no;
        $pdata["paramsVa"] = json_encode($payParams);
        $pdata["payFrom"] = 'weixinpays';
        $m = new PM();
        $m->addPayLog($pdata);
        $rs = $refund->getResult();
		if($rs["result_code"]=="SUCCESS"){
			return WSTReturn("退款成功",1); 
		}else{
			return WSTReturn($rs['err_code_des'],-1); 
		}
    }

    /**
     * 异步通知
     */
   	public function tuanNotify(){
   		// 使用通用通知接口
		$notify = new \Notify();
		// 存储微信的回调
		$xml = file_get_contents("php://input");
		$notify->saveData ( $xml );
		if ($notify->data ["return_code"] == "SUCCESS"){
			$order = $notify->getData ();
			$req_info = $order["req_info"];

			$reqinfo = $notify->decryptReqinfo($req_info);//解密退款加密信息
			$transId = $reqinfo["out_refund_no"];
			$m = new PM();
          	$payParams = $m->getPayLog(["transId"=>$transId]);
	   		$obj = array();
	        $obj['refundTradeNo'] = $reqinfo["refund_id"];//微信退款单号
	        $obj['orderNo'] = $payParams['orderNo'];
	        $obj['userId'] = $payParams['userId'];
	        $m = new TM();
	        $rs = $m->complateTuanRefund($obj);
	        if($rs['status']==1){
	        	echo "SUCCESS";
	        }else{
	        	echo "FAIL";
	        }
	    }
   	}

}