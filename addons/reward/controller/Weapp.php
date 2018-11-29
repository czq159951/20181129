<?php
namespace addons\reward\controller;

use think\addons\Controller;
use addons\reward\model\Rewards as M;
/**
 * weapp满就送接口插件
 */
class WeApp extends Controller{
    /**
     * 商品促销页面【商品详情】
     */
    public function goodsDetail(){
        $goodsId = (int)input('goodsId');
        $shopId = (int)input('shopId');
        $m = new M();
        $rs = $m->getAvailableRewards($shopId,$goodsId);
        if(!empty($rs)){
            return jsonReturn('success',1,['list'=>$rs['json'],'rewardTitle'=>$rs['rewardTitle'],'reward'=>WSTConf('WST_ADDONS.reward'),'state'=>'0']);
        }
        return jsonReturn('error',-1);
    }
}