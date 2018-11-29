<?php
namespace addons\auction\model;
use think\addons\BaseModel as Base;
use shangtao\common\model\GoodsCats;
use think\Db;
/**
 * 拍卖活动插件
 */
class Apis extends Base{
	/**
	* 刷新竞拍信息
	*/
	public function getAuctionInfo(){
		return Db::name('Auctions')->field('currPrice,fareInc,auctionNum,visitNum')->find((int)input('id'));
	}
}
