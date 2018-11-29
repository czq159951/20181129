<?php
namespace shangtao\app\controller;
use shangtao\app\model\Orders as M;
use shangtao\common\model\Payments;
/**
 * 订单控制器
 */
class Orders extends Base{
	// 前置方法执行列表
    protected $beforeActionList = [
        'checkAuth'
    ];
	/*********************************************** 用户操作订单 ************************************************************/
	/**
	*  提醒发货
	*/
	public function noticeDeliver(){
		$m = new M();
		$userId = $m->getUserId();
        return json_encode($m->noticeDeliver($userId));
	}
	/**
	* 根据订单号获取物流信息
	*/
	public function getLogistics(){
		$model = new \addons\kuaidi\model\Kuaidi();
		$orderId = (int)input('orderId');
		// 订单信息
		$data['orderInfo'] = $model->getOrderInfo();
		// 快递信息
		$data['logisticInfo'] = json_decode($model->getOrderExpress($orderId),true);
		
		if(!empty($data['logisticInfo'])){
			$state = isset($data['logisticInfo']['state'])?$data['logisticInfo']['state']:-1;
			// 存在物流信息
			switch ($state) {
				case '0':$stateTxt="运输中";break;
				case '1':$stateTxt="揽件";break;
				case '2':$stateTxt="疑难";break;
				case '3':$stateTxt="收件人已签收";break;
				case '4':$stateTxt="已退签";break;
				case '5':$stateTxt="派件中";break;
				case '6':$stateTxt="退回";break;
				default:$stateTxt="暂未获取到状态";break;
			}
			// 物流状态
			$data['orderInfo']['stateTxt'] = $stateTxt;
			$data['logisticInfo'] = isset($data['logisticInfo']['data'])?$data['logisticInfo']['data']:[];
		}
		// 域名-用于显示图片
		$data['domain'] = $this->domain();
		return json_encode(WSTReturn('ok',1,$data));
	}
    
	/**
	 * 提交订单
	 */
	public function submit(){
		$m = new M();
		$userId = $m->getUserId();
		$orderSrcArr = ['android'=>3,'ios'=>4];
		if(!isset($orderSrcArr[input('orderSrc')]))return json_encode(WSTReturn('非法订单来源',-1));
		$orderSrc = $orderSrcArr[input('orderSrc')];
		$rs = $m->submit((int)$orderSrc, $userId);
		return json_encode($rs);
	}
	/**
	 * 提交虚拟订单
	 */
	public function quickSubmit(){
		$m = new M();
		$userId = $m->getUserId();
		$rs = $m->quickSubmit(2, $userId);
		return json_encode($rs);
	}
	/**
	* 订单列表
	*/
	public function getOrderList(){
		/* 
		 	-3:拒收、退款列表
			-2:待付款列表 
			-1:已取消订单
			0,1: 待收货
			2:待评价/已完成
		*/
		$flag = -1;
		$type = input('param.type');
		$status = [];
		// 是否取出取消、拒收、退款订单理由
		$cancelData = $rejectData = $refundData = false;
		switch ($type) {
			case 'waitPay':
				$status=[-2];
				$cancelData = true;
				break;
			case 'waitDelivery':
				$status=[0];
				$rejectData = true;
				$cancelData = true;
				break;
			case 'waitReceive':
				$status=[1];
				$rejectData = true;
				$cancelData = true;
				break;
			case 'waitAppraise':
				$status=[2];
				$flag=0;
				break;
			case 'finish': 
				$status=[2];
				break;
			case 'abnormal': // 退款/拒收 与取消合并
				$status=[-1,-3];
				$refundData = true;
				break;
			default:
				$status=[-3,-2,-1,0,1,2];
				$cancelData = $rejectData = $refundData = true;
				break;
		}
		$m = new M();
		$userId = $m->getUserId();
		$rs = $m->userOrdersByPage($status, $flag, $userId);
		foreach($rs['data'] as $k=>$v){
			// 删除无用字段
			WSTUnset($rs['data'][$k],'shopQQ,shopWangWang,goodsMoney,totalMoney,deliverMoney,orderSrc,createTime,complainId,refundId,payTypeName,hook,isRefund');
			$a = WSTLangDeliverType(1);
			$b = WSTLangDeliverType(0);
			$rs['data'][$k]['deliverType'] = ($v['deliverType']==$a)?1:0;
			// 判断是否退款
			if(in_array($v['orderStatus'],[-1,-3]) && ($v['payType']==1) && ($v['isPay']==1) ){
				$rs['data'][$k]['status'] .= ($v['isRefund']==1)?'(已退款)':'(未退款)';
			}
			if(!empty($v['list'])){
				foreach($v['list'] as $k1=>$v1){
					$rs['data'][$k]['list'][$k1]['goodsImg'] = $v1['goodsImg'];
				}
			}
		}
		// 获取域名,用于显示图片
		$rs['domain'] = $this->domain();

		// 根据获取的type来取
		// 取消理由
		if($cancelData)$rs['cancelReason'] = WSTDatas('ORDER_CANCEL');
		// 拒收理由
		if($rejectData)$rs['rejectReason'] = WSTDatas('ORDER_REJECT');
		// 退款理由
		if($refundData)$rs['refundReason'] = WSTDatas('REFUND_TYPE');


		if(empty($rs['data']))return json_encode(WSTReturn('没有相关订单',-1));
		return json_encode(WSTReturn('请求成功', 1, $rs));
	}

