<?php
namespace shangtao\mobile\controller;
use shangtao\common\model\Invoices as M;
/**
 * 发票信息控制器
 */
class Invoices extends Base{
    /**
     * 列表
     */
    public function pageQuery(){
    	$m = new M();
    	$rs = $m->pageQuery(5);// 移动版只显示5条发票信息
    	return $rs;
    }
    /**
     * 新增
     */
    public function add(){
    	$m = new M();
    	return $m->add();
    }
    /**
     * 修改
     */
    public function edit(){
    	$m = new M();
    	return $m->edit();
    }
}
