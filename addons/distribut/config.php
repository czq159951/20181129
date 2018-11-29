<?php

return array(
	
	'distributDeal'=>array(
		'title'=>"成为分销商条件<span style='color:#FF6666;'>【请确保系统已开启在线支付功能】</span>",
		'type'=>'radio',
		'options'=>array(
			'1'=>'直接成为分销商',	
			'2'=>'购买商品后成为分销商',
		),
		'value'=>'1',
	),
	'buyerRate'=>array(
		'title'=>"购买者分成<span style='color:#FF6666;'>【请确保分成佣金百分比之和为100%】</span>",
		'type'=>'text',
		'value'=>'',
		'placeholder'=>'50',
		'tips'=>'%'
	),
	'secondRate'=>array(
		'title'=>'第二级分成',
		'type'=>'text',
		'value'=>'',
		'placeholder'=>'30',
		'tips'=>'%'
	),
	'thirdRate'=>array(
		'title'=>"第三级分成",
		'type'=>'text',
		'value'=>'',
		'placeholder'=>'20',
		'tips'=>'%'
	),
	'goodsShareTitle'=>array(
		'title'=>'商品分享标题',
		'type'=>'text',
		'value'=>''
	),
	'shopShareTitle'=>array(
		'title'=>'店铺分享标题',
		'type'=>'text',
		'value'=>''
	),
	'mallShareTitle'=>array(
		'title'=>'商城分享标题',
		'type'=>'text',
		'value'=>''
	)
);
