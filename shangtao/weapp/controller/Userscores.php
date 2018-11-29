<?php
namespace shangtao\weapp\controller;
use shangtao\weapp\model\UserScores as M;
use shangtao\common\model\UserScores as CM;
/**
 * 积分控制器
 */
class Userscores extends Base{
    // 前置方法执行列表
   	protected $beforeActionList = ['checkAuth'];
	/**
    * 查看
    */
	public function index(){
		$userId = model('weapp/users')->getUserId();
		$data = model('Users')->getFieldsById($userId,['userScore','userTotalScore']);
		return jsonReturn('success',1,$data);
	}
    /**
    * 获取数据
    */
    public function pageQuery(){
        $userId = model('weapp/users')->getUserId();
        $m = new M();
        $data['list'] = $m->pageQuery($userId);
        return jsonReturn('success',1,$data);
    }
    /**
    * 用户签到
    */
    public function sign(){
        $m = new CM();
        $userId = model('weapp/users')->getUserId();
        $rs = $m->signScore($userId);
        return jsonReturn('',1,$rs);
    }
}
