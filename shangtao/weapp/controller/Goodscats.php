<?php
namespace shangtao\weapp\controller;
use shangtao\weapp\model\GoodsCats as M;
/**
 * 商品分类控制器
 */
class GoodsCats{
	/**
     * 列表
     */
    public function index(){
    	$m = new M();
    	$goodsCatList = $m->getGoodsCats();
    	if(!empty($goodsCatList)){
            return jsonReturn('success',1,$goodsCatList);
        }
        return jsonReturn('error',-1);
    }
    /**
     * 获取指定列表
     */
    public function lists(){
    	$keyword = (int)input('parentId');
    	$isFloor = input('isFloor',-1);
    	return jsonReturn('success',1,WSTGoodsCats($keyword,$isFloor));
    }
}
