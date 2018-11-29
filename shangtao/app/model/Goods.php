<?php
namespace shangtao\app\model;
use shangtao\common\model\Goods as CGoods;
use think\Db;
/**
 * 商品类
 */
class Goods extends CGoods{
	/*********************************************************  商家操作商品start ******************************************************************/
	/**
      *  上架商品列表
      */
	public function saleByPage($shopId){
		$where = [];
		$where['shopId'] = $shopId;
		$where['goodsStatus'] = 1;
		$where['dataFlag'] = 1;
		$where['isSale'] = 1;
		$goodsType = input('goodsType');
		if($goodsType!='')$where['goodsType'] = (int)$goodsType;
		$c1Id = (int)input('cat1');
		$c2Id = (int)input('cat2');
		$goodsName = input('goodsName');
		if($goodsName != ''){
			$where[] = ['goodsName','like',"%$goodsName%"];
		}
		if($c2Id!=0 && $c1Id!=0){
			$where['shopCatId2'] = $c2Id;
		}else if($c1Id!=0){
			$where['shopCatId1'] = $c1Id;
		}
		$where['m.shopId'] = $shopId;
		$rs = $this->alias('m')
		    ->where($where)
			->field('goodsId,goodsName,goodsImg,goodsType,goodsSn,isSale,isBest,isHot,isNew,isRecom,goodsStock,saleNum,shopPrice,isSpec')
			->order('saleTime', 'desc')
			->paginate(input('pagesize/d'))->toArray();
		return $rs;
	}

	/**
	 * 仓库中的商品
	 */
    public function storeByPage($shopId){
    	$where['shopId']=$shopId;
		$where['dataFlag'] = 1;
		$where['isSale'] = 0;
		$goodsType = input('goodsType');
		if($goodsType!='')$where['goodsType'] = (int)$goodsType;
		$c1Id = (int)input('cat1');
		$c2Id = (int)input('cat2');
		$goodsName = input('goodsName');
		if($goodsName != ''){
			$where[] = ['goodsName','like',"%$goodsName%"];
		}
		if($c2Id!=0 && $c1Id!=0){
			$where['shopCatId2'] = $c2Id;
		}else if($c1Id!=0){
			$where['shopCatId1'] = $c1Id;
		}
		$rs = $this->alias('m')
		    ->where($where)
		    ->where('goodsStatus','<>',-1)
			->field('goodsId,goodsName,goodsImg,goodsType,goodsSn,isSale,isBest,isHot,isNew,isRecom,goodsStock,saleNum,shopPrice,isSpec')
			->order('saleTime', 'desc')
			->paginate(input('pagesize/d'))->toArray();
        foreach ($rs['data'] as $key => $v){
			$rs['data'][$key]['verfiycode'] =  WSTShopEncrypt($shopId);
		}
		return $rs;
	}

