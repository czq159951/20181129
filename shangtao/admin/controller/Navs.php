<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\Navs as M;
/**
 * 导航控制器
 */
class Navs extends Base{

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
     * 跳去编辑页面
     */
    public function toEdit(){
        //获取省级信息
        $this->assign('area1',model('areas')->listQuery(0));
        $m = new M();
        $rs = $m->getById((int)Input("get.id"));
        $this->assign("data",$rs);
        return $this->fetch("edit");
    }
    /*
    * 获取数据
    */
    public function get(){
        $m = new M();
        return $m->getById((int)Input("id"));
    }
    /**
     * 新增
     */
    public function add(){
        $m = new M();
        return $m->add();
    }
    /**
    * 修改
    */
    public function edit(){
        $m = new M();
        return $m->edit();
    }
    /**
     * 删除
     */
    public function del(){
        $m = new M();
        return $m->del();
    }
    /**
    * 显示隐藏
    */
    public function editiIsShow(){
        $m = new M();
        return $m->editiIsShow();
    }

    
}
