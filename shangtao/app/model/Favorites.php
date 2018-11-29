<?php
namespace shangtao\app\model;
use think\Db;
use shangtao\common\model\Shops as CShops;
/**
 * 收藏类
 */
class Favorites extends Base{
	protected $pk = 'favoriteId';
	/**
	 * 关注的商品列表
	 */
	public function listGoodsQuery(){
	
		$userId = $this->getUserId();
		$page = Db::name("favorites")->alias('f')
    	->join('__GOODS__ g','g.goodsId = f.targetId','left')
    	->join('__SHOPS__ s','s.shopId = g.shopId','left')
    	->field('f.favoriteId,f.targetId,g.goodsId,g.goodsName,g.goodsImg,g.shopPrice,g.saleNum')
    	->where(['f.userId'=> $userId,'favoriteType'=> 0])
    	->order('f.favoriteId desc')
    	->select();
    	// g.marketPrice,g.appraiseNum,s.shopId,s.shopName
		/*foreach ($page as $key =>$v){
			//认证
			$shop = new CShops();
			$accreds = $shop->shopAccreds($v["shopId"]);
			$page[$key]['accreds'] = $accreds;
		}*/
		return $page;
	}
	/**
	 * 关注的店铺列表
	 */
	public function listShopQuery(){
		$userId = $this->getUserId();
		$page = Db::name("favorites")->alias('f')
		->join('__SHOPS__ s','s.shopId = f.targetId','left')
		->field('f.favoriteId,f.targetId,s.shopId,s.shopName,s.shopImg')
		->where(['f.userId'=> $userId,'favoriteType'=> 1])
		->order('f.favoriteId desc')
		->select();
		foreach ($page as $key =>$v){
			// 店铺评分
			$score = Db::name('shop_scores')->field('totalScore,totalUsers')->where(['shopId'=>$v['shopId']])->find();
			$page[$key]['totalScore'] = WSTScore($score["totalScore"]/3, $score["totalUsers"]);
			// 店铺分类
			$page[$key]['shopCat'] = Db::name('cat_shops')->alias('cs')
														  ->join('__GOODS_CATS__ gc','cs.catId=gc.catId and gc.dataFlag=1','left')
			               								  ->where([['shopId','in',$v['shopId']]])
			               								  ->value('gc.catName');

			//商品列表
			$goods = db('goods')->where(['dataFlag'=> 1,'isSale'=>1,'shopId'=> $v["shopId"]])
								->field('goodsId,goodsName,shopPrice,goodsImg')
								->limit(4)->order('saleTime desc')
								->select();
			$page[$key]['goods'] = $goods;
		}
		return $page;
	}
	/**
	 * 判断是否已关注
	 */
	public function checkFavorite($id,$type){
		$userId = $this->getUserId();
		$rs = $this->where(['userId'=>(int)$userId,'favoriteType'=>$type,'targetId'=>$id])->find();
		return empty($rs)?0:$rs['favoriteId'];
	}

	/**
	 * 取消关注
	 */
	public function del(){
		// 店铺id
		$id = input("param.id"); // 字符串 1,2,3,4,5,6
		// 类型 0:商品  1 ：店铺 
		$type = input("param.type/d");
		
		$userId = $this->getUserId();
		$ids = explode(',',$id);
		
		if(empty($ids))return WSTReturn("取消失败", -1);
		$rs = $this->where([['favoriteId','in',$ids],['favoriteType','=',$type],['userId','=',$userId]])->delete();
		if(false !== $rs){
			return WSTReturn("取消成功", 1);
		}else{
			return WSTReturn($this->getError(),-1);
		}
	}

	
	/**
	 * 新增关注
	 */
	public function add(){
	    $id = input("param.id/d");
		$type = input("param.type/d");
		
		$userId = $this->getUserId();
		//判断记录是否存在
		$isFind = false;
		if($type==0){
			$c = Db::name('goods')->where(['goodsStatus'=>1,'dataFlag'=>1,'goodsId'=>$id])->count();
			$isFind = ($c>0);
		}else{
			$c = Db::name('shops')->where(['shopStatus'=>1,'dataFlag'=>1,'shopId'=>$id])->count();
			$isFind = ($c>0);
		}
		if(!$isFind)return WSTReturn("关注失败，无效的关注对象", -1);
		$data = [];
		$data['userId'] = $userId;
		$data['favoriteType'] = $type;
		$data['targetId'] = $id;
		//判断是否已关注
		$rc = $this->where($data)->count();
		if($rc>0)return WSTReturn("关注成功", 1);
		$data['createTime'] = date('Y-m-d H:i:s');
		$rs = $this->save($data);
		if(false !== $rs){
			// 新增关注之后,返回favoriteId,用于取消时
			return WSTReturn("关注成功", 1,['fId'=>$this->favoriteId]);
		}else{
			return WSTReturn($this->getError(),-1);
		}
	}
	/**
	 * 关注数
	 */
	public function followNum($id,$type){
		$rs = $this->where(['favoriteType'=>$type,'targetId'=>$id])->count();
		return $rs;
	}
}
