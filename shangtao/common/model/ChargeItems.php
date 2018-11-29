<?php
namespace shangtao\common\model;
use think\Db;
/**
 * 充值项业务处理
 */
class ChargeItems extends Base{
	/**
	 * 分页
	 */
	public function queryList(){
		$where = [];
		$where['dataFlag'] = 1;
		return $this->where($where)->field(true)->order('itemSort asc,id asc')->select();
	}
	
	public function getItemMoney($itmeId){
		$where = [];
		$where['dataFlag'] = 1;
		$where['id'] = $itmeId;
		return $this->where($where)->field("chargeMoney,giveMoney")->find();
	}
}
