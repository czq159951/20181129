<?php 
namespace shangtao\admin\validate;
use think\Validate;
/**
 * 商品咨询验证器
 */
class GoodsConsult extends Validate{
	protected $rule = [
		'isShow' => 'require|in:0,1',
		'consultContent' => 'require|length:3,600',
        'reply' => 'require|length:3,600',
    ];
    
    protected $message = [
        'isShow.require' => '状态不能为空',
        'isShow.in' => '状态必须为0或1',
        'consultContent.require' => '请输入咨询内容',
        'consultContent.length' => '咨询内容应为3-200个字',
        'reply.require' => '请输入回复内容',
        'reply.length' => '回复内容应为3-200个字',
    ];
    
    protected $scene = [
        'edit'=>['isShow','consultContent','reply'],
    ]; 
}