<?php
namespace shangtao\app\controller;
use shangtao\common\model\Areas as M;
/**
 * 地区控制器
 */
class Areas extends Base{
	/**
	 * 列表查询
	 */
    public function listQuery(){
        $m = new M();
        $rs = $m->listQuery();
        return Json_encode(WSTReturn('请求成功', 1,$rs));
    }
}