	/**
	 * 订单详情
	 */
	public function getDetail(){
		$m = new M();
		$userId = (int)$m->getUserId();
		$isShop = (int)input('isShop');
		if($isShop==1){
			// 根据用户id查询店铺id
			$userId = (int)model('shops')->getShopId($userId);
		}
		$rs = $m->getByView((int)input('id'), $userId);
		if(isset($rs['status']))return json_encode($rs);
		// 删除无用字段
		unset($rs['log']);
		// 发票税号
		$invoiceArr = json_decode($rs['invoiceJson'],true);
		if(isset($invoiceArr['invoiceCode']))$rs['invoiceCode'] = $invoiceArr['invoiceCode'];
		$rs['status'] = WSTLangOrderStatus($rs['orderStatus']);
		$rs['payInfo'] = WSTLangPayType($rs['payType']);
		$rs['deliverInfo'] = WSTLangDeliverType($rs['deliverType']);
		foreach($rs['goods'] as $k=>$v){
			$v['goodsImg'] = WSTImg($v['goodsImg'],3);
		}
		// 若为取消或拒收则取出相应理由
		if($rs['orderStatus']==-1){
			if($rs['cancelReason']==0){
				$rs['cancelDesc'] = "订单长时间未支付，系统自动取消订单";
			}else{
				// 取消理由
				$reason = WSTDatas('ORDER_CANCEL');
				$rs['cancelDesc'] = $reason[$rs['cancelReason']]['dataName'];
			}
		}else if($rs['orderStatus']==-3){
			// 拒收理由
			$reason = WSTDatas('ORDER_REJECT');
			$rs['cancelDesc'] = $reason[$rs['rejectReason']]['dataName'];
		}
		// 退款理由   $rs['refundReason'] = WSTDatas('REFUND_TYPE');
		$rs['domain'] = $this->domain();
		/*******  满就送减免金额 *******/
        foreach($rs['goods'] as $k=>$v){
            if(isset($v['promotionJson']) && $v['promotionJson']!=''){// 有使用优惠券
                $rs['goods'][$k]['promotionJson'] = json_decode($v['promotionJson'],true);
                $rs['goods'][$k]['promotionJson']['extraJson'] = json_decode($rs['goods'][$k]['promotionJson']['extraJson'],true);
                // 满就送减免金额
                $rs['rewardMoney'] = $money = $rs['goods'][$k]['promotionJson']['promotionMoney'];
                break;
            }
        }
        /*********  优惠券  *********/
        if(isset($rs['userCouponId']) && $rs['userCouponId']>0){
            // 获取优惠券信息
            $money = json_decode($rs['userCouponJson'],true)['money']; // 优惠券优惠金额
            $rs['couponMoney'] = number_format($money,2);
        }
		return json_encode(WSTReturn('请求成功',1,$rs));
	}
	/**
	* 获取取消、拒收、退款订单操作的理由
	* @params $type 1:取消 2:拒收 4:退款
	*/
	public function getReason(){
		$codeArr = ['1'=>'ORDER_CANCEL','2'=>'ORDER_REJECT','4'=>'REFUND_TYPE'];
		$type = input('type');
		$type = (isset($codeArr[$type]))?$codeArr[$type]:$type;
		$data = WSTDatas($type);
		if(empty($data))return json_encode(WSTReturn('暂无数据',-1));
		return json_encode(WSTReturn('请求成功',1,$data));
	}
	/**
	 * 用户确认收货
	 */
	public function receive(){
		$m = new M();
		$orderId = input('param.orderId');
		$userId = $m->getUserId();
		$rs = $m->receive($orderId, $userId);
		return json_encode($rs);
	}

