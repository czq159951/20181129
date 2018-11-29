<?php
namespace addons\cron\controller;

use think\addons\Controller;
use addons\cron\model\Crons as M;
/**
 * 计划任务控制器
 */
class Cron extends Controller{
    
    public function index(){
    	return $this->fetch("admin/list");
    }
    /**
     * 获取分页
     */
    public function pageQuery(){
        $m = new M();
        return WSTGrid($m->pageQuery());
    }
    public function toEdit(){
        $m = new M();
        $rs = $m->getById(Input("id/d",0));
        $this->assign("data",$rs);
        return $this->fetch("admin/edit");
    }
    /**
     * 修改
     */
    public function edit(){
        $m = new M();
        return $m->edit();
    }

    /**
     * 执行计划任务
     */
    public function runCron(){
        $m = new M();
        return $m->runCron();
    }
    public function runCrons(){
        $m = new M();
        return $m->runCrons();
    }

    /**
     * 停用计划任务 
     */
    public function changeEnableStatus(){
        $m = new M();
        return $m->changeEnableStatus();
    }
    
}
