<?php
namespace shangtao\mobile\controller;
use shangtao\common\model\Brands as M;
/**
 * 品牌控制器
 */
class Brands extends Base{
	/**
     * 主页
     */
    public function index(){
    	return $this->fetch('brands');
    }  
    /**
     * 列表
     */
    public function pageQuery(){
    	$m = new M();
    	$rs = $m->pageQuery(input('pagesize/d'));
    	foreach ($rs['data'] as $key =>$v){
    		$rs['data'][$key]['brandImg'] = WSTImg($v['brandImg'],3);
    	}
    	return $rs;
    }
}
