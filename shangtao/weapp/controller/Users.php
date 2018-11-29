<?php
namespace shangtao\weapp\controller;
use shangtao\weapp\model\Users as M;
use shangtao\common\model\Users as MUsers;
use shangtao\weapp\model\UserScores as MUS;
use shangtao\common\model\LogSms;
use think\Db;
/**
 * 用户控制器
 */
class Users extends Base{
    // 前置方法执行列表
    protected $beforeActionList = [
          'checkAuth' =>  ['except'=>'checklogin,login,register,getphonecode,getverify,toregister,findpass,findpassinfo,getfindphone,verifybacklogin,resetfindpass,handlelogin,checkuserphone']// 访问这些except下的方法不需要执行前置操作
    ];
    // 验证是否登录,
    public function checklogin(){
        if($this->checkAuth())return jsonReturn('身份验证通过',1);
    }
	/**
	 * 会员中心
	 */
	public function index(){
		$m = new M();
		$userId = (int)$m->getUserId();
		$user = $m->getById();
		if($user['userName']=='')$user['userName']=$user['loginName'];
		$user['userPhoto'] = $this->userPhoto($user['userPhoto']);
		//商城未读消息的数量 及 各订单状态数量
		$user['datam'] = model('index')->getSysMsg('msg','order');
		// 是否开启签到获得积分
		$signScore = explode(",",WSTConf('CONF.signScore'));// 签到积分配置
    	$user['isOpenSign'] = (WSTConf('CONF.signScoreSwitch')==1 && $signScore[0]>0);//是否开启积分
    	// 是否已签到
    	$m = new MUS();
    	$user['isSign'] = $m->isSign();
        $user = $user->toArray();
		return jsonReturn('success',1,$user);
	}
	/**
     * 登录验证
     */
	public function login(){
		$m = new M();
		return $m->login();
	}
	/**
     * 会员注册
     */
    public function register(){
    	$m = new M();
    	return $m->register();
    }
    /**
     * 登陆处理
     */
    public function handleLogin(){
    	$openId = $session_key = $unionId = [];
    	$sessionKey = input("sessionKey");
    	if(!$sessionKey)return jsonReturn('',-1);
    	$sessionKey = base64_decode($sessionKey);
    	$sessionKey = explode('_',$sessionKey);
    	if(isset($sessionKey[0]))$openId = base64_decode($sessionKey[0]);
    	if(isset($sessionKey[1]))$session_key = base64_decode($sessionKey[1]);
    	$unionKey = input("unionKey");
    	if($unionKey){
    		$unionKey = base64_decode($unionKey);
    		$unionKey = explode('_',$unionKey);
    		if(isset($sessionKey[0]))$unionId = base64_decode($unionKey[0]);
    	}
    	if($openId){
    		$m = new M();
    		$rs = $m->accordLogin($openId,$unionId);
    		return $rs;
    	}else{
    		return jsonReturn('',-1);
    	}
    }
    /**
     * 头像处理
     */
    public function userPhoto($userPhoto){
    	if(substr($userPhoto,0,4)!='http' && $userPhoto){
    		$userPhoto  = url('/','','',true).$userPhoto;
    	}else if(!$userPhoto){
    		$userPhoto  = url('/','','',true).WSTConf('CONF.userLogo');
    	}
    	return $userPhoto;
    }
    /**
     * 注册/获取验证码
     */
    public function getphonecode(){
    	$userPhone = input("post.userPhone");
    	$rs = array();
    	if(!WSTIsPhone($userPhone)){
    		return jsonReturn('手机号格式不正确!');
    		exit();
    	}
    	$musers = new MUsers();
    	$rs = $musers->checkUserPhone($userPhone,0);
    	if($rs["status"]!=1){
    		return jsonReturn('手机号已存在!');
    		exit();
    	}
    	$phoneVerify = rand(100000,999999);
    	$tpl = WSTMsgTemplates('PHONE_USER_REGISTER_VERFIY');
    	if( $tpl['tplContent']!='' && $tpl['status']=='1'){
    		$params = ['tpl'=>$tpl,'params'=>['MALL_NAME'=>WSTConf("CONF.mallName"),'VERFIY_CODE'=>$phoneVerify,'VERFIY_TIME'=>10]];
    		$m = new LogSms();
    		$rv = $m->sendSMS(0,$userPhone,$params,'getPhoneVerifyCode',$phoneVerify);
    	}
    	if($rv['status']==1){
    		session('VerifyCode_userPhone',$userPhone);
    		session('VerifyCode_userPhone_Verify',$phoneVerify);
    		session('VerifyCode_userPhone_Time',time());
    	}
    	return jsonReturn('',1,$rv);
    } 
    /**
	 * 修改个人信息
	 */
	public function edit(){
    	$m = new M();
    	return jsonReturn($m->edit());
	}
	/**
	* 修改支付密码
	*/
	public function editpayPwd(){
		$m = new M();
		$mu = new MUsers();
		$userId = $m->getUserId();
		$rs = $mu->editPayPass($userId);
		return jsonReturn('',1,$rs);
	}
	/**
	 * 修改登录密码
	 */
	public function editloginPwd(){
		$m = new M();
		$mu = new MUsers();
		$userId = $m->getUserId();
		$rs = $mu->editPass($userId);
		return jsonReturn('',1,$rs);
	}
	/**
	 * 手机号码是否存在
	 */
	public function checkUserPhone(){
		$userPhone = input("post.userPhone");
		$m = new MUsers();
		$rs = $m->checkUserPhone($userPhone,0);
		if($rs["status"]!=1){
			return jsonReturn("手机号已注册",-1);
		}else{
			return jsonReturn("",1);
		}
	}
	/***********************************  修改\绑定 手机号码 **************************************/
	/**
	 * 绑定手机：发送短信验证码
	 */
	public function sendCodeTie(){
		$userPhone = input("post.userPhone");
        if(!WSTIsPhone($userPhone)){
            return jsonReturn("手机号格式不正确!",-1);
            exit();
        }
        $rs = array();
        $m = new M();
        // 获取用户id
        $userId = $m->getUserId();

        $rs = WSTCheckLoginKey($userPhone, $userId);
        if($rs["status"]!=1){
            return jsonReturn("手机号已存在!",-1);
            exit();
        }
        $data = $m->getById();
        $phoneVerify = rand(100000,999999);
        $rv = ['status'=>-1,'msg'=>'短信发送失败'];
        $tpl = WSTMsgTemplates('PHONE_BIND');
        if( $tpl['tplContent']!='' && $tpl['status']=='1'){
            $params = ['tpl'=>$tpl,'params'=>['LOGIN_NAME'=>$data['loginName'],'VERFIY_CODE'=>$phoneVerify,'VERFIY_TIME'=>10]];
            $m = new LogSms();
            $rv = $m->sendSMS(0,$userPhone,$params,'sendCodeTie',$phoneVerify);
        }
        if($rv['status']==1){
            $USER = '';
            $USER['userPhone'] = $userPhone;
            $USER['phoneVerify'] = $phoneVerify;
            session('Verify_info',$USER);
            session('Verify_userPhone_Time',time());
            return jsonReturn('短信发送成功!',1);
        }
        return jsonReturn('',1,$rv);
	}
	/**
	 * 绑定手机:验证校验码是否正确
	 */
	public function phoneEdit(){
		$phoneVerify = input("post.phoneCode");
        $timeVerify = session('Verify_userPhone_Time');
        if(!session('Verify_info.phoneVerify') || time()>floatval($timeVerify)+10*60){
            return jsonReturn("校验码已失效，请重新发送！");
            exit();
        }
        if($phoneVerify==session('Verify_info.phoneVerify')){
        	$m = new M();
        	$userId = $m->getUserId();
            $mu = new MUsers();
            $rs = $mu->editPhone($userId,session('Verify_info.userPhone'));
            return jsonReturn('',1,$rs);
        }
        return jsonReturn("校验码不一致，请重新输入！");
	}
	/**
	 * 修改手机：发送短信验证码
	 */
	public function sendCodeEdit(){
    	$m = new M();
        $data = $m->getById();
        $userPhone = $data['userPhone'];
        $phoneVerify = rand(100000,999999);
        $rv = ['status'=>-1,'msg'=>'短信发送失败'];
        $tpl = WSTMsgTemplates('PHONE_EDIT');
        if( $tpl['tplContent']!='' && $tpl['status']=='1'){
            $params = ['tpl'=>$tpl,'params'=>['LOGIN_NAME'=>$data['loginName'],'VERFIY_CODE'=>$phoneVerify,'VERFIY_TIME'=>10]];
            $m = new LogSms();
            $rv = $m->sendSMS(0,$userPhone,$params,'getPhoneVerifyt',$phoneVerify);
        }
        if($rv['status']==1){
            $USER = '';
            $USER['userPhone'] = $userPhone;
            $USER['phoneVerify'] = $phoneVerify;
            session('Verify_info2',$USER);
            session('Verify_userPhone_Time2',time());
            return jsonReturn('短信发送成功!',1);
        }
        return jsonReturn('',1,$rv);
	}
	/**
	 * 修改手机
	 */
	public function phoneEdito(){
		$phoneVerify = input("post.phoneCode");
        $timeVerify = session('Verify_userPhone_Time2');
        if(!session('Verify_info2.phoneVerify') || time()>floatval($timeVerify)+10*60){
            return jsonReturn("校验码已失效，请重新发送！");
            exit();
        }
        if($phoneVerify==session('Verify_info2.phoneVerify')){
            session('Edit_userPhone_Time',time());
            return jsonReturn("验证成功",1);
        }
        return jsonReturn("校验码不一致，请重新输入！");
	}

