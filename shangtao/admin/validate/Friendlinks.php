<?php 
namespace shangtao\admin\validate;
use think\Validate;
/**
 * 友情链接验证器
 */
class Friendlinks extends Validate{
	protected $rule = [
	    'friendlinkName' => 'require|max:90',
        'friendlinkUrl' => 'require',
    ];
    
    protected $message = [
        'friendlinkName.require' => '请输入网站名称',
        'friendlinkName.max' => '网站名称不能超过30个字符',
        'friendlinkUrl.require' => '请输入网址',
    ];

    protected $scene = [
        'add'   =>  ['friendlinkName'],
        'edit'  =>  ['friendlinkName'],
    ]; 
}