<?php 
namespace shangtao\common\validate;
use think\Validate;
/**
 * 商品咨询验证器
 */
class GoodsConsult extends Validate{
	protected $rule = [
		'consultContent'  => 'require|length:3,600',
		'consultType'   => 'in:1,2,3,4',
		'reply' => 'require|length:3,600'
	];
	
	protected $message  =   [
		'consultContent.require'   => '请输入咨询内容',
		'consultContent.length' => '咨询内容应为3-200个字',
		'consultType.in' => '请选择咨询类别',
		'reply.require'   => '请输入回复内容',
		'reply.length'  => '回复内容应为3-200个字'
	];

    protected $scene = [
        'add'   =>  ['consultContent','consultType'],
        'edit'  =>  ['reply']
    ]; 
}