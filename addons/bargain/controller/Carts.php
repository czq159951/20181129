<?php
namespace addons\bargain\controller;

use think\addons\Controller;
use addons\bargain\model\Carts as M;
use addons\bargain\model\Bargains;
use shangtao\common\model\UserAddress;
/**
 * 全民砍价插件
 */
class Carts extends Controller{
	/**
	 * 去下单
	 */
	public function addCart(){
		$m = new M();
		return $m->addCart();
	}
	/**
	 * 下单
	 */
	public function submit(){
		$m = new M();
		return $m->submit(1);
	}
	/**
	 * 计算运费、积分和总商品价格
	 */
	public function getCartMoney(){
		$m = new M();
		$data = $m->getCartMoney();
		return $data;
	}
	/**
	 * 微信结算页面
	 */
	public function wxSettlement(){
		$CARTS = session('BARGAIN_CARTS');
		if(empty($CARTS)){
			session('wxdetail','对不起没有相关订单~~o(>_<)o~~');
			$this->redirect('wechat/error/message',['code'=>'wxdetail']);
		}
		//获取一个用户地址
		$addressId = (int)input('addressId');
		$ua = new UserAddress();
		if($addressId>0){
			$userAddress = $ua->getById($addressId);
		}else{
			$userAddress = $ua->getDefaultAddress();
		}
		$this->assign('userAddress',$userAddress);
		//获取省份
		$areas = model('common/Areas')->listQuery();
		$this->assign('areaList',$areas);
		$m = new M();
		$carts = $m->getCarts();
		$this->assign('carts',$carts);
		//获取用户积分
		$user = model('common/users')->getFieldsById((int)session('WST_USER.userId'),'userScore');
		//计算可用积分和金额
		$goodsTotalMoney = $carts['goodsTotalMoney'];
		$goodsTotalScore = WSTScoreToMoney($goodsTotalMoney,true);
		$useOrderScore =0;
		$useOrderMoney = 0;
		if($user['userScore']>$goodsTotalScore){
			$useOrderScore = $goodsTotalScore;
			$useOrderMoney = $goodsTotalMoney;
		}else{
			$useOrderScore = $user['userScore'];
			$useOrderMoney = WSTScoreToMoney($useOrderScore);
		}
		$this->assign('userOrderScore',$useOrderScore);
		$this->assign('userOrderMoney',$useOrderMoney);
		//获取支付方式
		$onlineType = ($carts['goodsType']==1)?1:-1;
		$payments = model('common/payments')->getByGroup('3',$onlineType);
		$this->assign('payments',$payments);
		return $this->fetch("/wechat/index/settlement");
	}
}