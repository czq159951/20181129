<?php
namespace shangtao\app\controller;
use shangtao\app\model\Users as M;
use shangtao\app\model\UserScores as MUS;
use shangtao\common\model\LogSms;
use shangtao\common\model\Users as MUsers;
/**
 * 用户控制器
 */
class Users extends Base{
    // 前置方法执行列表
    protected $beforeActionList = [
          'checkAuth' =>  ['except'=>'protocol,checklogin,login,register,getphonecode,getverify,toregister,forgetpass,forgetpasst,forgetpasss,forgetpassf,findpass,getfindphone,resetpass,getfindemail']// 访问这些except下的方法不需要执行前置操作
    ];
    public function editloginPwd(){
		$m = new M();
		$userId = model('index')->getUserId();
		return json_encode($m->editPass($userId));
	}


    // 用户注册协议
    public function protocol(){
        return $this->fetch('protocol');
    }
    // 验证是否登录,
    public function checklogin(){
        if($this->checkAuth())return json_encode(WSTReturn('身份验证通过',1));
    }
    // 用户注销
    public function logout(){
        $m = new M();
        return json_encode($m->logout());
    }
	/**
	 * 会员中心
	 */
	public function index(){
		$m = new M();
		$userId = (int)$m->getUserId();
		$user = $m->getById();
		if($user['userName']=='')$user['userName']=$user['loginName'];
		//商城未读消息的数量 及 各订单状态数量
		$user['datam'] = model('index')->getSysMsg('msg','order');
		// 域名
		$user['domain'] = $this->domain();
		// 是否开启签到获得积分
		$signScore = explode(",",WSTConf('CONF.signScore'));// 签到积分配置
    	$user['isOpenSign'] = (WSTConf('CONF.signScoreSwitch')==1 && $signScore[0]>0);//是否开启积分
    	// 是否已签到
    	$m = new MUS();
    	$user['isSign'] = $m->isSign();

		echo(json_encode(WSTReturn('success',1,$user)));die;
	}
	/**
     * 登录验证
     */
	public function login(){
		$m = new M();
		return json_encode($m->login());
	}
	/**
     * 会员注册
     */
    public function register(){
    	$m = new M();
    	return json_encode($m->register());
    }
    /**
     * 注册/获取验证码
     */
    public function getphonecode(){
    	$userPhone = input("post.userPhone");
    	$rs = array();
    	if(!WSTIsPhone($userPhone)){
    		return json_encode(WSTReturn("手机号格式不正确!"));
    		exit();
    	}
    	$musers = new MUsers();
    	$rs = $musers->checkUserPhone($userPhone,0);
    	if($rs["status"]!=1){
    		return json_encode(WSTReturn("手机号已存在!"));
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
    	return json_encode($rv);
    }
    /************************************************   ********************************************************/
    /**
    * 获取用户信息
    */
    public function getById(){
        $m = new M();
        $rs = $m->getById();
        // 域名
        $rs['domain'] = $this->domain();
        return json_encode(WSTReturn('请求成功',1,$rs));
    }
    /**
	 * 修改个人信息
	 */
	public function edit(){
    	$m = new M();
    	return json_encode($m->edit());
	}
	/**
	* 修改支付密码
	*/
	public function editpayPwd(){
		$m = new M();
		return json_encode($m->editPayPass());
	}



	/***********************************  修改\绑定 手机号码 **************************************/

	/**
	 * 绑定手机：发送短信验证码
	 */
	public function sendCodeTie(){
		$userPhone = input("post.userPhone");
        if(!WSTIsPhone($userPhone)){
            return json_encode(WSTReturn("手机号格式不正确!",-1));
            exit();
        }
        $rs = array();
        $m = new M();
        // 获取用户id
        $userId = (int)$m->getUserId();

        $rs = WSTCheckLoginKey($userPhone, $userId);
        if($rs["status"]!=1){
            return json_encode(WSTReturn("手机号已存在!",-1));
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
            return json_encode(WSTReturn('短信发送成功!',1));
        }
        return json_encode($rv);
	}
	/**
	 * 绑定手机:验证校验码是否正确
	 */
	public function phoneEdit(){
		$phoneVerify = input("post.phoneCode");
        $timeVerify = session('Verify_userPhone_Time');
        if(!session('Verify_info.phoneVerify') || time()>floatval($timeVerify)+10*60){
            return WSTReturn("校验码已失效，请重新发送！");
            exit();
        }
        if($phoneVerify==session('Verify_info.phoneVerify')){
            $m = new M();
            $rs = $m->editPhone(session('Verify_info.userPhone'));
            return json_encode($rs);
        }
        return json_encode(WSTReturn("校验码不一致，请重新输入！",-1));
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
            return json_encode(WSTReturn('短信发送成功!',1));
        }
        return json_encode($rv);
	}
	/**
	 * 修改手机
	 */
	public function phoneEdito(){
		$phoneVerify = input("post.phoneCode");
        $timeVerify = session('Verify_userPhone_Time2');
        if(!session('Verify_info2.phoneVerify') || time()>floatval($timeVerify)+10*60){
            return json_encode(WSTReturn("校验码已失效，请重新发送！"));
            exit();
        }
        if($phoneVerify==session('Verify_info2.phoneVerify')){
            session('Edit_userPhone_Time',time());
            return json_encode(WSTReturn("验证成功",1));
        }
        return json_encode(WSTReturn("校验码不一致，请重新输入！",-1));
	}


	
	/**
	 * 账户安全
	 */
	public function security(){
		$m = new M();
		$user = $m->getById();
		$payPwd = $user['payPwd'];
		$userPhone = $user['userPhone'];
		$user['payPwd'] = empty($payPwd)?0:1;
		$user['userPhone'] = WSTStrReplace($user['userPhone'],'*',3);
		$user['phoneType'] = empty($userPhone)?0:1;
		session('Edit_userPhone_Time', null);
		echo(json_encode(WSTReturn('success',1,$user)));die;
	}
	
