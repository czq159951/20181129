<?php
namespace addons\auction\model;
use think\Loader;
use think\Db;
use Env;
use shangtao\common\model\Payments as M;
use addons\auction\model\Auctions as AM;
use think\addons\BaseModel as Base;
/**
 * 阿里支付控制器
 */
class Alipays extends Base{

	/**
	 * 退款
	 */
	public function auctionRefund($amoney){

        $request_no = $amoney['id'].$amoney['userId'];
        $backMoney = $amoney['cautionMoney'];
        $tradeNo = $amoney['tradeNo'];
        $refund_reason = "拍卖退款";
        
        require Env::get('root_path') . 'extend/alipay/aop/AopClient.php';
	   	require Env::get('root_path') . 'extend/alipay/aop/request/AlipayTradeRefundRequest.php';
        $m = new M();
	   	$payment = $m->getPayment("alipays");
        $aop = new \AopClient ();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = $payment["appId"];
        $aop->rsaPrivateKey = $payment["rsaPrivateKey"];
        $aop->alipayrsaPublicKey=$payment["alipayrsaPublicKey"];
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset='UTF-8';
        $aop->format='json';
        $request = new \AlipayTradeRefundRequest ();

        $request->setBizContent("{" .
            "\"trade_no\":\"$tradeNo\"," .
            "\"refund_amount\":\"$backMoney\"," .
            "\"refund_reason\":\"$refund_reason\"," .
            "\"out_request_no\":\"$request_no\"" .
        "  }");

        $result = $aop->execute ( $request); 
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if(!empty($resultCode) && $resultCode == 10000){
        	if($result->$responseNode->fund_change=="Y"){
        		$obj = array();
		        $obj['refundTradeNo'] = $request_no;//退款单号
		        $obj['id'] = $amoney['id'];
                $m = new AM();
		        $rs = $m->complateAuctionRefund($obj);
		        if($rs['status']==1){
		        	return WSTReturn("退款成功",1); 
		        }else{
		        	return WSTReturn("退款失败",1);
		        }
        	}
        } else {
        	$msg = $result->$responseNode->sub_msg;
            return WSTReturn($msg,-1); 
        }
    }

}
