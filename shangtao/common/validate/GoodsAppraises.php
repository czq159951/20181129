<?php 
namespace shangtao\common\validate;
use think\Validate;
/**
 * 评价验证器
 */
class GoodsAppraises extends Validate{
	protected $rule = [
		'goodsScore'  => 'between:1,5',
		'serviceScore'   => 'between:1,5',
		'timeScore' => 'between:1,5',
		'content' => 'require|length:3,600'
	];
	
	protected $message  =   [
		'goodsScore.between'   => '评分必须在1-5之间',
		'serviceScore.between' => '评分必须在1-5之间',
		'timeScore.between' => '评分必须在1-5之间',
		'content.require'   => '点评内容不能为空',
		'content.length'   => '点评内容应为3-200个字'
	];
	
    protected $scene = [
        'add'   =>  ['goodsScore','serviceScore','timeScore','content']
    ];
}