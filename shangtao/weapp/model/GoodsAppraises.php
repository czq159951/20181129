<?php
namespace shangtao\weapp\model;
use shangtao\common\model\GoodsAppraises as CGoodsAppraises;
use think\Db;
/**
 * 评价类
 */
class GoodsAppraises extends CGoodsAppraises{
	/**
	 *  获取评论
	 */
	public function getAppr($uId=0){
		$oId = (int)input('oId');
		$userId = ((int)$uId==0)?(int)session('WST_USER.userId'):$uId;
		$gId = (int)input('gId');
		$specId = (int)input('sId');
		$orderGoodsId = (int)input('orderGoodsId');
		$rs = $this->where(['orderId'=>$oId,'userId'=>$userId,'goodsId'=>$gId,'goodsSpecId'=>$specId,'orderGoodsId'=>$orderGoodsId])->find();
		if($rs!==false){
			$rs = !empty($rs)?$rs:['goodsScore'=>'','timeScore'=>'','serviceScore'=>'','content'=>''];
		}
		return $rs;
	}
}
