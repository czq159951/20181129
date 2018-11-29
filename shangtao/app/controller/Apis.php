<?php
namespace shangtao\app\controller;
use shangtao\app\model\Apis as M;
/**
 * API控制器
 */
class Apis extends Base{
	
    public function index(){
        $m = new M();
        $rs = $m->listQuery();
        $this->assign('list',$rs);
        $this->assign('apiType',(input('apiType/d',0)==1));
    	return $this->fetch("list");
    } 
}
