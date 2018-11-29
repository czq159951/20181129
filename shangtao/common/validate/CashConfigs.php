<?php 
namespace shangtao\common\validate;
use think\Validate;
/**
 * 提现账号验证器
 */
class CashConfigs extends Validate{
	protected $rule = [
		'accTargetId'  => 'require',
		'accAreaId'   => 'require',
		'accNo' => 'require',
		'accUser' => 'require'
	];
	
	protected $message  =   [
		'accTargetId.require'   => '请选择开卡银行',
		'accAreaId.require' => '请选择开卡地区',
		'accNo.require' => '请输入银行卡号',
		'accUser.require'   => '请输入持卡人'
	];

    protected $scene = [
        'add'   =>  ['accTargetId','accNo','accUser'],
        'edit'  =>  ['accTargetId','accNo','accUser']
    ]; 
}