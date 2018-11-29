<?php
/**
* 取出正在进行中的拍卖商品
*/
function auction_list($num=4){
	$au = new \addons\auction\model\Auctions;
	$rs = $au->where(['dataFlag'=>1,'isClose'=>0,'auctionStatus'=>1])
		           ->limit($num)->order('auctionNum desc,visitNum desc')
		           ->field('auctionId,goodsName,goodsImg,startTime,endTime')
		           ->cache(600)
		           ->select();
	return $rs;
}