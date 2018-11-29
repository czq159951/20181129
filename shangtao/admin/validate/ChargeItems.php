<?php 
namespace shangtao\admin\validate;
use think\Validate;
/**
 * 广告位置验证器
 */
class ChargeItems extends Validate{
	protected $rule = [
	    'chargeMoney' => 'require|egt:1',
	    'giveMoney' => 'require|egt:0',
    ];
    
    protected $message = [
        'chargeMoney.require' => '请输入充值金额',
        'chargeMoney.egt' => '充值金额必须大于0',
        'giveMoney.require' => '请输入赠送金额',
        'giveMoney.egt' => '赠送金额必须不小于0',
    ];

    protected $scene = [
        'add'   =>  ['chargeMoney','giveMoney'],
        'edit'  =>  ['chargeMoney','positionCode'],
    ]; 
}