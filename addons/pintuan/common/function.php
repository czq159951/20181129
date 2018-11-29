<?php
/**
* 取出拼团商品
* @_field 需要取的字段。
* @extra 需要额外取的字段
* @num
*/
function pintuan_list($field='',$extra=[],$num=5){
	$gm = new \addons\groupon\model\Groupons;
	$_field = array_merge(['g.goodsName','g.goodsImg','g.marketPrice','gu.grouponId','gu.grouponPrice'],$extra);
	if($field!='')$_field=$field;
	$rs = $gm->alias('gu')->join('__GOODS__ g','gu.goodsId=g.goodsId','inner')
	          ->where('g.dataFlag=1 and g.isSale=1 and g.goodsStatus=1 and gu.dataFlag=1 and gu.grouponStatus=1')
	          ->field($_field)
	          ->order('gu.updateTime desc,gu.startTime asc,grouponId desc')
	          ->limit($num)
	          ->select();
	return $rs;
}