	/**
	* 用户-评价页
	*/
	public function orderAppraise(){
		$m = model('Orders');
		$oId = (int)input('oId');
		//根据订单id获取 商品信息
		$userId = $m->getUserId();
		$data = $m->getOrderInfoAndAppr($userId);
		$data['shopName'] = model('shops')->getShopName($oId);
		$data['oId'] = $oId;
		$data['domain'] = $this->domain();
		return json_encode(WSTReturn('请求成功', 1, $data));
	}
	
	/**
	 * 用户取消订单
	 */
	public function cancellation(){
		$m = new M();
		$userId = $m->getUserId();
		$rs = $m->cancel($userId);
		return json_encode($rs);
	}
   
	/**
	 * 用户拒收订单
	 */
	public function reject(){
		$m = new M();
		$userId = $m->getUserId();
		$rs = $m->reject((int)$userId);
		return json_encode($rs);
	}

	/**
	* 用户退款
	*/
	public function getRefund(){
		$m = new M();
		$rs = $m->getMoneyByOrder((int)input('id'));
		return json_encode(WSTReturn('请求成功',1,$rs));
	}




	/*********************************************** 商家操作订单 ************************************************************/


	/**
	* 商家-订单列表
	*/
	public function getSellerOrderList(){
		/* 
		 	-3:拒收、退款列表
			-2:待付款列表 
			-1:已取消订单
			 0: 待发货
			1,2:待评价/已完成
		*/
		$type = input('param.type');
		$express = false;// 快递公司数据
		$status = [];
		switch ($type) {
			case 'waitPay':
				$status=-2;
				break;
			case 'waitReceive':
				$status=1;
				break;
			case 'waitDelivery':
				$status=0;
				$express=true;
				break;
			case 'finish': 
				$status=2;
				break;
			case 'abnormal': // 退款/拒收 与取消合并
				$status=[-1,-3];
				break;
			case 'waitRefund': // 待退款
				$status=[-1,-3];
				break;
			default:
				$status=[-5,-4,-3,-2,-1,0,1,2];
				$express=true;
				break;
		}
		$m = new M();
		$userId = $m->getUserId();
		$shopId = (int)$m->getShopId($userId);

		$rs = $m->shopOrdersByPage($status, $shopId);

		foreach($rs['data'] as $k=>$v){
			// 删除无用字段
			WSTUnset($rs['data'][$k],'goodsMoney,totalMoney,deliverType,deliverMoney,orderSrc,createTime,payTypeName,isRefund,userAddress,userName,deliverTypeName');
			// 判断是否退款
			if(in_array($v['orderStatus'],[-1,-3]) && ($v['payType']==1) && ($v['isPay']==1) ){
				$rs['data'][$k]['status'] .= ($v['isRefund']==1)?'(已退款)':'(未退款)';
			}
			if(!empty($v['list'])){
				foreach($v['list'] as $k1=>$v1){
					$rs['data'][$k]['list'][$k1]['goodsImg'] = $v1['goodsImg'];
				}
			}
		}
		// 获取域名,用于显示图片
		$rs['domain'] = $this->domain();
		// 快递公司数据
		if($express)$rs['express'] = model('Express')->listQuery();

		if(empty($rs['data']))return json_encode(WSTReturn('没有相关订单',-1));
		return json_encode(WSTReturn('请求成功', 1, $rs));
	}

	/**
	 * 商家发货
	 */
	public function deliver(){
		$m = new M();
		$userId = (int)$m->getUserId();
		$shopId = (int)$m->getShopId($userId);
		$rs = $m->deliver($userId, $shopId);

		return json_encode($rs);
	}
	/**
	 * 商家修改订单价格
	 */
	public function editOrderMoney(){
		$m = new M();
		$userId = (int)$m->getUserId();
		$shopId = (int)$m->getShopId($userId);
		$rs = $m->editOrderMoney($userId, $shopId);

		return json_encode($rs);
	}
	/**
	 * 商家-操作退款
	 */
	public function toShopRefund(){
		$rs = model('OrderRefunds')->getRefundMoneyByOrder((int)input('id'));
		return json_encode(WSTReturn('请求成功', 1, $rs));
	}
	
	
}
