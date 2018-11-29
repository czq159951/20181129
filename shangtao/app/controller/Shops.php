<?php
namespace shangtao\app\controller;
use shangtao\common\model\GoodsCats;
use shangtao\common\model\Tags;
use think\Db;
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
            WSTAllow($swiper[$k1],'adFile,adURL');
        }
        $data['swiper'] = $swiper;
    	// 域名
    	$data['domain'] = $this->domain();
    	echo json_encode(WSTReturn('店铺数据请求成功',1,$data));
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
        // 域名
        $data['domain'] = $this->domain();
        $data['shopAdtop'] = WSTConf('CONF.shopAdtop');
        echo json_encode(WSTReturn('店铺数据请求成功',1,$data));
        die;
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
        /*搜索数据*/
        $data['ct1'] = $ct1;//一级分类
        $data['ct2'] = $ct2;//二级分类
        $data['goodsName'] = urldecode($goodsName);//搜索

        // 是否已关注
        $data['isFavor'] = model('favorites')->checkFavorite($shopId,1);

        
        // 店主推荐
        $rec = model('Tags')->listShopGoods('recom',$shopId,4);
        $_rec = [];
        foreach($rec as $k=>$v){
            $_rec[$k]['goodsId'] = $v['goodsId'];
            $_rec[$k]['goodsName'] = $v['goodsName'];
            $_rec[$k]['shopPrice'] = $v['shopPrice'];
            $_rec[$k]['goodsImg'] = WSTImg($v['goodsImg'],3);
        }
        $data['rec'] = $_rec;
        $carts = model('carts')->getCartInfo();
        unset(
                $data['shop']['shopAddress'],
                $data['shop']['shopQQ'],
                $data['shop']['shopWangWang'],
                $data['shop']['serviceStartTime'],
                $data['shop']['serviceEndTime'],
                $data['shop']['shopKeeper'],
                $data['shop']['catshops'],
                $data['shop']['shopTitle'],
                $data['shop']['shopDesc'],
                $data['shop']['shopKeywords'],
                $data['shop']['shopHotWords'],
                $carts['list']);
        $data['carts'] = $carts;
        // 域名
        $data['domain'] = $this->domain();
        $data['shopAdtop'] = WSTConf('CONF.shopAdtop');
        $data['followNum'] = model('favorites')->followNum($shopId,1);
        echo json_encode(WSTReturn('店铺数据请求成功',1,$data));
        die;
    }
    /**
    * 获取店铺商品
    */
    public function getShopGoods(){
        $shopId = (int)input('shopId',1);
        $g = model('goods');
        $rs = $g->shopGoods($shopId);
        if(empty($rs['data']))return json_encode(WSTReturn('没有相关商品',-1));
        foreach($rs['data'] as $k=>$v){
            $rs['data'][$k]['goodsImg'] = WSTImg($v['goodsImg'],2);
        }
        // 域名
        $rs['domain'] = $this->domain();
        // 购物车信息
        $carts = model('carts')->getCartInfo();
        // 删除无用字段
        unset($carts['list']);
        $data['carts'] = $carts;
        return json_encode(WSTReturn('请求成功',1,$rs));
    }

    /**
    * 自营店铺
    */
    public function selfShop(){
        $s = model('shops');
        $data['shop'] = $s->getShopInfo(1);
        if(empty($data['shop']))return json_encode(WSTReturn('暂无店铺数据',-1));
        // 删除无用字段
        unset(
                $data['shop']['shopAddress'],
                $data['shop']['shopQQ'],
                $data['shop']['shopWangWang'],
                $data['shop']['serviceStartTime'],
                $data['shop']['serviceEndTime'],
                $data['shop']['shopKeeper'],
                $data['shop']['catshops'],
                $data['shop']['shopTitle'],
                $data['shop']['shopDesc'],
                $data['shop']['shopKeywords'],
                $data['shop']['shopHotWords']);
        // 店长推荐
        $data['rec'] = $s->getRecGoods('rec');
        // 热销商品
        $data['hot'] = $s->getRecGoods('hot');
        // 是否已关注
        $data['isFavor'] = model('favorites')->checkFavorite(1,1);
        // 域名
        $data['domain'] = $this->domain();
        $data['shopAdtop'] = WSTConf('CONF.shopAdtop');
        $data['followNum'] = model('favorites')->followNum(1,1);
        echo json_encode(WSTReturn('店铺数据请求成功',1,$data));
        die;
    }
    public function getFloorData(){
        $s = model('shops');
        $rs = $s->getFloorData();
        echo json_encode(WSTReturn('success',1,$rs));
    }
    // 店铺分类
    public function getShopCats(){
        $shopId = (int)input('shopId');
        $rs = model('ShopCats')->getShopCats($shopId);
        if(empty($rs))return json_encode(WSTReturn('暂无店铺商品分类数据',-1));
        return json_encode(WSTReturn('数据请求成功',1,$rs));
    }

    /**
     * 店铺街列表
     */
    public function pageQuery(){
    	$m = model('shops');
    	$rs = $m->pageQuery();
    	foreach ($rs['data'] as $key =>$v){
    		$rs['data'][$key]['shopImg'] = WSTImg($v['shopImg'],3);
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
                $_rec[$k1]['goodsImg'] = WSTImg($v1['goodsImg'],3);
            }
            $rs['data'][$key]['rec'] = $_rec;
    	}
    	echo json_encode(WSTReturn('数据请求成功',1,$rs));
    	die;
    }

    /**
     * 获取指定店铺商品
     */
    public function listShopGoods(){
        $shopId = (int)input('shopId');
        $type = ['0'=>'recom','1'=>'new','2'=>'hot','3'=>'best'];
        $num = 5;
        $cache = 0;
        /*$cacheData = cache('App_SHOP_GOODS_'.$type."_".$shopId);
        if($cacheData)return $cacheData;*/
        foreach ($type as $key => $value) {
        $types = ['recom'=>'isRecom','new'=>'isNew','hot'=>'isHot','best'=>'isBest'];
        $order = ['recom'=>'saleNum desc,goodsId asc','new'=>'saleTime desc,goodsId asc','hot'=>'saleNum desc,goodsId asc','best'=>'saleNum desc,goodsId asc'];
        $where = [];
        $where['shopId'] = $shopId;
        $where['isSale'] = 1;
        $where['goodsStatus'] = 1; 
        $where['dataFlag'] = 1; 
        $where[$types[$value]] = 1;

        $goods[$value] = Db::name('goods')
                   ->where($where)->field('goodsId,goodsName,goodsImg,goodsSn,goodsStock,saleNum,shopPrice,marketPrice,isSpec,appraiseNum,visitNum')
                   ->order($order[$value])->limit($num)->select();     
        $ids = [];
        foreach($goods[$value] as $key =>$v){
            if($v['isSpec']==1)$ids[] = $v['goodsId'];
        }
        if(!empty($ids)){
            $specs = [];
            $rs = Db::name('goods_specs gs ')->where([['goodsId','in',$ids],['dataFlag','=',1]])->order('id asc')->select();
            foreach ($rs as $key => $v){
                $specs[$v['goodsId']] = $v;
            }
            foreach($goods[$value] as $key =>$v){
                if(isset($specs[$v['goodsId']]))
                $goods[$value][$key]['specs'] = $specs[$v['goodsId']];
            }
        }
        }  
        $goods['domain'] = $this->domain();
       //cache('App_SHOP_GOODS_'.$type."_".$shopId,$goods,$cache);
        echo json_encode(WSTReturn('数据请求成功',1,$goods));
        exit;
    }
    public function map(){
        $longitude = input('longitude');
        $latitude = input('latitude');
        $shopName = input('shopName');
        $this->assign('longitude',$longitude);
        $this->assign('latitude',$latitude);
        $this->assign('shopName',$shopName);
        return $this->fetch('shop_map');
    }

}
