<?php
namespace shangtao\admin\behavior;
/**
 * 初始化基础数据
 */
class InitConfig 
{
    public function run($params){
        WSTConf('listenUrl',WSTVisitPrivilege());
    }
}