<?php 
namespace shangtao\app\model;
use shangtao\common\model\ShopCats as CSC;
use shangtao\common\validate\ShopCats as Validate;
class ShopCats extends CSC{
	/**
	* 保存
	*/
	public function saveData($shopId){
		$data = input('param.');
		$data['shopId'] = $shopId;
		$validate = new Validate;
		if (!$validate->scene('add')->check($data)) {
			return WSTReturn($validate->getError());
		}
		$data['isShow'] = (int)!!$data['isShow'];
		$data['catSort'] = (int)!!$data['catSort'];
		$where = [];
		if($data['catId']==0){
			$data['createTime'] = date('Y-m-d H:i:s');
		}else{
			$where['catId'] = $data['catId'];
		}
		$rs = $this->save($data,$where);
		if(false !== $rs){
			return WSTReturn("",1);
		}
		return WSTReturn($this->getError());
	}
	 /**
	  * 删除
	  */
	 public function del($shopId=0){
	 	$ids = input("post.ids");
		//把相关的商品下架了
		$gm = new \shangtao\home\model\Goods();
		$gm->whereIn("shopCatId1|shopCatId2",$ids)
		   ->where(['shopId'=>$shopId])
		   ->update(['isSale'=>0]);
		//删除商品分类
	 	$rs = $this->whereIn('catId|parentId',$ids)->where(["shopId"=>$shopId])->update(['dataFlag'=>-1]);
	    if(false !== $rs){
			return WSTReturn("删除成功",1);
		}
		return WSTReturn($this->getError());
		
	 }
}