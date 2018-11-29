<?php 
namespace shangtao\admin\validate;
use think\Validate;
/**
 * 数据验证器
 */
class Datas extends Validate{
	protected $rule = [
        'dataName' => 'require',
        'dataVal' => 'require',
    ];
     
    protected $message = [
        'dataName.require' => '请输入数据名称',
        'dataVal.require' => '请输入数据值'
    ];
    
    protected $scene = [
        'add'   =>  ['dataName','dataVal'],
        'edit'  =>  ['dataName','dataVal'],
    ]; 
}