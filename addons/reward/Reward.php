<?php
namespace addons\reward;


use think\addons\Addons;
use addons\reward\model\Rewards as DM;

/**
 * 满就送
 * @author shangtao
 */
class Reward extends Addons{
    // 该插件的基础信息
    public $info = [
        'name' => 'Reward',   // 插件标识
        'title' => '满就送',  // 插件名称
        'description' => '营销插件-满就送、满就减、满包邮',    // 插件简介
        'status' => 0,  // 状态
        'author' => 'shangtao',
        'version' => '1.0.0'
    ];

	
    /**
     * 插件安装方法
     * @return bool
     */
    public function install(){
        $m = new DM();
        $flag = $m->installMenu();
        WSTClearHookCache();
        return $flag;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall(){
        $m = new DM();
        $flag = $m->uninstallMenu();
        WSTClearHookCache();
        return $flag;
    }
    
	/**
     * 插件启用方法
     * @return bool
     */
    public function enable(){
        $m = new DM();
        $flag = $m->toggleShow(1);
        WSTClearHookCache();
    	WSTClearHookCache();
        return true;
    }
    
    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable(){
        $m = new DM();
        $flag = $m->toggleShow(0);
        WSTClearHookCache();
    	WSTClearHookCache();
    	return true;
    }
    
    /**
     * 插件设置方法
     * @return bool
     */
    public function saveConfig(){
    	WSTClearHookCache();
    	return true;
    }

    /**
     * 商品列表
     */
    public function afterQueryGoods($params){
        $m = new DM();
        foreach ($params["page"]["data"] as $key => $v){
            $rs = $m->getGoodsRewardTags($v['goodsId']);
            if($rs>0){
                if(isset($params['isApp'])){
                    $params["page"]["data"][$key]['isReward'] = true;
                }else{
                    $params["page"]["data"][$key]['tags'][] = '<span class="tag">满送</span>';
                }
            }
        }
    }

    /**
     * 商品促销页面
     */
    public function homeDocumentGoodsPromotionDetail($params){
        $m = new DM();
        $rs = $m->getAvailableRewards($params['goods']['shopId'],$params['goods']['goodsId']);
        $this->assign('rewards',$rs);
        $this->assign('goodsId',$params['goods']['goodsId']);
        return $this->fetch('view/home/index/promotion');
    }

    /**
     * 查询购物车之后执行代码
     */
    public function afterQueryCarts($params){
        $m = new DM();
        if(!$params['isVirtual']){
            $m->afterQueryCarts($params);
        }
        
    }

    /**
     * 购物车-商品栏
     */
    public function homeDocumentCartGoodsPromotion($params){
        $this->assign("goods",$params['goods']);
        return $this->fetch('view/home/index/cart_reward_goods');
    }
    /**
     * 购物车结算-商品栏
     */
    public function homeDocumentSettlementGoodsPromotion($params){
        $this->assign("goods",$params['goods']);
        return $this->fetch('view/home/index/settlement_reward_goods');
    }
   
    /**
     * 修改订单数据
     */
    public function beforeInsertOrder($params){
        $m = new DM();
        $m->beforeInsertOrder($params);
    }
    /**
     * 修改订单商品数据
     */
    public function beforeInsertOrderGoods($params){
        $m = new DM();
        $m->beforeInsertOrderGoods($params);
    }
    /**
     * 用户收货
     */
    public function afterUserReceive($params){
        $m = new DM();
        $m->afterUserReceive($params);
    }
    /**
     * 前台订单详情-商品栏
     */
    public function homeDocumentOrderViewGoodsPromotion($params){
        $params['goods']['isHead'] = false;
        if($params['goods']['promotionJson']!=''){
            $params['goods']['promotionJson'] = json_decode($params['goods']['promotionJson'],true);
            $params['goods']['promotionJson']['extraJson'] = json_decode($params['goods']['promotionJson']['extraJson'],true);
            if($params['goods']['promotionJson']['promotionGoodsIds'][0] == $params['goods']['goodsId']){
                $params['goods']['isHead'] = true;
            }
        }else{
            $params['goods']['promotionJson'] = [];
        }
        $this->assign("goods",$params['goods']);
        return $this->fetch('view/home/order_reward_goods');
    }
    /**
     * 管理员订单详情-商品栏
     */
    public function adminDocumentOrderViewGoodsPromotion($params){
        $params['goods']['isHead'] = false;
        if($params['goods']['promotionJson']!=''){
            $params['goods']['promotionJson'] = json_decode($params['goods']['promotionJson'],true);
            $params['goods']['promotionJson']['extraJson'] = json_decode($params['goods']['promotionJson']['extraJson'],true);
            if($params['goods']['promotionJson']['promotionGoodsIds'][0] == $params['goods']['goodsId']){
                $params['goods']['isHead'] = true;
            }
        }else{
            $params['goods']['promotionJson'] = [];
        }
        $this->assign("goods",$params['goods']);
        return $this->fetch('view/admin/order_reward_goods');
    }
    /*************************************************  手机版 **************************************************/
    /**
     * 购物车-商品栏
     */
    public function mobileDocumentCartGoodsPromotion($params){
        $this->assign("goods",$params['goods']);
        return $this->fetch('view/mobile/index/cart_reward_goods');
    }
    /**
     * 购物车结算-商品栏
     */
    public function mobileDocumentSettlementGoodsPromotion($params){
        $this->assign("goods",$params['goods']);
        // 活动参与门槛金额
        $orderMoney = isset($params['goods']['promotion']['data']['json'][0]['orderMoney'])?$params['goods']['promotion']['data']['json'][0]['orderMoney']:0;
        $this->assign("orderMoney",$orderMoney);
        return $this->fetch('view/mobile/index/settlement_reward_goods');
    }
    /**
     * 商品促销页面【商品详情】
     */
    public function mobileDocumentGoodsPromotionDetail($params){
        $m = new DM();
        $rs = $m->getAvailableRewards($params['goods']['shopId'],$params['goods']['goodsId']);
        $this->assign('rewards',$rs);
        $this->assign('goodsId',$params['goods']['goodsId']);
        return $this->fetch('view/mobile/index/promotion');
    }
    /**
    * 手机版订单详情
    */
    public function mobileDocumentOrderViewGoodsPromotion($params){
        foreach($params['rs']['goods'] as $k=>$v){
            if($v['promotionJson']!=''){// 有使用优惠券
                $params['rs']['goods'][$k]['promotionJson'] = json_decode($v['promotionJson'],true);
                $params['rs']['goods'][$k]['promotionJson']['extraJson'] = json_decode($params['rs']['goods'][$k]['promotionJson']['extraJson'],true);
                $money = $params['rs']['goods'][$k]['promotionJson']['promotionMoney'];
                $hook = "<p class='price'><span class='title'>满减优惠：</span>￥-". number_format($money,2)."</p>";
                if(isset($params['rs']['hook'])){
                    $params['rs']['hook'] .= $hook;
                }else{
                    $params['rs']['hook'] = $hook;
                }
                break;
            }
        }
    }
    /*************************************************  微信版 **************************************************/
    /**
     * 购物车-商品栏
     */
    public function wechatDocumentCartGoodsPromotion($params){
        $this->assign("goods",$params['goods']);
        return $this->fetch('view/wechat/index/cart_reward_goods');
    }
    /**
     * 购物车结算-商品栏
     */
    public function wechatDocumentSettlementGoodsPromotion($params){
        $this->assign("goods",$params['goods']);
        // 活动参与门槛金额
        $orderMoney = isset($params['goods']['promotion']['data']['json'][0]['orderMoney'])?$params['goods']['promotion']['data']['json'][0]['orderMoney']:0;
        $this->assign("orderMoney",$orderMoney);
        return $this->fetch('view/wechat/index/settlement_reward_goods');
    }
    /**
     * 商品促销页面【商品详情】
     */
    public function wechatDocumentGoodsPromotionDetail($params){
        $m = new DM();
        $rs = $m->getAvailableRewards($params['goods']['shopId'],$params['goods']['goodsId']);
        $this->assign('rewards',$rs);
        $this->assign('goodsId',$params['goods']['goodsId']);
        return $this->fetch('view/wechat/index/promotion');
    }
    /**
    * 微信版订单详情
    */
    public function wechatDocumentOrderViewGoodsPromotion($params){
        foreach($params['rs']['goods'] as $k=>$v){
            if($v['promotionJson']!=''){// 有使用优惠券
                $params['rs']['goods'][$k]['promotionJson'] = json_decode($v['promotionJson'],true);
                $params['rs']['goods'][$k]['promotionJson']['extraJson'] = json_decode($params['rs']['goods'][$k]['promotionJson']['extraJson'],true);
                $money = $params['rs']['goods'][$k]['promotionJson']['promotionMoney'];
                $hook = "<p class='price'><span class='title'>满减优惠：</span>￥-". number_format($money,2)."</p>";
                if(isset($params['rs']['hook'])){
                    $params['rs']['hook'] .= $hook;
                }else{
                    $params['rs']['hook'] = $hook;
                }
                break;
            }
        }
    }
}