<?php
namespace shangtao\weapp\model;
use shangtao\common\model\Orders as COrders;
use think\Db;
/**
 * 订单类
 */
class Orders extends COrders{
	protected $pk = 'orderId';
	public function getOrderPayFrom($out_trade_no){
		$rs = $this->where(['dataFlag'=>1,'orderNo|orderunique'=>$out_trade_no])->field('orderId,userId,orderNo,orderunique')->find();
		if(!empty($rs)){
			$rs['isBatch'] = ($rs['orderunique'] == $out_trade_no)?1:0;
		}
		return $rs;
	}
}
