<?php
namespace addons\distribut\controller;
use think\addons\Controller;
use addons\distribut\model\Goods as GM;
use addons\distribut\model\Distribut as DM;
use think\Db;
/**
 * 插件控制器
 */
class Apis extends Controller{
    /**
    * APP请求检测是否有安装插件
    */
    public function index(){
        return json_encode(['status'=>1]);
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
	/**
     * 域名
     */
    public function domain(){
    	return url('/','','',true);
    }
    /**
    * 分销商品列表
    */
    public function pageQuery(){
    	$m = new GM();
    	$rs = $m->pageQuery();
    	foreach ($rs['data'] as $key =>$v){
    		$rs['data'][$key]['goodsImg'] = WSTImg($v['goodsImg'],2);
    	}
    	// 域名
		$rs['domain'] = $this->domain();
    	return json_encode(WSTReturn('ok',1,$rs));
    }
    /**
     * 用户“我的”
     */
    public function userIndex(){
        $this->checkAuth();
        $m = new DM();
        $userId = model('app/index')->getUserId();
        $user = $m->getUser($userId);
        return json_encode(WSTReturn('ok',1,$user));
    }
    /**
     * 分销主页
     */
    public function distributHome(){
        $this->checkAuth();
        $m = new DM();
        $userId = model('app/index')->getUserId();
        $user = $m->getUserInfo($userId);
        $cfg = $m->getAddonConfig();
        //分享信息
        $shareInfo= array(
            'title'=>$cfg["mallShareTitle"],
            'desc'=>WSTConf('CONF.mallName'),
            'link'=>url('wechat/index/index',array('shareUserId'=>base64_encode($userId)),true,true),
            'imgUrl'=>WSTConf('CONF.mallLogo')
        );
        // 删除敏感信息
        unset($user['loginSecret'],$user['loginPwd'],$user['payPwd']);
        // 分享信息
        $user['shareInfo'] = $shareInfo;
        // 域名
        $user['domain'] = $this->domain();
        return json_encode(WSTReturn('ok',1,$user));
    }
    /**
     * 获取用户列表
     */
    public function queryDistributUsers(){
        $this->checkAuth();
        $userId = model('app/index')->getUserId();
        $m = new DM();
        $rs = $m->queryMineUsers($userId);
        $rs['domain'] = $this->domain();
        return json_encode(WSTReturn('ok',1,$rs));
    }
    /**
     * 获取佣金列表
     * type:2(分销分佣列表) 1:购买者分佣列表
     */
    public function queryDistributMoneys(){
        $this->checkAuth();
        $userId = model('app/index')->getUserId();
        $m = new DM();
        $rs = $m->queryUserMoneys($userId);
        $user = $m->getUser($userId);
        $rs['user'] = $user;
        return json_encode(WSTReturn('ok',1,$rs));
    }
    /**
      *生成二维码
      *@param $sharerId分享者id
      */
    public function makeQrCode(){
        // 分享者id
        $sharerId = (int)input('sharerId');
        // 二维码宽高
        $qrcodeWH = 275;
        $savePath = WSTRootPath().DS.'upload'.DS.'shareImg'.DS;
        if(!is_dir($savePath)){
          // 判断文件夹是否存在，若不存在则创建
          mkdir($savePath,0777);
        }

        $fileName = $sharerId.'.png';// 用户id.png
        $filePath = $savePath.$fileName;
        $imgUrl = url('/','','',true).'upload'.DS.'shareImg'.DS.$fileName;

        if(is_file($filePath))return json_encode(WSTReturn('ok',1,$imgUrl));

        $qrcodeInfo = url('mobile/index/index','','',true).'?sharerId='.base64_encode((string)$sharerId);
        $url = 'http://pan.baidu.com/share/qrcode?w='.$qrcodeWH.'&h='.$qrcodeWH.'&url='.urlencode($qrcodeInfo);

        $flag = file_put_contents($filePath, file_get_contents($url));
        if($flag!==false){
          return json_encode(WSTReturn('ok',1,$imgUrl));
        }
        return json_encode('二维码生成失败,请重试');
    }

	
}