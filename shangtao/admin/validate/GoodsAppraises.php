<?php 
namespace shangtao\admin\validate;
use think\Validate;
/**
 * 商品评价验证器
 */
class GoodsAppraises extends Validate{
	protected $rule = [
		'goodsScore' => 'number|gt:0',
		'timeScore' => 'number|gt:0',
		'serviceScore' => 'number|gt:0',
		'content' => 'length:3,50',
    ];
    
    protected $message = [
        'goodsScore.number' => '评分只能是数字',
        'goodsScore.gt' => '评分必须大于0',
        'timeScore.number' => '评分只能是数字',
        'timeScore.gt' => '评分必须大于0',
        'serviceScore.number' => '评分只能是数字',
        'serviceScore.gt' => '评分必须大于0',
        'content.length' =>'评价内容3-50个字',
    ];
    
    protected $scene = [
        'edit'=>['isShow','goodsScore','timeScore','serviceScore','content'],
    ]; 
}