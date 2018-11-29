<?php
namespace shangtao\home\controller;
use shangtao\common\model\Areas as M;
/**
 * 地区控制器
 */
class Areas extends Base{
	/**
	* 获取地区信息
	*/
    public function listQuery(){
        $m = new M();
        $rs = $m->listQuery();
        return WSTReturn('', 1,$rs);
    }
}
