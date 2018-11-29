<?php
namespace shangtao\home\controller;
use shangtao\common\model\GoodsAppraises as M;
/**
 * 评价控制器
 */
class GoodsAppraises extends Base{
	protected $beforeActionList = [
          'checkAuth' =>  ['except'=>'userappraise,getbyid'],
          'checkShopAuth'=>['only'=>'index,querybypage,shopreply']
    ];
	/**
	* 获取评价列表 商家
	*/
	public function index(){
		return $this->fetch('shops/goodsappraises/list');
	}
	/**
	* 获取评价列表 用户
	*/
	public function myAppraise(){
		return $this->fetch('users/orders/appraise_manage');
	}
	// 获取评价列表 商家
	public function queryByPage(){
		$m = new M();
		return $m->queryByPage();
	}
	// 获取评价列表 用户
	public function userAppraise(){
		$m = new M();
		return $m->userAppraise();
	}
	/**
	* 添加评价
	*/
	public function add(){
		$m = new M();
		$rs = $m->add();
		return $rs;

	}
	/**
	* 根据商品id取评论
	*/
	public function getById(){
		$m = new M();
		$rs = $m->getById();
		return $rs;
	}

	/**
	* 商家回复评价
	*/
	public function shopReply(){
		$m = new M();
		return $m->shopReply();
	}
}
