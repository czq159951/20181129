<?php
namespace shangtao\common\model;
/**
 * 广告类
 */
class Ads extends Base{
	protected $pk = 'adId';
	public function recordClick(){
		$id = (int)input('id');
		return $this->where("adId=$id")->setInc('adClickNum');
	}
}
