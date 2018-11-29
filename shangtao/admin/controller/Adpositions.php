<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\AdPositions as M;
/**
 * 广告位置控制器
 */
class Adpositions extends Base{
	
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
        $m = new M();
        $assign = ['data'=>$m->getById(Input("get.id/d",0))];
        return $this->fetch("edit",$assign);
    }
    /*
    * 获取数据
    */
    public function get(){
        $m = new M();
        return $m->getById(Input("id/d",0));
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
    * 获取位置信息（用于广告）
    */
    public function getPositon(){
        $m = new M();
        return $m->getPositon((int)input('post.positionType/d'));
    }  
}
