<?php 
namespace shangtao\common\validate;
use think\Validate;
/**
 * 用户地址验证器
 */
class UserAddress extends Validate{
	protected $rule = [
		'areaId' => 'require',
		'userAddress'  => 'require',
		'userName' => 'require',
		'isDefault' => 'in:0,1',
		'userPhone' => 'require'
	];
	
	protected $message  =  [
		'areaId.require' => '请选择地址',
		'userAddress.require' => '请输入详细地址',
		'userName.require' => '请输入联系名称',
		'isDefault.in'  => '请选择是否默认地址',
		'userPhone.require'  => '请输入联系电话'
	];

    protected $scene = [
        'add'   =>  ['areaId','userAddress','userName','isDefault','userPhone'],
        'edit'  =>  ['areaId','userAddress','userName','isDefault','userPhone'],
    ]; 
}