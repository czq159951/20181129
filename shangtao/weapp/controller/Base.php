<?php
namespace shangtao\weapp\controller;
use think\Controller;
use think\Db;
/**
 * 默认控制器
 */
class Base extends Controller{
	public function __construct(){
		parent::__construct();
        $sessionId = input("sessionId");
		if($sessionId)session_id($sessionId);
	}
    /**
     * 域名
     */
    public function domain(){
        return url('/','','',true);
    }
    /**
	 * 获取验证码
	 */
	public function getVerify(){
		WSTVerify();
	}
    // 权限验证方法
    protected function checkAuth(){
        $tokenId = input('tokenId');
        if($tokenId==''){
            $rs = jsonReturn('您还未登录',-999);
            die($rs);
        }
        $userId = Db::name('weapp_session')->alias('as')
        ->join('__USERS__ u','u.userId=as.userId','inner')
        ->where("as.tokenId='{$tokenId}' and u.dataflag=1 and u.userStatus=1")
        ->value('as.userId');
        if(empty($userId)){
            $rs = jsonReturn('登录信息已过期,请重新登录',-999);
            die($rs);
        }
        return true;
    }
    /**
     * 上传图片
     */
    public function uploadPic(){
        return WSTUploadPic(0);
    }
    /**
     * 转换图片即删除无用字段
     */
    public function transitionImg($img){
    	if(empty($img))return [];
    	// 图片转换及删除无用字段
    	$_img = [];
    	foreach ($img as $k => $v) {
    		$_img[$k]['adId'] = $v['adId'];
    		$_img[$k]['adURL'] = $v['adURL'];
    		$_img[$k]['adFile'] = WSTImg($v['adFile'],2);
    	}
    	return $_img;
    }
}
