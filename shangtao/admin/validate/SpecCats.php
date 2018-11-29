<?php
namespace shangtao\admin\validate;
use think\Validate;
/**
 * 规格类型验证器
 */
class SpecCats extends Validate{
	protected $rule = [
		'catName' => 'require|max:30',
		'goodsCatId' => 'require|gt:0',
		'isAllowImg' => 'number|in:0,1',
		'isShow' => 'number|in:0,1',
	];

	protected $message = [
        'catName.require' => '请输入规格名称',
        'catName.max' => '规格名称不能超过10个字符',
        'goodsCatId.require' => '请选择所属商品分类',
        'goodsCatId.gt' => '请选择所属商品分类',
        'isAllowImg.number' => '请选择是否显示允许上传图片',
        'isAllowImg.in' => '请选择是否显示允许上传图片',
        'isShow.number' => '请选择是否显示',
        'isShow.in' => '请选择是否显示',
	];

	protected $scene = [
		'add'=>['catName','goodsCatId','isAllowImg','isShow'],
		'edit'=>['catName','goodsCatId','isAllowImg','isShow']
	];
	
}