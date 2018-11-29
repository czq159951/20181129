<?php
namespace shangtao\app\controller;
use shangtao\app\model\Brands as M;
/**
 * 品牌控制器
 */
class Brands extends Base{
    /**
     * 列表
     */
    public function pageQuery(){
    	$m = new M();
        $selectedId = (int)input('id');
    	$rs['brands'] = $m->pageQuery(100,$selectedId);
    	foreach ($rs['brands']['data'] as $key =>$v){
    		$rs['brands']['data'][$key]['brandImg'] = WSTImg($v['brandImg'],2);
    	}   	
    	// 域名
    	$rs['domain'] = $this->domain();
    	return json_encode(WSTReturn('success',1,$rs));
    }
}
