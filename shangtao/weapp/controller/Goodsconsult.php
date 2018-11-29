<?php
namespace shangtao\weapp\controller;
use shangtao\common\model\GoodsConsult as M;
/**
 * 商品咨询控制器
 */
class GoodsConsult extends Base{
    /**
    * 获取商品咨询类别
    */
    public function getConsultType(){
        $arr = WSTDatas('COUSULT_TYPE');
        return jsonReturn('success',1,$arr);
    }
	/**
	* 根据商品id获取商品咨询
	*/
    public function listQuery(){
        $m = new M();
        $rs = $m->listQuery();
        return jsonReturn('',1,$rs);
    }
    /**
    * 新增
    */
    public function add(){
    	
    	$m = new M();
    	$rs = $m->add();
        return jsonReturn('',1,$rs);
    }
}
