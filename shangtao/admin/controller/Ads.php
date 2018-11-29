<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\Ads as M;
use shangtao\admin\model\AdPositions as AdPositions;
/**
 * 广告控制器
 */
class Ads extends Base{
	
    public function index(){
    	return $this->fetch("list");
    }
    public function index2(){
    	$m = new AdPositions();
    	$data = $m->getById((int)input("id"));
    	$this->assign("data",$data);
    	return $this->fetch("list2");
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
        $data = $m->getById(Input("id/d",0));
        return $this->fetch("edit",['data'=>$data]);
    }
    /**
     * 跳去编辑页面
     */
    public function toEdit2(){
    	$m = new M();
    	$data = $m->getById(Input("id/d",0));
    	$m = new AdPositions();
    	$position = $m->getById((int)input("adPositionId"));
    	return $this->fetch("edit2",['data'=>$data,'position'=>$position]);
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
    * 修改广告排序
    */
    public function changeSort(){
        $m = new M();
        return $m->changeSort();
    }

    
}
