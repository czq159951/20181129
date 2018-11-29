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
class Alipays extends Controller{

	public function __construct(){
		parent::__construct();
		$m = new AM();
		$data = $m->getConf('Auction');
		$this->assign("seoAuctionKeywords",$data['seoAuctionKeywords']);
		$this->assign("seoAuctionDesc",$data['seoAuctionDesc']);
	}
	
	/**
	 * 生成支付代码
	 */
	function getAlipaysUrl(){
		$payObj = input("payObj/s");
		$payFrom = (int)input("payFrom/s");
		$am = new AM();
		$obj = array();
		$data = array();
		$orderAmount = 0;
		$out_trade_no = WSTOrderNo();
		$passback_params = "";
		$subject = "";
		$body = "";
		$auctionId = input("auctionId/d",0);
		$userId = (int)session('WST_USER.userId');

		$pm = new PM();
        $payment = $pm->getPayment("alipays");
        require Env::get('root_path') . 'extend/alipay/aop/AopClient.php' ;
        require Env::get('root_path') . 'extend/alipay/aop/request/AlipayTradePagePayRequest.php' ;

		if($payObj=="bao"){//保证金
			$auction = $am->getUserAuction($auctionId);
			$orderAmount = $auction["cautionMoney"];
			if($auction["userId"]>0){
				$data["status"] = -1;
				$data["msg"] = "您已缴保证金";
			}else{
				$data["status"] = $orderAmount>0?1:-1;
				$data["msg"] = ($data["status"]==-1)?"无需支付保证金":"";
				$passback_params = $payObj."@".$userId."@".$auctionId;
				$subject = '拍卖保证金 ¥'.$orderAmount.'元';
				$body = '支付保证金';
			}
			$returnUrl = addon_url("auction://alipays/paysuccess","",true,true);
		}else{
			
			$auction = $am->getAuctionPay($auctionId);
			if($auction["endPayTime"]<date("Y-m-d H:i:s")){
				$data["status"] = -1;
				$data["msg"] = "您已过拍卖支付货款期限";
			}else{
				$orderAmount = $auction["payPrice"];
				$subject = '拍卖货款 ¥'.$orderAmount.'元';
				$body = '支付拍卖货款';
				if($auction["isPay"]==1){
					$data["status"] = -1;
					$data["msg"] = "您已缴拍卖货款";
				}else{
					$data["status"] = $orderAmount>0?1:-1;
					$data["msg"] = ($data["status"]==-1)?"无需支付拍卖货款":"";
					$passback_params = $payObj."@".$userId."@".$auctionId;
				}
			}
			$returnUrl = addon_url("auction://users/checkPayStatus",array("id"=>$auctionId),true,true);
		}
		
		if($data["status"]==1){
          
            $aop = new \AopClient ();  
            $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';  
            $aop->appId = $payment["appId"];  
            $aop->rsaPrivateKey = $payment["rsaPrivateKey"]; 
            $aop->apiVersion = '1.0';  
            $aop->signType = 'RSA2';  
            $aop->postCharset= "UTF-8";;  
            $aop->format='json';  
            $request = new \AlipayTradePagePayRequest ();  
            $request->setReturnUrl($returnUrl);
            $request->setNotifyUrl(addon_url("auction://alipays/aliNotify","",true,true));  
            $passback_params = urlencode($passback_params);
            $bizcontent = "{\"body\":\"$body\","
                        . "\"subject\": \"$subject\","
                        . "\"out_trade_no\": \"$out_trade_no\","
                        . "\"total_amount\": \"$orderAmount\","
                        . "\"passback_params\": \"$passback_params\","
                        . "\"product_code\":\"FAST_INSTANT_TRADE_PAY\""
                        . "}";
            $request->setBizContent($bizcontent);

            //请求  
            $result = $aop->pageExecute ($request);
            $data["result"]= $result;
            return $data;
        }else{
            return $data;
        }
	}
	

	function aliCheck($params){
        require Env::get('root_path') . 'extend/alipay/aop/AopClient.php' ;
        $aop = new \AopClient;
        $m = new PM();
        $payment = $m->getPayment("alipays");
        $aop->alipayrsaPublicKey = $payment["alipayrsaPublicKey"];
        $flag = $aop->rsaCheckV1($params, NULL, "RSA2");
        return $flag;
    }

    function paySuccess(){
        return $this->fetch("/home/index/pay_success");
    }
	
	/**
	 * 支付结果异步回调
	 */
	function aliNotify(){
		
		if($this->aliCheck($_POST)){
            if ($_POST['trade_status'] == 'TRADE_SUCCESS' || $_POST['trade_status'] == 'TRADE_FINISHED'){
            	$extras = explode("@",urldecode($_POST['passback_params']));
				$rs = array();
				
				$userId = (int)$extras [1];
				$auctionId = (int)$extras [2];
				$obj = array ();
				$obj["trade_no"] = $_POST['trade_no'];
				$obj["out_trade_no"] = $_POST["out_trade_no"];;
				$obj["userId"] = $userId;
				$obj["auctionId"] = $auctionId;
				$obj["total_fee"] = $_POST['total_amount'];
				$obj["payFrom"] = 'alipays';
				$obj["payObj"] = $extras[0];
				// 支付成功业务逻辑
				$m = new AM();
				$rs = $m->complateCautionMoney ( $obj );
				if($rs["status"]==1){
					echo 'success';
				}else{
					echo 'fail';
				}
            }
        }else{
			echo 'fail';
		}
	}
}
