<?php
namespace shangtao\home\controller;
use shangtao\common\model\CashConfigs as M;
/**
 * 提现账号控制器
 */
class Cashconfigs extends Base{
    protected $beforeActionList = ['checkAuth'];
    /**
     * 获取用户数据
     */
    public function pageQuery(){
        $userId = (int)session('WST_USER.userId');
        $data = model('CashConfigs')->pageQuery(0,$userId);
        return WSTReturn("", 1,$data);
    }

    /**
     * 跳转新增/编辑页面
     */
    public function toEdit(){
        $id = (int)input('id');
        $object = [];
        $m = new M();
        if($id>0){
            $object = $m->getById($id);
        }else{
            $object = $m->getEModel('cash_configs');
            $object['accAreaIdPath'] = '';
        }
        $this->assign('object',$object);
        $this->assign('areas',model('areas')->listQuery(0));
        $this->assign('banks',model('banks')->listQuery(0));
        return $this->fetch('users/cashdraws/box_config');
    }
    /**
     * 新增
     */
    public function add(){
        $m = new M();
        return $m->add();
    }
    /**
     * 编辑
     */
    public function edit(){
        $m = new M();
        return $m->edit();
    }
    /**
     * 删除
     */
    public function del(){
        $m = new M();
        return $m->del();
    }
}
