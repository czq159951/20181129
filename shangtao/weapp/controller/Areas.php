<?php
namespace shangtao\weapp\controller;
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
        return jsonReturn('success', 1,$rs);
    }
}
