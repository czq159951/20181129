<?php
use think\Db;
/**
* 取出分销商品
* @_field 需要取的字段。
* @extra 需要额外取的字段
* @num
*/
function distribut_list($field='',$extra=[],$num=4){
	$where = [];
	$where['goodsStatus'] = 1;
	$where['dataFlag'] = 1;
	$where['isSale'] = 1;
	$where['isDistribut'] = 1;

	$_field = array_merge(['goodsName','goodsImg','goodsId','shopPrice'],$extra);
	if($field!='')$_field=$field;
	$rs = Db::name("goods")->alias('g')
			->where($where)
			->field($_field)
			->order("goodsId asc")
			->limit($num)
	        ->select();
	return $rs;
}
