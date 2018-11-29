<?php 
namespace shangtao\common\model;
use think\Db;
/**
 * 报表模型类
 */
class Reports{
	/**
	 * 获取商品销售排行
	 */
	public function getTopSaleGoods($sId=0){
		$start = date('Y-m-d 00:00:00',strtotime(input('startDate')));
    	$end = date('Y-m-d 23:59:59',strtotime(input('endDate')));
    	$shopId = ($sId==0)?(int)session('WST_USER.shopId'):$sId;
    	$prefix = config('database.prefix');
    	$rs = Db::table($prefix.'order_goods')->alias([$prefix.'order_goods'=>'og',$prefix.'orders'=>'o',$prefix.'goods'=>'g'])
    	  ->join($prefix.'orders','og.orderId=o.orderId')
    	  ->join($prefix.'goods','og.goodsId=g.goodsId')
    	  ->order('goodsNum desc')
    	  ->whereTime('o.createTime','between',[$start,$end])
          ->where('(payType=0 or (payType=1 and isPay=1)) and o.dataFlag=1 and o.shopId='.$shopId)->group('og.goodsId')
          ->field('og.goodsId,g.goodsName,goodsSn,sum(og.goodsNum) goodsNum,g.goodsImg')
          ->limit(10)->select();
        return WSTReturn('',1,$rs);
	}
	/**
	 * 获取销售额统计
	 */
	public function getStatSales($sId=0){
		$start = date('Y-m-d 00:00:00',strtotime(input('startDate')));
        $end = date('Y-m-d 23:59:59',strtotime(input('endDate')));
        $payType = (int)input('payType',-1);
        $shopId = ($sId==0)?(int)session('WST_USER.shopId'):$sId;
        $rs = Db::field('left(createTime,10) createTime,sum(totalMoney) totalMoney,count(orderId) orderNum')->name('orders')->whereTime('createTime','between',[$start,$end])
                ->where('shopId',$shopId)
                ->where('(payType=0 or (payType=1 and isPay=1)) and dataFlag=1 '.(in_array($payType,[0,1])?" and payType=".$payType:''))
                ->order('createTime asc')
                ->group('left(createTime,10)')->select();
        $rdata = [];
        if(count($rs)>0){
            $days = [];
            $tmp = [];
            foreach($rs as $key => $v){
                $days[] = $v['createTime'];
                $rdata['dayVals'][] = $v['totalMoney'];
                $rdata['list'][] = ['day'=>$v['createTime'],'val'=>$v['totalMoney'],'num'=>$v['orderNum']];
            }
            $rdata['days'] = $days;
        }
        return WSTReturn('',1,$rdata);
	}

    /**
     * 获取商家订单情况
     */
    public function getStatOrders($sId=0){
        $start = date('Y-m-d 00:00:00',strtotime(input('startDate')));
        $end = date('Y-m-d 23:59:59',strtotime(input('endDate')));
        $shopId = ($sId==0)?(int)session('WST_USER.shopId'):$sId;
        $rs = Db::field('left(createTime,10) createTime,orderStatus,count(orderId) orderNum')->name('orders')->whereTime('createTime','between',[$start,$end])
                ->where('shopId',$shopId)
                ->order('createTime asc')
                ->group('left(createTime,10),orderStatus')->select();
       $rdata = [];
       if(count($rs)>0){
            $days = [];
            $tmp = [];
            $map = ['-3'=>0,'-1'=>0,'1'=>0];
            foreach($rs as $key => $v){
                if(!in_array($v['createTime'],$days))$days[] = $v['createTime'];
                $tmp[$v['orderStatus'].'_'.$v['createTime']] = $v['orderNum'];
            }
            foreach($days as $v){
                $total = 0;
                $ou = 0;
                $o_3 = isset($tmp['-3_'.$v])?$tmp['-3_'.$v]:0;
                $o_1 = isset($tmp['-1_'.$v])?$tmp['-1_'.$v]:0;
                if(isset($tmp['0_'.$v]))$ou += $tmp['0_'.$v];
                if(isset($tmp['1_'.$v]))$ou += $tmp['1_'.$v];
                if(isset($tmp['2_'.$v]))$ou += $tmp['2_'.$v];
                $rdata['-3'][] = $o_3;
                $rdata['-1'][] = $o_1;
                $rdata['1'][] = $ou;
                $map['-3']  += $o_3;
                $map['-1']  += $o_1;
                $map['1']  += $ou;
                $total += $o_3;
                $total += $o_1;
                $total += $ou;
                $rdata['total'][] = $total;
                $rdata['list'][] = ['day'=>$v,'o3'=>$o_3,'o1'=>$o_1,'ou'=>$ou];
            }
            $rdata['days'] = $days;
            $rdata['map'] = $map;
       }
       return WSTReturn('',1,$rdata);
    }
}