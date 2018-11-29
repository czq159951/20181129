<?php
namespace addons\auction\controller;
use think\addons\Controller;
use think\Loader;
use Env;
use shangtao\common\model\Payments as PM;
use shangtao\common\model\Payments as M;
use shangtao\common\model\Orders as OM;
use shangtao\common\model\LogMoneys as LM;
use addons\auction\model\Auctions as AM;
/**
 * 银联支付控制器
 */
class Unionpays extends Controller{
	
	/**
	 * 初始化
	 */
	private $unionConfig;
	public function initialize() {
		header ("Content-type: text/html; charset=utf-8");
		require Env::get('root_path') . 'extend/unionpay/sdk/acp_service.php';
		$m = new M();
		$this->unionConfig = $m->getPayment("unionpays");
	
		$config = array();
		$config["signCertPwd"] = $this->unionConfig["unionSignCertPwd"];//"000000"
		$config["signMethod"] = "01";
		$config["frontUrl"] = addon_url("auction://unionpays/response","",true,true);
		$config["backUrl"] = addon_url("auction://unionpays/notify","",true,true);
		new \SDKConfig($config);
	}
	
	
	public function getUnionpaysUrl(){
		$am = new AM();
		$payObj = input("payObj/s");
		$data = array();
		if($payObj=="bao"){
			$auctionId = input("auctionId/d",0);
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
					$data["msg"] = "您已缴成拍卖货款";
				}else{
					$data["status"] = $orderAmount>0?1:-1;
					$data["msg"] = ($data["status"]==-1)?"无需支付拍卖货款":"";
				}
			}
		}
		return $data;
	}
	
	/**
     * 生成支付代码
     * @param   array   $order      订单信息
     * @param   array   $config_value    支付方式信息
     */
    public function toUnionpays(){
    	
    	$payObj = input("payObj/s");
    	$am = new AM();
    	$obj = array();
    	$data = array();
    	$orderAmount = 0;
    	$extra_param = "";
    	$auctionId = input("auctionId/d",0);
    	$userId = (int)session('WST_USER.userId');
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
    			$extra_param = $payObj."|".$userId."|".$auctionId;
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
	    			$extra_param = $payObj."|".$userId."|".$auctionId;
	    		}
    		}
    	}
    	
    	if($data["status"]==1){
	    	$params = array(
	    		//以下信息非特殊情况不需要改动
	    		'version' => \SDKConfig::$version,                 //版本号
	    		'encoding' => 'utf-8',				  //编码方式
	    		'txnType' => '01',				      //交易类型
	    		'txnSubType' => '01',				  //交易子类
	    		'bizType' => '000201',				  //业务类型
	    		'frontUrl' =>  \SDKConfig::$frontUrl,  //前台通知地址
	    		'backUrl' => \SDKConfig::$backUrl,	  //后台通知地址
	    		'signMethod' => \SDKConfig::$signMethod,//签名方法
	    		'channelType' => '07',	              //渠道类型，07-PC，08-手机
	    		'accessType' => '0',		          //接入类型
	    		'currencyCode' => '156',	          //交易币种，境内商户固定156
	    		//TODO 以下信息需要填写
	    		'merId' => $this->unionConfig["unionMerId"], //"777290058110048",//商户代码
	    		'orderId' => WSTOrderNo(),	//商户订单号，8-32位数字字母，不能含“-”或“_”
	    		'txnTime' => date('YmdHis'),	//订单发送时间，格式为YYYYMMDDhhmmss，取北京时间
	    		'txnAmt' => $orderAmount*100,	//交易金额，单位分，此处默认取demo演示页面传递的参数
	    		// 订单超时时间。
	    		//'payTimeout' => date('YmdHis', strtotime('+15 minutes')),
	    	
	    		'reqReserved' => $extra_param,
	    	);
	    	$acpService = new \AcpService();
	    	$acpService::sign ( $params );
	    	$uri = \SDKConfig::$frontTransUrl;
	    	$html_form = $acpService::createAutoFormHtml( $params, $uri );
	    	echo $html_form;
    	}
    }
    
    /**
     * 异步回调接口
     */
    public function notify(){                
      
        //计算得出通知验证结果        
        $acpService = new \AcpService(); // 使用银联原生自带的累 和方法 这里只是引用了一下 而已
        $verify_result = $acpService->validate($_POST);
        
     	if($verify_result){//验证成功
         	$out_trade_no = $_POST['orderId']; //商户订单号                    
            $queryId = $_POST['queryId']; //银联支付流水号
            // 解释: 交易成功且结束，即不可再做任何操作。
           	if($_POST['respMsg'] == 'Success!'){                    
	           	$m = new OM();
				$extras = explode("|",$_POST['reqReserved']);
				$rs = array();
				
				$userId = (int)$extras [1];
				$auctionId = (int)$extras [2];
				$obj = array ();
				$obj["trade_no"] = $queryId;
				$obj["out_trade_no"] = $out_trade_no;
				$obj["userId"] = $userId;
				$obj["auctionId"] = $auctionId;
				$obj["total_fee"] = (int)$_POST['settleAmt']/100;
				$obj["payFrom"] = 'unionpays';
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
      		echo "fail"; //验证失败                                
  		}
    }
    
    /**
     * 同步回调接口
     */
    public function response(){
        //计算得出通知验证结果        
        $acpService = new \AcpService(); // 使用银联原生自带的累 和方法 这里只是引用了一下 而已
        $verify_result = $acpService->validate($_POST);
        
   		if($verify_result){ //验证成功
       		$order_sn = $out_trade_no = $_POST['orderId']; //商户订单号
        	$queryId = $_POST['queryId']; //银联支付流水号                   
          	$respMsg = $_POST['respMsg']; //交易状态
                    
      		if($_POST['respMsg'] == 'success'){
      			$extras = explode("|",$_POST['reqReserved']);
      			$auctionId = (int)$extras [2];
      			$m = new OM();
   				if($extras[0]=="bao"){//充值
   					return $this->fetch('/home/index/pay_success');
   				}else{
   					$this->redirect(addon_url("auction://users/checkPayStatus",array("id"=>$auctionId),true,true));
   				}
       		}else {                        
              session('0001','支付失败');
              $this->redirect('home/error/message',['code'=>'0001']);
   			}
     	}else {
     		 session('0001','支付失败');
         $this->redirect('home/error/message',['code'=>'0001']);
 		  }
    }

}
