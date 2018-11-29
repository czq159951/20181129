<?php
namespace addons\decoration;  // 注意命名空间规范

use think\addons\Addons;
use addons\decoration\model\Decoration as DM;

/**
 * 店铺装修插件
 * @author shangtao
 */
class Decoration extends Addons{
    // 该插件的基础信息
    public $info = [
        'name' => 'Decoration',   // 插件标识
        'title' => '店铺装修',  // 插件名称
        'description' => '店铺装修插件',    // 插件简介
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
        return $flag;
    }

    /**
     * 插件设置方法
     * @return bool
     */
    public function saveConfig(){
        WSTClearHookCache();
        return true;
    }
    /**
     * 跳去店铺首页前执行
     */
    public function homeBeforeGoShopHome($params){
        $m = new DM();
        $shopId = (int)$params["shopId"];
        $shopId = ($shopId>0)?$shopId:1;
        $conf = $m->getShopConf($shopId);
        if($conf["userDecoration"]==1){
        	echo $this->fetch('shoptpl/'.md5($shopId));
        	exit();
        }
        
    }
    /**
     * 跳去自营店铺首页前执行
     */
    public function homeBeforeGoSelfShop($params){
    	$m = new DM();
    	$shopId = (int)$params["shopId"];
        $shopId = ($shopId>0)?$shopId:1;
    	$conf = $m->getShopConf($shopId);
    	if($conf["userDecoration"]==1){
    		echo $this->fetch('shoptpl/'.md5($shopId));
    		exit();
    	}
    }
    
}