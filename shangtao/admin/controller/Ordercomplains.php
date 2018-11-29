<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\OrderComplains as M;
/**
 * 订单投诉控制器
 */
class OrderComplains extends Base{
	
    public function index(){
        $areaList = model('areas')->listQuery(0); 
        $this->assign("areaList",$areaList);
    	return $this->fetch("list");
    }
    /**
    * 查看投诉信息
    */
    public function view(){
        $m = model('OrderComplains');
        if((int)Input('cid')>0){
            $data = $m->getDetail();
            $this->assign('order',$data);
            $rs = model('orders')->getByView($data['orderId']);
            $this->assign('object',$rs);
            $this->assign("from",input("from/d",0));
        }
        return $this->fetch('view');
    }
    /**
     * 跳去处理页面
     */
    public function toHandle(){
        $m = model('OrderComplains');
        if(Input('cid')>0){
            $data = $m->getDetail();
            $this->assign('order',$data);
            $rs = model('orders')->getByView($data['orderId']);
            $this->assign('object',$rs);
        }
        return $this->fetch("handle");
    }
    /**
     * 转交给应诉人回应
     */
    public function deliverRespond(){
        return model('OrderComplains')->deliverRespond();
    }
    /**
     * 获取分页
     */
    public function pageQuery(){
        $m = new M();
        return WSTGrid($m->pageQuery());
    }
    /**
     * 仲裁投诉记录
     */
    public function finalHandle(){
        return model('OrderComplains')->finalHandle();
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

    
}
