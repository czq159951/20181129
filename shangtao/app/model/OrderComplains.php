<?php
namespace shangtao\app\model;
use shangtao\common\validate\OrderComplains as validate;
use think\Db;
/**
 * 订单投诉类
 */
class OrderComplains extends Base{
	 protected $pk = 'complainId';
	 /**
	  * 获取用户投诉列表
	  */
	public function queryUserComplainByPage(){
		$userId = $this->getUserId();
		$orderNo = (int)Input('orderNo');

		$where[] = ['o.userId','=',$userId];
		if($orderNo>0){
			$where[] = ['o.orderNo','like',"%$orderNo%"];
		}
		$rs = $this->alias('oc')
				   ->field('oc.complainId,o.orderNo,s.shopName,oc.complainStatus,oc.complainTime')
				   ->join('__SHOPS__ s','oc.respondTargetId=s.shopId','left')
				   ->join('__ORDERS__ o','oc.orderId=o.orderId and o.dataFlag=1','inner')
				   ->order('oc.complainId desc')
				   ->where($where)
				   ->paginate()
				   ->toArray();
		foreach($rs['data'] as $k=>$v){
			if($v['complainStatus']==0){
				$rs['data'][$k]['complainStatus'] = '等待处理';
			}elseif($v['complainStatus']==1){
				$rs['data'][$k]['complainStatus'] = '等待被投诉方回应';
			}elseif($v['complainStatus']==2 || $v['complainStatus']==3 ){
				$rs['data'][$k]['complainStatus'] = '等待仲裁';
			}elseif($v['complainStatus']==4){
				$rs['data'][$k]['complainStatus'] = '已仲裁';
			}
		}
		return $rs;
	}
	/**
	 * 获取订单信息
	 */
	public function getOrderInfo(){
		$userId = $this->getUserId();
		$orderId = (int)Input('orderId');

		//判断是否提交过投诉
		$rs = $this->alreadyComplain($orderId,$userId);
		$data = array('complainStatus'=>1);
		if($rs['complainId']==''){
			$where['o.orderId'] = $orderId;
			$where['o.userId'] = $userId;
			//获取订单信息
			$order = db('orders')->alias('o')
			 						 ->field('o.realTotalMoney,o.orderNo,o.orderId,o.createTime,o.deliverMoney,s.shopName,s.shopId')
									 ->join('__SHOPS__ s','o.shopId=s.shopId','left')
									 ->where($where)
									 ->find();
			if($order){
				//获取相关商品
			    $goods = $this->getOrderGoods($orderId);
				$order["goodsList"] = $goods;
			}
			$data['order'] = $order;
			$data['complainStatus'] = 0;
		}
		
        return $data;
	}
	// 判断是否已经投诉过
	public function alreadyComplain($orderId,$userId){
		return $this->field('complainId')->where("orderId=$orderId and complainTargetId=$userId")->find();
	}
	//获取相关商品
	public function getOrderGoods($orderId){
	  return db('goods')->alias('g')
						->field('og.orderId, og.goodsId ,g.goodsSn, og.goodsName , og.goodsPrice shopPrice,og.goodsImg')
						->join('__ORDER_GOODS__ og','g.goodsId = og.goodsId','inner')
						->where("og.orderId=$orderId")
						->select();
	}

	/**
	 * 保存订单投诉信息
	 */
	public function saveComplain(){

		$userId = $this->getUserId();
		$data['orderId'] = (int)input('orderId');
        //判断订单是否该用户的
		$order = db('orders')->field('orderId,shopId')->where("userId=$userId")->find($data['orderId']);
		if(!$order){
			return WSTReturn('无效的订单信息',-1);
		}

		//判断是否提交过投诉
		$rs = $this->alreadyComplain($data['orderId'],$userId);

		if((int)$rs['complainId']>0){
			return WSTReturn("该订单已进行了投诉,请勿重提提交投诉信息",-1);
		}
		Db::startTrans();
		try{
			$data['complainTargetId'] = $userId;
			$data['respondTargetId'] = $order['shopId'];
			$data['complainStatus'] = 0;
			$data['complainType'] = (int)input('complainType');
			$data['complainTime'] = date('Y-m-d H:i:s');
			$data['complainAnnex'] = input('complainAnnex');
			$data['complainContent'] = input('complainContent');
			$validate = new validate();
		    if(!$validate->scene('add')->check($data))return WSTReturn($validate->getError());
			$rs = $this->save($data);
			if($rs !==false){
				WSTUseImages(0, $this->complainId, $data['complainAnnex']);
				Db::commit();
				return WSTReturn('您的订单投诉已提交,请留意商城消息',1);
			}
		}catch (\Exception $e) {
		    Db::rollback();
	    }
	    return WSTReturn('投诉失败',-1);
	}

	/**
	 * 获取投诉详情
	 */
	public function getComplainDetail($userType = 0){
		$userId = $this->getUserId();
		$id = (int)Input('id');
		$where['complainTargetId']=$userId;

		//获取订单信息
		$where['complainId'] = $id;
		$rs = $this->alias('oc')
				   ->field('oc.*,o.realTotalMoney,o.orderNo,o.orderId,o.createTime,o.deliverMoney,s.shopName,s.shopId')
				   ->join('__ORDERS__ o','oc.orderId=o.orderId','inner')
				   ->join('__SHOPS__ s','o.shopId=s.shopId')
				   ->where($where)->find();
		if($rs){
			if($rs['complainAnnex']!='')$rs['complainAnnex'] = explode(',',$rs['complainAnnex']);
			if($rs['respondAnnex']!='')$rs['respondAnnex'] = explode(',',$rs['respondAnnex']);

			//获取相关商品
			$goods = $this->getOrderGoods($rs['orderId']);
			$rs["goodsList"] = $goods;
		}
        return $rs;
	}
	
}
