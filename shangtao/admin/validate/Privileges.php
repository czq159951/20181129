<?php 
namespace shangtao\admin\validate;
use think\Validate;
/**
 * 权限验证器
 */
class Privileges extends Validate{
	protected $rule = [
	    'privilegeName' => 'require|max:60',
        'privilegeCode' => 'require|max:30',
        'menuId' => 'number',
    ];
    
    protected $message = [
        'privilegeName.require' => '请输入权限名称',
        'privilegeName.max' => '权限名称不能超过20个字符',
        'privilegeCode.require' => '请输入权限代码',
        'privilegeCode.max' => '权限代码不能超过10个字符',
        'menuId.number' => '无效的权限菜单',
    ];
    
    protected $scene = [
        'add'   =>  ['privilegeName','privilegeCode','menuId'],
        'edit'  =>  ['privilegeName','privilegeCode'],
    ]; 
}