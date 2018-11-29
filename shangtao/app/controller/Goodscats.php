<?php
namespace shangtao\app\controller;
use shangtao\app\model\GoodsCats as M;
/**
 * 商品分类控制器
 */
class GoodsCats{
    /**
    * 获取所有一级分类
    */
    public function pageQuery(){
        $rs = model('goodsCats')->listQuery(0);
        return json_encode(WSTReturn('success',1,$rs));
    }
    /**
    * 获取一级商品分类
    */
    public function getGoodsCats(){
        $rs = WSTGoodsCats(0);
        return json_encode(WSTReturn('ok',1,$rs));
    }
	/**
     * 列表
     */
    public function index(){
    	$m = new M();
    	$goodsCatList = $m->getGoodsCats();
    	if(!empty($goodsCatList)){
            // 域名
            $goodsCatList['domain'] = url('/','','',true);
            return json_encode(WSTReturn('success',1,$goodsCatList));
        }
        return json_encode(WSTReturn('error',-1));
    }  
}
