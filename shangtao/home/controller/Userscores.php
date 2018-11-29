<?php
namespace shangtao\home\controller;
use shangtao\common\model\UserScores as MUserscores;
/**
 * 积分控制器
 */
class Userscores extends Base{
    protected $beforeActionList = ['checkAuth'];
    /**
    * 查看商城消息
    */
	public function index(){
		$rs = model('Users')->getFieldsById((int)session('WST_USER.userId'),['userScore','userTotalScore']);
		$this->assign('object',$rs);
		return $this->fetch('users/userscores/list');
	}
    /**
    * 获取数据
    */
    public function pageQuery(){
        $userId = (int)session('WST_USER.userId');
        $data = model('UserScores')->pageQuery($userId);
        return WSTReturn("", 1,$data);
    }
    /**
     * 签到积分
     */
    public function signScore(){
    	$m = new MUserscores();
    	$userId = (int)session('WST_USER.userId');
    	$rs = $m->signScore($userId);
    	return $rs;
    }
}
