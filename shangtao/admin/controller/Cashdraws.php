<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\CashDraws as M;
/**
 * 提现控制器
 */
class Cashdraws extends Base{

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
    public function toHandle(){
        //获取该记录信息
        $m = new M();
        $this->assign('object', $m->getById());
        return $this->fetch("edit");
    }
    
    /**
    * 修改
    */
    public function handle(){
        $drawsStatus = (int)input('cashSatus',-1);
        $m = new M();
        if($drawsStatus==1){
            return $m->handle();
        }else{
            return $m->handleFail();
        }
    }

    /**
     * 查看提现内容
     */
    public function toView(){
        $m = new M();
        $this->assign('object', $m->getById());
        return $this->fetch("view");
    }
    /**
     * 导出
     */
    public function toExport(){
        $m = new M();
        $rs = $m->toExport();
        $this->assign('rs',$rs);
    }
}
