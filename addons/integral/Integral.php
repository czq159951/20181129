<?php
namespace addons\integral;  // 注意命名空间规范


use think\addons\Addons;
use addons\integral\model\Integrals as DM;

/**
 * 积分商城插件
 * @author WSTMart
 */
class Integral extends Addons{
    // 该插件的基础信息
    public $info = [
        'name' => 'Integral',   // 插件标识
        'title' => '积分商城',  // 插件名称
        'description' => '积分商城',    // 插件简介
        'status' => 0,  // 状态
        'author' => 'shangtao',
        'version' => '1.0.0'
    ];

	
    /**
     * 插件安装方法
     * @return bool
     */
    public function install(){
        $m = new DM();
        $flag = $m->installMenu();
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
        $flag = $m->uninstallMenu();
        WSTClearHookCache();
        cache('hooks',null);
        return $flag;
    }
    
	/**
     * 插件启用方法
     * @return bool
     */
    public function enable(){
        $m = new DM();
        $flag = $m->toggleShow(1);
        WSTClearHookCache();
        cache('hooks',null);
        return $flag;
    }
    
    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable(){
        $m = new DM();
        $flag = $m->toggleShow(0);
        WSTClearHookCache();
        cache('hooks',null);
        return $flag;
    }

    /**
     * 插件设置方法
     * @return bool
     */
    public function saveConfig(){
        WSTClearHookCache();
        cache('hooks',null);
        return true;
    }
  
    /**
     * 订单取消之后执行
     */
    public function afterCancelOrder($params){
        $m = new DM();
        $m->cancelOrder($params);
        return true;
    }
    
}