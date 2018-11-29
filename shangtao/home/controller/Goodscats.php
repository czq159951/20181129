<?php
namespace shangtao\home\controller;
use shangtao\common\model\GoodsCats as M;
/**
 * 商品分类控制器
 */
class Goodscats extends Base{
    /**
     * 获取列表
     */
    public function listQuery(){
    	$m = new M();
    	$rs = $m->listQuery(input('parentId/d',0));
    	return WSTReturn("", 1,$rs);
    }
 
}