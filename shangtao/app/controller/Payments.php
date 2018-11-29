<?php
namespace shangtao\app\controller;
use think\Loader;
use Env;
use shangtao\app\model\Orders as M;
use shangtao\common\model\Orders as OM;
use shangtao\common\model\Payments as PM;
use shangtao\common\model\ChargeItems as CM;
use shangtao\common\model\LogPayParams as LPM;
use shangtao\common\model\LogMoneys as LM;
/**
 * 支付控制器
 */
class Payments extends Base{
    /**
    * 获取在线支付方式
    */
    public function getPayments(){
        $pa = new PM();
        $payments = $pa->getByGroup('4', 1, true);
        return json_encode(WSTReturn('ok',1,$payments));
    }
	/**
     * 支付宝支付跳转方法
     */
    public function aliPay(){
	    $m = new M();
	    $om = new OM();
	    $userId = $m->getUserId();
	    
        $payObj = input('payObj');

        if($payObj=="recharge"){//充值
            $itemId = (int)input("itemId/d");
            $orderAmount = 0;
            if($itemId>0){
                $cm = new CM();
                $item = $cm->getItemMoney($itemId);
                $total_fee = isSet($item["chargeMoney"])?$item["chargeMoney"]:0;
            }else{
                $total_fee = (int)input("needPay/d");
            }
            $out_trade_no = WSTOrderNo();
            $obj = array();
            $obj["targetId"] = $userId;
            $obj["targetType"] = 0;// 充值对象1:商家 0:用户
            $obj["itemId"] = $itemId;
            $obj["payObj"] = $payObj;
            $subject = '钱包充值';
        }else{
            $obj = array();
            $obj["userId"] = $userId;
            $obj['orderNo'] = input('orderNo');
            $obj['isBatch'] = (int)input('isBatch');
            $data = $om->checkOrderPay2($obj);
            if($data["status"]==-1){
                return json_encode(WSTReturn('您的订单已支付，不要重复支付！',-1));
            }
            $order = $om->getPayOrders($obj);
            $total_fee = $order["needPay"];
            $payRand = $order["payRand"];
            $out_trade_no = $obj["orderNo"]."a".$payRand;
            $subject = "支付订单费用";
        }



	   	require Env::get('root_path') . 'extend/alipay/aop/AopClient.php';
	   	require Env::get('root_path') . 'extend/alipay/aop/request/AlipayTradeAppPayRequest.php';
	    $m = new PM();
	    $payment = $m->getPayment("alipays");
	    $aop = new \AopClient;
		$aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
		$aop->appId = $payment["appId"];
		$aop->rsaPrivateKey = $payment["rsaPrivateKey"];
		$aop->format = "json";
		$aop->charset = "UTF-8";
		$aop->signType = "RSA2";
		$aop->alipayrsaPublicKey = $payment["alipayrsaPublicKey"];
		//实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
		$request = new \AlipayTradeAppPayRequest();
		//SDK已经封装掉了公共参数，这里只需要传入业务参数
		$bizcontent = "{\"body\":\"$subject\","
						. "\"subject\": \"$subject\","
						. "\"out_trade_no\": \"$out_trade_no\","
						. "\"timeout_express\": \"30m\","
						. "\"total_amount\": \"$total_fee\","
						. "\"product_code\":\"QUICK_MSECURITY_PAY\""
						. "}";
			
	
		$request->setNotifyUrl(url("app/payments/aliNotify","",true,true));
		$request->setBizContent($bizcontent);

        /** 记录日志以便回调时区分是充值还是订单支付 **/
        $data = array();
        $data["userId"] = $userId;
        $data["transId"] = $out_trade_no;
        $data["paramsVa"] = json_encode($obj);
        $data["payFrom"] = 'alipays';
        $m = new LPM();
        $m->addPayLog($data);
        /** 记录日志以便回调时区分是充值还是订单支付 **/

		//这里和普通的接口调用不同，使用的是sdkExecute
		$response = $aop->sdkExecute($request);
		//htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
        echo $response;die;
    }
    
