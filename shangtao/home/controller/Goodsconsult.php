<?php
namespace shangtao\home\controller;
use shangtao\common\model\GoodsConsult as M;
/**
 * 商品咨询控制器
 */
class GoodsConsult extends Base{
    protected $beforeActionList = [
          'checkShopAuth'=>['only'=>'pageQuery,shopReplyConsult,reply']
    ];
	/**
	* 根据商品id获取商品咨询
	*/
    public function listQuery(){
        $m = new M();
        $rs = $m->listQuery();
        return $rs;
    }
    /**
    * 新增
    */
    public function add(){
    	$m = new M();
    	return $m->add();
    }
    /**
    * 修改
    */
    public function edit(){   
        $m = new M();
        return $m->edit();
    }
    /**
	* 根据店铺id获取商品咨询
	*/
    public function pageQuery(){
        $m = new M();
        $rs = $m->pageQuery();
        return $rs;
    }
    /**
	* 获取商品咨询 商家
	*/
	public function shopReplyConsult(){
		return $this->fetch('shops/goodsconsult/list');
	}
    /**
    * 商家回复
    */
    public function reply(){
    	$m = new M();
    	return $m->reply();
    }
    /**
    * 用户-商品咨询
    */
    public function myConsult(){
        return $this->fetch('users/my_consult');
    }
    /**
    * 用户-商品咨询列表查询
    */
    public function myConsultByPage(){
        $m = new M();
        return $m->myConsultByPage();
    }
}
