<?php 
namespace shangtao\common\validate;
use think\Validate;
/**
 * 订单投诉验证器
 */
class OrderComplains extends Validate{
	protected $rule = [
		'complainType'  => 'in:1,2,3,4',
		'complainContent'   => 'require|length:3,600',
		'respondContent' => 'require|length:3,600'
	];
	
	protected $message  =   [
		'complainType.in'   => '无效的投诉类型！',
		'complainContent.require' => '投诉内容不能为空',
		'complainContent.length' => '投诉内容应为3-200个字',
		'respondContent.require'   => '应诉内容不能为空',
		'respondContent.length' => '应诉内容应为3-200个字'
	];
	
	
    protected $scene = [
        'add'   =>  ['complainType','complainContent'],
        'edit'   =>  ['complainType','complainContent'],
        'respond' =>['respondContent']
    ]; 
}