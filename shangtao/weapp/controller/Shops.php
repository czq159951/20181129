<?php
namespace shangtao\weapp\controller;
use shangtao\common\model\GoodsCats;
use shangtao\common\model\Tags;
/**
 * 门店控制器
 */
class Shops extends Base{
    /**
     * 店铺街头部: 广告及商品分类
     */
    public function shopStreet(){
    	$gc = new GoodsCats();
        $goodscats = $gc->listQuery(0);
        foreach ($goodscats as $k => $v) {
            $_this = $goodscats[$k];
            // 删除无用字段
            unset(
                $_this['parentId'],
                $_this['isShow'],
                $_this['isFloor'],
                $_this['catSort'],
                $_this['dataFlag'],
                $_this['createTime'],
                $_this['commissionRate']);
            
        }
    	$data['goodscats'] =  $goodscats;
    	$data['keyword'] = urldecode(input('keyword'));
    	$ta = new Tags();
        $swiper = $ta->listAds('app-ads-street',4);
        foreach ($swiper as $k1 => $v1) {
            WSTAllow($swiper[$k1],'adFile');
        }
    	$data['swiper'] = $swiper;
    	echo jsonReturn('店铺数据请求成功',1,$data);
    	die;
    }
    /**
     * 店铺首页
     */
    public function index(){
        $s = model('shops');
        $shopId = (int)input('shopId',1);
        $data = $s->getShopSummary($shopId);
        // 是否已关注
        $data['isFavor'] = model('favorites')->checkFavorite($shopId,1);
        $data['shopAdtop'] = WSTConf('CONF.shopAdtop');
        echo jsonReturn('店铺数据请求成功',1,$data);
        die;
    }
    /**
    * 店铺详情
    */
    public function home(){
        $s = model('shops');
        $shopId = (int)input("param.shopId/d",1);
        $data['shop'] = $s->getShopInfo($shopId);
        $data['shopcats'] = model('ShopCats')->getShopCats($shopId);

        $ct1 = input("param.ct1/d",0);
        $ct2 = input("param.ct2/d",0);
        $goodsName = input("param.goodsName");
        /*搜索数据*/
        $data['ct1'] = $ct1;//一级分类
        $data['ct2'] = $ct2;//二级分类
        $data['goodsName'] = urldecode($goodsName);//搜索

        // 是否已关注
        $data['isFavor'] = model('favorites')->checkFavorite($shopId,1);
        $_rec = [];
        $resArr = ['recom','new','hot','best'];
        foreach($resArr as $key => $var){
            // 店主推荐
            $rec = model('Tags')->listShopGoods($var,$shopId,4);
            foreach($rec as $k=>$v){
                $_rec[$var][$k]['goodsId'] = $v['goodsId'];
                $_rec[$var][$k]['goodsName'] = $v['goodsName'];
                $_rec[$var][$k]['shopPrice'] = $v['shopPrice'];
                $_rec[$var][$k]['goodsImg'] = WSTImg($v['goodsImg'],3,'goodsLogo');
            }

        }
        $data['rec'] = $_rec;
        $carts = model('carts')->getCartInfo();
        unset(
                $data['shop']['shopAddress'],
                $data['shop']['shopQQ'],
                $data['shop']['shopWangWang'],
                $data['shop']['serviceStartTime'],
                $data['shop']['serviceEndTime'],
                $data['shop']['catshops'],
                $data['shop']['shopTitle'],
                $data['shop']['shopDesc'],
                $data['shop']['shopKeywords'],
                $data['shop']['shopHotWords'],
                $carts['list']);
        $data['shop']['shopAdtop'] = WSTConf('CONF.shopAdtop');
        $data['carts'] = $carts;
        $data['followNum'] = model('favorites')->followNum($shopId,1);
        echo jsonReturn('店铺数据请求成功',1,$data);
        die;
    }
    /**
    * 获取店铺商品
    */
    public function getShopGoods(){
        $shopId = (int)input('shopId',1);
        $g = model('goods');
        $rs = $g->shopGoods($shopId);
        if(empty($rs['data']))return jsonReturn('没有相关商品',-1);
        foreach($rs['data'] as $k=>$v){
            $rs['data'][$k]['goodsImg'] = WSTImg($v['goodsImg'],3,'goodsLogo');
        }
        // 购物车信息
        $carts = model('carts')->getCartInfo();
        // 删除无用字段
        unset($carts['list']);
        $data['carts'] = $carts;
        return jsonReturn('请求成功',1,$rs);
    }

