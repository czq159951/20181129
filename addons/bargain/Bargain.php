<?php
namespace addons\bargain;


use think\addons\Addons;
use addons\bargain\model\Bargains as DM;

/**
 * 帮忙砍价插件
 */
class Bargain extends Addons{
    // 该插件的基础信息
    public $info = [
        'name' => 'Bargain',   // 插件标识
        'title' => '全民砍价',  // 插件名称
        'description' => '邀请好友帮忙砍价插件<font color="red">【仅微信端】</font>',    // 插件简介
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
        $flag = $m->install();
        WSTClearHookCache();
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
     * 微信用户“我的”
     */
    public function wechatDocumentUserIndexTools(){
    	return $this->fetch('view/wechat/users/index');
    }
}