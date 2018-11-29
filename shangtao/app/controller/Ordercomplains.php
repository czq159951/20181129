<?php
namespace shangtao\app\controller;
use shangtao\app\model\OrderComplains as M;
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
        return json_encode($rs);
    }

    /**
    * 获取用户投诉列表
    */    
    public function complainByPage(){
        $m = new M();
        $data = $m->queryUserComplainByPage();
        echo(json_encode(WSTReturn('success',1,$data)));die;
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
        		$annex1[] = WSTImg($v,2);
        	}
        	$rs['list']['complainAnnex'] = $annex1;
        }
        // 域名
		$rs['domain'] = $this->domain();
        echo(json_encode(WSTReturn('success',1,$rs)));die;
    }

}
