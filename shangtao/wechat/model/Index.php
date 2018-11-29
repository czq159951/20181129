<?php
namespace shangtao\wechat\model;
use shangtao\common\model\Tags as T;
use think\Db;
/**
 * 默认类
 */
class Index extends Base{
	/**
	 * 楼层
	 */
	public function pageQuery(){
		$limit = (int)input('post.currPage');
		if($limit>9)return;
		$cacheData = cache('WX_CATS_ADS'.$limit);
		if($cacheData)return $cacheData;
		$rs = Db::name('goods_cats')->where(['dataFlag'=>1,'isShow'=>1,'parentId'=>0,'isFloor'=>1])->field('catId,catName')->order('catSort asc,catId asc')->limit($limit,1)->select();
		if($rs){
			$rs= $rs[0];
			$t = new T();
			$rs['ads'] = $t->listAds('wx-ads-'.$limit,'1');
			$rs['goods'] = Db::name('goods')->alias('g')->join('__RECOMMENDS__ r','g.goodsId=r.dataId')->join('__GOODS_SCORES__ gs','gs.goodsId=g.goodsId')
			->where(['r.goodsCatId'=>$rs['catId'],'g.isSale'=>1,'g.dataFlag'=>1,'g.goodsStatus'=>1,'r.dataSrc'=>0,'r.dataType'=>1])
			->field('g.goodsId,g.goodsName,g.goodsImg,g.shopPrice,g.marketPrice,g.saleNum,gs.totalScore,gs.totalUsers')->order('r.dataSort asc')->select();
			if(empty($rs['goods'])){
				$rs['goods'] = Db::name('goods')->alias('g')->join('__GOODS_SCORES__ gs','gs.goodsId=g.goodsId')
				->where([['g.goodsCatIdPath','like',$rs['catId'].'_%'],['g.isSale','=',1],['g.dataFlag','=',1],['g.goodsStatus','=',1],['g.isHot','=',1]])
				->field('g.goodsId,g.goodsName,g.goodsImg,g.shopPrice,g.marketPrice,g.saleNum,gs.totalScore,gs.totalUsers')
				->order('saleNum desc,goodsId asc')->select();
			}
			if($rs['goods']){
				foreach ($rs['goods'] as $key =>$v){
					$rs['goods'][$key]['praiseRate'] = ($v['totalScore']>0)?(sprintf("%.2f",$v['totalScore']/($v['totalUsers']*15))*100).'%':'100%';
				}
			}
			$rs['currPage'] = $limit;
		}
		cache('WX_CATS_ADS'.$limit,$rs,86400);
		return $rs;
	}
	/**
	* 获取系统消息
	*/
	function getSysMsg($msg='',$order='',$follow='',$history=''){
		$data = [];
		$userId = (int)session('WST_USER.userId');
		if($msg!=''){
			$data['message']['num'] = Db::name('messages')->where(['receiveUserId'=>$userId,'msgStatus'=>0,'dataFlag'=>1])->count();
		}
		if($order!=''){
			$data['order']['waitPay'] = Db::name('orders')->where(['userId'=>$userId,'orderStatus'=>-2,'dataFlag'=>1])->count();
			$data['order']['waitSend'] = Db::name('orders')->where(['userId'=>$userId,'orderStatus'=>0,'dataFlag'=>1])->count();
			$data['order']['waitReceive'] = Db::name('orders')->where(['userId'=>$userId,'orderStatus'=>1,'dataFlag'=>1])->count();
			$data['order']['waitAppraise'] = Db::name('orders')->where(['userId'=>$userId,'orderStatus'=>2,'isAppraise'=>0,'dataFlag'=>1])->count();
		}
		if($follow!=''){
			$data['follow']['goods'] = Db::name('favorites')->where(['userId'=>$userId,'favoriteType'=>0])->count();
			$data['follow']['shops'] = Db::name('favorites')->where(['userId'=>$userId,'favoriteType'=>1])->count();
		}
		if($history!=''){
			$history = cookie("wx_history_goods");
			$data['history']['num'] = count($history);
		}
		return $data;
	}
}
