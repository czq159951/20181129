<?php
namespace shangtao\app\controller;
use shangtao\app\model\Carts as M;
use shangtao\common\model\UserAddress;
use shangtao\common\model\Payments;
/**
 * 购物车控制器
 */
class Carts extends Base{
	// 前置方法执行列表
    protected $beforeActionList = [
        'checkAuth'
    ];

	/**
	 * 查看购物车列表
	 */
	public function index(){
		$m = new M();
		$carts = $m->getCarts(false);
		if(!empty($carts['carts'])){
			// 域名
			$carts['domain'] = $this->domain();
			return json_encode(WSTReturn('ok',1,$carts));
		}
		return json_encode(WSTReturn('暂无购物车数据',-1));
	}
    /**
    * 加入购物车
    */
	public function addCart(){
		$m = new M();
		$rs = $m->addCart();
		return json_encode($rs);
	}
	/**
	 * 修改购物车商品状态
	 */
	public function changeCartGoods(){
		$m = new M();
		$rs = $m->changeCartGoods();
		return json_encode($rs);
	}
	/*
	* 批量设置选中
	*/
	public function batchSetIsCheck(){
		$m = new M();
		$rs = $m->batchSetIsCheck();
		return json_encode($rs);
	}
	/**
	 * 删除购物车里的商品
	 */
	public function delCart(){
		$m = new M();
		$rs= $m->delCart();
		return json_encode($rs);
	}
	/**
	 * 计算运费、积分和总商品价格
	 */
	public function getCartMoney(){
		$m = new M();
		$data = $m->getCartMoney();
		return json_encode($data);
	}
	/**
	 * 计算运费、积分和总商品价格/虚拟商品
	 */
	public function getQuickCartMoney(){
		$m = new M();
		$data = $m->getQuickCartMoney();
		return json_encode($data);
	}
	/**
	 * 结算页面数据
	 */
	public function settlement(){
		$m = new M();
		//获取一个用户地址
		$addressId = (int)input('addressId');
		$ua = new UserAddress();
		$userId = (int)model('app/index')->getUserId();
		if($addressId>0){
			$userAddress = $ua->getById($addressId, $userId);
		}else{
			$userAddress = $ua->getDefaultAddress($userId);
		}
		//获取支付方式
		$pa = new Payments();
		$payments = $pa->getByGroup('4', -1, true);
		//获取已选的购物车商品
		$carts = $m->getCarts(true);
		if(empty($carts['carts']))return json_encode(WSTReturn('请选择商品',-1));
		$carts['userAddress'] = $userAddress;
		
		hook("mobileControllerCartsSettlement",["carts"=>$carts,"payments"=>&$payments]);

		$carts['payments'] = $payments;


		//获取用户积分
		$user = model('users')->getFieldsById($userId, 'userScore');
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
        $carts['userOrderScore'] = $useOrderScore;

        $carts['userOrderMoney'] = $useOrderMoney;
		$carts['domain'] = $this->domain();
		// 是否开启积分支付
		$carts['isOpenScorePay'] = WSTConf('CONF.isOpenScorePay');
		return json_encode(WSTReturn('请求成功',1,$carts));
	}
	/**
	 * 跳去虚拟商品购物车结算页面
	 */
	public function quickSettlement(){
		$m = new M();
		$userId = (int)$m->getUserId();
		//获取用户积分
		$user = model('users')->getFieldsById($userId,'userScore');

		//获取已选的购物车商品
		$carts = $m->getQuickCarts();

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

		//获取支付方式
		$pa = new Payments();
		$payments = $pa->getByGroup('4', -1, true);


		$carts['payments'] = $payments;
		$carts['userOrderScore'] = $useOrderScore;
        $carts['userOrderMoney'] = $useOrderMoney;
        // 是否开启积分支付
		$carts['isOpenScorePay'] = WSTConf('CONF.isOpenScorePay');
		$carts['domain'] = $this->domain();
		return json_encode(WSTReturn('请求成功',1,$carts));
	}
	/**
	* 获取购物车数量
	*/
	public function getCartNum(){
		$userId = (int)model('app/index')->getUserId();
		$rs = model('carts')->field('cartNum')->where(['userId'=>$userId])->count();
		$data['cartNum'] = $rs;
		return json_encode(WSTReturn('请求成功', 1, $data));
	}
}
