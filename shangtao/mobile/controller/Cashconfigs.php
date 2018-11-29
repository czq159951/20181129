<?php
namespace shangtao\mobile\controller;
/**
 * 提现账号控制器
 */
class Cashconfigs extends Base{
    // 前置方法执行列表
    protected $beforeActionList = [
        'checkAuth',
    ];
	/**
     * 查看提现账号
     */
	public function index(){
        $this->assign('area',model('areas')->listQuery(0));
        $this->assign('banks',model('banks')->listQuery(0));
		return $this->fetch('users/cashconfigs/list');
    }

    /**
     * 获取用户数据
     */
    public function pageQuery(){
        $userId = (int)session('WST_USER.userId');
        $data = model('CashConfigs')->pageQuery(0,$userId);
        return WSTReturn("", 1,$data);
    }
    /**
    * 获取记录
    */
    public function getById(){
       $id = (int)input('id');
       return model('CashConfigs')->getById($id);
    }
    /**
     * 新增
     */
    public function add(){
        return model('CashConfigs')->add();
    }
    /**
     * 编辑
     */
    public function edit(){
        return model('CashConfigs')->edit();
    }
    /**
     * 删除
     */
    public function del(){
        return model('CashConfigs')->del();
    }
}
