<?php
namespace shangtao\weapp\controller;
use shangtao\weapp\model\Brands as M;
/**
 * 品牌控制器
 */
class Brands extends Base{
    /**
     * 列表
     */
    public function pageQuery(){
    	$m = new M();
    	$rs['cat'] = WSTGoodsCats();
    	$id = (int)input('id');
    	if(isset($rs['cat'][0]['catId']) && $id==0){
    		$id = $rs['cat'][0]['catId'];
    	}
    	$rs['list'] = $m->pageQuery($id);
    	$rs['catId'] = $id;
    	foreach ($rs['list'] as $key =>$v){
    		$rs['list'][$key]['brandImg'] = WSTImg($v['brandImg'],3);
    	}   	
    	return jsonReturn('success',1,$rs);
    }
}
