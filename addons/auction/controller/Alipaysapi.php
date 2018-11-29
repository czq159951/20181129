<?php
namespace addons\auction\controller;
use think\Loader;
use Env;
use think\addons\Controller;
use shangtao\common\model\Payments as PM;
use addons\auction\model\Auctions as AM;
use shangtao\common\model\LogPayParams as LPM;

/**
 * 阿里支付控制器
 */
class Alipaysapi extends Controller{
    /**
     * 支付宝支付跳转方法
     * tokenId
     * auctionId
     * payObj
     */
    public function toAliPay(){
    	$payObj = input("payObj/s");
    	$am = new AM();
    	$obj = array();
    	$data = array();
    	$orderAmount = 0;
    	$auctionId = input("auctionId/d",0);
        $userId = model('app/index')->getUserId();
        if($userId <= 0){
            return json_encode(WSTReturn('您还未登录',-999));
        }
    	
    	if($payObj=="bao"){//充值
    		$auction = $am->getUserAuction($auctionId);
    		$orderAmount = $auction["cautionMoney"];
    		if($auction["userId"]>0){
    			$data["status"] = -1;
                $data['msg'] = '您已缴保证金';
    		}else{
    			$data["status"] = $orderAmount>0?1:-1;
    			$data["msg"] = ($data["status"]==-1)?"无需支付保证金":"";
    		}
    	}else{
    		$auction = $am->getAuctionPay($auctionId,$userId);
    		if($auction["endPayTime"]<date("Y-m-d H:i:s")){
    			$data["status"] = -1;
    			$data["msg"] = "您已过拍卖支付货款期限";
    		}else{
	    		$orderAmount = $auction["payPrice"];
	    		if($auction["isPay"]==1){
	    			$data["status"] = -1;
	    			$data["msg"] = "您已缴拍卖货款";
	    		}else{
	    			$data["status"] = $orderAmount>0?1:-1;
	    			$data["msg"] = ($data["status"]==-1)?"无需支付拍卖货款":"";
	    		}
    		}
    	}
    	
    	$jsonParams = array();
    	$jsonParams["payObj"] = $payObj;
    	$jsonParams["userId"] = $userId;
    	$jsonParams["auctionId"] = $auctionId;

    	$notify_url = addon_url("auction://alipaysapi/aliNotify","",true,true);//  异步回调地址

    	if($data["status"]==1){
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
	    	$subject = ($payObj=="bao")?'支付保证金':'支付拍卖货款';
	    	
	    	$out_trade_no = WSTOrderNo();// 生成支付订单号

            // 获取需要支付的金额,订单号
            $total_fee = $orderAmount;// 需要支付的金额
            $out_trade_no = WSTOrderNo();
            
            
            $bizcontent = "{\"body\":\"支付订单费用\","
                            . "\"subject\": \"$subject\","
                            . "\"out_trade_no\": \"$out_trade_no\","
                            . "\"timeout_express\": \"30m\","
                            . "\"total_amount\": \"$total_fee\","
                            . "\"product_code\":\"QUICK_MSECURITY_PAY\""
                            . "}";
            // 记录支付订单日志
            $m = new LPM();
            $obj = array();
            $obj["userId"] = $userId;
            $obj["transId"] = $out_trade_no;
            $obj["paramsVa"] = json_encode($jsonParams);
            $rs = $m->addPayLog($obj);
            $request->setNotifyUrl(addon_url("auction://alipaysapi/aliNotify","",true,true));
            $request->setBizContent($bizcontent);
            //这里和普通的接口调用不同，使用的是sdkExecute
            $response = $aop->sdkExecute($request);
            //htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
            echo $response;die;
    	}else{
    		return json_encode($data);
    	}
    }
    
    /**
     * 服务器异步通知页面方法
     *
     */
    function aliNotify() {

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
                
                $obj = array();
                $m = new LPM();
                $obj["transId"] = $out_trade_no;
                $params = $m->getPayLog($obj);

                $obj["trade_no"] = $trade_no;// 1.订单号
                $obj["out_trade_no"] = $out_trade_no;// 2.订单号
                $obj["total_fee"] = $total_fee;// 3.支付的费用
                $obj["payFrom"] = 'alipays';// 4.支付来源

                // 获取用户id auctionId 支付类型
                $obj["userId"] = (int)$params["userId"];// 5.用户id
                $obj["auctionId"] = (int)$params["auctionId"];
                $obj["payObj"] = $params["payObj"];

                // 支付成功业务逻辑
                $m = new AM();
                $rs = $m->complateCautionMoney ( $obj );
                
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
	
}
