<?php
namespace shangtao\weapp\model;
use think\Db;
/**
 * 用户类
 */
class Users extends Base{
	protected $pk = 'userId';
	/**
	 * 登录验证
	 * loginKey 64位加密传过来，密匙->例如:base64(base64(账号)._.base64(密码))
	 * status:-1:账号不存在!  -2:账号已被停用! -3:账号或密码不正确! 1:登录成功~
	 * msg:登录信息
	 */
	public function login(){
    	$loginName = input("post.loginName");
    	$loginPwd = input("post.loginPwd");
		$code = input("verifyCode");
		if(!WSTVerifyCheck($code) && strpos(WSTConf("CONF.captcha_model"),"4")>=0){
			return jsonReturn('验证码错误!',-1);
		}
		$decrypt_data = WSTRSA($loginPwd);
		if($decrypt_data['status']==1){
			$loginPwd = $decrypt_data['data'];
		}else{
			return WSTReturn('登录失败');
		}
		
		//解码
		$openId = $session_key = $unionId =  [];
		$sessionKey = input("sessionKey");
		if(!$sessionKey)return json_encode(WSTReturn('',-1));
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
		if($loginName=='' || $loginPwd=='')return jsonReturn('账号密码不能为空',-1);
		$m = model('users');
	
		$urs = $this->field('userId,loginName,loginSecret,loginPwd,userName,userSex,userPhoto,userStatus,userScore,userType')
		->where("loginName='{$loginName}' or userPhone='{$loginName}' or userEmail='{$loginName}'")
		->where('dataFlag=1')
		->find();
	
		if(empty($urs) || $urs['userStatus']==0)return jsonReturn('账号不存在!',-1);
	
		if(md5($loginPwd.$urs['loginSecret'])!=$urs['loginPwd'])return jsonReturn('账号或密码不正确',-1);
		
		$ip = request()->ip();
		$update = [];
		$update = ["lastTime"=>date('Y-m-d H:i:s'),"lastIP"=>$ip];
		if($session_key){
			$update['weOpenId'] = $openId;
		}
		if($unionId){
			$update['wxUnionId'] = $unionId;
		}
		$this->where(["userId"=>$urs['userId']])->update($update);
	
		unset($urs['loginSecret'],$urs['loginPwd'],$urs['userStatus']);
		//记录登录信息
		$data = [];
		$data["userId"] = $urs['userId'];
		$data["loginTime"] = date('Y-m-d H:i:s');
		// 用户登录地址 $data["loginIp"] = get_client_ip();
		$data["loginIp"] = request()->ip();
		//登录来源、登录设备
		$data["loginSrc"] = 5;
		/**************** 记录登录日志  **************/
		Db::name('log_user_logins')->insert($data);
	
		//记录tokenId
		$m = Db::name('weapp_session');
		/*************************   制作key  **********************/
		$key = sprintf('%011d',$urs['userId']);
		$tokenId = $this->to_guid_string($key.time());
		$data = [];
		$data['userId'] = $urs['userId'];
		$data['tokenId'] = $tokenId;
		$data['startTime'] = date('Y-m-d H:i:s');
		$data['deviceId'] = input('deviceId');
		$m->insert($data);
		//删除上一条登录记录
		$m->where('tokenId!="'.$tokenId.'" and userId='.$urs['userId'])->delete();
		return jsonReturn('登录成功',1,$tokenId);
	}
	
