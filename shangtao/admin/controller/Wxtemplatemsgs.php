<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\TemplateMsgs as M;
/**
 * 微信消息模板控制器
 */
class Wxtemplatemsgs extends Base{
	
    public function index(){
    	return $this->fetch("list");
    }
    /**
     * 获取分页
     */
    public function pageQuery(){
        $m = new M();
        return WSTGrid($m->pageQuery(3,'TEMPLATE_WX'));
    }
    /**
     * 跳转去新增页面
     */ 
    public function toEdit(){
        $id = (int)input('id');
        $m = new M();
        if($id>0){
            $data = $m->getById($id);
        }else{
            $data = $m->getEModel('template_msgs');
        }
        $this->assign('object',$data);
        return $this->fetch("edit");
    }

    /**
    * 发送消息
    */
    public function edit(){
        return model('WxTemplateParams')->edit();
    }

    /**
     * 获取参数列表
     */
    public function listQuery(){
        return model('WxTemplateParams')->listQuery((int)input('post.parentId'));
    }

}
