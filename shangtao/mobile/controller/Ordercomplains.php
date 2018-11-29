<?php
namespace shangtao\mobile\controller;
use shangtao\common\model\OrderComplains as M;
/**
 * 投诉控制器
 */
class orderComplains extends Base{
    // 前置方法执行列表
    protected $beforeActionList = [
        'checkAuth'
    ];
	public function complain(){
		$oId = (int)input('oId');
		$this->assign('oId',$oId);
		return $this->fetch('users/orders/orders_complains');
	}
	/**
     * 保存订单投诉信息
     */
    public function saveComplain(){
        return model('OrderComplains')->saveComplain();
    }
    /**
    * 用户投诉列表
    */
    public function index(){
    	return $this->fetch('users/orders/list_complains');
    }

    /**
    * 获取用户投诉列表
    */    
    public function complainByPage(){
        $m = model('OrderComplains');
        return $m->queryUserComplainByPage();
        
    }

    /**
     * 用户查投诉详情
     */
    public function getComplainDetail(){
        $rs = model('OrderComplains')->getComplainDetail(0);
        $annex = $rs['complainAnnex'];
        if($annex){
        	foreach($annex as $k=>$v){
        		$annex1[] = WSTImg($v,3);
        	}
        	$rs['complainAnnex'] = $annex1;
        }
        return $rs;
    }

}
