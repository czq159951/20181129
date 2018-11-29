<?php 
namespace shangtao\admin\validate;
use think\Validate;
/**
 * 移动版按钮验证器
 */
class MobileBtns extends Validate{
	protected $rule = [
	    'btnName' => 'require',
	    'btnUrl' => 'require',
        'btnImg' => 'require',
    ];
    
    protected $message = [
        'btnName.require' => '请输入按钮名称',
        'btnUrl.require' => '请输入按钮名称',
        'btnImg.require' => '请上传图标',
    ];
    
    protected $scene = [
        'add'   =>  ['btnName','btnImg','btnUrl'],
        'edit'  =>  ['btnName','btnUrl'],
    ]; 
}