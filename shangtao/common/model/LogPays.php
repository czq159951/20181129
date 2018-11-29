<?php
namespace shangtao\common\model;
use think;
/**
 * 支付日志类
 */
class LogPays extends Base{
	
	/**
	 * 添加支付日志
	 */
	public function addPayLog($obj){
		$obj['createTime'] = date('Y-m-d H:i:s');
		$this->insert($obj);
	}
	
	/**
	 * 获取支付日志
	 */
	public function getPayLog($obj){
		return $this->where($obj)->find();
	}
	
	/**
	 * 删除支付日志
	 */
	public function delPayLog($obj){
		return $this->where($obj)->delete();
	}
	
}