	/**
	 * 用户注册
	 * registerKey 64位加密传过来，密匙->例如:base64(base64(账号)._.base64(密码))
	 * loginRemark:标记是android还是ios
	 * deviceId:设备Id
	 *
	 */
	public function register(){
		$data = [];
		$data['loginName'] = input("post.loginName");
		$loginPwd = input("post.loginPwd");
		$startTime = (int)session('VerifyCode_userPhone_Time');
		if((time()-$startTime)>120){
			return jsonReturn("验证码已超过有效期!");
		}
		$loginName = session('VerifyCode_userPhone');
		if($data['loginName']!=$loginName){
			return WSTReturn("注册手机号与验证手机号不一致!");
		}
		$decrypt_data = WSTRSA($loginPwd);
		if($decrypt_data['status']==1){
			$loginPwd = $decrypt_data['data'];
		}else{
			return WSTReturn('注册失败');
		}
		//解码
		$openId = $session_key = $unionId = [];
		$sessionKey = input("sessionKey");
		if(!$sessionKey)return json_encode(WSTReturn('',-1));
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
		
		//检测账号是否存在
		$rs = WSTCheckLoginKey($loginName);
	
		$mobileCode = input("post.mobileCode");
		$data['userPhone'] = $loginName;
		$verify = session('VerifyCode_userPhone_Verify');
		if($mobileCode=="" || $verify != $mobileCode){
			return jsonReturn("验证码错误!");
		}
		$loginName = WSTRandomLoginName($loginName);
		if($rs['status']==1){
			$data['loginName'] = $loginName;
			$data['userName'] = '手机用户'.substr($data['userPhone'],-4);
			$data["loginSecret"] = rand(1000,9999);
			$data['loginPwd'] = md5($loginPwd.$data['loginSecret']);
			$data['userType'] = 0;
			$data['createTime'] = date('Y-m-d H:i:s');
			$data['dataFlag'] = 1;
			if($session_key){
				$data['userName'] = input('nickName');
				$data['userPhoto'] = input('avatarUrl');
				$data['userSex'] = input('gender');
				$data['weOpenId'] = $openId;
			}
			if($unionId){
				$data['wxUnionId'] = $unionId;
			}
			$userId = $this->data($data)->save();
			if(false !== $userId){
				$data = [];
				$userId = $this->userId;
				$data["userId"] = $userId;
				$data["loginTime"] = date('Y-m-d H:i:s');
				$data["loginIp"] = request()->ip();
				$data["loginSrc"] = 5;
				$data["loginRemark"] = input('loginRemark');
				Db::name('log_user_logins')->insert($data);
				//记录tokenId
				$data = array();
				$key = sprintf('%011d',$userId);
				$tokenId = $this->to_guid_string($key.time());
				$data['userId'] = $userId;
				$data['tokenId'] = $tokenId;
				$data['startTime'] = date('Y-m-d H:i:s');
				$data['deviceId'] = input('deviceId');
				Db::name('weapp_session')->insert($data);
				$user = $this->where("userId=".$userId)->field("userId,loginName,userName,userSex,userType,userPhoto,userScore")->find();
				return jsonReturn('注册成功~',1,$tokenId);
			}
		}else{
			return jsonReturn('账号已存在!',-1);
		}
	}
	
	/**
	 * 用户自动登录
	 */
	public function accordLogin($openId,$unionId){
		$where = [];
		$where['dataFlag'] = 1;
		$where['userStatus'] = 1;
		if($unionId){
			$where['wxUnionId'] = $unionId;
		}else{
			$where['weOpenId'] = $openId;
		}
		$rs = $this->where($where)->order('lastTime desc')->find();
		if(!empty($rs)){
			$userId = $rs['userId'];
			//获取用户等级
			$rrs = WSTUserRank($rs['userTotalScore']);
			$rs['rankId'] = $rrs['rankId'];
			$rs['rankName'] = $rrs['rankName'];
			$rs['userrankImg'] = $rrs['userrankImg'];
			$ip = request()->ip();
			$update = [];
			$update = ["lastTime"=>date('Y-m-d H:i:s'),"lastIP"=>$ip];
			$update['weOpenId'] = $openId;
			if($unionId){
				$update['wxUnionId'] = $unionId;
			}
			$this->where(["userId"=>$userId])->update($update);
			//如果是店铺则加载店铺信息
			if($rs['userType']>=1){
				$shop = model('shops')->where(["userId"=>$userId,"dataFlag" =>1])->find();
				if(!empty($shop))$rs = array_merge($shop->toArray(),$rs->toArray());
			}
			//记录登录日志
			$data = array();
			$data["userId"] = $userId;
			$data["loginTime"] = date('Y-m-d H:i:s');
			$data["loginIp"] = $ip;
			$data['loginSrc'] = 5;
			Db::name('log_user_logins')->insert($data);
			//记录tokenId
			$m = Db::name('weapp_session');
			/*************************   制作key  **********************/
			$key = sprintf('%011d',$userId);
			$tokenId = $this->to_guid_string($key.time());
			$data = [];
			$data['userId'] = $userId;
			$data['tokenId'] = $tokenId;
			$data['startTime'] = date('Y-m-d H:i:s');
			$data['deviceId'] = input('deviceId');
			$m->insert($data);
		
			//删除上一条登录记录
			$m->where('tokenId!="'.$tokenId.'" and userId='.$userId)->delete();
			return jsonReturn("",1,$tokenId);
		}
		return jsonReturn("用户不存在");
	}
	
