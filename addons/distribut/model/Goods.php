<?php
namespace addons\distribut\model;
use shangtao\common\model\Goods as CGoods;
use think\Db;
/**
 * 商品类
 */
class Goods extends CGoods{
     
	
	/**
	 * 获取分页商品记录
	 */
	public function pageQuery($goodsCatIds = []){
		//查询条件
		$isStock = input('isStock/d');
		$isNew = input('isNew/d');
		$keyword = input('keyword');
		$isFreeShipping = input('isFreeShipping/d');
		$where = $where2 = $where3 = [];
		$where['goodsStatus'] = 1;
		$where['g.dataFlag'] = 1;
		$where['isSale'] = 1;
		$where['isDistribut'] = 1;
		if($keyword!='')$where[] = ['goodsName','like','%'.$keyword.'%'];
		//属性筛选
		$goodsIds = $this->filterByAttributes();
		if(!empty($goodsIds))$where[] = ['goodsId','in',$goodsIds];
		// 发货地
		$areaId = (int)input('areaId');
		if($areaId>0)$where['areaId'] = $areaId;
		//排序条件
		$orderBy = input('orderBy/d',0);
		$orderBy = ($orderBy>=0 && $orderBy<=4)?$orderBy:0;
		$order = (input('order/d',0)==1)?1:0;
		$pageBy = ['saleNum','shopPrice','appraiseNum','visitNum','saleTime'];
		$pageOrder = ['asc','desc'];
		if($isStock==1)$where[] = ['goodsStock','>',0];
		if($isNew==1)$where['isNew'] = 1;
		if($isFreeShipping==1)$where['isFreeShipping'] = 1;
		if(!empty($goodsCatIds))$where[] = ['goodsCatIdPath','like',implode('_',$goodsCatIds).'_%'];
	    $sprice = input("param.sprice");//开始价格
	    $eprice = input("param.eprice");//结束价格
		if($sprice!='' && $eprice!=''){
	    	$where[] = ['g.shopPrice','between',[(int)$sprice,(int)$eprice]];
	    }elseif($sprice!=''){
	    	$where[] = ['g.shopPrice','>=',(int)$sprice];
		}elseif($eprice!=''){
			$where[] = ['g.shopPrice','<=',(int)$eprice];
		}
		$list = Db::name("goods")->alias('g')->join("__SHOPS__ s","g.shopId = s.shopId")
			->where($where)
			->field('goodsId,goodsName,goodsSn,goodsStock,saleNum,shopPrice,marketPrice,isSpec,goodsImg,appraiseNum,visitNum,s.shopId,shopName')
			->order($pageBy[$orderBy]." ".$pageOrder[$order].",goodsId asc")
			->paginate(input('pagesize/d'))->toArray();

		return $list;
	}
	/**
	 * 获取价格范围
	 */
	public function getPriceGrade($goodsCatIds = []){
		$isStock = input('isStock/d');
		$isNew = input('isNew/d');
		$keyword = input('keyword');
		$isFreeShipping = input('isFreeShipping/d');
		$where = $where2 = $where3 = [];
		$where['goodsStatus'] = 1;
		$where['g.dataFlag'] = 1;
		$where['isSale'] = 1;
		if($keyword!='')$where[] = ['goodsName','like','%'.$keyword.'%'];
		$areaId = (int)input('areaId');
		if($areaId>0)$where['areaId'] = $areaId;
        //属性筛选
		$goodsIds = $this->filterByAttributes();
		if(!empty($goodsIds))$where[] = ['goodsId','in',$goodsIds];
		//排序条件
		$orderBy = input('orderBy/d',0);
		$orderBy = ($orderBy>=0 && $orderBy<=4)?$orderBy:0;
		$order = (input('order/d',0)==1)?1:0;
		$pageBy = ['saleNum','shopPrice','appraiseNum','visitNum','saleTime'];
		$pageOrder = ['asc','desc'];
		if($isStock==1)$where[] = ['goodsStock','>',0];
		if($isNew==1)$where['isNew'] = 1;
		if($isFreeShipping==1)$where['isFreeShipping'] = $isFreeShipping;
		if(!empty($goodsCatIds))$where[] = ['goodsCatIdPath','like',implode('_',$goodsCatIds).'_%'];
		$sprice = input("param.sprice");//开始价格
	    $eprice = input("param.eprice");//结束价格
	    if($sprice!='' && $eprice!=''){
	    	$where[] = ['g.shopPrice','between',[(int)$sprice,(int)$eprice]];
	    }elseif($sprice!=''){
	    	$where[] = ['g.shopPrice','>=',(int)$sprice];
		}elseif($eprice!=''){
			$where[] = ['g.shopPrice','<=',(int)$eprice];
		}
		$rs = Db::name("goods")->alias('g')->join("__SHOPS__ s","g.shopId = s.shopId",'inner')
			->where($where)
			->field('min(shopPrice) minPrice,max(shopPrice) maxPrice')->find();
		
		if($rs['maxPrice']=='')return;
		$minPrice = 0;
		$maxPrice = $rs['maxPrice'];
		$pavg5 = ($maxPrice/5);
		$prices = array();
    	$price_grade = 0.0001;
        for($i=-2; $i<= log10($maxPrice); $i++){
            $price_grade *= 10;
        }
    	//区间跨度
        $span = ceil(($maxPrice - $minPrice) / 8 / $price_grade) * $price_grade;
        if($span == 0){
            $span = $price_grade;
        }
		for($i=1;$i<=8;$i++){
			$prices[($i-1)*$span."_".($span * $i)] = ($i-1)*$span."-".($span * $i);
			if(($span * $i)>$maxPrice) break;
		}

		return $prices;
	}


	/**
	 * 获取符合筛选条件的商品ID
	 */
	public function filterByAttributes(){
		$vs = input('vs');
		if($vs=='')return [];
		$vs = explode(',',$vs);
		$goodsIds = [];
		$prefix = config('database.prefix');
		//循环遍历每个属性相关的商品ID
		foreach ($vs as $v){
			$goodsIds2 = [];
			$attrVal = input('v_'.(int)$v);
			if($attrVal=='')continue;
			$sql = "select goodsId goodsId from ".$prefix."goods_attributes
	    			where attrId=".(int)$v." and find_in_set('".$attrVal."',attrVal) ";
			$rs = Db::query($sql);
			if(!empty($rs)){
				foreach ($rs as $vg){
					$goodsIds2[] = $vg['goodsId'];
				}
			}
			//如果有一个属性是没有商品的话就不需要查了
			if(empty($goodsIds2))return [-1];
			//第一次比较就先过滤，第二次以后的就找集合
			if(empty($goodsIds)){
				$goodsIds = $goodsIds2;
			}else{
				$goodsIds = array_intersect($goodsIds,$goodsIds2);
			}
		}
		return $goodsIds;
	}
}
