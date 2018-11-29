<?php 
namespace shangtao\admin\validate;
use think\Validate;
/**
 * 认证商家验证器
 */
class Accreds extends Validate{
	protected $rule = [
	    'accredName' => 'require|max:30',
        'accredImg' => 'require',
    ];
    protected $message  =   [
	    'accredName.require' => '请输入认证名称',
	    'accredName.max'     => '认证名称不能超过30个字符',
	    'accredImg.require'   => '请上传图标',  
    ];
    protected $scene = [
        'add'   =>  ['accredName','accredImg'],
        'edit'  =>  ['accredName'],
    ]; 
}