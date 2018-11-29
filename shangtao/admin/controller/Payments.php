<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\Payments as M;
/**
 * 支付控制器
 */
class Payments extends Base{

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
        $rs = $m->getById((int)Input("get.id"));
        $payConfig = json_decode($rs['payConfig']);
        //判断是否为空
        if(!empty($payConfig)){
            foreach($payConfig as $k=>$v)
                $rs[$k]=$v;
        }
        $this->assign("object",$rs);
        return $this->fetch("pay_".input('get.payCode'));
    }
    /*
    * 获取数据
    */
    public function get(){
        $m = new M();
        return $m->getById((int)Input("id"));
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
