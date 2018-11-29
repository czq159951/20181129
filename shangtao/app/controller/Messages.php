<?php
namespace shangtao\app\controller;
use shangtao\app\model\Messages as M;
/**
 * 商城消息控制器
 */
class Messages extends Base{
	// 前置方法执行列表
    protected $beforeActionList = [
        'checkAuth'  =>  ['except'=>'index'],
    ];
	/**
	 * 获取列表
	 */
	public function pageQuery(){
		$m = new M();
		$data =  $m->pageQuery();
		echo(json_encode(WSTReturn('success',1,$data)));die;
	}
	/**
	* 查看消息
	*/
	public function index(){
		$m = new M();
		$data = $m->getById();
		$this->assign('data',$data);
		return $this->fetch('message');
	}
	/**
	 * 获取列表详情
	 */
	public function getById(){
		$m = new M();
		$data = $m->getById();
		echo(json_encode(WSTReturn('success',1,$data)));die;
	}
	/**
	 * 删除消息
	 */
	public function del(){
		$m = new M();
		$rs = $m->batchDel();
		return json_encode($rs);
	}
}
