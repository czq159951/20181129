<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\Wxusers as M;
/**
 * 微信用户控制器
 */
class Wxusers extends Base{
    public function recodeUnionId(){
        $m = new M();
        return json_encode($m->recodeUnionId());
    }

    public function index(){
    	return $this->fetch("list");
    }
    
    /**
     * 获取分页
     */
    public function pageQuery(){
    	$m = new M();
        
    	return WSTGrid($m->pageQuery());
    }
    
    /**
     * 与微信用户管理同步
     */
    public function synchroWx(){
    	$m = new M();
    	return $m->synchroWx();
    }
    public function wxLoad(){
    	$m = new M();
    	return $m->wxLoad();
    }
    
    /**
     * 获取指定对象
     */
    public function getById(){
    	$m = new M();
    	return $m->getById((int)input('id'));
    }
    
    /**
     * 编辑
     */
    public function edit(){
    	$m = new M();
    	return $m->edit();
    } 
}
