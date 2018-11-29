<?php
namespace shangtao\weapp\controller;
use shangtao\weapp\model\Messages as M;
/**
 * 商城消息控制器
 */
class Messages extends Base{
	// 前置方法执行列表
    protected $beforeActionList = [
        'checkAuth'
    ];
	/**
	 * 获取列表
	 */
	public function pageQuery(){
		$m = new M();
		$data =  $m->pageQuery();
		return jsonReturn('success',1,$data);
	}
	/**
	 * 获取列表详情
	 */
	public function getById(){
		$m = new M();
		$data = $m->getById();
		return jsonReturn('success',1,$data);
	}
	/**
	 * 删除消息
	 */
	public function del(){
		$m = new M();
		$rs = $m->batchDel();
		return $rs;
	}
}
