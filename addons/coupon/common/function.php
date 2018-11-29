<?php

use think\Db;
function coupon_list($field='',$extra=[],$num=5){
    $userId = (int)session('WST_USER.userId');
    $where = [['c.dataFlag','=',1],['endDate','>=',date('Y-m-d')]];
    $_field = array_merge(['c.*'],$extra);
	if($field!='')$_field=$field;
	$rs =  Db::name('coupons')->alias('c')
	              ->join('__SHOPS__ s','c.shopId=s.shopId and s.dataFlag=1 and s.shopStatus=1')
	              ->where($where)
	              ->field($_field)
                  ->order('c.endDate','desc')
                  ->limit($num)
              	  ->select();
    $userCoupons = [];
    if($userId>0){
        $userCoupons = Db::name('coupon_users')->where(['userId'=>$userId])->column('couponId');
    }
    $time = time();
    foreach ($rs as $key => $v) {
    	$rs[$key]['isOut'] = (($v['couponNum']<=$v['receiveNum']) || ($time>WSTStrToTime($v['endDate']." 23:59:59")))?true:false;
        $rs[$key]['isReceive'] = ($userId>0)?in_array($v['couponId'],$userCoupons):false;
    }
    return $rs;
}