<?php
namespace shangtao\mobile\model;
use shangtao\common\model\GoodsAppraises as CGoodsAppraises;
use think\Db;
/**
 * 评价类
 */
class GoodsAppraises extends CGoodsAppraises{
	/**
	 *  获取评论
	 */
	public function getAppr(){
		$oId = (int)input('oId');
		$uId = (int)session('WST_USER.userId');
		$gId = (int)input('gId');
		$specId = (int)input('sId');
		$orderGoodsId = (int)input('orderGoodsId');
		$rs = $this->where(['orderId'=>$oId,'userId'=>$uId,'goodsId'=>$gId,'goodsSpecId'=>$specId,'orderGoodsId'=>$orderGoodsId])->find();
		if($rs!==false){
			$rs = !empty($rs)?$rs:['goodsScore'=>'','timeScore'=>'','serviceScore'=>'','content'=>''];
			return WSTReturn('',1,$rs);
		}
		return WSTReturn('获取出错',-1);
	}
	
}