	/**
	 * 根据PHP各种类型变量生成唯一标识号
	 * @param mixed $mix 变量
	 * @return string
	 */
	private function to_guid_string($mix) {
		if (is_object($mix)) {
			return spl_object_hash($mix);
		} elseif (is_resource($mix)) {
			$mix = get_resource_type($mix) . strval($mix);
		} else {
			$mix = serialize($mix);
		}
		return md5($mix);
	}
	/**
     * 验证用户支付密码
     */
    function checkPayPwd(){
    	$payPwd = input('payPwd');
    	$decrypt_data = WSTRSA($payPwd);
    	if($decrypt_data['status']==1){
    		$payPwd = $decrypt_data['data'];
    	}else{
    		return jsonReturn('验证失败');
    	}
        $userId = $this->getUserId();
        $rs = $this->field('payPwd,loginSecret')->find($userId);
        if($rs['payPwd']==md5($payPwd.$rs['loginSecret'])){
            return jsonReturn('',1);
        }
        return jsonReturn('支付密码错误',-1);
    }
	/**
	 *  获取用户信息
	 */
	public function getById(){
		$id = $this->getUserId();
		$rs = $this->field('loginSecret,loginPwd,payPwd,userQQ,userEmail,trueName,lastIP,lastTime,dataFlag,userStatus,createTime,weOpenId,wxUnionId,distributMoney,isBuyer,brithday',true)
		->where(['userId'=>(int)$id])
		->find();
		$rs['ranks'] = WSTUserRank($rs['userTotalScore']);
		return $rs;
	}
	 /**
     * 获取用户可用积分
     */
    public function getFieldsById($userId,$fields){
        return $this->where(['userId'=>$userId,'dataFlag'=>1])->field($fields)->find();
    }
	/**
     * 编辑资料
    */
    public function edit(){
        $Id = $this->getUserId();
        $data = input('post.');
        if(isset($data['brithday']))$data['brithday'] = ($data['brithday']=='')?date('Y-m-d'):$data['brithday'];
        WSTAllow($data,'brithday,trueName,userName,userId,userPhoto,userQQ,userSex');
        Db::startTrans();
        try{
            if(isset($data['userPhoto']) && $data['userPhoto']!='')
                 WSTUseImages(0, $Id, $data['userPhoto'],'users','userPhoto');
             
            $result = $this->allowField(true)->save($data,['userId'=>$Id]);
            if(false !== $result){
                Db::commit();
                return jsonReturn("编辑成功", 1);
            }
        }catch (\Exception $e) {
            Db::rollback();
            return jsonReturn('编辑失败',-1);
        }   
    }
    /**
     * 重置用户登陆密码
     */
    public function resetbackLogin($uId=0){
    	$timeVerify = session('Verify_backLoginpwd_Time');
    	if(time()>floatval($timeVerify)+10*60){
    		return WSTReturn("校验码已失效，请重新验证！");
    		exit();
    	}
    	$data = array();
    	$data["loginPwd"] = input("post.newPass");
    	if(!$data["loginPwd"]){
    		return WSTReturn('支付密码不能为空',-1);
    	}
    	$userId = ($uId==0)?(int)session('WST_USER.userId'):$uId;
    	$rs = $this->where('userId='.$userId)->find();
    	$data["loginPwd"] = md5($data["loginPwd"].$rs['loginSecret']);
    	$rs = $this->update($data,['userId'=>$userId]);
    	if(false !== $rs){
    		session('Verify_backLoginpwd_info',null);
    		session('Verify_backLoginpwd_Time',null);
    		return WSTReturn("登陆密码设置成功", 1);
    	}else{
    		return WSTReturn("登陆密码修改失败",-1);
    	}
    }
    /**
     * 获取openid
     */
    public function userOpenid($uId=0){
    	$userId = ($uId==0)?(int)session('WST_USER.userId'):$uId;
    	$rs = $this->where(['userId'=>(int)$userId])->value('weOpenId');
    	return $rs;
    }
}