	/**
	 * 账户安全
	 */
	public function security(){
		$m = new M();
		$mu = new MUsers();
		$userId = $m->getUserId();
		$user = $mu->getById($userId);
		$loginPwd = $user['loginPwd'];
		$payPwd = $user['payPwd'];
		$userPhone = $user['userPhone'];
		$users['loginPwd'] = empty($loginPwd)?0:1;
		$users['payPwd'] = empty($payPwd)?0:1;
		$users['userPhone'] = WSTStrReplace($userPhone,'*',3);
		$users['phoneType'] = empty($userPhone)?0:1;
		session('Edit_userPhone_Time', null);
		return jsonReturn('success',1,$users);
	}
	/**
	 * 找回登陆密码（未登录）
	 */
	public function findPass(){
		$loginName = input("post.loginName");
		$code = input("post.verifyCode");
		session('findPass',null);
		if(!WSTVerifyCheck($code)){
			return jsonReturn('验证码错误!',-1);
		}
		$rs = WSTCheckLoginKey($loginName);
		if($rs["status"]==1){
			return jsonReturn("用户名不存在!");
			exit();
		}
		$m = new MUsers();
		$info = $m->checkAndGetLoginInfo($loginName);
		$info['userPhone'] = WSTStrReplace($info['userPhone'],'*',3);
		$info['phoneType'] = empty($info['userPhone'])?0:1;
		if ($info != false) {
			session('findPass',array('userId'=>$info['userId'],'loginName'=>$loginName,'userPhone'=>$info['userPhone'],'userEmail'=>$info['userEmail'],'phoneType'=>$info['phoneType'],'findTime'=>time()));
			return jsonReturn("操作成功",1);
		}else{
			return jsonReturn("用户名不存在!");
		}
	}
	/**
	 * 找回登陆密码（未登录）：获取用户手信息
	 */
	public function findPassInfo(){
		$info = session('findPass');
		if($info && (time()<floatval($info['findTime']))+30*60){
			return jsonReturn("操作成功",1,$info);
		}else{
			session('findPass',null);
			return jsonReturn("信息过期",-1);
		}
	}
	/**
	 * 找回登陆密码（未登录）：手机验证码获取
	 */
	public function getfindPhone(){
		$m = new MUsers();
		$userId = (int)session('findPass.userId');
		$data = $m->getById($userId);
		$userPhone = $data['userPhone'];
		$phoneVerify = rand(100000,999999);
		$rv = ['status'=>-1,'msg'=>'短信发送失败'];
		$tpl = WSTMsgTemplates('PHONE_FOTGET');
		if( $tpl['tplContent']!='' && $tpl['status']=='1'){
			$params = ['tpl'=>$tpl,'params'=>['LOGIN_NAME'=>$data['loginName'],'VERFIY_CODE'=>$phoneVerify,'VERFIY_TIME'=>10]];
			$m = new LogSms();
			$rv = $m->sendSMS(0,$userPhone,$params,'getPhoneVerifyt',$phoneVerify);
		}
		if($rv['status']==1){
			$USER = [];
			$USER['userPhone'] = $userPhone;
			$USER['phoneVerify'] = $phoneVerify;
			session('Verify_backLoginpwd_info',$USER);
			session('Verify_backLoginpwd_Time',time());
			return jsonReturn('短信发送成功!',1);
		}
		return jsonReturn('',1,$rv);
	}
	/**
	 * 找回登陆密码（未登录）：重置密码
	 */
	public function resetfindPass(){
		$m = new M();
		$userId = (int)session('findPass.userId');
		$rs = $m->resetbackLogin($userId);
		return jsonReturn('',1,$rs);
	}
	/**********************************************			找回登陆密码（已登录）		*************************************************************/
	/**
	 * 找回登陆密码：发送短信
	 */
	public function backloginCode(){
		$m = new MUsers();
		$userId = model('weapp/index')->getUserId();
		$data = $m->getById($userId);
		$userPhone = $data['userPhone'];
		$phoneVerify = rand(100000,999999);
		$rv = ['status'=>-1,'msg'=>'短信发送失败'];
		$tpl = WSTMsgTemplates('PHONE_FOTGET');
		if( $tpl['tplContent']!='' && $tpl['status']=='1'){
			$params = ['tpl'=>$tpl,'params'=>['LOGIN_NAME'=>$data['loginName'],'VERFIY_CODE'=>$phoneVerify,'VERFIY_TIME'=>10]];
			$m = new LogSms();
			$rv = $m->sendSMS(0,$userPhone,$params,'getPhoneVerifyt',$phoneVerify);
		}
		if($rv['status']==1){
			$USER = [];
			$USER['userPhone'] = $userPhone;
			$USER['phoneVerify'] = $phoneVerify;
			session('Verify_backLoginpwd_info',$USER);
			session('Verify_backLoginpwd_Time',time());
			return jsonReturn('短信发送成功!',1);
		}
		return jsonReturn('',1,$rv);
	}
	/**
	 * 找回登陆密码：验证短信
	 */
	public function verifybackLogin(){
		$phoneVerify = input("post.phoneCode");
		$timeVerify = session('Verify_backLoginpwd_Time');
		if(!session('Verify_backLoginpwd_info.phoneVerify') || time()>floatval($timeVerify)+10*60){
			return jsonReturn("校验码已失效，请重新发送！");
			exit();
		}
		if($phoneVerify==session('Verify_backLoginpwd_info.phoneVerify')){
			return jsonReturn("验证成功",1);
		}
		return jsonReturn("校验码不一致，请重新输入！");
	}
	/**
	 * 找回登陆密码：重置密码
	 */
	public function resetbackLogin(){
		$m = new M();
		$userId = $m->getUserId();
		$rs = $m->resetbackLogin($userId);
		return jsonReturn('',1,$rs);
	}
	/**********************************************			找回支付密码		*************************************************************/
	/**
	 * 找回支付密码：发送短信
	 */
	public function backpayCode(){
		$m = new MUsers();
		$userId = model('weapp/index')->getUserId();
		$data = $m->getById($userId);
		$userPhone = $data['userPhone'];
		$phoneVerify = rand(100000,999999);
		$rv = ['status'=>-1,'msg'=>'短信发送失败'];
		$tpl = WSTMsgTemplates('PHONE_FOTGET_PAY');
		if( $tpl['tplContent']!='' && $tpl['status']=='1'){
			$params = ['tpl'=>$tpl,'params'=>['LOGIN_NAME'=>$data['loginName'],'VERFIY_CODE'=>$phoneVerify,'VERFIY_TIME'=>10]];
			$m = new LogSms();
			$rv = $m->sendSMS(0,$userPhone,$params,'getPhoneVerifyt',$phoneVerify);
		}
		if($rv['status']==1){
			$USER = [];
			$USER['userPhone'] = $userPhone;
			$USER['phoneVerify'] = $phoneVerify;
			session('Verify_backPaypwd_info',$USER);
			session('Verify_backPaypwd_Time',time());
			return jsonReturn('短信发送成功!',1);
		}
		return jsonReturn('',1,$rv);
	}
	/**
	 * 找回支付密码：验证短信
	 */
	public function verifybackPay(){
		$phoneVerify = input("post.phoneCode");
		$timeVerify = session('Verify_backPaypwd_Time');
		if(!session('Verify_backPaypwd_info.phoneVerify') || time()>floatval($timeVerify)+10*60){
			return jsonReturn("校验码已失效，请重新发送！");
			exit();
		}
		if($phoneVerify==session('Verify_backPaypwd_info.phoneVerify')){
			return jsonReturn("验证成功",1);
		}
		return jsonReturn("校验码不一致，请重新输入！");
	}
	/**
	 * 找回支付密码：重置密码
	 */
	public function resetbackPay(){
		$m = new MUsers();
		$userId = model('weapp/index')->getUserId();
		$rs = $m->resetbackPay($userId);
		return jsonReturn('',1,$rs);
	}
    public function aboutUs(){
    	$data = [];
        $data['shopLogo'] = WSTImg(WSTConf('CONF.mallLogo'),3);
        $data['mallName'] = WSTConf('CONF.mallName');
        $data['serviceTel'] = WSTConf('CONF.serviceTel');
        $data['serviceQQ'] = WSTConf('CONF.serviceQQ');
        $data['serviceEmail'] = WSTConf('CONF.serviceEmail');
        $data['copyRight'] = WSTConf('CONF.copyRight');
        return jsonReturn('',1,$data);
    }
}
