<?php
namespace shangtao\app\model;
use shangtao\common\model\Orders as COrders;
use think\Db;
/**
 * 订单类
 */
class Orders extends COrders{
	protected $pk = 'orderId';
	/**
	 * 获取登录用户的id
	 */
	public function getUserId(){
		$tokenId = input('tokenId');
		return Db::name('app_session')->where("tokenId='{$tokenId}'")->value('userId');
	}
	/**
	* 获取当前用户对应的shopId
	*/
	public function getShopId($userId){
		return Db::name('shop_users')->where("userId='{$userId}'")->value('shopId');
	}


	public function getOrderPayFrom($out_trade_no){
	
		$rs = $this->where(['dataFlag'=>1,'orderNo|orderunique'=>$out_trade_no])->field('orderId,userId,orderNo,orderunique')->find();
		if(!empty($rs)){
			$rs['isBatch'] = ($rs['orderunique'] == $out_trade_no)?1:0;
		}
		return $rs;
	}
	
}
