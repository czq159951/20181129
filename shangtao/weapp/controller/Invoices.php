<?php
namespace shangtao\weapp\controller;
use shangtao\common\model\Invoices as M;
/**
 * 发票信息控制器
 */
class Invoices extends Base{
	// 前置方法执行列表
	protected $beforeActionList = [
			'checkAuth'
	];
     /**
     * 获取发票列表
     */
    public function pageQuery(){
        $m = new M();
        $userId = model('weapp/Users')->getUserId();
        $rs = $m->pageQuery(5,$userId);
        return jsonReturn('success',1,$rs);
    }
    /**
     * 新增发票
     */
    public function add(){
        $m = new M();
        $userId = model('weapp/Users')->getUserId();
        $rs = $m->add($userId);
        return jsonReturn('',1,$rs);
    }
    /**
     * 新增发票
     */
    public function edit(){
        $m = new M();
        $userId = model('weapp/Users')->getUserId();
        $rs = $m->edit($userId);
        return jsonReturn('',1,$rs);
    }
}
