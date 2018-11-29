<?php
namespace shangtao\common\model;
use shangtao\common\model\GoodsCats as M;
/**
 * 商品属性分类
 */
class Attributes extends Base{
	/**
	 * 获取可供筛选的商品属性
	 */
	public function listQueryByFilter($catId){
		$m = new M();
		$ids = $m->getParentIs($catId);
		if(!empty($ids)){
			$catIds = [];
			foreach ($ids as $key =>$v){
				$catIds[] = $v;
			}
			// 取出分类下有设置的属性。
			$attrs = $this->alias('a')
					  ->join('__GOODS_ATTRIBUTES__ ga','ga.attrId=a.attrId','inner')
					  ->field('ga.attrId,GROUP_CONCAT(distinct ga.attrVal) attrVal,a.attrName')
					  ->where(['a.isShow'=>1,'a.dataFlag'=>1])
					  ->where([['a.goodsCatId','in',$catIds],['a.attrType','<>',0]])
					  ->group('ga.attrId')
					  ->order('a.attrSort asc')
					  ->select();
			foreach ($attrs as $key =>$v){
			    $attrs[$key]['attrVal'] = explode(',',$v['attrVal']);
			}
			return $attrs;
		}
		return [];
	}
	/**
	* 根据商品id获取可供选择的属性
	*/
	public function getAttribute($goodsId){
		if(empty($goodsId))return [];
		$attrs = $this->alias('a')
					  ->join('__GOODS_ATTRIBUTES__ ga','ga.attrId=a.attrId','inner')
					  ->field('ga.attrId,GROUP_CONCAT(distinct ga.attrVal) attrVal,a.attrName')
					  ->where(['a.isShow'=>1,'a.dataFlag'=>1])
					  	->where([['ga.goodsId','in',$goodsId],['a.attrType','<>',0]])
					  ->group('ga.attrId')
					  ->order('a.attrSort asc')
					  ->select();
		if(empty($attrs))return [];
		foreach ($attrs as $key =>$v){
			    $attrs[$key]['attrVal'] = explode(',',$v['attrVal']);
		}
		return $attrs;
	}
}