	/**
	 *  预警库存列表
	 */
	public function stockByPage($shopId){
		$where = [];
		$c1Id = (int)input('cat1');
		$c2Id = (int)input('cat2');
		if($c1Id!=0)$where[] = " shopCatId1=".$c1Id;
		if($c2Id!=0)$where[] = " shopCatId2=".$c2Id;
		$where[] = " g.shopId = ".$shopId;
		$prefix = config('database.prefix');
		$sql1 = 'SELECT g.goodsId,g.goodsName,g.goodsType,g.goodsImg,gs.specStock goodsStock ,gs.warnStock warnStock,g.isSpec,gs.productNo,gs.id,gs.specIds,g.isSale
                    FROM '.$prefix.'goods g inner JOIN '.$prefix.'goods_specs gs ON gs.goodsId=g.goodsId and gs.specStock <= gs.warnStock and gs.warnStock>0
                    WHERE g.dataFlag = 1 and '.implode(' and ',$where);
		
		$sql2 = 'SELECT g.goodsId,g.goodsName,g.goodsType,g.goodsImg,g.goodsStock,g.warnStock,g.isSpec,g.productNo,0 as id,"" as specIds,g.isSale
                    FROM '.$prefix.'goods g 
                    WHERE g.dataFlag = 1  and isSpec=0 and g.goodsStock<=g.warnStock 
                    and g.warnStock>0 and '.implode(' and ',$where);
		$page = (int)input('post.'.config('paginate.var_page'));
		$page = ($page<=0)?1:$page;
		$pageSize = 15;
		$start = ($page-1)*$pageSize;
		$sql = $sql1." union ".$sql2;
		$sqlNum = 'select count(*) wstNum from ('.$sql.") as c";
		$sql = 'select * from ('.$sql.') as c order by isSale desc limit '.$start.','.$pageSize;
		$rsNum = Db::query($sqlNum);
		$rsdata = Db::query($sql);
		$rs = WSTPager((int)$rsNum[0]['wstNum'],$rsdata,$page,$pageSize);
		if(empty($rs['data']))return $rs;
		$specIds = [];
		foreach ($rs['data'] as $key =>$v){
			$specIds[$key] = explode(':',$v['specIds']);
			$rss = Db::name('spec_items')->alias('si')
			->join('__SPEC_CATS__ sc','sc.catId=si.catId','left')
			->where('si.shopId = '.$shopId.' and si.goodsId = '.$v['goodsId'])
			->where([['si.itemId','in',$specIds[$key]]])
			->field('si.itemId,si.itemName,sc.catId,sc.catName')
			->select();
			$rs['data'][$key]['spec'] = $rss;
		}
		return $rs;
	}
	/**
	 * 删除商品
	 */
	public function del($shopId){
	    $id = input('post.id/d');
		$data = [];
		$data['dataFlag'] = -1;
		Db::startTrans();
		try{
		    $result = $this->update($data,['goodsId'=>$id,'shopId'=>$shopId]);
	        if(false !== $result){
	        	WSTUnuseImage('goods','goodsImg',$id);
	        	WSTUnuseImage('goods','gallery',$id);
	        	// 商品描述图片
	        	$desc = $this->where('goodsId',$id)->value('goodsDesc');
				WSTEditorImageRocord(0, $id, $desc,'');
				model('common/carts')->delCartByUpdate($id);
				hook('afterChangeGoodsStatus',['goodsId'=>$id]);
				Db::commit();
	        	//标记删除购物车
	        	return WSTReturn("删除成功", 1);
	        }
		}catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('删除失败',-1);
	}
	/**
	 * 批量上(下)架商品
	 */
	public function changeSale($shopId){
		$ids = input('post.ids/a');
		$isSale = (int)input('post.isSale',1);
		//判断商品是否满足上架要求
		if($isSale==1){
			//0.核对店铺状态
	 		$shopRs = model('shops')->find($shopId);
	 		if($shopRs['shopStatus']!=1 || $shopRs['dataFlag']==-1){
	 			return 	WSTReturn('上架商品失败!您的店铺权限不能出售商品，如有疑问请与商城管理员联系。',-3);
	 		}
	 		//直接设置上架 返回受影响条数
	 		$where = [];
	 		$where[] = ['g.goodsId','in',$ids];
	 		$where[] = ['gc.dataFlag','=',1];
	 		$where[] = ['g.shopId','=',$shopId];
	 		$where[] = ['gc.isShow','=',1];
	 		$where[] = ['g.goodsImg','<>',''];
	 		$data = [];
	 		$data['isSale'] = 1;
			if(WSTConf("CONF.isGoodsVerify")==1){
				$data['goodsStatus'] = 0;
			}else{
				$data['goodsStatus'] = 1;
			}
			$rs = $this->alias('g')
				  ->join('__GOODS_CATS__ gc','g.goodsCatId=gc.CatId','inner')
				  ->where($where)->setField($data);	  
			if($rs!==false){
				//执行钩子事件
				foreach ($ids as $key => $gid) {
					hook('afterChangeGoodsStatus',['goodsId'=>$gid]);
			    }
				$status = ($rs==count($ids))?1:2;
				if($status==1){
					return WSTReturn('商品上架成功', 1,['num'=>$rs]);
				}else{
					return WSTReturn('已成功上架商品'.$rs.'件，请核对未能上架的商品信息是否完整。', 2,['num'=>$rs]);
				}
			}else{
	 			return WSTReturn('上架失败，请核对商品信息是否完整!', -2);
	 		}

		}else{
			$rs = $this->where([['goodsId','in',$ids],['shopId','=',$shopId]])->setField('isSale',0);
			if($rs !== false){
				//执行钩子事件
				foreach ($ids as $key => $gid) {
					hook('afterChangeGoodsStatus',['goodsId'=>$gid]);
			    }
				model('common/carts')->delCartByUpdate($ids);
				return WSTReturn('商品上架成功', 1);
			}else{
				return WSTReturn($this->getError(), -1);
			}
		}
	}
	/**
	* 修改商品状态
	*/
	public function changSaleStatus($shopId){
		$data = input('param.');
		$allowArr = ['isHot','isNew','isBest','isRecom'];
		$data = array_filter($data,function($key,$value) use ($allowArr){
			return in_array($value, $allowArr);
		},true);
		$id = (int)input('post.id');
		$rs = $this->where(["shopId"=>$shopId,'goodsId'=>$id])->update($data);
		if($rs!==false){
			return WSTReturn('设置成功',1);
		}
		return WSTReturn($this->getError(),-1);
	}



