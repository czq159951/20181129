<?php
namespace addons\coupon;


use think\addons\Addons;
use addons\coupon\model\Coupons as DM;

/**
 * 优惠券
 */
class Coupon extends Addons{
    // 该插件的基础信息
    public $info = [
        'name' => 'Coupon',   // 插件标识
        'title' => '优惠券',  // 插件名称
        'description' => '营销插件-优惠券',    // 插件简介
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
            $rs = $m->getGoodsCouponTags($v['goodsId']);
            if($rs>0){
                if(isset($params['isApp'])){
                    $params["page"]["data"][$key]['isCoupon'] = true;
                }else{
                    $params["page"]["data"][$key]['tags'][] = '<span class="tag">券</span>';
                }
            }
        }
    }
    /**
     * 商品详情页价格区域
     */
    public function homeDocumentGoodsPropDetail(){
        return $this->fetch('view/home/index/coupon');
    }
    /**
     * 购物车栏
     */
    public function homeDocumentCartShopPromotion($params){
        $m = new DM();
        $rs = $m->getCouponsByShop($params['shop']['shopId']);
        $this->assign("coupons",$rs['data']['coupons']);
        $this->assign("shopId",$params['shop']['shopId']);
        return $this->fetch('view/home/index/cart_coupon');
    }

    /**
     * 查询购物车之后执行代码
     */
    public function afterQueryCarts($params){
        if($params['isSettlement']){
            $m = new DM();
            foreach ($params['carts']['carts'] as $key => $v) {
                $params['carts']['carts'][$key]['coupons'] = $m->getAvailableCoupons($v['list'],$v['shopId'],$params['uId']);
            }
        }
    }

    /**
     * 计算订单金额
     */
    public function afterCalculateCartMoney($params){
        $m = new DM();
        if($params['isVirtual']){
            $m->calculateVirtualCartMoney($params);
        }else{
            $m->calculateCartMoney($params);
        }
    }

    /**
     * 新增订单前执行
     */
    public function beforeInsertOrder($params){
        $m = new DM();
        $m->beforeInsertOrder($params);
    }

    /**
     * pc版订单详情展示
     */
    public function homeDocumentOrderSummaryView($params){
        if($params['order']['userCouponId']>0){
            $params['order']['userCouponJson'] = json_decode($params['order']['userCouponJson'],true);
            $this->assign('order', $params['order']);
            return $this->fetch('view/home/view');
        }
    }
    /**
     * 管理员订单详情展示
     */
    public function adminDocumentOrderSummaryView($params){
        if($params['order']['userCouponId']>0){
            $params['order']['userCouponJson'] = json_decode($params['order']['userCouponJson'],true);
            $this->assign('order', $params['order']);
            return $this->fetch('view/admin/view');
        }
    }
    /**
     * 删除店铺时操作
     */
    public function afterChangeShopStatus($params){
        $m = new DM();
        $m->afterChangeShopStatus($params);
    }
    /**
     * 计算页面-店铺展示
     */
    public function homeDocumentSettlementShopSummary($params){
        $this->assign('coupons', $params['coupons']);
        $this->assign('shopId', $params['shopId']);
        return $this->fetch('view/home/index/settlement_shop');
    }
    /**
     * 手机用户“我的”
     */
    public function mobileDocumentUserIndexTools(){
        return $this->fetch('view/mobile/users/index');
    }
    /**
     * 微信用户“我的”
     */
    public function wechatDocumentUserIndexTools(){
        return $this->fetch('view/wechat/users/index');
    }
    /**
     * 手机用户“我的”
     */
    public function mobileDocumentUserIndexTerm(){
    	return $this->fetch('view/mobile/users/term');
    }
    /**
     * 微信用户“我的”
     */
    public function wechatDocumentUserIndexTerm(){
    	return $this->fetch('view/wechat/users/term');
    }

    /**
     * 手机版订单结算页面
     */
    public function mobileDocumentCartShopPromotion($params){
        $this->assign('coupons',$params['coupons']);
        $this->assign('shopId',$params['shopId']);
        return $this->fetch('view/mobile/users/coupon');
    }
    /**
    * 手机版订单详情
    */
    public function mobileDocumentOrderSummaryView($params){
        $hook = '';
        if($params['rs']['userCouponId']>0){
            // 获取优惠券信息
            $money = json_decode($params['rs']['userCouponJson'],true)['money']; // 优惠券优惠金额
            $hook = "<p class='price'><span class='title'>优惠券优惠：</span>￥-". number_format($money,2)."</p>";
        }
        $params['rs']['hook'] = $hook;
    }
    /**
    * 手机版商品详情
    */ 
    public function mobileDocumentGoodsPropDetail(){
        return $this->fetch('view/mobile/index/goods_detail_coupon');
    }
    /**
    * 微信版订单详情
    */
    public function wechatDocumentOrderSummaryView($params){
        $hook = '';
        if($params['rs']['userCouponId']>0){
            // 获取优惠券信息
            $money = json_decode($params['rs']['userCouponJson'],true)['money']; // 优惠券优惠金额
            $hook = "<p class='price'><span class='title'>优惠券优惠：</span>￥-". number_format($money,2)."</p>";
        }
        $params['rs']['hook'] = $hook;
    }
    /**
     * 微信版订单结算页面
     */
    public function wechatDocumentCartShopPromotion($params){
        $this->assign('coupons',$params['coupons']);
        $this->assign('shopId',$params['shopId']);
        return $this->fetch('view/wechat/users/coupon');
    }
    /**
    * 微信版商品详情
    */ 
    public function wechatDocumentGoodsPropDetail(){
        return $this->fetch('view/wechat/index/goods_detail_coupon');
    }


}