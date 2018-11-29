<?php
namespace shangtao\app\controller;
/**
 * 默认控制器
 */
use think\Controller;
use think\Db;
class Base extends Controller{
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
            $rs = json_encode(WSTReturn('您还未登录',-999));
            die($rs);
        }
        $userId = Db::name('app_session')->where("tokenId='{$tokenId}'")->value('userId');
        if(empty($userId)){
            $rs = json_encode(WSTReturn('登录信息已过期,请重新登录',-999));
            die($rs);
        }
        return true;
    }
    //登录验证方法--商家
    protected function checkShopAuth(){
        $this->checkAuth();
        $shopId = $this->getShopId();
        if($shopId>0)return true;
        return false;
    }
    // 获取商家id
    protected function getShopId(){
        $userId = model('index')->getUserId();
        return model('app/shops')->getShopId($userId);
    }


    /**
     * 上传图片
     */
    public function uploadPic(){
        return WSTUploadPic(0);
    }
    /**
    * 获取插件状态
    */
    public function getAddonStatus(){
        $addons = ['Auction','Kuaidi','Coupon','Reward','Integral','Groupon','Distribut','Wstim','Thirdlogin'];
        $rs = Db::name('addons')->where('dataFlag',1)->field('name,status')->select();
        $arr = [];
        foreach ($rs as $k=>$v) {
            if(in_array($v['name'], $addons)){
                $arr[$v['name']] = ($v['status']==1);
            }
        }
        if(isset($arr['Thirdlogin']) && $arr['Thirdlogin']==true ){
            $config = Db::name('addons')->where(['dataFlag'=>1,'name'=>'Thirdlogin'])->value('config');
            $config = json_decode($config,true);
            // 获取开启了哪些第三方登录
            $arr['ThirdloginCfg'] = $config['thirdTypes'];
        }
        return json_encode(WSTReturn('ok',1,$arr));
    }
}
