<?php
namespace shangtao\home\behavior;
/**
 * 初始化基础数据
 */
class InitConfig 
{
    public function run($params){
        WSTConf('protectedUrl',model('HomeMenus')->getMenusUrl());
        hook('initConfigHook',['getParams'=>input()]);
    }
}