<?php
namespace shangtao\mobile\controller;
use shangtao\common\model\GoodsConsult as CG;
/**
 * 商品咨询控制器
 */
class GoodsConsult extends Base{
    /**
    * 商品咨询页
    */
    public function index(){
        $this->assign('goodsId',(int)input('goodsId'));
        return $this->fetch('goodsconsult/list');
    }
    /**
    * 根据商品id获取商品咨询
    */
    public function listQuery(){
        $m = new CG();
        return $m->listQuery();
    }
    /**
    * 发布商品咨询页
    */
    public function consult(){
        $this->assign('goodsId',(int)input('goodsId'));
        return $this->fetch('goodsconsult/consult');
    }
    public function add(){
        $m = new CG();
        return $m->add();
    }

}
