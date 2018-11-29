<?php
namespace shangtao\common\model;
use think\Db;
/**
 */
class Goods extends Base{
	protected $pk = 'goodsId';
	/**
	 * 获取店铺商品列表
	 */
	public function shopGoods($shopId){
		$msort = input("param.msort/d");
		$mdesc = input("param.mdesc/d");
		$order = array('g.saleTime'=>'desc');
		$orderFile = array('1'=>'g.isHot','2'=>'g.saleNum','3'=>'g.shopPrice','4'=>'g.shopPrice','5'=>'(gs.totalScore/gs.totalUsers)','6'=>'g.saleTime');
		$orderSort = array('0'=>'asc','1'=>'desc');
		if($msort>0){
			$order = array($orderFile[$msort]=>$orderSort[$mdesc]);
		}
		$goodsName = input("param.goodsName");//搜索店鋪名
		$words = $where = $where2 = $where3 = $where4 = [];
		if($goodsName!=""){
			$words = explode(" ",$goodsName);
		}
		if(!empty($words)){
			$sarr = array();
			foreach ($words as $key => $word) {
				if($word!=""){
					$sarr[] = "g.goodsName like '%$word%'";
				}
			}
			$where4 = implode(" or ", $sarr);
		}

		$sprice = input("param.sprice");//开始价格
		$eprice = input("param.eprice");//结束价格
		if($sprice!="")$where2 = "g.shopPrice >= ".(float)$sprice;
		if($eprice!="")$where3 = "g.shopPrice <= ".(float)$eprice;
		$ct1 = input("param.ct1/d");
		$ct2 = input("param.ct2/d");
		if($ct1>0)$where['shopCatId1'] = $ct1;
		if($ct2>0)$where['shopCatId2'] = $ct2;
		$goods = Db::name('goods')->alias('g')
		->join('__GOODS_SCORES__ gs','gs.goodsId = g.goodsId','left')
		->where(['g.shopId'=>$shopId,'g.isSale'=>1,'g.goodsStatus'=>1,'g.dataFlag'=>1])
		->where($where)->where($where2)->where($where3)->where($where4)
		->field('g.goodsId,g.goodsName,g.goodsImg,g.shopPrice,g.marketPrice,g.saleNum,g.appraiseNum,g.goodsStock,g.isFreeShipping,gallery')
		->order($order)
		->paginate((input('pagesize/d')>0)?input('pagesize/d'):16)->toArray();
		return  $goods;
	}

}
