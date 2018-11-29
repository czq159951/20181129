<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\GoodsAppraises as M;
/**
 * 商品评价控制器
 */
class Goodsappraises extends Base{
	
    public function index(){
        //获取地区
        $area1 = model('areas')->listQuery(0);
        return $this->fetch("list",['area1'=>$area1,]);
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
        $data = $m->getById(input("get.id/d",0));
        if($data['images']!='')
            $data['images'] = explode(',',$data['images']);
        $assign = ['data'=>$data];
        return $this->fetch("edit",$assign);
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

    
}
