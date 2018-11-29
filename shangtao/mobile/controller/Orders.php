<?php
namespace shangtao\mobile\controller;
use shangtao\common\model\Orders as M;
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
		return $m->noticeDeliver();
	}
	/**
	 * 提交订单
	 */
	public function submit(){
		$m = new M();
		$rs = $m->submit(2);
		return $rs;
	}
	/**
	 * 提交虚拟订单
	 */
	public function quickSubmit(){
		$m = new M();
		$rs = $m->quickSubmit();
		return $rs;
	}
	/**
	 * 在线支付方式
	 */
	public function succeed(){
		//获取支付方式
		$pa = new Payments();
		$payments = $pa->getByGroup('2');
		$this->assign('payments',$payments);
		$this->assign('orderNo',input("get.orderNo"));
		$this->assign('isBatch',(int)input("get.isBatch/d",0));
		return $this->fetch("users/orders/orders_pay_list");
	}
	/**
	 * 订单管理
	 */
	public function index(){
		$type = input('param.type','');
		$this->assign('type',$type);
		return $this->fetch("users/orders/orders_list");
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
		switch ($type) {
			case 'waitPay':
				$status=[-2];
				break;
			case 'waitDeliver':
				$status=[0];
				break;
			case 'waitReceive':
				$status=[1];
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
				break;
			default:
				$status=[-5,-4,-3,-2,-1,0,1,2];
				break;
		}
		$m = new M();
		$rs = $m->userOrdersByPage($status,$flag);
		foreach($rs['data'] as $k=>$v){
			if(!empty($v['list'])){
				foreach($v['list'] as $k1=>$v1){
					$rs['data'][$k]['list'][$k1]['goodsImg'] = WSTImg($v1['goodsImg'],3);
				}
			}
		}
		return $rs;
	}

	/**
	 * 订单详情
	 */
	public function getDetail(){
		$m = new M();
		$rs = $m->getByView((int)input('id'));
		$rs['status'] = WSTLangOrderStatus($rs['orderStatus']);
		$rs['payInfo'] = WSTLangPayType($rs['payType']);
		$rs['deliverInfo'] = WSTLangDeliverType($rs['deliverType']);
		foreach($rs['goods'] as $k=>$v){
			$rs['goods'][$k]['goodsImg'] = WSTImg($v['goodsImg'],3);
		}
		// 优惠券钩子
		hook('mobileDocumentOrderSummaryView',['rs'=>&$rs]);
		// 满就送钩子
		hook('mobileDocumentOrderViewGoodsPromotion',['rs'=>&$rs]);
		return $rs;
	}

	/**
	 * 用户确认收货
	 */
	public function receive(){
		$m = new M();
		$rs = $m->receive();
		return $rs;
	}

	/**
	* 用户-评价页
	*/
	public function orderAppraise(){
		$m = model('Orders');
		$oId = (int)input('oId');
		//根据订单id获取 商品信息
		$data = $m->getOrderInfoAndAppr();
		$data['shopName']=model('shops')->getShopName($oId);
		$this->assign('data',$data);
		$this->assign('oId',$oId);
		return $this->fetch('users/orders/orders_appraises');
	}
	
	/**
	 * 用户取消订单
	 */
	public function cancellation(){
		$m = new M();
		$rs = $m->cancel();
		return $rs;
	}
   
	/**
	 * 用户拒收订单
	 */
	public function reject(){
		$m = new M();
		$rs = $m->reject();
		return $rs;
	}

	/**
	* 用户退款
	*/
	public function getRefund(){
		$m = new M();
		return $m->getMoneyByOrder((int)input('id'));
	}




	/*********************************************** 商家操作订单 ************************************************************/

	/**
	* 商家-查看订单列表
	*/
	public function sellerOrder(){
		$this->checkShopAuth("list");
		$type = input('param.type','all');
		$this->assign('type',$type);
		$express = model('Express')->listQuery();
		$this->assign('express',$express);
		return $this->fetch('users/sellerorders/orders_list');
	}

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
		$this->checkShopAuth($type);
		$status = [];
		switch ($type) {
			case 'waitPay':
				$status=-2;
				break;
			case 'waitDeliver':
				$status=0;
				break;
			case 'waitReceive':
				$status=1;
				break;
			case 'waitDelivery':
				$status=0;
				break;
			case 'finish': 
				$status=2;
				break;
			case 'abnormal': // 退款/拒收 与取消合并
				$status=[-1,-3];
				break;
			default:
				$status=[-5,-4,-3,-2,-1,0,1,2];
				break;
		}
		$m = new M();
		$rs = $m->shopOrdersByPage($status);
		foreach($rs['data'] as $k=>$v){
			if(!empty($v['list'])){
				foreach($v['list'] as $k1=>$v1){
					$rs['data'][$k]['list'][$k1]['goodsImg'] = WSTImg($v1['goodsImg'],3);
				}
			}
		}
		return WSTReturn('操作成功',1,$rs);
	}

	/**
	 * 商家发货
	 */
	public function deliver(){
		$this->checkShopAuth("waitDeliver");
		$m = new M();
		$rs = $m->deliver();
		return $rs;
	}
	/**
	 * 商家修改订单价格
	 */
	public function editOrderMoney(){
		$this->checkShopAuth("waitPay");
		$m = new M();
		$rs = $m->editOrderMoney();
		return $rs;
	}
	/**
	 * 商家-操作退款
	 */
	public function toShopRefund(){
		$this->checkShopAuth("abnormal");
		return model('OrderRefunds')->getRefundMoneyByOrder((int)input('id'));
	}
	
	
}
