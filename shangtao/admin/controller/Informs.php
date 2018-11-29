<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\Informs as M;
/**
 * 订单投诉控制器
 */
class Informs extends Base{
	
    public function index(){
    	return $this->fetch("list");
    }
    /**
    * 查看举报信息
    */
    public function view(){
        $m = model('informs');
        if((int)Input('cid')>0){
            $data = $m->getDetail();
            $this->assign('data',$data);
        }
        return $this->fetch('view');
    }
    /**
     * 跳去处理页面
     */
    public function toHandle(){
        $m = model('informs');
        if(Input('cid')>0){
            $data = $m->getDetail();
            $this->assign('data',$data);
        }
        return $this->fetch("handle");
    }
    
    /**
     * 获取分页
     */
    public function pageQuery(){
        $m = new M();
        return WSTGrid($m->pageQuery());
    } 
     /**
     * 举报记录
     */
    public function finalHandle(){
        return model('Informs')->finalHandle();
    }

  
}