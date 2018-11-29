<?php
namespace shangtao\wechat\controller;
/**
 * 提现记录控制器
 */
class Cashdraws extends Base{
    // 前置方法执行列表
    protected $beforeActionList = [
        'checkAuth',
    ];
	/**
     * 查看用户提现记录
     */
	public function index(){
		return $this->fetch('users/cashdraws/list');
	}

	/**
     * 获取用户数据
     */
    public function pageQuery(){
        $userId = (int)session('WST_USER.userId');
        $data = model('CashDraws')->pageQuery(0,$userId);
        return WSTReturn("", 1,$data);
    }

    /**
     * 提现
     */ 
    public function drawMoney(){
        return model('CashDraws')->drawMoney();
    }
}
