<?php 
namespace shangtao\admin\validate;
use think\Validate;
/**
 * 广告位置验证器
 */
class AdPositions extends Validate{
	protected $rule = [
	    'positionName' => 'require|max:30',
	    'positionCode' => 'require|max:60',
		'positionType' => 'require',
	    'positionWidth' => 'require|number',
	    'positionHeight' => 'require|number',
	    'apSort'  => 'number',
    ];
     
    protected $message = [
        'positionName.require' => '请输入位置名称',
        'positionName.max' => '位置名称不能超过10个字符',
        'positionCode.require' => '请输入位置代码',
        'positionCode.max' => '位置代码不能超过20个字符',
        'positionType.require' => '请选择位置类型',
        'positionWidth.require' => '请输入建议宽度',
        'positionWidth.number' => '建议宽度只能为数字',
        'positionHeight.require' => '请输入建议高度',
        'positionHeight.require' => '建议高度只能为数字',
        'apSort.number' => '排序号只能为数字',

    ];
    
    protected $scene = [
        'add'   =>  ['positionName','positionCode','positionType','positionWidth','positionHeight','apSort'],
        'edit'  =>  ['positionName','positionCode','positionType','positionWidth','positionHeight','apSort'],
    ]; 
}