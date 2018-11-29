<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\UserScores as M;
/**
 * 积分日志控制器
 */
class Userscores extends Base{
	
    public function toUserScores(){
        $m = new M();
        $object = $m->getUserInfo();
        $this->assign("object",$object);
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
     * 跳去新增界面
     */
    public function toAdd(){
        $m = new M();
        $object = $m->getUserInfo();
        $this->assign("object",$object);
        return $this->fetch("box");
    }

    /**
     * 新增
     */
    public function add(){
        $m = new M();
        return $m->addByAdmin();
    }
}
