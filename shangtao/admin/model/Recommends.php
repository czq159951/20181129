<?php
namespace shangtao\admin\model;
/**
 * 推荐业务处理
 */
use think\Db;
class Recommends extends Base{
	/**
	 * 获取已推荐商品
	 */
	public function listQueryByGoods(){
		$dataType = (int)input('post.dataType');
	    $goodsCatId = (int)input('post.goodsCatId');
		$rs = $this->alias('r')->join('__GOODS__ g','r.dataId=g.goodsId','inner')
		           ->join('__SHOPS__ s','s.shopId=g.shopId','inner')
		           ->where(['dataSrc'=>0,'dataType'=>$dataType,'r.goodsCatId'=>$goodsCatId])
		           ->field('dataId,goodsName,shopName,dataSort,isSale,g.dataFlag,goodsStatus')->order('dataSort asc')->select();
		$data = [];
		foreach ($rs as $key => $v){
			if($v['isSale']!=1 || $v['dataFlag']!=1 || $v['goodsStatus']!=1)$v['invalid'] = true;
			$data[] = $v;
		}   
		return $data;        
	}
	/**
	 * 推荐商品
	 */
    public function editGoods(){
	    $ids = input('post.ids');
	    $dataType = (int)input('post.dataType');
	    $goodsCatId = (int)input('post.goodsCatId');
	    $ids = explode(',',$ids);
	    Db::startTrans();
	    try{
		    $this->where(['dataSrc'=>0,'dataType'=>$dataType,'goodsCatId'=>$goodsCatId])->delete();
		    if(!empty($ids)){
			    //查看商品是否有效
		        $rs = Db::name('goods')->where([['goodsStatus','=',1],['dataFlag','=',1],['goodsId','in',$ids]])->field('goodsId')->select();
			    if(!empty($rs)){
				    $data = [];
				    foreach ($rs as $key => $v){
				    	$tmp = [];
				    	$tmp['goodsCatId'] = $goodsCatId;
				    	$tmp['dataSrc'] = 0;
				    	$tmp['dataType'] = $dataType;
				    	$tmp['dataId'] = $v['goodsId'];
				    	$tmp['dataSort'] = (int)input('post.ipt'.$v['goodsId']);
				    	$data[] = $tmp;
				    }
				    $this->saveAll($data);
				}
				WSTClearAllCache();
			}
		    Db::commit();
	        return WSTReturn("提交成功", 1);
	    }catch(\Exception $e) {
            Db::rollback();
            return WSTReturn('提交失败',-1);
        }
	}
	
	
    /**
	 * 获取已推荐店铺
	 */
	public function listQueryByShops(){
		$dataType = (int)input('post.dataType');
	    $goodsCatId = (int)input('post.goodsCatId');
		$rs = $this->alias('r')->join('__SHOPS__ s','r.dataId=s.shopId','inner')
		           ->where(['dataSrc'=>1,'dataType'=>$dataType,'r.goodsCatId'=>$goodsCatId])
		           ->field('dataId,shopSn,shopName,dataSort,shopStatus,dataFlag')->order('dataSort asc')->select();
		$data = [];
		foreach ($rs as $key => $v){
			if($v['dataFlag']!=1 || $v['shopStatus']!=1)$v['invalid'] = true;
			$data[] = $v;
		}   
		return $data;        
	}
    /**
	 * 推荐店铺
	 */
    public function editShops(){
	    $ids = input('post.ids');
	    $dataType = (int)input('post.dataType');
	    $goodsCatId = (int)input('post.goodsCatId');
	    $ids = explode(',',$ids);
	    Db::startTrans();
	    try{
		    $this->where(['dataSrc'=>1,'dataType'=>$dataType,'goodsCatId'=>$goodsCatId])->delete();
		    if(!empty($ids)){
		    	//查看商品是否有效
	            $rs = Db::name('shops')->where([['shopStatus','=',1],['dataFlag','=',1],['shopId','in',$ids]])->field('shopId')->select();
	            if(!empty($rs)){
				    $data = [];
				    foreach ($rs as $key => $v){
				    	$tmp = [];
				    	$tmp['goodsCatId'] = $goodsCatId;
				    	$tmp['dataSrc'] = 1;
				    	$tmp['dataType'] = $dataType;
				    	$tmp['dataId'] = $v['shopId'];
				    	$tmp['dataSort'] = (int)input('post.ipt'.$v['shopId']);
				    	$data[] = $tmp;
				    }
				    $this->saveAll($data);
				}
				WSTClearAllCache();
			}
		    Db::commit();
	        return WSTReturn("提交成功", 1);
	    }catch(\Exception $e) {
            Db::rollback();
            return WSTReturn('提交失败',-1);
        }
	}
	
	
    /**
	 * 获取已推荐品牌
	 */
	public function listQueryByBrands(){
		$dataType = (int)input('post.dataType');
	    $goodsCatId = (int)input('post.goodsCatId');
		$rs = $this->alias('r')->join('__BRANDS__ s','r.dataId=s.brandId','inner')
		           ->where(['dataSrc'=>2,'dataType'=>$dataType,'r.goodsCatId'=>$goodsCatId])
		           ->field('dataId,brandName,dataSort,dataFlag')->order('dataSort asc')->select();
		$data = [];
		foreach ($rs as $key => $v){
			if($v['dataFlag']!=1)$v['invalid'] = true;
			$data[] = $v;
		}   
		return $data;        
	}
    /**
	 * 推荐品牌
	 */
    public function editBrands(){
	    $ids = input('post.ids');
	    $dataType = (int)input('post.dataType');
	    $goodsCatId = (int)input('post.goodsCatId');
	    $ids = explode(',',$ids);
	    Db::startTrans();
	    try{
		    $this->where(['dataSrc'=>2,'dataType'=>$dataType,'goodsCatId'=>$goodsCatId])->delete();
		    if(!empty($ids)){
                //查看商品是否有效
	            $rs = Db::name('brands')->where([['dataFlag','=',1],['brandId','in',$ids]])->field('brandId')->select();
	            if(!empty($rs)){
	            	$data = [];
				    foreach ($rs as $key => $v){
				    	$tmp = [];
				    	$tmp['goodsCatId'] = $goodsCatId;
				    	$tmp['dataSrc'] = 2;
				    	$tmp['dataType'] = $dataType;
				    	$tmp['dataId'] = $v['brandId'];
				    	$tmp['dataSort'] = (int)input('post.ipt'.$v['brandId']);
				    	$data[] = $tmp;
				    }
				    $this->saveAll($data);
	            }
	            WSTClearAllCache();
		    }
		    Db::commit();
	        return WSTReturn("提交成功", 1);
	    }catch(\Exception $e) {
            Db::rollback();
            return WSTReturn('提交失败',-1);
        }
	}
}
