<?php
namespace addons\coupon\controller;
use think\addons\Controller;
use addons\coupon\model\Coupons as M;
use think\Db;
/**
 * weapp优惠券接口插件
 */
class WeApp extends Controller{
	/**
	 * 权限验证方法
	 */
	protected function checkAuth(){
		$tokenId = input('tokenId');
		if($tokenId==''){
			$rs = jsonReturn('您还未登录',-999);
			die($rs);
		}
		$userId = db('weapp_session')->where("tokenId='{$tokenId}'")->value('userId');
		if(empty($userId)){
			$rs = jsonReturn('登录信息已过期,请重新登录',-999);
			die($rs);
		}
		return true;
	}
	/**
	 * 领券中心列表查询
	 */
	public function pageCouponQuery(){
		$m = new M();
		$userId = model('weapp/index')->getUserId();
		$rs =  $m->pageCouponQuery($userId);
		if($rs){
			return jsonReturn('success',1,$rs);
		}else{
			return jsonReturn('',-1);
		}
	}
	/**
	 * 领取优惠券
	 */
	public function receive(){
		$this->checkAuth();
		$m = new M();
		$userId = model('weapp/index')->getUserId();
		$rs = $m->receive($userId);
		return jsonReturn('',1,$rs);
	}
	/**
	 * 加载优惠券数据
	 */
	public function pageQueryByUser(){
		$this->checkAuth();
		$m = new M();
		$userId = model('weapp/index')->getUserId();
		$rs =  $m->pageQueryByUser($userId);
		if($rs){
			return jsonReturn('success',1,$rs);
		}else{
			return jsonReturn('',-1);
		}
	}
	/**
	 *  可用优惠券商品查询
	 */
	public function pageQueryByCouponGoods(){
		$m = new M();
		$rs =  $m->pageQueryByCouponGoods();
		if($rs){
			return jsonReturn('success',1,$rs);
		}else{
			return jsonReturn('',-1);
		}
	}
	/**
	 *  可用优惠券商品查询
	 */
	public function getCouponsByGoods(){
		$m = new M();
		$userId = model('weapp/index')->getUserId();
		$rs =  $m->getCouponsByGoods($userId);
		if($rs){
			return jsonReturn('success',1,$rs);
		}else{
			return jsonReturn('',-1);
		}
	}
	/**
	 *  领取的优惠券数
	 */
	public function couponsNum(){
		$this->checkAuth();
		$m = new M();
		$userId = model('weapp/index')->getUserId();
		$rs = $m->couponsNum($userId);
		return jsonReturn('',1,$rs);
	}	
}