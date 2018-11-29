<?php
namespace addons\pintuan;  // 注意命名空间规范


use think\addons\Addons;
use addons\pintuan\model\Pintuans as DM;

/**
 * 拼团插件
 * @author shangtao
 */
class Pintuan extends Addons{
    // 该插件的基础信息
    public $info = [
        'name' => 'Pintuan',   // 插件标识
        'title' => '拼团',  // 插件名称
        'description' => '拼团插件<font color="red">【仅微信端，需计划任务支持!!】</font>',    // 插件简介
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
     * 订单取消之前执行
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
        $m = new DM();
        $data = $m->getConf('Pintuan');
        $addonStyle = ($data['addonsStyle']=='')?'default':$data['addonsStyle'];
        $this->assign("addonStyle",$addonStyle);
        return $this->fetch('view/'.$addonStyle.'/wechat/users/index');
    }
    
}