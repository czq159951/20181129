<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\LogMoneys as M;
/**
 * 资金流水日志控制器
 */
class Logmoneys extends Base{
	
    public function index(){
    	return $this->fetch("list");
    }
    
    /**
     * 获取用户分页
     */
    public function pageQueryByUser(){
    	$m = new M();
    	return WSTGrid($m->pageQueryByUser());
    }
    /**
     * 获取商分页
     */
    public function pageQueryByShop(){
        $m = new M();
        return WSTGrid($m->pageQueryByShop());
    }
    /**
     * 获取指定记录
     */
    public function tologmoneys(){
        $m = new M();
        $object = $m->getUserInfoByType();
        $this->assign("object",$object);
        return $this->fetch("list_log");
    }
    public function pageQuery(){
        $m = new M();
        return WSTGrid($m->pageQuery());
    }
}
