<?php
function integral_list($field='',$extra=[],$num=5){
	$gm = think\Db::name('integral_goods');
	$time = date('Y-m-d H:i:s');
	$where = [];
	$where[] = ['startTime','<=',$time];
	$where[] = ['ig.endTime','>',$time];
	$_field = array_merge(['g.goodsName','g.goodsImg','ig.id','ig.goodsPrice','ig.integralNum'],$extra);
	if($field!='')$_field=$field;
	$rs = $gm->alias('ig')->join('__GOODS__ g','ig.goodsId=g.goodsId','inner')
	          ->where('g.dataFlag=1 and g.isSale=1 and g.goodsStatus=1 and ig.dataFlag=1 and ig.integralStatus=1')
	          ->where($where)
	          ->field($_field)
	          ->order('ig.updateTime desc,ig.startTime asc,ig.id desc')
	          ->limit($num)
	          ->select();
	return $rs;
}