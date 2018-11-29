<?php
namespace shangtao\home\controller;
use shangtao\common\model\Carts as M;
use shangtao\common\model\Payments as PM;
/**
 * 购物车控制器
 */
class Carts extends Base{
	protected $beforeActionList = ['checkAuth'];
    /**
    * 加入购物车
    */
	public function addCart(){
		$m = new M();
		$rs = $m->addCart();
		return $rs;
	}
	/**
	 * 查看购物车列表
	 */
	public function index(){
		$m = new M();
		$carts = $m->getCarts(false);
		$this->assign('carts',$carts);
		return $this->fetch('carts');
	}
	/**
	 * 删除购物车里的商品
	 */
	public function delCart(){
		$m = new M();
		$rs= $m->delCart();
		return $rs;
	}
	/**
	 * 虚拟商品下单
	 */
	public function quickSettlement(){
		$m = new M();
		//获取支付方式
		$pm = new PM();
		$payments = $pm->getByGroup('1',1);
        $carts = $m->getQuickCarts();
        if(empty($carts['carts'])){
        	$this->assign('message','Sorry~您还未选择商品。。。');
			return $this->fetch('error_msg');
        }
        hook("homeControllerCartsSettlement",["carts"=>$carts,"payments"=>&$payments]);
        //获取用户积分
        $user = model('users')->getFieldsById((int)session('WST_USER.userId'),'userScore');
        //计算可用积分和金额
        $goodsTotalMoney = $carts['goodsTotalMoney']-$carts['promotionMoney'];
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
        $this->assign('payments',$payments);
        $this->assign('carts',$carts);
        return $this->fetch('settlement_quick');
	}
	/**
	 * 跳去购物车结算页面
	 */
    public function settlement(){
		$m = new M();
		//获取一个用户地址
		$userAddress = model('UserAddress')->getDefaultAddress();
		$this->assign('userAddress',$userAddress);
		//获取省份
		$areas = model('Areas')->listQuery();
		$this->assign('areaList',$areas);
		//获取支付方式
		$pm = new PM();
		$payments = $pm->getByGroup('1');
		$carts = $m->getCarts(true);
		if(empty($carts['carts'])){
        	$this->assign('message','Sorry~您还未选择商品。。。');
			return $this->fetch('error_msg');
        }
		hook("homeControllerCartsSettlement",["carts"=>$carts,"payments"=>&$payments]);
        //获取用户积分
        $user = model('users')->getFieldsById((int)session('WST_USER.userId'),'userScore');
        //计算可用积分和金额
        $goodsTotalMoney = $carts['goodsTotalMoney']-$carts['promotionMoney'];
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
		$this->assign('carts',$carts);
		$this->assign('payments',$payments);
		return $this->fetch('settlement');
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
	 * 计算运费、积分和总商品价格
	 */
	public function getQuickCartMoney(){
		$m = new M();
		$data = $m->getQuickCartMoney();
		return $data;
	}
	/**
	 * 修改购物车商品状态
	 */
	public function changeCartGoods(){
		$m = new M();
		$rs = $m->changeCartGoods();
		return $rs;
	}
	/**
	 * 批量修改购物车商品状态
	 */
	public function batchChangeCartGoods(){
		$m = new M();
		$rs = $m->batchChangeCartGoods();
		return $rs;
	}
	/**
	 * 获取购物车商品
	 */
    public function getCart(){
		$m = new M();
		$carts = $m->getCarts(false);
		return WSTReturn("", 1,$carts);;
	}
	/**
	 * 获取购物车信息
	 */
	public function getCartInfo(){
		$m = new M();
		$rs = $m->getCartInfo();
		return WSTReturn("", 1,$rs);
	}
}
