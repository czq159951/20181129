<?php
namespace shangtao\home\controller;
/**
 * 门店配置控制器
 */
class Shopconfigs extends Base{
    protected $beforeActionList = ['checkShopAuth'];
    /**
    * 店铺设置
    */
    public function toShopCfg(){
        //获取商品信息
        $m = model('ShopConfigs');
        $this->assign('object',$m->getShopCfg((int)session('WST_USER.shopId')));
        return $this->fetch('shops/shopconfigs/shop_cfg');
    }

    /**
     * 新增/修改 店铺设置
     */
    public function editShopCfg(){
        $shopId = (int)session('WST_USER.shopId');
        $m = model('ShopConfigs');
        if($shopId>0){
            $rs = $m->editShopCfg($shopId);
        }
        return $rs;
    }

}
