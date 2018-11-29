<?php
namespace addons\auction\controller;
use think\Loader;
use Env;
use think\addons\Controller;
use shangtao\common\model\Payments as PM;
use addons\auction\model\Auctions as AM;
/**
 * 阿里支付控制器
 */
class Alipaysmo extends Controller{

	public function getAlipaysUrl(){

		$am = new AM();
		$payObj = input("payObj/s");
		$data = array();
		$auctionId = input("auctionId/d",0);
		if($payObj=="bao"){
			$auction = $am->getUserAuction($auctionId);
			$orderAmount = $auction["cautionMoney"];
			if($auction["userId"]>0){
				$data["status"] = -1;
				$data["msg"] = "您已缴保证金";
			}else{
				$data["status"] = $orderAmount>0?1:-1;
				$data["msg"] = ($data["status"]==-1)?"无需支付保证金":"";
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
				}	
			}
		}
		return $data;
	}
	
    /**
     * 支付宝支付跳转方法
     */
    public function toAliPay(){

        

    	$payObj = input("payObj/s");
    	$am = new AM();
    	$obj = array();
    	$data = array();
    	$orderAmount = 0;
    	$auctionId = input("auctionId/d",0);
    	$userId = (int)session('WST_USER.userId');
    	$call_back_url = "";
    	
    	if($payObj=="bao"){//充值
    		$auction = $am->getUserAuction($auctionId);
    		$orderAmount = $auction["cautionMoney"];
    		if($auction["userId"]>0){
    			$data["status"] = -1;
                session('0001','您已缴保证金');
                $this->redirect('home/error/message',['code'=>'0001']);
    		}else{
    			$data["status"] = $orderAmount>0?1:-1;
    			$data["msg"] = ($data["status"]==-1)?"无需支付保证金":"";
    		}
    		$returnUrl = addon_url("auction://goods/modetail",array("id"=>$auctionId),true,true);
    		
    	}else{
    		$auction = $am->getAuctionPay($auctionId);
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
	    		$returnUrl = addon_url("auction://users/mocheckPayStatus",array("id"=>$auctionId),true,true);
    		}
    	}
    	
        if($data["status"]==1){
            $out_trade_no = WSTOrderNo();
            $body = ($payObj=="bao")?'支付保证金':'支付拍卖货款';
            $subject = ($payObj=="bao")?'保证金':'拍卖货款';
            $passback_params = $payObj."@".$userId."@".$auctionId;
            $m = new PM();
            $payment = $m->getPayment("alipays");
            require Env::get('root_path') . 'extend/alipay/aop/AopClient.php' ;
            require Env::get('root_path') . 'extend/alipay/aop/request/AlipayTradeWapPayRequest.php' ;
            $aop = new \AopClient ();  
            $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';  
            $aop->appId = $payment["appId"];  
            $aop->rsaPrivateKey = $payment["rsaPrivateKey"]; 
            $aop->alipayrsaPublicKey = $payment["alipayrsaPublicKey"];
            $aop->apiVersion = '1.0';  
            $aop->signType = 'RSA2';  
            $aop->postCharset= "UTF-8";;  
            $aop->format='json';  
            $request = new \AlipayTradeWapPayRequest  ();  
            $request->setReturnUrl($returnUrl);  
            $request->setNotifyUrl(addon_url("auction://alipaysmo/aliNotify","",true,true));
            $passback_params = urlencode($passback_params);
            $bizcontent = "{\"body\":\"$body\","
                        . "\"subject\": \"$subject\","
                        . "\"out_trade_no\": \"$out_trade_no\","
                        . "\"timeout_express\": \"90m\","
                        . "\"total_amount\": \"$orderAmount\","
                        . "\"passback_params\": \"$passback_params\","
                        . "\"product_code\":\"QUICK_WAP_WAY\""
                        . "}";
            $request->setBizContent($bizcontent);
            //请求  
            $result = $aop->pageExecute ($request);
            echo $result;
        }else{
    		echo "<span style='font-size:40px;'>".$data["msg"]."</span>";
    		return;
    	}
    }
    
    /**
     * 验证签名
     */
    function aliCheck($params){
        require Env::get('root_path') . 'extend/alipay/aop/AopClient.php' ;
        $aop = new \AopClient;
        $m = new PM();
        $payment = $m->getPayment("alipays");
        $aop->alipayrsaPublicKey = $payment["alipayrsaPublicKey"];
        $flag = $aop->rsaCheckV1($params, NULL, "RSA2");
        return $flag;
    }

    /**
     * 服务器异步通知方法
     */
    function aliNotify() {
        if($this->aliCheck($_POST)){
            if ($_POST['trade_status'] == 'TRADE_SUCCESS' || $_POST['trade_status'] == 'TRADE_FINISHED'){
                $extras = explode("@",urldecode($_POST['passback_params']));
                $obj = array ();
                $obj["trade_no"] = $_POST['trade_no'];
                $obj["out_trade_no"] = $_POST["out_trade_no"];
                $obj["total_fee"] = $_POST['total_amount'];
                $obj["payObj"] = $extras[0];
                $obj["userId"] = (int)$extras[1];
                $obj["auctionId"] = (int)$extras[2];
                $obj["payFrom"] = 'alipays';
                // 支付成功业务逻辑
                $m = new AM();
                $rs = $m->complateCautionMoney ( $obj );
                if($rs["status"]==1){
                    echo 'success';
                }else{
                    echo 'fail';
                }
            }
        }
    }

}
