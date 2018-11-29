<?php 
namespace shangtao\admin\model;
use think\Db;
/**
 * 报表业务处理
 */
class Reports extends Base{   
    /**
     * 获取商品销售统计
     */
    public function topSaleGoodsByPage(){
    	$start = date('Y-m-d 00:00:00',strtotime(input('startDate')));
    	$end = date('Y-m-d 23:59:59',strtotime(input('endDate')));
        $prefix = config('database.prefix');
    	return Db::table($prefix.'order_goods')
          ->alias([$prefix.'order_goods'=>'og',$prefix.'orders'=>'o',$prefix.'goods'=>'g',$prefix.'shops'=>'s'])
          ->field('og.goodsId,g.goodsName,goodsSn,s.shopId,shopName,sum(og.goodsNum) goodsNum,og.goodsImg')
    	  ->join($prefix.'orders','og.orderId=o.orderId')
    	  ->join($prefix.'goods','og.goodsId=g.goodsId')
    	  ->join($prefix.'shops','g.shopId=s.shopId')
    	  ->order('goodsNum','desc')
    	  ->whereTime('o.createTime','between',[$start,$end])
          ->where('(o.payType=0 or (o.payType=1 and o.isPay=1)) and o.dataFlag=1')->group('og.goodsId,g.goodsName,goodsSn,s.shopId,shopName,og.goodsImg')
          ->paginate(input('limit/d'));
    }
    /**
     * 获取店铺销售统计
     */
    public function topShopSalesByPage(){
        $start = date('Y-m-d 00:00:00',strtotime(input('startDate')));
        $end = date('Y-m-d 23:59:59',strtotime(input('endDate')));
        $prefix = config('database.prefix');
        $rs = Db::table($prefix.'shops')
                 ->alias([$prefix.'shops'=>'s',$prefix.'orders' => 'o'])
                 ->field('s.shopId,s.shopImg,s.shopName,sum(o.totalMoney) totalMoney,count(o.orderId) orderNum')
                 ->join($prefix.'orders o','s.shopId=o.shopId')
                 ->order('totalMoney desc,orderNum desc')
                 ->whereTime('o.createTime','between',[$start,$end])
                 ->where('(payType=0 or (payType=1 and isPay=1)) and o.dataFlag=1 and orderStatus=2')
                 ->group('o.shopId')
                 ->paginate(input('limit/d'))->toArray();
        foreach($rs['data'] as $k=>$v){
            $onLineArr = Db::name('orders')
                 ->whereTime('createTime','between',[$start,$end])
                 ->field('sum(totalMoney) totalMoney,sum(realTotalMoney) realTotalMoney')
                 ->where('payType=1 and isPay=1 and dataFlag=1 and orderStatus=2')
                 ->where(['shopId'=>$v['shopId']])
                 ->find();
            $rs['data'][$k]['onLinePayMoney'] = (float)$onLineArr['totalMoney'];// 在线支付总金额
            $rs['data'][$k]['onLinePayTrueMoney'] = (float)$onLineArr['realTotalMoney'];// 在线支付实际金额
            $rs['data'][$k]['offLinePayMoney'] = (float)
            Db::name('orders')
                 ->whereTime('createTime','between',[$start,$end])
                 ->where('payType=0 and dataFlag=1 and orderStatus=2')
                 ->where(['shopId'=>$v['shopId']])
                 ->value('sum(totalMoney)');;// 货到付款金额
        }
        return $rs;

    }

