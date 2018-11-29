<?php
namespace shangtao\app\model;
use think\Db;
/**
 * API业务处理
 */
class Apis extends Base{
	/**
	 * 分页
	 */
	public function listQuery(){
		$where = [['dataFlag','=',1]];
		$where[] = ['apiType','=',(input('apiType/d',0)==1)?1:0];
		return $this->where($where)->order('apiSort','asc')->select();
	}
}