    /**
     * 服务器异步通知页面方法
     *
     */
    function aliNotify() {
    	$m = new M();
    	$om = new OM();
    	
    	require Env::get('root_path') . 'extend/alipay/aop/AopClient.php';
    	
    	$aop = new \AopClient;
        $m = new PM();
    	$payment = $m->getPayment("alipays");
		$aop->alipayrsaPublicKey = $payment["alipayrsaPublicKey"];
		$flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");
    	
    	if ($flag) {
    		$notify_data = $_POST;
    		// 交易号
    		$trade_no = $_POST["trade_no"];
    		// 商户订单号
    		$out_trade_no = $_POST["out_trade_no"];
    		$total_fee = $_POST["total_amount"];
    		// 交易状态
    		$trade_status = $_POST["trade_status"];
    		if ($trade_status == 'TRADE_FINISHED' OR $trade_status  == 'TRADE_SUCCESS') {
    			$obj["trade_no"] = $trade_no;
    			$tradeNo = explode("a",$out_trade_no);

      			$obj["out_trade_no"] = $tradeNo[0];
    			$obj["total_fee"] = $total_fee;
    			$obj["payFrom"] = "alipays";

                $m = new LPM();
                $payParams = $m->getPayLog(["transId"=>$obj["out_trade_no"]]);
                if(isSet($payParams["payObj"]) && $payParams["payObj"]=='recharge'){
                    $obj["targetId"] = $payParams["targetId"];
                    $obj["targetType"] = $payParams["targetType"];
                    $obj["itemId"] = $payParams["itemId"];;
                    // 支付成功业务逻辑
                    $m = new LM();
                    $rs = $m->complateRecharge ( $obj );
                }else{
        			$payFrom = $om->getOrderPayFrom($tradeNo[0]);
        			$obj["userId"] = $payFrom["userId"];
        			$obj["isBatch"] = $payFrom["isBatch"];
        			//支付成功业务逻辑
        			$rs = $om->complatePay($obj);
                }
    			if($rs["status"]==1){
    				echo 'success';
    			}else{
    				echo 'fail';
    			}
    		}
    		echo "success"; // 请不要修改或删除
    		
    	} else {
    		echo "fail";
    	}
    }
    

    
    /**
     * 微信支付
     */
    public function weixinPay(){
    	$m = new PM();
	    $om = new OM();
        $userId = model('index')->getUserId();
        $payObj = input('payObj');
        if($payObj=='recharge'){
            $cm = new CM();
            $itemId = (int)input("itemId/d");
            $targetType = 0;// 充值对象1:商家 0:用户

            $needPay = 0;
            if($itemId>0){
                $item = $cm->getItemMoney($itemId);
                $needPay = isSet($item["chargeMoney"])?$item["chargeMoney"]:0;
            }else{
                $needPay = (int)input("needPay/d");
            }
            $out_trade_no = WSTOrderNo();
            $subject = "钱包充值";
            $attach = $payObj."@".$userId."@".$targetType."@".$needPay."@".$itemId;
            $total_fee = $needPay;
        }else{
            $obj = array();
            $obj["userId"] = $userId;
            $obj['orderNo'] = input('orderNo');
            $obj['isBatch'] = (int)input('isBatch');
            $data = $om->checkOrderPay2($obj);
            if($data["status"]==-1){
                return json_encode(WSTReturn('您的订单已支付，不要重复支付！',-1));
            }
            $order = $om->getPayOrders($obj);
            $total_fee = $order["needPay"];
            $payRand = $order["payRand"];
            $out_trade_no = $obj["orderNo"]."a".$payRand;
            $attach = $userId."@".$obj["orderNo"]."@".$obj["isBatch"];
            $subject = '支付订单费用';
        }



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
    	$wxpayConfig ['notifyurl'] = url("app/payments/weixinNotify","",true,true);
    	$wxpayConfig ['returnurl'] =  "";
    	 
    	// 初始化WxPayConf
    	new \WxPayConf ( $wxpayConfig );
    	//使用统一支付接口
    	$unifiedOrder = new \UnifiedOrder();
    	$unifiedOrder->setParameter("out_trade_no",$out_trade_no);//商户订单号
    	$unifiedOrder->setParameter("notify_url",$wxpayConfig ['notifyurl']);//通知地址
    	$unifiedOrder->setParameter("trade_type","APP");//交易类型
        $unifiedOrder->setParameter("attach",$attach);//扩展参数
    	$unifiedOrder->setParameter("body",$subject);//商品描述
    	$needPay = WSTBCMoney($total_fee,0,2);
    	$unifiedOrder->setParameter("total_fee", $needPay * 100);//总金额

    	$prepay_id = $unifiedOrder->getPrepayId();
    	$obj["prepayid"] = $prepay_id;
    	$rs = $unifiedOrder->getParameters($obj);
    	$data =array('msg'=>'success','status'=>1,'data'=>array($rs));
    	echo json_encode($data);
    	 
    }
    
    
    /**
     * 微信回调接口
     */
    public function weixinNotify() {

    	$m = new PM();
    	$om = new OM();
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
    	$wxpayConfig ['notifyurl'] = url("app/payments/weixinNotify","",true,true);
    	$wxpayConfig ['returnurl'] =  "";
    	// 初始化WxPayConf
    	new \WxPayConf ( $wxpayConfig );
    
    	// 使用通用通知接口
    	$notify = new \Notify();
    	// 存储微信的回调
    	$xml = file_get_contents('php://input');
    	$notify->saveData ( $xml );
    	$notify->setReturnParameter ( "return_code", "SUCCESS" ); // 设置返回码
    
    	$returnXml = $notify->returnXml ();
    
    	if ($notify->data ["return_code"] == "FAIL") {
    		// 此处应该更新一下订单状态，商户自行增删操作
    	} elseif ($notify->data ["result_code"] == "FAIL") {
    		// 此处应该更新一下订单状态，商户自行增删操作
    	} else {
    		$order = $notify->getData ();
            $obj = array();
            $obj["trade_no"] = $order['transaction_id'];
            $obj["total_fee"] = (float)$order["total_fee"]/100;
            $extras =  explode ( "@", $order ["attach"] );
            if($extras[0]=="recharge"){//充值
                $targetId = (int)$extras [1];
                $targetType = (int)$extras [2];
                $itemId = (int)$extras [4];

                $obj["out_trade_no"] = $order['out_trade_no'];
                $obj["targetId"] = $targetId;
                $obj["targetType"] = $targetType;
                $obj["itemId"] = $itemId;
                $obj["payFrom"] = 'app_weixinpays';
                // 支付成功业务逻辑
                $m = new LM();
                $rs = $m->complateRecharge ( $obj );
            }else{
               
                $obj["userId"] = $extras[0];
                $obj["out_trade_no"] = $extras[1];
                $obj["isBatch"] = $extras[2];
                $obj["payFrom"] = "app_weixinpays";
        		//支付成功业务逻辑
        		$rs = $om->complatePay($obj);
            }

    		if($rs["status"]==1){
    			echo 'success';
    		}else{
    			echo 'fail';
    		}
    	}
    
    }
}
