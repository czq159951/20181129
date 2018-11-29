<?php
namespace shangtao\common\behavior;
/**
 * 初始化基础数据
 */
class InitConfig 
{
    public function run($params){
        WSTConf('CONF',WSTConfig());
    }
}