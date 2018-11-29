<?php
namespace shangtao\admin\validate;
use think\Validate;
/**
 * 导航验证器
 */
class Navs extends Validate{
	protected $rule = [
		'navTitle' => 'require|max:30',
		'navUrl' => 'require',
		'navSort' => 'integer',
	];

	protected $message = [
        'navTitle.require' => '请输入导航名称',
        'navTitle.max' => '导航名称不能超过10个字符',
        'navUrl.require' => '请输入导航链接',
        'navSort.integer' => '排序号只能为整数',
	];
	
	protected $scene = [
		'add'=>['navTitle','navUrl','navSort'],
		'edit'=>['navTitle','navUrl','navSort'],
	];
	
}