    /**
     * 获取销售额
     */
    public function statSales(){
        $start = date('Y-m-d 00:00:00',strtotime(input('startDate')));
        $end = date('Y-m-d 23:59:59',strtotime(input('endDate')));
        $payType = (int)input('payType',-1);
        $rs = Db::field('left(createTime,10) createTime,orderSrc,sum(totalMoney) totalMoney')->name('orders')->whereTime('createTime','between',[$start,$end])
                ->where('((payType=0 or (payType=1 and isPay=1)) and dataFlag=1) '.(in_array($payType,[0,1])?" and payType=".$payType:''))
                ->order('createTime asc')
                ->group('left(createTime,10),orderSrc')->select();
        $rdata = [];
        if(count($rs)>0){
            $days = [];
            $payTypes = [0,1,2,3,4];
            $tmp = [];
            foreach($rs as $key => $v){
                if(!in_array($v['createTime'],$days))$days[] = $v['createTime'];
                $tmp[$v['orderSrc']."_".$v['createTime']] = $v['totalMoney'];
            }
            $rdata['map'] = ['p0'=>0,'p1'=>0,'p2'=>0,'p3'=>0,'p4'=>0];
            foreach($days as $v){
                $total = 0;
                foreach($payTypes as $p){
                    $pv = isset($tmp[$p."_".$v])?$tmp[$p."_".$v]:0;
                    $rdata['p'.$p][] = (float)$pv;
                    $total = $total + (float)$pv;
                    $rdata['map']['p'.$p] = $rdata['map']['p'.$p] + (float)$pv;
                }
                $rdata['total'][] = $total;
            }
            $rdata['days'] = $days;
        }
        return WSTReturn('',1,$rdata);
    }

