<?php 
namespace shangtao\admin\validate;
use think\Validate;
/**
 * 菜单验证器
 */
class HomeMenus extends Validate{
	protected $rule = [
        'menuName' => 'require|max:30',
		'parentId' => 'number',
		'menuType' => 'require',
		'menuUrl' => 'require',
		'isShow' => 'require',
    ];
    
    protected $message = [
        'menuName.require' => '请输入菜单名称',
        'menuName.max' => '菜单名称不能超过10个字符',
        'parentId.number' => '无效的父级菜单',
        'menuType.require' => '请输入菜单类型',
        'menuUrl.require' => '请输入菜单Url',
        'isShow.require' => '请选择是否显示',
    ];
    
    protected $scene = [
        'add'   =>  ['menuName','parentId','menuType','menuUrl','isShow'],
        'edit'  =>  ['menuName','menuType','menuUrl','isShow']
    ]; 
}