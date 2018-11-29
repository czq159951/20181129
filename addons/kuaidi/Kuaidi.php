<?php
namespace addons\kuaidi;  // 注意命名空间规范


use think\addons\Addons;
use addons\kuaidi\model\Kuaidi as DM;

/**
 * 快递100
 * @author shangtao
 */
class Kuaidi extends Addons{
    // 该插件的基础信息
    public $info = [
        'name' => 'Kuaidi',   // 插件标识
        'title' => '快递100',  // 插件名称
        'description' => '为您更好的跟踪您的订单动态',    // 插件简介
        'status' => 0,  // 状态
        'author' => 'shangtao',
        'version' => '1.0.1'
    ];

	
    /**
     * 插件安装方法
     * @return bool
     */
    public function install(){
    	$m = new DM();
    	$flag = $m->install();
    	WSTClearHookCache();
    	cache('hooks',null);
        return $flag;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall(){
    	$m = new DM();
    	$flag = $m->uninstall();
    	WSTClearHookCache();
    	cache('hooks',null);
        return $flag;
    }
    
	/**
     * 插件启用方法
     * @return bool
     */
    public function enable(){
    	WSTClearHookCache();
    	cache('hooks',null);
        return true;
    }
    
    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable(){
    	WSTClearHookCache();
    	cache('hooks',null);
    	return true;
    }

    /**
     * 插件设置方法
     * @return bool
     */
    public function saveConfig(){
    	$m = new DM();
    	WSTClearHookCache();
    	cache('hooks',null);
    	return true;
    }
    /**
     * 跳转订单详情【admin】
     */
    public function adminDocumentOrderView($params){
        $m = new DM();
        $rs = $m->getOrderDeliver($params['orderId']);
        if($rs["deliverType"]==0 && $rs["orderStatus"]>0){
        	$express = $m->getExpress($params['orderId']);
        	if($express["expressNo"]!=""){
	            $rs = $m->getOrderExpress($params['orderId']);
	            
	            $expressLogs = json_decode($rs, true);
	            $this->assign('expressLogs', $expressLogs);
	            return $this->fetch('view/admin/view');
        	}
        }
    }
    
    /**
     * 跳转订单详情【home】
     */
    public function homeDocumentOrderView($params){
    	$m = new DM();
    	$rs = $m->getOrderDeliver($params['orderId']);
    	if($rs["deliverType"]==0 && $rs["orderStatus"]>0){
    		$express = $m->getExpress($params['orderId']);
    		if($express["expressNo"]!=""){
    			$rs = $m->getOrderExpress($params['orderId']);
    			$expressLogs = json_decode($rs, true);
    			$this->assign('expressLogs', $expressLogs);
    			return $this->fetch('view/home/view');
    		}
    		
    	}
    }
    
	public function afterQueryUserOrders($params){
		$m = new DM();
    	foreach ($params["page"]["data"] as $key => $v){
    		$rs = $m->getOrderDeliver($v['orderId']);
    		if($rs["deliverType"]==0 && $rs["orderStatus"]>0 && $rs["expressNo"]!=""){
    			$bnt = '<button class="ui-btn o-btn o-cancel-btn" onclick="checkExpress('.$v['orderId'].')">查看物流</button>';
    			$params["page"]["data"][$key]['hook'] = ($v['orderStatus']==1 || $v['orderStatus']==2)?$bnt:"";
    		}else{
    			$params["page"]["data"][$key]['hook'] = "";
    		}
    		
    	}
    }
    
    /**
     * 订单列表【mobile】
     */
	public function mobileDocumentOrderList(){
		return $this->fetch('view/mobile/view');
	}
	
	/**
	 * 订单列表【wechat】
	 */
	public function wechatDocumentOrderList(){
		return $this->fetch('view/wechat/view');
	}

}