	/*********************************************************  商家操作商品end ******************************************************************/
	/**
	* 预加载商品
	*/
	public function preloadGoods(){
		$goodsId = (int)input('goodsId');
		$rs = $this->field('goodsName,goodsImg')->find($goodsId);
		if(empty($rs))return WSTReturn('商品不存在',-1);

		return $rs;
	}
	/**
	 * 获取列表
	 */
	public function pageQuery($goodsCatIds = []){
		//查询条件
		$keyword = input('keyword');
		$brandId = input('brandId/d');
		$isFreeShipping = input('isFreeShipping/d');
		$where = $where2 = $where3 = $where4 = [];
		$where[] = ['goodsStatus','=',1];
		$where[] = ['g.dataFlag','=',1];
		$where[] = ['isSale','=',1];
		if($keyword!='')$where2 = $this->getKeyWords($keyword);
		if($brandId>0)$where[] = ['g.brandId','=',$brandId];
		//排序条件
		$orderBy = input('condition/d',0);
		$orderBy = ($orderBy>=0 && $orderBy<=4)?$orderBy:0;
		$order = (input('desc/d',0)==1)?1:0;
		$pageBy = ['saleNum','shopPrice','visitNum','saleTime'];
		$pageOrder = ['desc','asc'];
		if($isFreeShipping==1)$where[] = ['isFreeShipping','=',1];

		//属性筛选
		$goodsIds = $this->filterByAttributes();
		//处理价格
		$minPrice = input("minPrice/d");//开始价格
		$maxPrice = input("maxPrice/d");//结束价格
		if($minPrice!="")$where3 = "g.shopPrice >= ".(float)$minPrice;
		if($maxPrice!="")$where4 = "g.shopPrice <= ".(float)$maxPrice;

		if(!empty($goodsIds))$where[] = ['g.goodsId','in',$goodsIds];
		if(!empty($goodsCatIds))$where[] = ['goodsCatIdPath','like',implode('_',$goodsCatIds).'_%'];
		$list = Db::name('goods')->alias('g')
		->join("__SHOPS__ s","g.shopId = s.shopId")
		->join('__GOODS_SCORES__ gs','gs.goodsId=g.goodsId')
		->where($where)->where($where2)->where($where3)->where($where4)
		->field('g.shopId,g.goodsId,g.goodsName,g.saleNum,g.shopPrice,g.goodsImg,g.isFreeShipping,gs.totalScore,gs.totalUsers')
		->order($pageBy[$orderBy]." ".$pageOrder[$order].",goodsId asc")
		->paginate(input('pagesize/d'))->toArray();
		return $list;
	}
    
	/**
	 * 关键字
	 */
	public function getKeyWords($name){
		$words = WSTAnalysis($name);
		if(!empty($words)){
			if(count($words)==1){
				return "g.goodsSerachKeywords LIKE '%$words[0]%' ";
			}else{
				$str = [];
				foreach ($words as $v){
					$str[] = " g.goodsSerachKeywords LIKE '%$v%' ";
				}
				return implode(" and ",$str);
			}
		}
		return "";
	}
	
