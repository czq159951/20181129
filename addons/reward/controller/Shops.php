<?php
namespace addons\reward\controller;

use think\addons\Controller;
use addons\reward\model\Rewards as M;
/**
 * 满就送插件
 */
class Shops extends Controller{
	protected $beforeActionList = ['checkShopAuth'];
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
	/**
	 * 满就送列表
	 */
	public function index(){
    	return $this->fetch("/home/shops/list");
	}
	/**
	 * 加载活动数据
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
			$object = $m->getEModel('rewards');
			$object['goods'] = [];
		}
		$this->assign("object",$object);
		return $this->fetch("/home/shops/edit");
	}

	/**
	 * 保存活动信息
	 */
	public function toEdit(){
		$id = (int)input('post.rewardId');
		$m = new M();
		if($id==0){
            return $m->add();
		}else{
            return $m->edit();
		}
	}

	/**
	 * 删除活动
	 */
	public function del(){
		$m = new M();
		return $m->del();
	}
	/**
	 * 获取在售商品
	 */
	public function getSaleGoods(){
		$m = new M();
		return $m->getSaleGoods();
	}
	/**
	 * 获取优惠券
	 */
	public function getCoupons(){
		$m = new M();
		return $m->getCoupons();
	}

	/**
	 * 查询商品
	 */
	public function searchGoods(){
		$m = new M();
		return $m->searchGoods();
	}
}