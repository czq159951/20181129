<?php
namespace addons\pintuan\controller;

use think\addons\Controller;
use addons\pintuan\model\Pintuans as M;
/**
 * 拼团商品插件
 */
class Apis extends Controller{
    /**
     * 域名
     */
    public function domain(){
        return url('/','','',true);
    }
    
}