	/**
	 * 获取商品资料在前台展示
	 */
	public function getBySale($goodsId){
		$key = input('key');
		// 浏览量
		$this->where('goodsId',$goodsId)->setInc('visitNum',1);
		$rs = Db::name('goods')->field('goodsDesc',true)->where(['goodsId'=>$goodsId,'dataFlag'=>1])->find();
		if(!empty($rs)){
			$rs['read'] = false;
			//判断是否可以公开查看
			$viKey = WSTShopEncrypt($rs['shopId']);
			if(($rs['isSale']==0 || $rs['goodsStatus']==0) && $viKey != $key)return [];
			if($key!='')$rs['read'] = true;
			//获取店铺信息
			$rs['shop'] = model('shops')->getBriefShop((int)$rs['shopId']);
			if(empty($rs['shop']))return [];
			$goodsCats = Db::name('cat_shops')->alias('cs')->join('__GOODS_CATS__ gc','cs.catId=gc.catId and gc.dataFlag=1','left')->join('__SHOPS__ s','s.shopId = cs.shopId','left')
			->where('cs.shopId',$rs['shopId'])->field('cs.shopId,s.shopTel,gc.catId,gc.catName')->select();
			$rs['shop']['catId'] = $goodsCats[0]['catId'];
			$rs['shop']['shopTel'] = $goodsCats[0]['shopTel'];

			$cat = [];
			foreach ($goodsCats as $v){
				$cat[] = $v['catName'];
			}
			$rs['shop']['cat'] = implode('，',$cat);
			if(empty($rs['shop']))return [];
			$gallery = [];
			$gallery[] = $rs['goodsImg'];
			if($rs['gallery']!=''){
				$tmp = explode(',',$rs['gallery']);
				$gallery = array_merge($gallery,$tmp);
			}
			$rs['gallery'] = $gallery;
			//获取规格值
			$specs = Db::name('spec_cats')->alias('gc')->join('__SPEC_ITEMS__ sit','gc.catId=sit.catId','inner')
			->where(['sit.goodsId'=>$goodsId,'gc.isShow'=>1,'sit.dataFlag'=>1])
			->field('gc.isAllowImg,gc.catName,sit.catId,sit.itemId,sit.itemName,sit.itemImg')
			->order('gc.isAllowImg desc,gc.catSort asc,gc.catId asc')->select();
			$rs['spec']=[];
			foreach ($specs as $key =>$v){
				$rs['spec'][$v['catId']]['name'] = $v['catName'];
				$rs['spec'][$v['catId']]['list'][] = $v;
			}
			//获取销售规格
			$sales = Db::name('goods_specs')->where('goodsId',$goodsId)->field('id,isDefault,productNo,specIds,marketPrice,specPrice,specStock')->select();
			if(!empty($sales)){
				foreach ($sales as $key =>$v){
					$str = explode(':',$v['specIds']);
					sort($str);
					unset($v['specIds']);
					$rs['saleSpec'][implode(':',$str)] = $v;
				}
			}
			//获取商品属性
			$rs['attrs'] = Db::name('attributes')->alias('a')->join('goods_attributes ga','a.attrId=ga.attrId','inner')
			->where(['a.isShow'=>1,'dataFlag'=>1,'goodsId'=>$goodsId])->field('a.attrName,ga.attrVal')
			->order('attrSort asc')->select();
			//获取商品评分
			$rs['scores'] = Db::name('goods_scores')->where('goodsId',$goodsId)->field('totalScore,totalUsers')->find();
			$rs['scores']['totalScores'] = ($rs['scores']['totalScore']==0)?5:WSTScore($rs['scores']['totalScore'],$rs['scores']['totalUsers'],5,0,3);
			WSTUnset($rs, 'totalUsers');
			//关注
			$f = model('Favorites');
			$rs['favShop'] = $f->checkFavorite($rs['shopId'],1);
			$rs['favGood'] = $f->checkFavorite($goodsId,0);
			// 获取一条商品评价
			$appr = model('app/GoodsAppraises')
								->alias('ga')
								->join('users U','ga.userId=U.userId')
								->field('U.loginName,U.userPhoto,ga.content')
								->where(['goodsId'=>$goodsId,'U.dataFlag'=>1,'ga.dataFlag'=>1])
								->find();
			if(!empty($appr)){
				// 若未设置头像,则取商城默认头像
				$appr['userPhoto'] = ($appr['userPhoto']!='')?$appr['userPhoto']:WSTConf('CONF.userLogo');
				// 过滤html标签
				$appr['content'] = strip_tags($appr['content']);
				// 处理匿名
				$start = floor((strlen($appr['loginName'])/2))-1;
				$appr['loginName'] = substr_replace($appr['loginName'],'**',$start,2);
			}
			$rs['goodsAppr'] = $appr;


		}
		return $rs;
	}
	// 获取商品详情
	public function getGoodsDetail($goodsId=0){
		// 未传递goodsId返回空数组
		if($goodsId <= 0)return [];
		return Db::name('goods')->field('goodsDesc')->where(['goodsId'=>$goodsId,'dataFlag'=>1])->find();
	}


	public function historyQuery(){
		$ids = input('history');
		if(empty($ids))return [];
	    $where = [];
	    $where[] = ['isSale','=',1];
	    $where[] = ['goodsStatus','=',1]; 
	    $where[] = ['dataFlag','=',1]; 
	    $where[] = ['goodsId','in',$ids];
        return Db::name('goods')
                   ->where($where)->field('goodsId,goodsName,goodsImg,saleNum,shopPrice')
                   ->select();
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
	    	$attrs = input('attrs');
    	    $attrs = explode(',',$attrs);
	    	foreach ($attrs as $key => $value) {
	    		if($key == $v){
	    	      $attrVal = $value;
	    		}
	    	}
	    	if($attrVal=='')continue;
		    	$sql = "select goodsId from ".$prefix."goods_attributes 
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
			$goodsIds2[] = -1;
			if(empty($goodsIds)){
				$goodsIds = $goodsIds2;
			}else{
				$goodsIds = array_intersect($goodsIds,$goodsIds2);
			}
		}
		return $goodsIds;
    }
}
