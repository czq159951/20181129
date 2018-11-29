<?php 
namespace shangtao\admin\validate;
use think\Validate;
/**
 * 快递验证器
 */
class express extends Validate{
	protected $rule = [
        'expressName' => 'require|max:30',
    ];
    
    protected $message = [
        'expressName.require' => '请输入快递名称',
        'expressName.max' => '快递名称不能超过10个字符',
    ];
    
    protected $scene = [
        'add'   =>  ['expressName'],
        'edit'  =>  ['expressName'],
    ]; 
}