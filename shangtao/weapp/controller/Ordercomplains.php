<?php
namespace shangtao\weapp\controller;
use shangtao\weapp\model\OrderComplains as M;
/**
 * 投诉控制器
 */
class orderComplains extends Base{
    // 前置方法执行列表
    protected $beforeActionList = [
        'checkAuth'
    ];
	/**
     * 保存订单投诉信息
     */
    public function saveComplain(){
    	$m = new M(); 
        $rs = $m->saveComplain();
        return jsonReturn('',1,$rs);
    }

    /**
    * 获取用户投诉列表
    */    
    public function complainByPage(){
        $m = new M();
        $data = $m->queryUserComplainByPage();
        echo(jsonReturn('success',1,$data));die;
    }

    /**
     * 用户查投诉详情
     */
    public function getComplainDetail(){
    	$m = new M();
        $rs['list'] = $m->getComplainDetail(0);
        $annex = $rs['list']['complainAnnex'];
        if($annex){
        	foreach($annex as $k=>$v){
        		$annex1[] = WSTImg($v,3);
        	}
        	$rs['list']['complainAnnex'] = $annex1;
        }
        echo(jsonReturn('success',1,$rs));die;
    }

}
