<?php
namespace shangtao\weapp\controller;
use shangtao\common\model\GoodsAppraises as M;
/**
 * 评价控制器
 */
class GoodsAppraises extends Base{
    // 前置方法执行列表
    protected $beforeActionList = [
        'checkAuth'  =>  ['except'=>'getbyid'],// 只要访问only下的方法才才需要执行前置操作
    ];
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
				for($i=1; $i<=$v['avgScore']; $i++) {
					$rs['data']['data'][$k]['avgScores'][] = $i;
				}
			}
		}
		return jsonReturn('',1,$rs);
	}
	/**
	* 根据订单id,用户id,商品id获取评价
	*/
	public function getAppr(){
		$m = model('GoodsAppraises');
		$userId = model('weapp/index')->getUserId();
		$rs = $m->getAppr($userId);
		if($rs){
			// 删除无用字段
			unset($rs['shopId']);
			unset($rs['shopReply']);
			unset($rs['isShow']);
			unset($rs['dataFlag']);
			unset($rs['replyTime']);
			if(!empty($rs['images'])){
				$imgs = explode(',',$rs['images']);
				foreach($imgs as $k=>$v){
					$imgs[$k] = WSTImg($v,3);
				}
				$rs['images'] = $imgs;
			}
			return jsonReturn('success',1,$rs);
		}else{
			return jsonReturn('无相关评价',-1);
		}
	}
	/**
	* 添加评价
	*/
	public function add(){
		$m = new M();
		$userId = model('weapp/index')->getUserId();
		$rs = $m->add($userId);
		return jsonReturn('',1,$rs);
	}
}
