<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\Staffs as M;
use shangtao\admin\model\Roles as R;
/**
 * 职员控制器
 */
class Staffs extends Base{
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
     * 获取
     */
    public function get(){
    	$m = new M();
    	return $m->get((int)Input("post.id"));
    }
    /**
     * 跳去新增界面
     */
    public function toAdd(){
    	$id = (int)Input("get.id",0);
    	$m = new M();
    	$this->assign("object",['staffId'=>0,'workStatus'=>1,'staffStatus'=>1]);
    	$m = new R();
    	$this->assign("roles",$m->listQuery());
    	return $this->fetch("add");
    }
    /**
     * 跳去编辑页面
     */
    public function toEdit(){
    	$id = (int)Input("get.id",0);
    	$m = new M();
    	$rs = $m->getById($id);
    	$this->assign("object",$rs);
    	$m = new R();
    	$this->assign("roles",$m->listQuery());
    	return $this->fetch("edit");
    }
    /**
     * 新增
     */
    public function add(){
    	$m = new M();
    	return $m->add();
    }
    /**
     * 编辑菜单
     */
    public function edit(){
    	$m = new M();
    	return $m->edit();
    }
    /**
     * 删除菜单
     */
    public function del(){
    	$m = new M();
    	return $m->del();
    }
    /**
     * 检测账号是否重复
     */
    public function checkLoginKey(){
    	$m = new M();
    	return $m->checkLoginKey(input('post.key'));
    }
    /**
     * 编辑自己密码
     */
    public function editMyPass(){
    	$m = new M();
    	return $m->editMyPass((int)session('WST_STAFF.staffId'));
    }
    /**
     * 编辑职员密码
     */
    public function editPass(){
    	$m = new M();
    	return $m->editPass((int)input('post.staffId'));
    }
}
