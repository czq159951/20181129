<?php 
namespace shangtao\admin\validate;
use think\Validate;
/**
 * 银行验证器
 */
class Banks extends Validate{
	protected $rule = [
        'bankName' => 'require|max:30',
    ];
    
    protected $message = [
        'bankName.require' => '请输入银行名称',
        'bankName.max' => '银行名称不能超过10个字符',
    ];
    protected $scene = [
        'add'   =>  ['bankName'],
        'edit'  =>  ['bankName'],
    ]; 
}