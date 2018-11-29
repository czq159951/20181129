<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\SysConfigs as M;
/**
 * 小程序配置控制器
 */
class Weappconfigs extends Base{
	
    public function index(){
    	$m = new M();
    	$object = $m->getSysConfigs();
    	$this->assign("object",$object);
    	return $this->fetch("edit");
    }
    
    /**
     * 保存
     */
    public function edit(){
    	$m = new M();
    	return $m->edit(2);
    }
}
