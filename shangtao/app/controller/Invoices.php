<?php
namespace shangtao\app\controller;
use shangtao\common\model\Invoices as M;
/**
 * 发票信息控制器
 */
class Invoices extends Base{
     /**
     * 获取发票列表
     */
    public function pageQuery(){
        $m = new M();
        $userId = model('app/Users')->getUserId();
        $rs = $m->pageQuery(5,$userId);
        return json_encode(WSTReturn('success',1,$rs));
    }
    /**
     * 新增发票
     */
    public function add(){
        $m = new M();
        $userId = model('app/Users')->getUserId();
        $rs = $m->add($userId);
        return json_encode($rs);
    }
    /**
     * 新增发票
     */
    public function edit(){
        $m = new M();
        $userId = model('app/Users')->getUserId();
        $rs = $m->edit($userId);
        return json_encode($rs);
    }
}