    /**
    * 自营店铺
    */
    public function selfShop(){
        $s = model('shops');
        $num = input('num');
        $shopId  = input('shopId');
        $data['shop'] = $s->getShopInfo(1);
        $data['shopcats'] = model('ShopCats')->getShopCats($shopId);
        if(empty($data['shop']))return jsonReturn('暂无店铺数据',-1);
        // 删除无用字段
        unset(
                $data['shop']['shopAddress'],
                $data['shop']['shopQQ'],
                $data['shop']['shopWangWang'],
                $data['shop']['serviceStartTime'],
                $data['shop']['serviceEndTime'],
                $data['shop']['catshops'],
                $data['shop']['shopTitle'],
                $data['shop']['shopDesc'],
                $data['shop']['shopKeywords'],
                $data['shop']['shopHotWords']);
       
        if($num == 1){
        	//当请求商品列表时，只返回分类
        	
        }else{
        	 // 店长推荐
        $data['rec'] = $s->getRecGoods('rec');
        // 热销商品
        $data['hot'] = $s->getRecGoods('hot');
        // 是否已关注
        $data['isFavor'] = model('favorites')->checkFavorite(1,1);
        $data['followNum'] = model('favorites')->followNum(1,1);
        }
        $data['shop']['shopAdtop'] = WSTConf('CONF.shopAdtop');
        echo jsonReturn('店铺数据请求成功',1,$data);
        die;
    }
    public function getFloorData(){
        $s = model('shops');
        $rs = $s->getFloorData();
        if(isset($rs['goods'])){
            foreach($rs['goods'] as $k=>$v){
                $rs['goods'][$k]['goodsImg'] = WSTImg($v['goodsImg'],3,'goodsLogo');
            }
        }
        echo jsonReturn('success',1,$rs);
    }

    /**
     * 店铺街列表
     */
    public function pageQuery(){
    	$m = model('shops');
    	$rs = $m->pageQuery(input('pagesize/d'));
    	foreach ($rs['data'] as $key =>$v){
    		$rs['data'][$key]['shopImg'] = WSTImg($v['shopImg'],3,'shopLogo');
            // 删除无用字段
            unset(
                    $rs['data'][$key]['areaId'],
                    $rs['data'][$key]['areaIdPath'],
                    $rs['data'][$key]['timeUsers'],
                    $rs['data'][$key]['timeScore'],
                    $rs['data'][$key]['serviceUsers'],
                    $rs['data'][$key]['serviceScore'],
                    $rs['data'][$key]['goodsUsers'],
                    $rs['data'][$key]['goodsScore'],
                    $rs['data'][$key]['totalUsers'],
                    $rs['data'][$key]['shopCompany']);
            $_rec = [];
            foreach ($rs['data'][$key]['rec'] as $k1 => $v1) {
                $_rec[$k1]['goodsId'] = $v1['goodsId'];
                $_rec[$k1]['goodsName'] = $v1['goodsName'];
                $_rec[$k1]['shopPrice'] = $v1['shopPrice'];
                $_rec[$k1]['goodsImg'] = WSTImg($v1['goodsImg'],3,'goodsLogo');
            }
            $rs['data'][$key]['rec'] = $_rec;
    	}
    	echo jsonReturn('数据请求成功',1,$rs);
    	die;
    }

}