	/**
	 * 忘记密码
	 */
	public function forgetPasst(){
		if(time()<floatval(session('findPass.findTime'))+30*60){
			$userId = session('findPass.userId');
			$m = new MUsers();
			$info = $m->getById($userId);
			$infos['loginName'] = $info['loginName'];
			if($info['userPhone']!='')$infos['userPhone'] = WSTStrReplace($info['userPhone'],'*',3);
			if($info['userEmail']!='')$infos['userEmail'] = WSTStrReplace($info['userEmail'],'*',2,'@');
		}else{
			$infos['loginName'] = $infos['userPhone'] = $infos['userEmail'] ='';
		}
		echo(json_encode(WSTReturn('success',1,$infos)));die;
	}
	
	/**
	 * 找回密码
	 */
	public function findPass(){
		//禁止缓存
		header('Cache-Control:no-cache,must-revalidate');
		header('Pragma:no-cache');
		$code = input("post.verifyCode");
		$step = input("post.step/d");
		switch ($step) {
			case 1:#第一步，验证身份
				session('findPhone',null);
				if(!WSTVerifyCheck($code)){
					return json_encode(WSTReturn('验证码错误!',-1));
				}
				$loginName = input("post.loginName");
				$rs = WSTCheckLoginKey($loginName);
				if($rs["status"]==1){
					return json_encode(WSTReturn("用户名不存在!"));
					exit();
				}
				$m = new MUsers();
				$info = $m->checkAndGetLoginInfo($loginName);
				if ($info != false) {
					session('findPass',array('userId'=>$info['userId'],'loginName'=>$loginName,'userPhone'=>$info['userPhone'],'userEmail'=>$info['userEmail'],'loginSecret'=>$info['loginSecret'],'findTime'=>time()));
					return json_encode(WSTReturn("操作成功",1));
				}else return json_encode(WSTReturn("用户名不存在!"));
				break;
			case 2:#第二步,验证方式
				if (session('findPass.loginName') != null ){
					$obtainVerify = input("post.Checkcode");
					if(!$obtainVerify){
						return json_encode(WSTReturn('校验码不能为空!',-1));
					}
					if((int)input("modes")==1){
						if ( session('findPass.userPhone') == null) {
							return json_encode(WSTReturn('你没有预留手机号码，请通过邮箱方式找回密码！',-1));
						}
						return $this->testingVerify($obtainVerify);
					}else{
						if (session('findPass.userEmail')==null) {
							return json_encode(WSTReturn('你没有预留邮箱，请通过手机号码找回密码！',-1));
						}
						return $this->testingVerify($obtainVerify);
					}
				}else return json_encode(WSTReturn('操作失败',-1));
				break;
			case 3:#第三步,设置新密码
				$resetPass = session('REST_success');
				if($resetPass != 1)return json_encode(WSTReturn('操作失败',-1));
				$loginPwd = input("post.loginPwd");
				$repassword = input("post.repassword");
				if ($loginPwd == $repassword) {
					$m = new MUsers();
					$rs = $m->resetPass(1);
					return json_encode($rs);
				}else return json_encode(WSTReturn('两次密码不同！',-1));
				break;
			default:
				return json_encode(WSTReturn('操作失败',-1));
				break;
		}
	}
	/**
	 * 手机验证码获取
	 */
	public function getfindPhone(){
		session('WST_USER',session('findPass.userId'));
		if(session('findPass.userPhone')==''){
			return json_encode(WSTReturn('你没有预留手机号码，请通过邮箱方式找回密码！',-1));
		}
		$phoneVerify = rand(100000,999999);
		session('WST_USER',null);
		$rv = ['status'=>-1,'msg'=>'短信发送失败'];
		$tpl = WSTMsgTemplates('PHONE_FOTGET');
		if( $tpl['tplContent']!='' && $tpl['status']=='1'){
			$params = ['tpl'=>$tpl,'params'=>['VERFIY_CODE'=>$phoneVerify,'VERFIY_TIME'=>10]];
			$m = new LogSms();
			$rv = $m->sendSMS(0,session('findPass.userPhone'),$params,'getPhoneVerify',$phoneVerify);
		}
		if($rv['status']==1){
			$USER = '';
			$USER['phoneVerify'] = $phoneVerify;
			$USER['time'] = time();
			session('findPhone',$USER);
			return json_encode(WSTReturn('短信发送成功!',1));
		}
		return json_encode($rv);
	}
	/**
	 * 发送验证邮件/找回密码
	 */
	public function getfindEmail(){
		$smsverfy = input("post.smsVerfy");
		if(!WSTVerifyCheck($smsverfy)){
			return json_encode(WSTReturn('验证码不正确!',-1));
		}
		if (session('findPass.userEmail')==null) {
			return json_encode(WSTReturn('你没有预留邮箱，请通过手机号码找回密码！',-1));
		}
		$code = rand(0,999999);
		$sendRs = ['status'=>-1,'msg'=>'邮件发送失败'];
		$tpl = WSTMsgTemplates('EMAIL_EDIT');
		if( $tpl['tplContent']!='' && $tpl['status']=='1'){
			$find = ['${LOGIN_NAME}','${SEND_TIME}','${VERFIY_CODE}','${VERFIY_TIME}'];
			$replace = [session('findPass.loginName'),date('Y-m-d H:i:s'),$code,30];
			$sendRs = WSTSendMail(session('findPass.userEmail'),'密码重置',str_replace($find,$replace,$tpl['content']));
		}
		if($sendRs['status']==1){
			$USER = '';
			$USER['phoneVerify'] = $code;
			$USER['time'] = time();
			session('findPhone',$USER);
			return json_encode(WSTReturn("发送成功",1));
		}else{
			return json_encode(WSTReturn($sendRs['msg'],-1));
		}
	}
	/**
	 * 验证码检测/找回密码【由该控制器调用】
	 * -1 错误，1正确
	 */
	public function testingVerify($obtainVerify){
		if(!session('findPhone.phoneVerify') || time()>floatval(session('findPhone.time'))+10*60){
			return json_encode(WSTReturn("校验码已失效，请重新发送！"));
			exit();
		}
		if (session('findPhone.phoneVerify') == $obtainVerify) {
			$fuserId = session('findPass.userId');
			if(!empty($fuserId)){
				// 记录发送短信的时间,用于验证是否过期
				session('REST_Time',time());
				session('REST_userId',$fuserId);
				session('REST_success','1');
				$rs['status'] = 1;
				$rs['url'] = 'ForgetPassLast';
				return json_encode($rs);
			}
			return json_encode(WSTReturn('无效用户',-1));
		}
		return json_encode(WSTReturn('校验码错误!',-1));
	}
	/**********************************************			找回支付密码		*************************************************************/
	/**
	 * 忘记支付密码
	 */
	public function backPayPass(){
		$m = new M();
		$user = $m->getById();
		$_users = [];
		$userPhone = $user['userPhone'];
		$_users['userPhone'] = WSTStrReplace($user['userPhone'],'*',3);
		$_users['phoneType'] = empty($userPhone)?0:1;// 是否绑定了手机
		$timeVerify = session('Verify_backPaypwd_Time');
		return json_encode(WSTReturn('success',1,$_users));
		
	}
	/**
	 * 忘记支付密码：发送短信
	 */
	public function backpayCode(){
		$m = new MUsers();
		$userId = model('index')->getUserId();
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
			return json_encode(WSTReturn('短信发送成功!',1));
		}
		return json_encode($rv);
	}
	/**
	 * 忘记支付密码：验证短信
	 */
	public function verifybackPay(){
		$phoneVerify = input("post.phoneCode");
		$timeVerify = session('Verify_backPaypwd_Time');
		if(!session('Verify_backPaypwd_info.phoneVerify') || time()>floatval($timeVerify)+10*60){
			return json_encode(WSTReturn("校验码已失效，请重新发送！"));
			exit();
		}
		if($phoneVerify==session('Verify_backPaypwd_info.phoneVerify')){
			return json_encode(WSTReturn("验证成功",1));
		}
		return json_encode(WSTReturn("校验码不一致，请重新输入！"));
	}
	/**
	 * 忘记支付密码：重置密码
	 */
	public function resetbackPay(){
		$m = new MUsers();
		$userId = model('index')->getUserId();
		return json_encode($m->resetbackPay($userId));
	}
}
