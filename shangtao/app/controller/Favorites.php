<?php
namespace shangtao\app\controller;
use shangtao\app\model\Favorites as M;
/**
 * 收藏控制器
 */
class Favorites extends Base{
	// 前置方法执行列表
    protected $beforeActionList = [
        'checkAuth',
    ];
    /**
    * 判断是否已关注
    */
    public function checkFavorite(){
    	$id = (int)input('id');
    	$userId = model('index')->getUserId();
    	$rs = model('common/favorites')->checkFavorite($id,0,$userId);
    	return json_encode(WSTReturn('ok',1,['fId'=>$rs]));
    }
	/**
	 * 关注的商品列表
	 */
	public function listGoodsQuery(){
		$m = new M();
		$data['list'] = $m->listGoodsQuery();
		if(!empty($data['list'])){
			foreach($data['list'] as $k=>$v){
				$data['list'][$k]['goodsImg'] = WSTImg($v['goodsImg'],3);
			}
		}
		// 域名
		$data['domain'] = $this->domain();
		echo(json_encode(WSTReturn('success',1,$data)));die;
	}
	/**
	 * 关注的店铺列表
	 */
	public function listShopQuery(){
		$m = new M();
		$data['list'] = $m->listShopQuery();
		if(!empty($data['list'])){
		foreach($data['list'] as $k=>$v){
			$data['list'][$k]['shopImg'] = WSTImg($v['shopImg'],3);
			if(!empty($v['goods'])){
				foreach($v['goods'] as $k1=>$v1){
					$v[$k1]['goodsImg'] = WSTImg($v1['goodsImg'],3);
				}
			}
		}
		}
		// 域名
		$data['domain'] = $this->domain();
		echo(json_encode(WSTReturn('success',1,$data)));die;
	}
	/**
	 * 取消关注
	 */
	public function cancel(){
		$m = model('Favorites');
		$rs = $m->del();
		return json_encode($rs);
	}
	/**
	 * 增加关注
	 */
	public function add(){
		$m = model('Favorites');
		$rs = $m->add();
		return json_encode($rs);
	}
}
