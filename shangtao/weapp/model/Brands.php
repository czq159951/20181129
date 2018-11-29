<?php
namespace shangtao\weapp\model;
use think\Db;
use think\Model;
/**
 * 品牌业务处理类
 */
class Brands extends Model{
	/**
	 * 获取品牌列表
	 */
	public function pageQuery($id = 0){
		$id = ($id>0)?$id:(int)input('id');
		$where['b.dataFlag']=1;
		$prefix = config('database.prefix');
		$where['gcb.catId']=$id;
		$rs = $this->alias('b')
		->join('__CAT_BRANDS__ gcb','gcb.brandId=b.brandId','left')
		->where($where)
		->field('b.brandId,brandName,brandImg,gcb.catId')
		->group('b.brandId,gcb.catId')
		->order('b.sortNo asc')
		->select();
		return $rs;
	}
}
