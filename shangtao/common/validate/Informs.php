<?php 
namespace shangtao\common\validate;
use think\Validate;
/**
 * 订单投诉验证器
 */
class Informs extends Validate{
	protected $rule = [
		'informType'  => 'in:1,2,3,4',
		'informContent'   => 'require|length:3,600',
		'respondContent' => 'require|length:3,600'
	];
	
	protected $message  =   [
		'informType.in'   => '无效的投诉类型！',
		'informContent.require' => '投诉内容不能为空',
		'informContent.length' => '投诉内容应为3-200个字',
		'respondContent.require'   => '应诉内容不能为空',
		'respondContent.length' => '应诉内容应为3-200个字'
	];

    protected $scene = [
        'add'   =>  ['informType','informContent'],
        'edit'   =>  ['informType','informContent'],
        'respond' =>['respondContent']
    ]; 
}