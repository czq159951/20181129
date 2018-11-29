<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\TemplateMsgs as M;
/**
 * 消息模板控制器
 */
class Templatemsgs extends Base{
	
    public function index(){
        $this->assign('src',(int)input('src'));
    	return $this->fetch("list");
    }
    /**
     * 获取分页
     */
    public function pageMsgQuery(){
        $m = new M();
        return WSTGrid($m->pageQuery(0,'TEMPLATE_SYS'));
    }
     /**
     * 设置是否显示/隐藏
     */
    public function editiIsShow(){
    	$m = new M();
    	$rs = $m->editiIsShow();
    	return $rs;
    }
    /**
     * 跳转去新增页面
     */ 
    public function toEditMsg(){
        $id = (int)input('id');
        $m = new M();
        if($id>0){
            $data = $m->getById($id);
        }else{
            $data = $m->getEModel('template_msgs');
        }
        $this->assign('object',$data);
        return $this->fetch("edit_msg");
    }
    /**
     * 跳转去新增页面
     */ 
    public function toEditEmail(){
        $id = (int)input('id');
        $m = new M();
        if($id>0){
            $data = $m->getById($id);
        }else{
            $data = $m->getEModel('template_email');
        }
        $this->assign('object',$data);
        return $this->fetch("edit_email");
    }
    /**
     * 跳转去新增页面
     */ 
    public function toEditSMS(){
        $id = (int)input('id');
        $m = new M();
        if($id>0){
            $data = $m->getById($id);
        }else{
            $data = $m->getEModel('template_sms');
        }
        $this->assign('object',$data);
        return $this->fetch("edit_sms");
    }

    /**
    * 发送消息
    */
    public function edit(){
        $m = new M();
        return $m->edit();
    }

    /**
     * 获取分页
     */
    public function pageEmailQuery(){
        $m = new M();
        return WSTGrid($m->pageEmailQuery());
    }
    /**
     * 获取分页
     */
    public function pageSMSQuery(){
        $m = new M();
        return WSTGrid($m->pageQuery(2,'TEMPLATE_SMS'));
    }

}
