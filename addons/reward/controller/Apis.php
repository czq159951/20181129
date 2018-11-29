<?php
namespace addons\reward\controller;

use think\addons\Controller;
use addons\reward\model\Rewards as M;
/**
 * app满就送接口插件
 */
class Apis extends Controller{
    /**
     * 商品促销页面【商品详情】
     */
    public function goodsPromotionDetail(){
        $goodsId = (int)input('goodsId');
        $shopId = (int)input('shopId');
        $m = new M();
        $rs = $m->getAvailableRewards($shopId,$goodsId);
        if(!empty($rs)){
            $rewardDesc = '';
            foreach($rs['json'] as $k=>$v){
                $rewardDesc = "消费满{$v['orderMoney']}元 - ";
                if($v['favourableJson']['chk0'])$rewardDesc .= "减￥{$v['favourableJson']['chk0val']}、\n";
                if($v['favourableJson']['chk1'])$rewardDesc .= "送赠品【{$v['favourableJson']['chk1val']['text']}】、\n";
                if($v['favourableJson']['chk2'])$rewardDesc .= "免邮费、\n";
                if(WSTConf('WST_ADDONS.reward') && $v['favourableJson']['chk3'])$rewardDesc .= "送{$v['favourableJson']['chk3val']['text']}优惠券";
            }  
            return json_encode(WSTReturn('ok',1,['rewardTitle'=>$rs['rewardTitle'],'rewardDesc'=>$rewardDesc]));
        }
        return json_encode(WSTReturn('error',-1));
    }
}