    /**
     * 获取订单统计
     */
    public function statOrders(){
        $start = date('Y-m-d 00:00:00',strtotime(input('startDate')));
        $end = date('Y-m-d 23:59:59',strtotime(input('endDate')));
        $payType = (int)input('payType',-1);
        $rs = Db::field('left(createTime,10) createTime,orderSrc,count(orderId) orderNum')->name('orders')->whereTime('createTime','between',[$start,$end])
                ->where('((payType=0 or (payType=1 and isPay=1)) and dataFlag=1) '.(in_array($payType,[0,1])?" and payType=".$payType:''))
                ->order('createTime asc')
                ->group('left(createTime,10),orderSrc')->select();
        $rdata = [];
        if(count($rs)>0){
            $days = [];
            $payTypes = [0,1,2,3,4];
            $tmp = [];
            foreach($rs as $key => $v){
                if(!in_array($v['createTime'],$days))$days[] = $v['createTime'];
                $tmp[$v['orderSrc']."_".$v['createTime']] = $v['orderNum'];
            }
            $rdata['map'] = ['p0'=>0,'p1'=>0,'p2'=>0,'p3'=>0,'p4'=>0];
            foreach($days as $v){
                $total = 0;
                foreach($payTypes as $p){
                    $pv = isset($tmp[$p."_".$v])?$tmp[$p."_".$v]:0;
                    $rdata['p'.$p][] = (float)$pv;
                    $total = $total + (float)$pv;
                    $rdata['map']['p'.$p] = $rdata['map']['p'.$p] + (float)$pv;
                }
                $rdata['total'][] = $total;
            }
            $rdata['days'] = $days;
        }
        return WSTReturn('',1,$rdata);
    }
    /*首页获取订单数量*/
    public function getOrders(){
    	$data = cache('orderData');
        if(empty($data)){
        	$start = date('Y-m-d 00:00:00',strtotime(input('startDate')));
            $end = date('Y-m-d 23:59:59',strtotime(input('endDate')));
            $payType = -1;
            $rs = Db::field('left(createTime,10) createTime,orderSrc,count(orderId) orderNum')->name('orders')->whereTime('createTime','between',[$start,$end])
                    ->where('((payType=0 or (payType=1 and isPay=1)) and dataFlag=1) '.(in_array($payType,[0,1])?" and payType=".$payType:''))
                    ->order('createTime asc')
                    ->group('left(createTime,10),orderSrc')->select();
            $rdata = [];
         if(count($rs)>0){
            $days = [];
            $tmp = [];
			$payTypes = [0,1,2,3,4];
            foreach($rs as $key => $v){
                if(!in_array($v['createTime'],$days))$days[] = $v['createTime'];
                $tmp[$v['orderSrc']."_".$v['createTime']] = $v['orderNum'];
            }
            foreach($days as $v){
		 		 $total = 0;
				foreach($payTypes as $p){
                    $pv = isset($tmp[$p."_".$v])?$tmp[$p."_".$v]:0;
                    $total = $total + (float)$pv;
                }
                $rdata['total'][] = $total;
            }
            $rdata['days'] = $days;
			cache('orderData',$rdata,7200);
          }
        }else{
        	$rdata = cache('orderData');
        }
       return WSTReturn('',1,$rdata);
       }
    /**
     * 获取新增用户
     */
    public function statNewUser(){
        $start = date('Y-m-d 00:00:00',strtotime(input('startDate')));
        $end = date('Y-m-d 23:59:59',strtotime(input('endDate')));
        $urs = Db::field('left(createTime,10) createTime,count(userId) userNum')
                ->name('users')
                ->whereTime('createTime','between',[$start,$end])
                ->where(['dataFlag'=>1,'userType'=>0])
                ->order('createTime asc')
                ->group('left(createTime,10)')
                ->select();
        $srs = Db::field('left(createTime,10) createTime,count(shopId) userNum')
                ->name('shops')
                ->whereTime('createTime','between',[$start,$end])
                ->where(['dataFlag'=>1])
                ->order('createTime asc')
                ->group('left(createTime,10)')
                ->select();
        $rdata = [];
        $days = [];
        $tmp = [];
        if(count($urs)>0){
            foreach($urs as $key => $v){
                if(!in_array($v['createTime'],$days))$days[] = $v['createTime'];
                $tmp["0_".$v['createTime']] = $v['userNum'];
            }
        }
        if(count($srs)>0){
            foreach($srs as $key => $v){
                if(!in_array($v['createTime'],$days))$days[] = $v['createTime'];
                $tmp["1_".$v['createTime']] = $v['userNum'];
            }
        }
        sort($days);
        foreach($days as $v){
            $rdata['u0'][] =  isset($tmp['0_'.$v])?$tmp['0_'.$v]:0;
            $rdata['u1'][] =  isset($tmp['1_'.$v])?$tmp['1_'.$v]:0;
        }
        $rdata['days'] = $days;
        return WSTReturn('',1,$rdata);
    }
    /**
     * 会员登录统计
     */
    public function statUserLogin(){
        $start = date('Y-m-d 00:00:00',strtotime(input('startDate')));
        $end = date('Y-m-d 23:59:59',strtotime(input('endDate')));
        $prefix = config('database.prefix');
        $sql ='select createTime,userType,count(userId) userNum from ( 
             SELECT left(loginTime,10) createTime,`userType`,u.userId
                FROM `'.$prefix.'users` `u` INNER JOIN `'.$prefix.'log_user_logins` `lg` ON `u`.`userId`=`lg`.`userId` 
                WHERE  `loginTime` BETWEEN "'.$start.'" AND "'.$end.'"  AND (  dataFlag=1 )
                GROUP BY left(loginTime,10),userType,lg.userId
              ) a GROUP BY createTime, userType ORDER BY createTime asc ';
        $rs = Db::query($sql);  
        $rdata = [];
        if(count($rs)>0){
            $days = [];
            $tmp = [];
            foreach($rs as $key => $v){
                if(!in_array($v['createTime'],$days))$days[] = $v['createTime'];
                $tmp[$v['userType']."_".$v['createTime']] = $v['userNum'];
            }
            foreach($days as $v){
                $rdata['u0'][] = isset($tmp['0_'.$v])?$tmp['0_'.$v]:0;
                $rdata['u1'][] = isset($tmp['1_'.$v])?$tmp['1_'.$v]:0;
            }
            $rdata['days'] = $days;
        }
        return WSTReturn('',1,$rdata);
    }
}