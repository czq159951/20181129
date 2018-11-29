<?php
namespace shangtao\app\controller;
use shangtao\common\model\GoodsAppraises as M;
/**
 * 评价控制器
 */
class GoodsAppraises extends Base{
    // 前置方法执行列表
    protected $beforeActionList = [
        'checkAuth'  =>  ['except'=>'getbyid'],// 只要访问only下的方法才才需要执行前置操作
        'checkShopAuth' => ['only'=>'querybypage,shopreply']
    ];
    /******************************************* 商家 ***************************************************/
    // 获取评价列表 商家
	public function queryByPage(){
		$m = new M();
		$shopId = $this->getShopId();
		$rs = $m->queryByPage($shopId);
		$rs['data']['domain'] = $this->domain();
		return json_encode($rs);
	}
	/**
	* 商家回复评价
	*/
	public function shopReply(){
		$m = new M();
		$shopId = $this->getShopId();
		return json_encode($m->shopReply($shopId));
	}
    /******************************************* 商家 ***************************************************/
	/**
	* 根据商品id评论
	*/
	public function getById(){
		$m = new M();
		$rs = $m->getById();
		if(isset($rs['data']['data'])){
			foreach($rs['data']['data'] as $k=>$v){
				if(isset($v['images'])){
					$imgs = explode(',',$v['images']);
					foreach($imgs as $k2=>$v2){
						$imgs[$k2] = WSTImg($v2,3);
					}
					$rs['data']['data'][$k]['images'] = $imgs;
				}
			}
		}
		$rs['domain'] = $this->domain();
		return json_encode($rs);
	}
	/**
	* 根据订单id,用户id,商品id获取评价
	*/
	public function getAppr(){
		$m = model('GoodsAppraises');
		
		$userId = (int)model('app/index')->getUserId();

		$rs = $m->getAppr($userId);
		// 删除无用字段
		unset($rs['data']['shopId']);
		unset($rs['data']['shopReply']);
		unset($rs['data']['isShow']);
		unset($rs['data']['dataFlag']);
		unset($rs['data']['replyTime']);
		if(!empty($rs['data']['images'])){
			$imgs = explode(',',$rs['data']['images']);
			foreach($imgs as $k=>$v){
				$imgs[$k] = WSTImg($v,1);
			}
			$rs['data']['images'] = $imgs;
		}
		return json_encode($rs);
	}
	/**
	* 添加评价
	*/
	public function add(){
		$m = new M();
		$userId = model('app/index')->getUserId();
		$rs = $m->add((int)$userId);
		return json_encode($rs);
	}
}
