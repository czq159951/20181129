<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\SpecCats as M;
/**
 * 规格类别控制器
 */
class Speccats extends Base{

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
        //获取该记录信息
        $this->assign('data', $this->get());
        return $this->fetch("edit");
    }
    /**
     * 获取数据
     */
    public function get(){
        $m = new M();
        return $m->getById(input("catId/d"));
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
    public function setToggle(){
        $m = new M();
        return $m->setToggle();
    }

    public function checkCatName(){
    	$m = new M();
    	$rs = $m->checkCatName();
   	 	if($rs["status"]==1){
			return array("ok"=>"");
		}else{
			return array("error"=>$rs["msg"]);
		}
    }
    
}
