<?php
namespace addons\bargain\controller;

use think\addons\Controller;
use addons\bargain\model\Admin as M;
/**
 * 全民砍价插件
 */
class Admin extends Controller{

    /**
     * 查看砍价商品列表
     */
    public function index(){
        $this->checkAdminPrivileges();
        $this->assign("areaList",model('common/areas')->listQuery(0));
        return $this->fetch("/admin/list");
    }

    /**
     * 查询砍价商品
     */
    public function pageQuery(){
        $this->checkAdminPrivileges();
        $m = new M();
        return WSTGrid($m->pageQuery(1));
    }
    /**
     * 查询待审核砍价商品
     */
    public function pageAuditQuery(){
        $this->checkAdminPrivileges();
        $m = new M();
        return WSTGrid($m->pageQuery(0));
    }

    /**
    * 设置违规商品
    */
    public function illegal(){
        $this->checkAdminPrivileges();
        $m = new M();
        return $m->illegal();
    }
    /**
     * 通过商品审核
     */
    public function allow(){
        $this->checkAdminPrivileges();
        $m = new M();
        return $m->allow();
    }

    /**
     * 删除
     */
    public function del(){
        $this->checkAdminPrivileges();
        $m = new M();
        return $m->del();
    }

    /**
     * 查看参与人
     */
    public function joins(){
        $this->checkAdminPrivileges();
        $this->assign("bargainId",(int)input('bargainId'));
        return $this->fetch("/admin/list_users");
    }
    public function pageyByJoins(){
        $this->checkAdminPrivileges();
        $m = new M();
        return WSTGrid($m->pageyByJoins());
    }
    /**
     * 查看亲友团
     */
    public function showHelps(){
        $this->checkAdminPrivileges();
        $this->assign("bargainId",input('bargainId/d'));
        $this->assign("bargainJoinId",input('bargainJoinId/d'));
        return $this->fetch("/admin/list_helps");
    }
    public function pageByHelps(){
        $this->checkAdminPrivileges();
        $m = new M();
        return WSTGrid($m->pageByHelps());
    }
}