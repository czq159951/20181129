<?php
namespace shangtao\app\controller;
use shangtao\common\model\GoodsConsult as M;
/**
 * 商品咨询控制器
 */
class GoodsConsult extends Base{
    protected $beforeActionList = [
          'checkShopAuth'=>['only'=>'pagequery,shopreplyconsult,reply']
    ];
    /****************************************** 商家 ***********************************************/
    /**
    * 根据店铺id获取商品咨询
    */
    public function pageQuery(){
        $shopId = $this->getShopId();
        $m = new M();
        $rs = $m->pageQuery($shopId);
        $rs['data']['domain'] = $this->domain();
        return json_encode($rs);
    }
    /**
    * 商家回复
    */
    public function reply(){
        $shopId = $this->getShopId();
        $m = new M();
        return json_encode($m->reply($shopId));
    }
    /****************************************** 商家 ***********************************************/


    /**
    * 获取商品咨询类别
    */
    public function getConsultType(){
        $arr = WSTDatas('COUSULT_TYPE');
        return json_encode(WSTReturn('success',1,$arr));
    }
	/**
	* 根据商品id获取商品咨询
	*/
    public function listQuery(){
        $m = new M();
        $rs = $m->listQuery();
        return json_encode($rs);
    }
    /**
    * 新增
    */
    public function add(){
    	$m = new M();
        $userId = model('index')->getUserId();
    	$rs = $m->add($userId);
        return json_encode($rs);
    }
}
