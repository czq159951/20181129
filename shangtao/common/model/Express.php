<?php
namespace shangtao\common\model;
/**
 * 快递业务处理类
 */
use think\Db;
class Express extends Base{
	protected $pk = 'expressId';
    /**
	 * 获取快递列表
	 */
    public function listQuery(){
         return $this->where('dataFlag',1)->select();
    }
}
