<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\Recommends as M;
/**
 * 推荐管理控制器
 */
class Recommends extends Base{
    /**
    * 查看商品推荐
    */
	public function goods(){
		return $this->fetch('goods');
	}
	/**
	 * 查询商品
	 */
	public function searchGoods(){
		$rs = model('Goods')->searchQuery();
		return WSTReturn("", 1,$rs);
	}
	/**
	 * 推荐商品
	 */
	public function editGoods(){
		$m = new M();
		return $m->editGoods();
	}
	/**
	 * 获取已选择商品
	 */
	public function listQueryByGoods(){
		$m = new M();
		$rs= $m->listQueryByGoods();
		return WSTReturn("", 1,$rs);
	}
	
    /**
    * 查看店铺推荐
    */
	public function shops(){
		return $this->fetch('shops');
	}
	/**
	 * 查询店铺
	 */
	public function searchShops(){
		$rs = model('Shops')->searchQuery();
		return WSTReturn("", 1,$rs);
	}
	/**
	 * 推荐店铺
	 */
	public function editShops(){
		$m = new M();
		return $m->editShops();
	}
	/**
	 * 获取已选择店铺
	 */
	public function listQueryByShops(){
		$m = new M();
		$rs= $m->listQueryByShops();
		return WSTReturn("", 1,$rs);
	}
	
	
   /**
    * 查看品牌推荐
    */
	public function brands(){
		return $this->fetch('brands');
	}
	/**
	 * 查询品牌
	 */
	public function searchBrands(){
		$rs = model('Brands')->searchBrands();
		return WSTReturn("", 1,$rs);
	}
	/**
	 * 推荐品牌
	 */
	public function editBrands(){
		$m = new M();
		$rs= $m->editBrands();
		return $rs;
	}
	/**
	 * 获取已选择品牌
	 */
	public function listQueryByBrands(){
		$m = new M();
		$rs= $m->listQueryByBrands();
		return WSTReturn("", 1,$rs);
	}
}
