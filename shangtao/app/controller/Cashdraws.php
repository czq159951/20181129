<?php
namespace shangtao\app\controller;
use shangtao\app\model\CashDraws as M;
/**
 * 提现记录控制器
 */
class Cashdraws extends Base{
    // 前置方法执行列表
    protected $beforeActionList = [
        'checkAuth',
    ];
	/**
     * 获取用户数据
     */
    public function pageQuery(){
        $userId = model('app/users')->getUserId();
        $m = new M();
        $data = $m->pageQuery(0,$userId);
        echo(json_encode(WSTReturn('success',1,$data)));die;
    }

    /**
     * 提现
     */ 
    public function drawMoney(){
    	$m = new M();
    	$rs = $m->drawMoney();
        return json_encode($rs);
    }
}
