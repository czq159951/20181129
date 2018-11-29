<?php
namespace shangtao\mobile\controller;
use shangtao\common\model\GoodsCats;
use shangtao\mobile\model\Goods;
/**
 * 门店控制器
 */
class Shops extends Base{
    /**
     * 店铺街
     */
    public function shopStreet(){
    	$gc = new GoodsCats();
    	$goodsCats = $gc->listQuery(0);
    	$this->assign('goodscats',$goodsCats);
    	$this->assign("keyword", input('keyword'));
    	return $this->fetch('shop_street');
    }
    /**
     * 店铺首页
     */
    public function index(){
        $s = model('shops');
        $shopId = (int)input('shopId',1);
        $data = $s->getShopSummary($shopId);
        $this->assign('data',$data);
        // 是否已关注
        $isFavor = model('favorites')->checkFavorite($shopId,1);
        $this->assign('isFavor',$isFavor);
        $this->assign("goodsName", input('goodsName'));
        return $this->fetch('shop_index');
    }
    /**
    * 店铺详情
    */
    public function home(){
        $s = model('shops');
        $shopId = (int)input("param.shopId/d",1);
        $data['shop'] = $s->getShopInfo($shopId);

        $ct1 = input("param.ct1/d",0);
        $ct2 = input("param.ct2/d",0);
        $goodsName = input("param.goodsName");
        if(($data['shop']['shopId']==1 || $shopId==0) && $ct1==0 && !isset($goodsName))
            $this->redirect('mobile/shops/selfShop');

        $gcModel = model('ShopCats');
        $data['shopcats'] = $gcModel->getShopCats($shopId);
        
        $this->assign('shopId',$shopId);//店铺id

        $this->assign('ct1',$ct1);//一级分类
        $this->assign('ct2',$ct2);//二级分类
        
        $this->assign('goodsName',urldecode($goodsName));//搜索
        $this->assign('data',$data);

        // 是否已关注
        $isFavor = model('favorites')->checkFavorite($shopId,1);
        $this->assign('isFavor',$isFavor);
        $followNum = model('favorites')->followNum($shopId,1);
        $this->assign('followNum',$followNum);
        
        $cart = model('carts')->getCartInfo();
        $this->assign('cart',$cart);
        return $this->fetch('shop_home');
    }
    /**
    * 店铺商品列表
    */
    public function shopGoodsList(){
        $s = model('shops');
        $shopId = (int)input("param.shopId/d",1);

        $ct1 = input("param.ct1/d",0);
        $ct2 = input("param.ct2/d",0);
        $goodsName = input("param.goodsName");
        $gcModel = model('ShopCats');
        $data['shopcats'] = $gcModel->getShopCats($shopId);
        
        $this->assign('shopId',$shopId);//店铺id

        $this->assign('ct1',$ct1);//一级分类
        $this->assign('ct2',$ct2);//二级分类
        
        $this->assign('goodsName',urldecode($goodsName));//搜索
        $this->assign('data',$data);

        return $this->fetch('shop_goods_list');
    }
    /**
    * 获取店铺商品
    */
    public function getShopGoods(){
        $shopId = (int)input('shopId',1);
        $g = model('goods');
        $rs = $g->shopGoods($shopId);
        foreach($rs['data'] as $k=>$v){
            $rs['data'][$k]['goodsImg'] = WSTImg($v['goodsImg'],3,'goodsLogo');
        }
        return $rs;
    }

    /**
    * 自营店铺
    */
    public function selfShop(){
        $s = model('shops');
        $data['shop'] = $s->getShopInfo(1);
        if(empty($data['shop']))return $this->fetch('error_lost');
        $this->assign('selfShop',1);
        $data['shopcats'] = model('ShopCats')->getShopCats(1);
        $this->assign('goodsName',urldecode(input("param.goodsName")));//搜索
        // 店长推荐
        $data['rec'] = $s->getRecGoods('rec');
        // 热销商品
        $data['hot'] = $s->getRecGoods('hot');
        $this->assign('data',$data);
        // 是否已关注
        $isFavor = model('favorites')->checkFavorite(1,1);
        $this->assign('isFavor',$isFavor);
        $followNum = model('favorites')->followNum(1,1);
        $this->assign('followNum',$followNum);
        $this->assign("keyword", input('keyword'));
        return $this->fetch('self_shop');
    }
    public function getFloorData(){
        $s = model('shops');
        $rs = $s->getFloorData();
        if(isset($rs['goods'])){
            foreach($rs['goods'] as $k=>$v){
                $rs['goods'][$k]['goodsImg'] = WSTImg($v['goodsImg'],3,'goodsLogo');
            }
        }
        return $rs;
    }

    /**
     * 店铺街列表
     */
    public function pageQuery(){
    	$m = model('shops');
    	$rs = $m->pageQuery(input('pagesize/d'));
    	foreach ($rs['data'] as $key =>$v){
    		$rs['data'][$key]['shopImg'] = WSTImg($v['shopImg'],3,'shopLogo');
    	}
    	return $rs;
    }

}
