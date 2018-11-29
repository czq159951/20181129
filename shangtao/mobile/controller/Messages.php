<?php
namespace shangtao\mobile\controller;
use shangtao\common\model\Messages as M;
/**
 * 商城消息控制器
 */
class Messages extends Base{
	// 前置方法执行列表
    protected $beforeActionList = [
        'checkAuth'
    ];
    /**
    * 查看商城消息
    */
	public function index(){
		return $this->fetch('users/messages/list');
	}
	/**
	 * 获取列表
	 */
	public function pageQuery(){
		$m = new M();
		return $m->pageQuery();
	}
	/**
	 * 获取列表详情
	 */
	public function getById(){
		$m = new M();
		return $m->getById();
	}
	/**
	 * 删除地址
	 */
	public function del(){
		$m = new M();
		return $m->batchDel();
	}
}
