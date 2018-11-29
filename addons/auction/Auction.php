<?php
namespace addons\auction;  // 注意命名空间规范


use think\addons\Addons;
use addons\auction\model\Auctions as DM;

/**
 * 拍卖活动插件
 * @author shangtao
 */
class Auction extends Addons{
    // 该插件的基础信息
    public $info = [
        'name' => 'Auction',   // 插件标识
        'title' => '拍卖活动',  // 插件名称
        'description' => '拍卖活动插件<font color="red">【需计划任务支持!!】</font>',    // 插件简介
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
     * 订单取消之后执行
     */
    public function beforeCancelOrder($params){
        $m = new DM();
        $m->beforeCancelOrder($params);
        return true;
    }
    
    /**
     * 微信用户“我的”
     */
    public function wechatDocumentUserIndexTools(){
    	return $this->fetch('view/wechat/users/index');
    }
    /**
     * 手机用户“我的”
     */
    public function mobileDocumentUserIndexTools(){
    	return $this->fetch('view/mobile/users/index');
    }
}