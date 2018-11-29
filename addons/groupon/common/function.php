<?php
/**
* 取出正在进行中的团购商品
* @_field 需要取的字段。
* @extra 需要额外取的字段
* @num
*/
function groupon_list($field='',$extra=[],$num=5){
	$gm = new \addons\groupon\model\Groupons;
	$time = date('Y-m-d H:i:s');
	$where = [];
	$where[] = ['startTime','<=',$time];
	$where[] = ['gu.endTime','>',$time];
	$_field = array_merge(['g.goodsName','g.goodsImg','g.marketPrice','gu.grouponId','gu.grouponPrice'],$extra);
	if($field!='')$_field=$field;
	$rs = $gm->alias('gu')->join('__GOODS__ g','gu.goodsId=g.goodsId','inner')
	          ->where('g.dataFlag=1 and g.isSale=1 and g.goodsStatus=1 and gu.dataFlag=1 and gu.grouponStatus=1')
	          ->where($where)
	          ->field($_field)
	          ->order('gu.updateTime desc,gu.startTime asc,grouponId desc')
	          ->limit($num)
	          ->select();
	return $rs;
}