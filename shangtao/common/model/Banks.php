<?php
namespace shangtao\common\model;
/**
 * 银行业务处理
 */
class Banks extends Base{
	protected $pk = 'bankId';
	/**
	 * 列表
	 */
	public function listQuery(){
		$data = cache('WST_BANKS');
		if(!$data){
			$data = $this->where('dataFlag',1)->field('bankId,bankName')->select();
			cache('WST_BANKS',$data,31536000);
		}
		return $data;
	}
}
