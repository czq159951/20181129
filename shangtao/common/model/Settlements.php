<?php
namespace shangtao\common\model;
use think\Db;
/**
 * 结算类
 */
class Settlements extends Base{
	protected $pk = 'settlementId';
	/**
     * 即时计算
     */
    public function speedySettlement($orderId){
        $order = model('common/orders')->get(['orderId'=>$orderId]);
        $shops = model('common/shops')->get(['shopId'=>$order->shopId]);
        if(empty($shops))return WSTReturn('结算失败，商家不存在');
        $backMoney = 0;
        if($order->payType==1){
             //在线支付的返还金额=实付金额+积分抵扣金额-佣金
             $backMoney = $order->realTotalMoney+$order->scoreMoney-$order->commissionFee;
        }else{
             //货到付款的返还金额=积分抵扣金额-佣金
             $backMoney = $order->scoreMoney-$order->commissionFee;
        }
        $data = [];
        $data['settlementType'] = 1;
        $data['shopId'] = $order->shopId;
        $data['settlementMoney'] = $order->scoreMoney+(($order->payType==1)?$order->realTotalMoney:0);
        $data['commissionFee'] = $order->commissionFee;
        $data['backMoney'] = $backMoney;
        $data['settlementStatus'] = 1;
        $data['settlementTime'] = date('Y-m-d H:i:s');
        $data['createTime'] = date('Y-m-d H:i:s');
        $data['settlementNo'] = '';
        $result = $this->save($data);
        if(false !==  $result){
            $this->settlementNo = $this->settlementId.(fmod($this->settlementId,7));
            $this->save();
            $order->settlementId = $this->settlementId;
            $order->save();
            //修改商家钱包
            $shops->shopMoney = $shops['shopMoney']+$backMoney;
            $shops->save();
            //返还金额
            $lmarr = [];
            //如果是货到付款并且有积分支付的话，还要补上一个积分支付的资金流水记录，不然流水上金额不对。
            if($order->payType==0 && $order->scoreMoney >0){
                $lm = [];
                $lm['targetType'] = 1;
                $lm['targetId'] = $order->shopId;
                $lm['dataId'] = $this->settlementId;
                $lm['dataSrc'] = 2;
                $lm['remark'] = '结算订单申请【'.$this->settlementNo.'】返还积分支付金额¥'.$order->scoreMoney;
                $lm['moneyType'] = 1;
                $lm['money'] =$order->scoreMoney;
                $lm['payType'] = 0;
                $lm['createTime'] = date('Y-m-d H:i:s');
                $lmarr[] = $lm;
            }
            //收取佣金
            if($order->commissionFee>0){
                $lm = [];
                $lm['targetType'] = 1;
                $lm['targetId'] = $order->shopId;
                $lm['dataId'] = $this->settlementId;
                $lm['dataSrc'] = 2;
                $lm['remark'] = '结算订单申请【'.$this->settlementNo.'】收取订单佣金¥'.$order->commissionFee;
                $lm['moneyType'] = 0;
                $lm['money'] = $order->commissionFee;
                $lm['payType'] = 0;
                $lm['createTime'] = date('Y-m-d H:i:s');
                $lmarr[] = $lm;
            }

            if($backMoney>0){
                $lm = [];
                $lm['targetType'] = 1;
                $lm['targetId'] = $order->shopId;
                $lm['dataId'] = $this->settlementId;
                $lm['dataSrc'] = 2;
                $lm['remark'] = '结算订单申请【'.$this->settlementNo.'】返还金额¥'.$backMoney;
                $lm['moneyType'] = 1;
                $lm['money'] =$backMoney;
                $lm['payType'] = 0;
                $lm['createTime'] = date('Y-m-d H:i:s');
                $lmarr[] = $lm;
            } 
            model('common/LogMoneys')->saveAll($lmarr);
        }
        return WSTReturn('结算失败');
    }
}
