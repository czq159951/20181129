<?php
namespace shangtao\app\model;
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
		$floor = (int)input('page');
		if($floor>10)return; // 最多取10层
		if($floor<=0)$floor=1;
		$cacheData = cache('APP_Floor'.$floor);
		if($cacheData)return $cacheData;
		$rs = Db::name('goods_cats')
			  ->where(['dataFlag'=>1,'isShow'=>1,'parentId'=>0,'isFloor'=>1])
			  ->field('catId,catName')
			  ->order('catSort asc,catId asc')
			  ->limit($floor-1,1)
			  ->select();
		if(empty($rs))return ['data'=>[],'last_page'=>0,'current_page'=>0];
		foreach($rs as $k=>$v){
			$t = new T();
			$rs[$k]['ads'] = $t->listAds('app-ads-'.($floor-1),'1');


			$rs[$k]['goods'] = Db::name('goods')
							   ->alias('g')
							   ->join('__RECOMMENDS__ r','g.goodsId=r.dataId')
							   ->join('__GOODS_SCORES__ gs','gs.goodsId=g.goodsId')
							   ->where(['r.goodsCatId'=>$v['catId'],
							   			'g.isSale'=>1,'g.dataFlag'=>1,
							   			'g.goodsStatus'=>1,
							   			'r.dataSrc'=>0,
							   			'r.dataType'=>1])
								->field('g.goodsId,g.goodsName,g.goodsImg,g.shopPrice,g.marketPrice,g.saleNum,gs.totalScore,gs.totalUsers')
								->order('r.dataSort asc')
								->select();
			if(empty($rs[$k]['goods'])){
				$rs[$k]['goods'] = Db::name('goods')
							   ->alias('g')
							   ->join('__GOODS_SCORES__ gs','gs.goodsId=g.goodsId')
							   ->where([['g.goodsCatIdPath','like',$v['catId'].'_%'],
							   			['g.isSale','=',1],
							   			['g.dataFlag','=',1],
							   			['g.goodsStatus','=',1],
							   			['g.isHot','=',1]])
							   ->field('g.goodsId,g.goodsName,g.goodsImg,g.shopPrice,g.marketPrice,g.saleNum,gs.totalScore,gs.totalUsers')
							   ->order('saleNum desc,goodsId asc')
							   ->select();
			}
			/*$rs[$k]['goods'] = Db::name('goods')
										->where([['goodsCatIdPath','like',$v['catId'].'_%'],['isSale','=',1],['goodsStatus','=',1],['dataFlag','=',1]])
										->field('goodsId,goodsName,goodsImg,shopPrice,saleNum,isFreeShipping')
										->order('isHot desc')
										->limit(6)
										->select();*/
		}
		$data = [];
		$data['data'] = $rs;
		$data['last_page'] = 10;// 总页数
		$data['current_page'] = $floor;// 当前页数
		cache('APP_Floor'.$floor,$data,86400);
		return $data;
	}
	/**
	 * 获取系统消息
	 */
	function getSysMsg($msg='',$order=''){
		$data = [];
		$userId = $this->getUserId();
		if($msg!=''){
			$data['message']['num'] = Db::name('messages')->where(['receiveUserId'=>$userId,'msgStatus'=>0,'dataFlag'=>1])->count();
		}
		if($order!=''){
			$data['order']['waitPay'] = Db::name('orders')->where(['userId'=>$userId,'orderStatus'=>-2,'dataFlag'=>1])->count();
			$data['order']['waitSend'] = Db::name('orders')->where(['userId'=>$userId,'orderStatus'=>0,'dataFlag'=>1])->count();
			$data['order']['waitReceive'] = Db::name('orders')->where(['userId'=>$userId,'orderStatus'=>1,'dataFlag'=>1])->count();
			$data['order']['waitAppraise'] = Db::name('orders')->where(['userId'=>$userId,'orderStatus'=>2,'isAppraise'=>0,'dataFlag'=>1])->count();
		}
		return $data;
	}
}
