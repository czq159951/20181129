<?php
namespace shangtao\common\model;
/**
 * 支付参数日志类
 */
class LogPayParams extends Base{
	
	/**
	 * 添加支付日志
	 */
	public function addPayLog($obj){
		$this->delPayLog(["transId"=>$obj["transId"]]);
		$obj['createTime'] = date('Y-m-d H:i:s');
		$this->insert($obj);
	}
	
	/**
	 * 获取支付日志
	 */
	public function getPayLog($obj){
		$rs = $this->where($obj)->find();
		if(!empty($rs)){
			return json_decode($rs["paramsVa"],true);
		}
		return $rs;
	}
	
	/**
	 * 删除支付日志
	 */
	public function delPayLog($obj){
		return $this->where($obj)->delete();
	}
	
}
