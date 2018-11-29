<?php
namespace shangtao\weapp\controller;
use shangtao\weapp\model\Orders as M;
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
	 * 提交订单
	 */
	public function submit(){
		$m = new M();
		$userId = model('weapp/index')->getUserId();
		$rs = $m->submit(5, $userId);
		return jsonReturn('',1,$rs);
	}
	/**
	 * 提交虚拟订单
	 */
	public function quickSubmit(){
		$m = new M();
		$userId = model('weapp/index')->getUserId();
		$rs = $m->quickSubmit(5, $userId);
		return jsonReturn('',1,$rs);
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
		$type = input('types');
		$status = [];
		// 是否取出取消、拒收、退款订单理由
		$cancelData = $rejectData = $refundData = false;
		switch ($type) {
			case 'waitPay':
				$status=[-2];
				$cancelData = true;
				break;
			case 'waitDeliver':
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
		$userId = model('weapp/index')->getUserId();
		$rs = $m->userOrdersByPage($status, $flag, $userId);
		foreach($rs['data'] as $k=>$v){
			// 删除无用字段
			WSTUnset($rs['data'][$k],'shopQQ,shopWangWang,goodsMoney,totalMoney,deliverType,deliverMoney,orderSrc,createTime,complainId,refundId,payTypeName,hook,isRefund');
			// 判断是否退款
			if(in_array($v['orderStatus'],[-1,-3]) && ($v['payType']==1) && ($v['isPay']==1) ){
				$rs['data'][$k]['status'] .= ($v['isRefund']==1)?'(已退款)':'(未退款)';
			}
			if(!empty($v['list'])){
				foreach($v['list'] as $k1=>$v1){
					$rs['data'][$k]['list'][$k1]['goodsImg'] = WSTImg($v1['goodsImg'],3);
				}
			}
		}
		// 根据获取的type来取
		// 取消理由
		if($cancelData){
			$rs['cancelIndex'][0] = 0;
			$rs['cancelData'][0] = '请选择您取消订单的原因';
			$cancelReason = WSTDatas('ORDER_CANCEL');
			if($cancelReason){
				foreach($cancelReason as $k=>$v){
					$rs['cancelIndex'][] = $v['dataVal'];
					$rs['cancelData'][] = $v['dataName'];
				}
			}
		}
		// 拒收理由
		if($rejectData){
			$rs['rejectIndex'][0] = 0;
			$rs['rejectData'][0] = '请选择您拒收订单的原因';
			$rsrejectReason = WSTDatas('ORDER_REJECT');
			if($rsrejectReason){
				foreach($rsrejectReason as $k=>$v){
					$rs['rejectIndex'][] = $v['dataVal'];
					$rs['rejectData'][] = $v['dataName'];
				}
			}
		}
		// 退款理由
		if($refundData){
			$rs['refundIndex'][0] = 0;
			$rs['refundData'][0] = '请选择您申请退款的原因';
			$refundReason = WSTDatas('REFUND_TYPE');
			if($refundReason){
				foreach($refundReason as $k=>$v){
					$rs['refundIndex'][] = $v['dataVal'];
					$rs['refundData'][] = $v['dataName'];
				}
			}
		}

		if(empty($rs['data']))return jsonReturn('没有相关订单',-1);
		return jsonReturn('success', 1, $rs);
	}

	/**
	 * 订单详情
	 */
	public function getDetail(){
		$m = new M();
		$type = input('types');
		$userId = model('weapp/index')->getUserId();
		$shopId = model('weapp/index')->getShopId($userId);
		if($type==2){
			$uId = $shopId;
		}else{
			$uId = $userId;
		}
		$rs = $m->getByView((int)input('id'), $uId);
		if(isset($rs['status']))return jsonReturn('', -1, $rs);
		// 删除无用字段
		unset($rs['log']);
		// 发票税号
		$invoiceArr = json_decode($rs['invoiceJson'],true);
		
		if(isset($invoiceArr['invoiceCode']))$rs['invoiceCode'] = $invoiceArr['invoiceCode'];
		$rs['orderWords'] = WSTLangOrderStatus($rs['orderStatus']);
		$rs['payInfo'] = WSTLangPayType($rs['payType']);
		$rs['deliverInfo'] = WSTLangDeliverType($rs['deliverType']);
		foreach($rs['goods'] as $k=>$v){
			$rs['goods'][$k]['goodsImg'] = WSTImg($v['goodsImg'],3);
			$rs['goods'][$k]['goodsSpecNames'] = explode('@@_@@',$v['goodsSpecNames']);
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
		return jsonReturn('success', 1, $rs);
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
		$orderId = input('post.id');
		$userId = model('weapp/index')->getUserId();
		$rs = $m->receive($orderId, $userId);
		return jsonReturn('', 1, $rs);
	}

	/**
	* 用户-评价页
	*/
	public function orderAppraise(){
		$m = model('Orders');
		$oId = (int)input('oId');
		//根据订单id获取 商品信息
		$userId = model('weapp/index')->getUserId();
		$data = $m->getOrderInfoAndAppr($userId);
		$data['shopName'] = model('shops')->getShopName($oId);
		return jsonReturn('success', 1, $data);
	}
	
	/**
	 * 用户取消订单
	 */
	public function cancellation(){
		$m = new M();
		$userId = model('weapp/index')->getUserId();
		$rs = $m->cancel($userId);
		return jsonReturn('', 1, $rs);
		
	}
   
	/**
	 * 用户拒收订单
	 */
	public function reject(){
		$m = new M();
		$userId = model('weapp/index')->getUserId();
		$rs = $m->reject($userId);
		return json_encode($rs);
	}

	/**
	* 用户退款
	*/
	public function getRefund(){
		$m = new M();
		$rs = $m->getMoneyByOrder((int)input('id'));
		return jsonReturn('', 1, $rs);
	}
	/**
	 * 提醒发货
	 */
	public function noticeDeliver(){
		$m = new M();
		$userId = model('weapp/index')->getUserId();
		$rs = $m->noticeDeliver($userId);
		return jsonReturn('success', 1, $rs);
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
		$type = input('param.types');
		$express = false;// 快递公司数据
		$status = [];
		switch ($type) {
			case 'waitPay':
				$status=-2;
				break;
			case 'waitReceive':
				$status=1;
				break;
			case 'waitDeliver':
				$status=0;
				$express=true;
				break;
			case 'finish': 
				$status=2;
				break;
			case 'abnormal': // 退款/拒收 与取消合并
				$status=[-1,-3];
				break;
			default:
				$status=[-5,-4,-3,-2,-1,0,1,2];
				$express=true;
				break;
		}
		$m = new M();
		$userId = model('weapp/index')->getUserId();
		$shopId = model('weapp/index')->getShopId($userId);

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
					$rs['data'][$k]['list'][$k1]['goodsImg'] = WSTImg($v1['goodsImg'],3);
				}
			}
		}
		// 快递公司数据
		if($express){
			$rs['deliverIndex'][0] = 0;
			$rs['deliverData'][0] = '请选择快递公司';
			$express = model('Express')->listQuery();
			if($express){
				foreach($express as $k=>$v){
					$rs['deliverIndex'][] = $v['expressId'];
					$rs['deliverData'][] = $v['expressName'];
				}
			}
		}

		if(empty($rs['data']))return jsonReturn('没有相关订单',-1);
		return jsonReturn('success', 1, $rs);
	}

	/**
	 * 商家发货
	 */
	public function deliver(){
		$m = new M();
		$userId = model('weapp/index')->getUserId();
		$shopId = model('weapp/index')->getShopId($userId);
		$rs = $m->deliver($userId, $shopId);
		return jsonReturn('', 1, $rs);
	}
	/**
	 * 商家修改订单价格
	 */
	public function editOrderMoney(){
		$m = new M();
		$userId = model('weapp/index')->getUserId();
		$shopId = model('weapp/index')->getShopId($userId);
		$rs = $m->editOrderMoney($userId, $shopId);
		return jsonReturn('', 1, $rs);
	}
	/**
	 * 商家-操作退款
	 */
	public function toShopRefund(){
		$rs = model('OrderRefunds')->getRefundMoneyByOrder((int)input('id'));
		return jsonReturn('', 1, $rs);
	}
}
