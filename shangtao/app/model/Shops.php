<?php
namespace shangtao\app\model;
use shangtao\common\model\Shops as CShops;
use think\Db;
/**
 * 门店类
 */
class Shops extends CShops{
    /**
     * 店铺街列表
     */
    public function pageQuery(){
        $lng = input("longitude");
        $lat = input("latitude");
        if($lng=='' && $lat==''){
            $lng = 0;
            $lat = 0;
        }
       /* if($lng=='' && $lat==''){
            $location = WSTIpLocation();
            $lng = $location['longitude'];
            $lat = $location['latitude'];
        }*/
    	$catId = (int)input("id");
    	$keyword = input("keyword");
    	$condition = input("condition");
    	$desc = input("desc");
        $totalScore = input('totalScore');
    	$datas = array('sort'=>array('1'=>'ss.totalScore/ss.totalUsers'),'desc'=>array('desc','asc'));
    	$datas1 = array('sort'=>array('1'=>'distince'),'desc'=>array('desc','asc'));
        $rs = $this->alias('s');
        $tol = $this->alias('s');
    	$where = [];
    	$where[] = ['s.dataFlag','=',1];
    	$where[] = ['s.shopStatus','=',1];
        $where[] = ['s.applyStatus','=',2];
    	if($keyword!='')$where[] = ['s.shopName','like','%'.$keyword.'%'];
    	if($catId>0){
    		$rs->join('__CAT_SHOPS__ cs','cs.shopId = s.shopId','left');
            $tol->join('__CAT_SHOPS__ cs','cs.shopId = s.shopId','left');
    		$where[] = ['cs.catId','=',$catId];
    	}
        $order = ['distince'=>'asc'];
    	if($condition == 1){
    		$order = [$datas['sort'][$condition]=>$datas['desc'][$desc]];
    	}
        if($condition == 2){
            $order = [$datas1['sort'][1]=>$datas['desc'][$desc]];
        }
        $shopIds = $this->filterByCondition();
        if(!empty($shopIds)){
            $where[] = $where1[] = ['s.shopId','in',$shopIds];
        }
        $a = [];
        if($totalScore != ''){
            $totalScore = explode('_',$totalScore);
            $maxScore = (float)(($totalScore[1])*5/100);
            $minScore = (float)(($totalScore[0])*5/100);
            //$a['maxScore'] = $maxScore;
            //$a['minScore1'] = $minScore;
            $where[] = ['(ss.totalScore/3)/ss.totalUsers','>=',$minScore];
            $where[] = ['(ss.totalScore/3)/(case when ss.totalUsers = 0 then 1 else ss.totalUsers end)','<=',$maxScore];
           // $where[] = ['(ss.totalScore/3)/ss.totalUsers','=',null];
        }
            $rs->field('(ss.totalScore/3)/(case when ss.totalUsers = 0 then 1 else ss.totalUsers end) as aa');
    	$page = $rs->join('__SHOP_SCORES__ ss','ss.shopId = s.shopId','left')
    	->where($where)->order($order)
    	->field('s.shopId,s.shopImg,s.isSelf,s.shopName,s.shopCompany,ss.totalScore,ss.totalUsers,ss.goodsScore,ss.goodsUsers,ss.serviceScore,ss.serviceUsers,ss.timeScore,ss.timeUsers,s.areaIdPath')
        ->field("round(6378.138*2*asin(sqrt(pow(sin( (".$lat."*pi()/180-s.latitude*pi()/180)/2),2)+cos(".$lat."*pi()/180)*cos(s.latitude*pi()/180)* pow(sin( (".$lng."*pi()/180-s.longitude*pi()/180)/2),2)))*1000)/1000 as distince")
    	->paginate()
        ->toArray();

        $totalShop = $tol->join('__SHOP_SCORES__ ss','ss.shopId = s.shopId','left')
                        ->where($where)
                        ->field('ss.totalScore,ss.totalUsers')
                        ->select();
        foreach ($page['data'] as $key =>$v){
            //商品列表
            $goods = model('Tags')->listShopGoods('recom',$v['shopId'],4);
            $page['data'][$key]['goods'] = $goods;
        }
        //$page['a'] = $a;

    	if(empty($page['data']))return $page;
    	$shopIds = [];
    	$areaIds = [];
        foreach ($totalShop as $key => $v) {
            if($v["totalUsers"] != 0){
               $totalScores[] = round(($v["totalScore"]/3)/$v["totalUsers"],8);
            }else{
               $totalScores[] = 5;
            }
        }
    	foreach ($page['data'] as $key =>$v){
    		$shopIds[] = $v['shopId'];
    		$tmp = explode('_',$v['areaIdPath']);
    		$areaIds[] = $tmp[1];
    		$page['data'][$key]['areaId'] = $tmp[1];
    		//总评分
            $page['data'][$key]['totalScore'] = WSTScore($v["totalScore"]/3, $v["totalUsers"]==0?1: $v["totalUsers"]);
    		$page['data'][$key]['goodsScore'] = WSTScore($v['goodsScore'],$v['goodsUsers']);
    		$page['data'][$key]['serviceScore'] = WSTScore($v['serviceScore'],$v['serviceUsers']);
    		$page['data'][$key]['timeScore'] = WSTScore($v['timeScore'],$v['timeUsers']);
		}
         $maxScore = max($totalScores)<5?((max($totalScores)/5)*100):100;
        $minScore = min($totalScores)>0?(((min($totalScores)-0.01)/5)*100):0;
        $scores = [];
        $scores['attrName'] ='好评率';
        $scores['attrId'] = 1;
        //区间跨度
        $span = ($maxScore - $minScore) / 3;
        for($i=1;$i<=3;$i++){
            if(($minScore+$i*$span)>100) {
                $scores['attrVal'][($minScore+($i-1)*$span)."_".(100)] = round(($minScore+($i-1)*$span),0)."%-".(100)."%";
                break;
            }
            $scores['attrVal'][($minScore+($i-1)*$span)."_".($minScore+$i*$span)] = round(($minScore+($i-1)*$span),0)."%-".round(($minScore+$i*$span),0)."%";
        }
        if($totalScore == ''){
         $page['screen'][] = $scores;
        }
        $page['minScore'] = $minScore.'-'.$maxScore;
		$rccredMap = [];
		$goodsCatMap = [];
		$areaMap = [];
		//认证、地址、分类、4件店铺推荐商品
		if(!empty($shopIds)){
			$rccreds = Db::name('shop_accreds')->alias('sac')->join('__ACCREDS__ a','a.accredId=sac.accredId and a.dataFlag=1','left')
			             ->where([['shopId','in',$shopIds]])->field('sac.shopId,accredName,accredImg,a.accredId')->select();
			$accredIds = [];
            $accreds = [];
            $accreds['attrName'] = '店铺服务';
            $accreds['attrId'] = 2;
            foreach ($rccreds as $v){
				$rccredMap[$v['shopId']][] = $v;
                if(!in_array($v['accredId'],$accredIds)){
                    $accredIds[] = $v['accredId'];
                    $accreds['attrVal'][$v['accredId']] = $v['accredName'];
                }
			}
            if(input('accredId') == ''){
              $page['screen'][] = $accreds;
            }
			$goodsCats = Db::name('cat_shops')->alias('cs')->join('__GOODS_CATS__ gc','cs.catId=gc.catId and gc.dataFlag=1','left')
			               ->where([['shopId','in',$shopIds]])->field('cs.shopId,gc.catName')->select();
		    foreach ($goodsCats as $v){
				$goodsCatMap[$v['shopId']][] = $v['catName'];
			}

            

		}
		foreach ($page['data'] as $key =>$v){
            $page['data'][$key]['lng'] = $lng;
            $page['data'][$key]['lat'] = $lat;
			$page['data'][$key]['accreds'] = (isset($rccredMap[$v['shopId']]))?$rccredMap[$v['shopId']]:[];
			$page['data'][$key]['catshops'] = (isset($goodsCatMap[$v['shopId']]))?$goodsCatMap[$v['shopId']][0]:'';// 取第一个分类为主营
			// 4件推荐商品
            $page['data'][$key]['rec'] = model('Tags')->listShopGoods('recom',$v['shopId'],4);
		}
    	return $page;
    }
    /**
     * 获取卖家中心信息
     */
    public function getShopSummary(){
        $shopId = (int)input('shopId',1);
        $shop = $this->alias('s')->join('__SHOP_SCORES__ cs','cs.shopId = s.shopId','left')->join('__SHOP_CONFIGS__ sc','sc.shopId = s.shopId','left')
                   ->where(['s.shopId'=>$shopId,'dataFlag'=>1])
        ->field('s.shopId,shopImg,shopName,shopkeeper,shopAddress,shopQQ,shopTel,serviceStartTime,serviceEndTime,isInvoice,invoiceRemarks,cs.*,sc.shopMoveBanner')
        ->find();
        //评分
        $scores['totalScore'] = WSTScore($shop['totalScore'],$shop['totalUsers']);
        $scores['goodsScore'] = WSTScore($shop['goodsScore'],$shop['goodsUsers']);
        $scores['serviceScore'] = WSTScore($shop['serviceScore'],$shop['serviceUsers']);
        $scores['timeScore'] = WSTScore($shop['timeScore'],$shop['timeUsers']);
        WSTUnset($shop, 'totalUsers,goodsUsers,serviceUsers,timeUsers');
        $shop['scores'] = $scores;
        //认证
        $accreds = $this->shopAccreds($shopId);
        $shop['accreds'] = $accreds;
        return ['shop'=>$shop];
    }
    /**
    * 自营店铺楼层
    */
    public function getFloorData(){
        $limit = (int)input('page');
        if($limit>6)return;
        if($limit<=0)$limit=1;
        $cacheData = cache('APP_SHOP_FLOOR'.$limit);
        if($cacheData)return $cacheData;
        $rs = Db::name('shop_cats')->where(['dataFlag'=>1,'isShow'=>1,'parentId'=>0,'shopId'=>1])->field('catId,catName')->order('catSort asc')->limit($limit-1,1)->select();
        if($rs){
            $rs= $rs[0];
            $goods = Db::name('goods')->where(['shopCatId1'=>$rs['catId'],'dataFlag'=>1,'isSale'=>1,'goodsStatus'=>1])->field('goodsId,goodsName,goodsImg,shopPrice,saleNum,isFreeShipping')->order('isHot desc')->limit(6)->select();
            // 图片转换
            foreach($goods as $k1=>$v1){
                $goods[$k1]['goodsImg'] = WSTImg($v1['goodsImg'],2);
            }
            $rs['goods'] = $goods;
            $rs['current_page'] = $limit;
        }
        // 分类下没有商品
       // if(empty($rs['goods']))return;
        $rs['total'] = 6;// 最多取6个楼层
        cache('APP_SHOP_FLOOR'.$limit,$rs,86400);
        return $rs;
    }
    /**
    * 根据订单id 获取店铺名称
    */
    public function getShopName($oId){
        return $this->alias('s')
                    ->join('__ORDERS__ o',"s.shopId=o.shopId",'inner')
                    ->where("o.orderId=$oId")
                    ->value('s.shopName');

    }
    /**
    * 根据userId 获取shopId
    */
    public function getShopId($userId){
        return Db::name('shop_users')->where("userId=$userId")->value('shopId');
    }
      /*过滤条件获取符合店铺Id*/
    public function filterByCondition(){
        $accredId = input('accredId');
        $res = [];
        $shopIds = Db::name('shop_accreds')->where(['accredId'=>$accredId])->field('shopId')->select();
        foreach ($shopIds as $key => $value) {
            $res[] = $value['shopId'];
        }
        return $res;
    }
}
