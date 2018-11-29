<?php 
namespace shangtao\admin\validate;
use think\Validate;
/**
 * 会员级别验证器
 */
class UserRanks extends Validate{
	protected $rule = [
        'rankName'  => 'require|max:30',
    ];
    
    protected $message = [
        'rankName.require' => '请输入会员等级名称',
        'rankName.max' => '会员等级名称不能超过10个字符',
    ];

    protected $scene = [
        'add'   =>  ['rankName'],
        'edit'  =>  ['rankName'],
    ]; 

    
}