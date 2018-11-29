<?php
use think\Db;
use shangtao\wechat\model\Users;
/**
 */
/**
 * 建立文件夹
 * @param string $aimUrl
 * @return viod
 */
function WSTCreateDir($aimUrl) {
	$aimUrl = str_replace('', '/', $aimUrl);
	$aimDir = '';
	$arr = explode('/', $aimUrl);
	$result = true;
	foreach ($arr as $str) {
		$aimDir .= $str . '/';
		if (!file_exists($aimDir)) {
			$result = mkdir($aimDir,0777);
		}
	}
	return $result;
}

/**
 * 下载网络文件到本地服务器
 */
function WSTDownFile($url,$folde='./Upload/image/'){
	set_time_limit (24 * 60 * 60);
	WSTCreateDir(WSTRootPath().$folde);
	$postfix = '';
	$newfname = $folde . time().rand(10,100).".".($postfix!=''?$postfix:"jpg");
	$file = fopen ($url, "rb");
	if ($file) {
		$newf = fopen ($newfname, "wb");
		if ($newf){
			while(!feof($file)) {
				fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
			}
		}
	}
	if ($file) {
		fclose($file);
	}
	if ($newf) {
		fclose($newf);
	}
	return $newfname;
}

/**
 * 微信配置
 */
function WSTWechat(){
	$wechat = new \wechat\WSTWechat(WSTConf('CONF.wxAppId'),WSTConf('CONF.wxAppKey'));
	return $wechat;
}
function WSTBindWeixin($type=1){
	$USER = session('WST_USER');
	$we = WSTWechat();
	if($USER['userId']=='' || $USER['wxOpenId']==''){
		$wdata = $we->getUserInfo(input('param.code'));//获取openid和access_token
		$userinfo = session('WST_WX_USERINFO');
		if(empty($userinfo['openid'])){
			$userinfo = $we->UserInfo($wdata);
			session('WST_WX_USERINFO',$userinfo);
		}
		WSTSigninfo($userinfo,$USER);
		$users = new Users();
		if($userinfo['openid']!=''){
		session('WST_WX_OPENID',$userinfo['openid']);
		$rs = Db::name('users')->where(['wxOpenId'=>$userinfo['openid'],'dataFlag'=>1])->field('wxOpenId')->select();
		if(count($rs)==0 && session('WST_WX_OPENID')!=''){
			if($type==1){
				header("location:".url('wechat/users/login'));
				exit;
			}
		}else{
			$users->accordLogin();
			$url = session('WST_WX_WlADDRESS');
			if($url){
				header("location:".$url);
				exit;
			}
		}
	}
	}
	WSTSigninfo(0,$USER);
}
//获取subscribe(是否关注公众号)
function WSTSigninfo($info,$user){
	if(!empty($info['openid'])){
		$we = WSTWechat();
		$openid = ($user['wxOpenId'])?$user['wxOpenId']:$info['openid'];
		$signinfo = $we->wxUserInfo($openid);
		session('WST_WX_SIGNINFO',$signinfo);
	}
}

function WSTIsWeixin(){
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
		return true;
	}
	$isLimit = false;
	$limitArr[] = array("controller"=>"Payments","action"=>"notify");
	for($i=0;$i<count($limitArr);$i++){
		$obj = $limitArr[$i];
		if(request()->controller()==$obj["controller"] && request()->action()==$obj["action"]){
			$isLimit = true;
			break;
		}
	}
	if($isLimit){
		return true;
	}
	$url=urlencode($_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
	$url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.WSTConf('CONF.wxAppId').'&redirect_uri=http%3a%2f%2f'.$url.'&response_type=code&scope=snsapi_userinfo&state='.WSTConf('CONF.wxAppCode').'#wechat_redirect';
	header("location:".$url);
	exit;
	return false;
}