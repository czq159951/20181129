<?php 
namespace shangtao\admin\validate;
use think\Validate;
/**
 * 地区验证器
 */
class Areas extends Validate{
	protected $rule = [
	    'areaName' => 'require|max:30',
		'areaKey' => 'require|max:2',
	    'areaSort' => 'require|max:16',
    ];

    protected $message = [
        'areaName.require' => '请输入地区名称|',
        'areaName.max' => '地区名称不能超过10个字符',
        'areaKey.require' => '请输入排序字母',
        'areaKey.max' => '排序字母不能超过1个字符',
        'areaSort.require' => '请输入排序号',
        'areaSort.max' => '排序号不能超过8个字符',
    ];

    protected $scene = [
        'add'   =>  ['areaName','areaKey','areaSort'],
        'edit'  =>  ['areaName','areaKey','areaSort'],
    ]; 
}