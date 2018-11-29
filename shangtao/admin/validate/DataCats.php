<?php 
namespace shangtao\admin\validate;
use think\Validate;
/**
 * 数据分类验证器
 */
class DataCats extends Validate{
	protected $rule = [
        'catName' => 'require',
        'catCode' => 'require',
    ];
    
    protected $message = [
        'catName.require' => '请输入数据分类名称',
        'catCode.require' => '请输入数据代码',
    ];
    
    protected $scene = [
        'add'   =>  ['catName','catCode'],
        'edit'  =>  ['catName','catCode'],
    ]; 
}