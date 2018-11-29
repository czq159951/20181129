<?php 
namespace shangtao\common\validate;
use think\Validate;
/**
 * 发票信息验证器
 */
class Invoices extends Validate{
	protected $rule = [
		'invoiceHead'  => 'require'
	];
	
	protected $message  =   [
		'invoiceHead.require'   => '请输入发票抬头'
	];

    protected $scene = [
        'add'   =>  ['invoiceHead'],
        'edit'  =>  ['invoiceHead']
    ]; 
}