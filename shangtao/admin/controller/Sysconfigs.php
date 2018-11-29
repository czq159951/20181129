<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\SysConfigs as M;
/**
 * 商城配置控制器
 */
class Sysconfigs extends Base{
	
    public function index(){
    	$m = new M();
    	$object = $m->getSysConfigs();
    	$this->assign("object",$object);
        $list = model('admin/staffs')->listQuery();
        $this->assign("list",$list);
    	return $this->fetch("edit");
    }
    
    /**
     * 保存
     */
    public function edit(){
    	$m = new M();
    	return $m->edit();
    }
}
