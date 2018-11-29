<?php
namespace addons\auction\controller;
use think\addons\Controller;
use addons\auction\model\Auctions as M;
use shangtao\common\model\Payments;
/**
 * 插件控制器
 */
class Apis extends Controller{
	/**
	* APP请求检测是否有安装插件
	*/
	public function index(){
		return json_encode(['status'=>1]);
	}
	/**
     * 域名
     */
    public function domain(){
    	return url('/','','',true);
    }
	/**
	 * 拍卖商品列表查询
	 */
    public function auctionListQuery(){
		$m = new M();
    	$rs = $m->pageQuery();
    	if(!empty($rs['data'])){
    		foreach ($rs['data'] as $key =>$v){
    			$rs['data'][$key]['goodsImg'] = WSTImg($v['goodsImg'],2);
    		}
    	}
		// 域名
		$rs['domain'] = $this->domain();
    	return json_encode(WSTReturn('ok',1,$rs));
    }
    /**
    * 拍卖介绍【app端】
    */
    public function getAuctionIntro(){
    	$m = new M();
		$userId = model('app/index')->getUserId();
    	$rs = $m->getBySale((int)input('id'),$userId);
    	$code = "<!DOCTYPE html><html><body>
    			<style>img{width:100%;height:100%;}html{font-size:90%}</style>
    			{$rs['auctionDesc']}
                <script>window.onload=function(){window.location.hash = 1;document.title = document.body.clientHeight;}</script>
                </body></html>";
        return $this->display($code);
    }
	/**
	* 拍卖商品详情
	*/
	public function getAuctionDetail(){
		$m = new M();
		$userId = model('app/index')->getUserId();
    	$rs = $m->getBySale((int)input('id'),$userId);
		// 未找到该拍卖商品
		if(empty($rs))return json_encode(WSTReturn('拍卖详情不存在',1));
		// 域名
		$rs['domain'] = $this->domain();
		// 热门拍卖
		$rs['hot'] = $m->getHotActions(6);
		unset($rs['auctionDesc']);
    	return json_encode(WSTReturn('ok',1,$rs));
	}
	/**
	 * 拍卖商品
	 * id: auctionId
	 * payPrice: 竞拍价
	 * tokenId: 用于获取用户id
	 */
	public function addAcution(){
		 $m = new M();
		 $userId = model('app/index')->getUserId();
		 return json_encode($m->addAcution($userId));
	}
	/**
	* 获取拍卖商品当前价、加价幅度、出价人数、围观人数
	* id:auctionId
	*/
	public function getAuctionInfo(){
		$m = new \addons\auction\model\Apis();
		$rs = $m->getAuctionInfo();
		if(!empty($rs)){
			return json_encode(WSTReturn('ok',1,$rs));
		}
		return json_encode(WSTReturn('未找到记录',-1));
	}
	/**
	* 我参与的拍卖
	*/
	public function pageQuery(){
		$m = new M();
		$userId = model('app/index')->getUserId();
		if($userId>0){
			$rs = $m->pageQueryByUser($userId);
			$rs['data']['domain'] = $this->domain();
			return json_encode($rs);
		}
		return json_encode(WSTReturn('你还未登录~',-999));
	}
	/**
	 * 获取保证金列表
	 */
	public function pageQueryByMoney(){
		$m = new M();
		$userId = model('app/index')->getUserId();
		if($userId>0){
			$rs = $m->pageQueryByMoney($userId);
			$rs['data']['domain'] = $this->domain();
			return json_encode($rs);
		}
		return json_encode(WSTReturn('你还未登录~',-999));
	}
	/**
	* 出价记录
	*/
	public function getAuctionRecord(){
		$m = new M();
    	$rs = $m->pageQueryByAuctionLog((int)input('id'),true);
		// 域名
    	return json_encode($rs);
	}
	/**
	* 获取支付方式
	*/
	public function payType(){
		//获取支付方式
		$m = new M();
		$pa = new Payments();
		$payments = $pa->getByGroup('4', -1, true)[1]; // 只能在线支付
		$rs = $m->getPayInfo((int)input('auctionId/d',0),1);
		$rs['data']['payments'] = $payments;
		return json_encode($rs);
	}
	/*************************************************  余额支付start ******************************************************/
	/**
	 * 生成支付代码--跳转余额支付前调用，获取key
	 * payObj=bao
	 */
	function getWalletsUrl(){
		$am = new M();
		$payObj = input("payObj/s");
		$payFrom = (int)input("payFrom");//0:PC 1:手机 2:微信 3:app
		$pkey = "";
		$data = array();
		$data['status'] = 1;
		$auctionId = input("auctionId/d",0);
		// 获取用户id
		$userId = model('app/index')->getUserId();
		if($userId==0)return json_encode(WSTReturn('您还未登录~',-999));
		if($payObj=="bao"){
			$auction = $am->getUserAuction($auctionId);
			$orderAmount = $auction["cautionMoney"];
			
			if($auction["userId"]>0){
				$data["status"] = -1;
				$data["msg"] = "您已缴保证金";
			}else{
				$data["status"] = $orderAmount>0?1:-1;
				$data["msg"] = ($data["status"]==-1)?"无需支付保证金":"";
				$pkey = $payObj."@".$auctionId;
			}
		}else{
			$auction = $am->getAuctionPay($auctionId, $userId);
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
					$pkey = $payObj."@".$auctionId;
				}
			}
		}
		$pkey .= "@".$payFrom;
		$base64 = new \org\Base64();
        $key = WSTBase64url($base64->encrypt($pkey, "shangtao"));
		$data['key'] = $key;
		return json_encode($data);
	}
	
	
	/**
	 * 跳去支付页面
	 * key
	 * 
	 */
	public function wallets(){
		$key = input('key');
        $key = WSTBase64url($key,false);
        $base64 = new \org\Base64();
        $key = $base64->decrypt($key,"shangtao");
        $key = explode('@',$key);
        $data = [];
        $auctionId = (int)$key[1];
        $payFrom = (int)$key[2];
        $data['auctionId'] = (int)$key[1];
		// 获取用户id
		$userId = model('app/index')->getUserId();
		if($userId==0)return json_encode(WSTReturn('您还未登录~',-999));
		
        $data['userId'] = $userId;
		$m = new M();
		$needPay = 0;
		$this->assign('payObj',$key[0]);
		$this->assign('auctionId',$auctionId);
		if($key[0]=="bao"){
			$auction = $m->getUserAuction($data['auctionId']);
			$needPay = $auction["cautionMoney"];
			$flag = (isset($auction["userId"]) && $auction["userId"]>0)?true:false;
		}else{
			$auction = $m->getAuctionPay($data['auctionId'],$userId);
			$needPay = $auction["payPrice"];
			$flag = ($auction["isPay"]==1)?true:false;
		}
		if($flag){
			return json_encode(WSTReturn('您已支付，请勿重复支付~',-1));
		}else{
			//获取用户钱包
			$user = model('common/users')->getFieldsById($data['userId'],'userMoney');
			if($key[0]=='bao'){
				$rs = $m->getPayInfo($auctionId,1);
			}else{
				$rs = $m->getPayInfo($auctionId,2);
			}
			// 删除无用字段
			unset($rs['data']['payments']);
			$rs['data']['needPay'] = $needPay;
			$rs['data']['userMoney'] = $user['userMoney'];
			$rs['data']['domain'] = $this->domain();
			return json_encode($rs);
	    }
	}
	/**
	 * 执行余额支付
	 * 需要传递支付密码跟余额支付生成的key
	 * payPwd 
	 * key
	 */
	public function payByWallet(){
		$m = new M();
		$userId = model('app/Index')->getUserId();
		if($userId>0){
			return json_encode($m->payByWallet($userId));
		}
		return json_encode(WSTReturn('您还未登录',-999));
	}
	/**********************************************  余额支付end  *********************************************************/
	
	/**
	 * 检测是否支付拍卖价格-填写订单信息
	 * id auctionId
	 * tokenId
	 * 【addressId】
	 */
	public function checkPayStatus(){
		$m = new M();
		$userId = model('app/index')->getUserId();
		if($userId<=0){
			return json_encode(WSTReturn('您还未登录~',-999));
		}
		$data = $m->checkAuctionPayStatus((int)input('id'),$userId);
		if(empty($data)){
			return json_encode(WSTReturn('无效的拍卖记录',-1));
		}else{
			if($data['isPay']==1){
				$auction =  $m->get($data['auctionId']);
				if($auction->orderId>0){
					// 判断是否已经下单完成
					return json_encode(WSTReturn('对不起，该拍卖已下单完成',-1));
				}else{
					$addData = [];
					//获取一个用户地址
					$addressId = (int)input('addressId');
					if($addressId>0){
						$userAddress = model('common/UserAddress')->getById($addressId,$userId);
					}else{
						$userAddress = model('common/UserAddress')->getDefaultAddress($userId);
					}
					$addData['userAddress'] = $userAddress;
					// 可获得积分
					$addData['payPrice'] = WSTMoneyGiftScore($data['payPrice']);
					return json_encode(WSTReturn('ok',1,$addData));
				}
			}
			return json_encode(WSTReturn('非法操作',-1));
		}
	}
	/**
	 * 下单
	 */
	public function submit(){
		$userId = model('app/index')->getUserId();
		if($userId<=0){
			return json_encode(WSTReturn('您还未登录~',-999));
		}
		$m = new M();
		$orderSrc = input('orderSrc');
		$orderSrcArr = ['android'=>3,'ios'=>4];
		if(!isset($orderSrcArr[$orderSrc])){
			return json_encode(WSTReturn('非法订单来源~',-1));
		}
		$orderSrc = $orderSrcArr[$orderSrc];
		$rs = $m->submit($orderSrc,$userId);
		return json_encode($rs);
	}
	
}