<?php
namespace shangtao\home\controller;
use shangtao\common\model\Invoices as M;
/**
 * 发票信息控制器
 */
class Invoices extends Base{
	/**
	* 
	*/
	public function index(){
		$m = new M();
		$data = $m->pageQuery();
		$this->assign('invoiceId',(int)input('invoiceId'));
		$this->assign('isInvoice',(int)input('isInvoice'));
		$this->assign('data',$data);
		return $this->fetch('invoices');
	}
	/**
	* 查询
	*/
    public function pageQuery(){
        $m = new M();
        return $m->pageQuery();
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
