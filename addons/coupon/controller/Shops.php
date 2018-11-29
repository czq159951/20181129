<?php
namespace addons\coupon\controller;

use think\addons\Controller;
use addons\coupon\model\Coupons as M;
/**
 * 优惠券插件
 */
class Shops extends Controller{
	protected $beforeActionList = ['checkShopAuth' ];
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
	/**
	 * 优惠券列表
	 */
	public function index(){
    	return $this->fetch("/home/shops/list");
	}
	/**
	 * 加载优惠券数据
	 */
	public function pageQuery(){
		$m = new M();
		return $m->pageQueryByShop();
	}

	/**
	 * 跳去编辑页面
	 */
	public function edit(){
		$id = (int)input('id');
		$object = [];
		$m = new M();
		if($id>0){
            $object = $m->getById($id);
		}else{
			$object = $m->getEModel('coupons');
			$object['goods'] = [];
		}
		$this->assign("object",$object);
		return $this->fetch("/home/shops/edit");
	}

	/**
	 * 保存优惠券信息
	 */
	public function toEdit(){
		$id = (int)input('post.couponId');
		$m = new M();
		if($id==0){
            return $m->add();
		}else{
            return $m->edit();
		}
	}

	/**
	 * 删除优惠券
	 */
	public function del(){
		$m = new M();
		return $m->del();
	}

	/**
	 * 查询商品
	 */
	public function searchGoods(){
		$m = new M();
		return $m->searchGoods();
	}
}