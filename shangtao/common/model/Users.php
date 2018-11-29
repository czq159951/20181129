<?php
namespace shangtao\common\model;
use think\Db;
use Env;
/**
 * 用户类
 */
class Users extends Base{
	protected $pk = 'userId';
    /**
     * 用户登录验证
     */
    public function checkLogin($loginSrc = 0){
    	$loginName = input("post.loginName");
    	$loginPwd = input("post.loginPwd");
    	$code = input("post.verifyCode");
        $typ = (int)input("post.typ");
    	$rememberPwd = input("post.rememberPwd",1);
    	if(!WSTVerifyCheck($code) && strpos(WSTConf("CONF.captcha_model"),"4")>=0){
    		return WSTReturn('验证码错误!');
    	}
    	$decrypt_data = WSTRSA($loginPwd);
    	if($decrypt_data['status']==1){
    		$loginPwd = $decrypt_data['data'];
    	}else{
    		return WSTReturn('登录失败');
    	}
    	$rs = $this->where("loginName|userEmail|userPhone",$loginName)
    				->where(["dataFlag"=>1, "userStatus"=>1])
    				->find();
    	
    	hook("beforeUserLogin",["user"=>&$rs]);
    	if(!empty($rs)){
            if($rs['loginPwd']!=md5($loginPwd.$rs['loginSecret']))return WSTReturn("密码错误");
            if($rs['userPhoto']=='')$rs['userPhoto'] = WSTConf('CONF.userLogo');
    		$userId = $rs['userId'];
    		//获取用户等级
	    	$rrs = Db::name('user_ranks')->where(['dataFlag'=>1])->where('startScore','<=',$rs['userTotalScore'])->where('endScore','>=',$rs['userTotalScore'])->field('rankId,rankName,userrankImg')->find();
	    	$rs['rankId'] = $rrs['rankId'];
	    	$rs['rankName'] = $rrs['rankName'];
	    	$rs['userrankImg'] = $rrs['userrankImg'];
    		if(input("post.typ")==2){
    			$shoprs=$this->where(["dataFlag"=>1, "userStatus"=>1,"userType"=>1,"userId"=>$userId])->find();
    			if(empty($shoprs)){
    				return WSTReturn('您还没申请店铺!');
    			}
    		}
    		$ip = request()->ip();
    		$update = [];
    		$update = ["lastTime"=>date('Y-m-d H:i:s'),"lastIP"=>$ip];
    		$wxOpenId = session('WST_WX_OPENID');
    		if($wxOpenId){
    			$update['wxOpenId'] = $rs['wxOpenId'] = session('WST_WX_OPENID');
                // 保存unionId【若存在】 详见 unionId说明 https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140839
                $userinfo = session('WST_WX_USERINFO');
                $update['wxUnionId'] = isset($userinfo['unionid'])?$userinfo['unionid']:'';
    		}
    		$this->where(["userId"=>$userId])->update($update);
    		
    		
    		//如果是店铺则加载店铺信息
    		if($rs['userType']>=1){
    			$shop = Db::name("shops s")
                        ->join("__SHOP_USERS__ su","s.shopId=su.shopId")
                        ->field("s.*,su.roleId")
                        ->where(["su.userId"=>$userId,"s.dataFlag" =>1,"s.shopStatus" =>1])->find();
                if($typ==2 && empty($shop)){
                    return WSTReturn("店铺已停用，不能登录!",-1);
                }
    			if(!empty($shop))$rs = array_merge($shop,$rs->toArray());
    		}
    		//签到时间
    		if(WSTConf('CONF.signScoreSwitch')==1){
    			$rs['signScoreTime'] = 0;
    			$userscores = Db::name('user_scores')->where(["userId"=>$userId,"dataSrc"=>5,])->order('createTime desc')->find();
    			if($userscores)$rs['signScoreTime'] = date("Y-m-d",strtotime($userscores['createTime']));
    		}
    		//记录登录日志
    		$data = array();
    		$data["userId"] = $userId;
    		$data["loginTime"] = date('Y-m-d H:i:s');
    		$data["loginIp"] = $ip;
            $data['loginSrc'] = $loginSrc;
    		Db::name('log_user_logins')->insert($data);
    		
    		$rd = $rs;
    		//记住密码
    		cookie("loginName", $loginName, 3600*24*90);
    		if($rememberPwd == "on"){
    			$datakey = md5($rs['loginName'])."_".md5($rs['loginPwd']);
    			$key = $rs['loginSecret'];
    			//加密
    			require Env::get('root_path') . 'extend/org/Base64.php';
    			$base64 = new \org\Base64();
    			$loginKey = $base64->encrypt($datakey, $key);

    			cookie("loginPwd", $loginKey, 3600*24*90);
    		}else{
    			cookie("loginPwd", null);
    		}
    		session('WST_USER',$rs);
    		
    		hook('afterUserLogin',['user'=>$rs]);
    		
    		return WSTReturn("登录成功","1");
    	
    	}
    	return WSTReturn("用户不存在");
    }
    
    /**
     * 会员注册
     */
    public function regist($loginSrc = 0){
    	$data = array();
    	$data['loginName'] = input("post.loginName");
    	$data['loginPwd'] = input("post.loginPwd");
    	$data['reUserPwd'] = input("post.reUserPwd");
    	$startTime = (int)session('VerifyCode_userPhone_Time');
    	if((time()-$startTime)>120){
    		return WSTReturn("验证码已超过有效期!");
    	}
    	$loginName = session('VerifyCode_userPhone');
    	if($data['loginName']!=$loginName){
    		return WSTReturn("注册手机号与验证手机号不一致!");
    	}
    	//检测账号是否存在
    	$crs = WSTCheckLoginKey($loginName);
    	if($crs['status']!=1)return $crs;
    	$decrypt_data = WSTRSA($data['loginPwd']);
    	$decrypt_data2 = WSTRSA($data['reUserPwd']);
    	if($decrypt_data['status']==1 && $decrypt_data2['status']==1){
    		$data['loginPwd'] = $decrypt_data['data'];
    		$data['reUserPwd'] = $decrypt_data2['data'];
    	}else{
    		return WSTReturn('注册失败');
    	}
    	if($data['loginPwd']!=$data['reUserPwd']){
    		return WSTReturn("两次输入密码不一致!");
    	}
    	foreach ($data as $v){
    		if($v ==''){
    			return WSTReturn("注册信息不完整!");
    		}
    	}
    	$mobileCode = input("post.mobileCode");
    	//请允许手机号码注册
		$data['userPhone'] = $loginName;
		$verify = session('VerifyCode_userPhone_Verify');
		if($mobileCode=="" || $verify != $mobileCode){
			return WSTReturn("短信验证码错误!");
		}
		$loginName = WSTRandomLoginName($loginName);
    	
    	if($loginName=='')return WSTReturn("注册失败!");//分派不了登录名
    	$data['loginName'] = $loginName;
    	unset($data['reUserPwd']);
    	unset($data['protocol']);
    	//检测账号，邮箱，手机是否存在
    	$data["loginSecret"] = rand(1000,9999);
    	$data['loginPwd'] = md5($data['loginPwd'].$data['loginSecret']);
    	$data['userType'] = 0;
    	$data['userName'] = '手机用户'.substr($data['userPhone'],-4);
    	$data['userQQ'] = "";
    	$data['userScore'] = 0;
    	$data['createTime'] = date('Y-m-d H:i:s');
    	$data['dataFlag'] = 1;
        $wxOpenId = session('WST_WX_OPENID');
    	if($wxOpenId){
    		$data['wxOpenId'] = session('WST_WX_OPENID');
			$userinfo = session('WST_WX_USERINFO');
			if($userinfo){
                $nickname = json_encode($userinfo['nickname']);
                $nickname = preg_replace("/\\\u[ed][0-9a-f]{3}\\\u[ed][0-9a-f]{3}/","*",$nickname);//替换成*
                $nickname = json_decode($nickname);
                if($nickname=="") $nickname = "微信用户";
                $data['userName'] = $nickname;
				$data['userSex'] = $userinfo['sex'];
				$data['userPhoto'] = $userinfo['headimgurl'];
                // 保存unionId【若存在】 详见 unionId说明 https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140839
                $data['wxUnionId'] = isset($userinfo['unionid'])?$userinfo['unionid']:'';
			}
    	}
    	Db::startTrans();
        try{
	    	$userId = $this->data($data)->save();
	    	if(false !== $userId){
	    		$data = array();
	    		$ip = request()->ip();
	    		$data['lastTime'] = date('Y-m-d H:i:s');
	    		$data['lastIP'] = $ip;
	    		$userId = $this->userId;
	    		$this->where(["userId"=>$userId])->update($data);
	    		//记录登录日志
	    		$data = array();
	    		$data["userId"] = $userId;
	    		$data["loginTime"] = date('Y-m-d H:i:s');
	    		$data["loginIp"] = $ip;
                $data['loginSrc'] = $loginSrc;
	    		Db::name('log_user_logins')->insert($data);
	    		$user = $this->get(['userId'=>$userId]);
	    	    if($user['userPhoto']=='')$user['userPhoto'] = WSTConf('CONF.userLogo');
	    		session('WST_USER',$user);
	    		//注册成功后执行钩子
	    		hook('afterUserRegist',['user'=>$user]);
                //发送消息
                $tpl = WSTMsgTemplates('USER_REGISTER');
                if( $tpl['tplContent']!='' && $tpl['status']=='1'){
                    $find = ['${LOGIN_NAME}','${MALL_NAME}'];
                    $replace = [$user['loginName'],WSTConf('CONF.mallName')];
                    WSTSendMsg($userId,str_replace($find,$replace,$tpl['tplContent']),['from'=>0,'dataId'=>0]);
                }
	    		Db::commit();
	    		return WSTReturn("注册成功",1);
	    	}
        }catch (\Exception $e) {
        	Db::rollback();
        }
    	return WSTReturn("注册失败!");
    }
    
    /**
     * 查询用户手机是否存在
     * 
     */
    public function checkUserPhone($userPhone,$userId = 0){
    	$dbo = $this->where(["dataFlag"=>1, "userPhone"=>$userPhone]);
    	if($userId>0){
    		$dbo->where("userId","<>",$userId);
    	}
    	$rs = $dbo->count();
    	if($rs>0){
    		return WSTReturn("手机号已存在!");
    	}else{
    		return WSTReturn("",1);
    	}
    }

    /**
     * 修改用户密码
     */
    public function editPass($id){
    	$data = array();
    	$newPass = input("post.newPass");
    	$decrypt_data = WSTRSA($newPass);
    	if($decrypt_data['status']==1){
    		$newPass = $decrypt_data['data'];
    	}else{
    		return WSTReturn('修改失败');
    	}
    	if(!$newPass){
    		return WSTReturn('密码不能为空',-1);
    	}
    	$rs = $this->where('userId='.$id)->find();
    	//核对密码
    	if($rs['loginPwd']){
    		$oldPass = input("post.oldPass");
    		$decrypt_data2 = WSTRSA($oldPass);
    		if($decrypt_data2['status']==1){
    			$oldPass = $decrypt_data2['data'];
    		}else{
    			return WSTReturn('修改失败');
    		}
    		if($rs['loginPwd']==md5($oldPass.$rs['loginSecret'])){
    			$data["loginPwd"] = md5($newPass.$rs['loginSecret']);
    			$rs = $this->update($data,['userId'=>$id]);
    			if(false !== $rs){
    				hook("afterEditPass",["userId"=>$id]);
    				return WSTReturn("密码修改成功", 1);
    			}else{
    				return WSTReturn($this->getError(),-1);
    			}
    		}else{
    			return WSTReturn('原始密码错误',-1);
    		}
    	}else{
    		$data["loginPwd"] = md5($newPass.$rs['loginSecret']);
    		$rs = $this->update($data,['userId'=>$id]);
    		if(false !== $rs){
    			hook("afterEditPass",["userId"=>$id]);
    			return WSTReturn("密码修改成功", 1);
    		}else{
    			return WSTReturn($this->getError(),-1);
    		}
    	}
    }
    /**
     * 修改用户支付密码
     */
    public function editPayPass($id){
        $data = array();
        $newPass = input("post.newPass");
        $decrypt_data = WSTRSA($newPass);
        if($decrypt_data['status']==1){
        	$newPass = $decrypt_data['data'];
        }else{
        	return WSTReturn('修改失败');
        }
        if(!$newPass){
            return WSTReturn('支付密码不能为空',-1);
        }
        $rs = $this->where('userId='.$id)->find();
        //核对密码
        if($rs['payPwd']){
        	$oldPass = input("post.oldPass");
        	$decrypt_data2 = WSTRSA($oldPass);
        	if($decrypt_data2['status']==1){
        		$oldPass = $decrypt_data2['data'];
        	}else{
        		return WSTReturn('修改失败');
        	}
            if($rs['payPwd']==md5($oldPass.$rs['loginSecret'])){
                $data["payPwd"] = md5($newPass.$rs['loginSecret']);
                $rs = $this->update($data,['userId'=>$id]);
                if(false !== $rs){
                    return WSTReturn("支付密码修改成功", 1);
                }else{
                    return WSTReturn("支付密码修改失败",-1);
                }
            }else{
                return WSTReturn('原始支付密码错误',-1);
            }
        }else{
            $data["payPwd"] = md5($newPass.$rs['loginSecret']);
            $rs = $this->update($data,['userId'=>$id]);
            if(false !== $rs){
                return WSTReturn("支付密码设置成功", 1);
            }else{
                return WSTReturn("支付密码修改失败",-1);
            }
        }
    }
    /**
     * 重置用户支付密码
     */
    public function resetbackPay($uId=0){
    	$timeVerify = session('Verify_backPaypwd_Time');
    	if(time()>floatval($timeVerify)+10*60){
    		session('Type_backPaypwd',null);
    		return WSTReturn("校验码已失效，请重新验证！");
    		exit();
    	}
    	$data = array();
    	$data["payPwd"] = input("post.newPass");
    	$decrypt_data = WSTRSA($data["payPwd"]);
    	if($decrypt_data['status']==1){
    		$data["payPwd"] = $decrypt_data['data'];
    	}else{
    		return WSTReturn('修改失败');
    	}
    	if(!$data["payPwd"]){
    		return WSTReturn('支付密码不能为空',-1);
    	}
    	$userId = ($uId==0)?(int)session('WST_USER.userId'):$uId;
    	$rs = $this->where('userId='.$userId)->find();
    	$data["payPwd"] = md5($data["payPwd"].$rs['loginSecret']);
    	$rs = $this->update($data,['userId'=>$userId]);
    	if(false !== $rs){
    		session('Type_backPaypwd',null);
    		session('Verify_backPaypwd_info',null);
    		session('Verify_backPaypwd_Time',null);
    		return WSTReturn("支付密码设置成功", 1);
    	}else{
    		return WSTReturn("支付密码修改失败",-1);
    	}
    }
   /**
    *  获取用户信息
    */
    public function getById($id){
    	$rs = $this->get(['userId'=>(int)$id]);
    	$rs['ranks'] = WSTUserRank($rs['userTotalScore']);
    	return $rs;
    }
    /**
     * 编辑资料
    */
    public function edit(){
    	$Id = (int)session('WST_USER.userId');
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
	    		return WSTReturn("编辑成功", 1);
	    	}
		}catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('编辑失败',-1);
        }	
    }
    /**
    * 绑定邮箱
     */
    public function editEmail($userId,$userEmail){
    	$data = array();
    	$data["userEmail"] = $userEmail;
    	Db::startTrans();
    	try{
    		$user = Db::name('users')->where(["userId"=>$userId])->field(["userId","loginName,userEmail"])->find();
			$rs = $this->update($data,['userId'=>$userId]);
			if(false !== $rs){
				hook("afterEditEmail",["user"=>$user]);
				Db::commit();
				return WSTReturn("绑定成功",1);
			}else{
				Db::rollback();
				return WSTReturn("",-1);
			}
		}catch (\Exception $e) {
    		Db::rollback();
    		return WSTReturn('编辑失败',-1);
    	}
    }
    /**
     * 绑定手机
     */
    public function editPhone($userId,$userPhone){
    	$data = array();
    	$data["userPhone"] = $userPhone;
    	$rs = $this->update($data,['userId'=>$userId]);
    	if(false !== $rs){
    		return WSTReturn("绑定成功", 1);
    	}else{
    		return WSTReturn($this->getError(),-1);
    	}
    }
    /**
     * 查询并加载用户资料
     */
    public function checkAndGetLoginInfo($key){
    	if($key=='')return array();
    	$rs = $this->where([["loginName|userEmail|userPhone",'=',$key],['dataFlag','=',1]])->find();
    	return $rs;
    }
    /**
     * 重置用户密码
     */
    public function resetPass($uId=0){
    	if(time()>floatval(session('REST_Time'))+30*60){
    		return WSTReturn("连接已失效！", -1);
    	}
    	$reset_userId = (int)session('REST_userId');
    	if($reset_userId==0){
    		return WSTReturn("无效的用户！", -1);
    	}
    	$user = $this->where(["dataFlag"=>1,"userStatus"=>1,"userId"=>$reset_userId])->find();
    	if(empty($user)){
    		return WSTReturn("无效的用户！", -1);
    	}
    	$loginPwd = input("post.loginPwd");
        if($uId==0){// 大于0表示来自app端
            $decrypt_data = WSTRSA($loginPwd);
            if($decrypt_data['status']==1){
                $loginPwd = $decrypt_data['data'];
            }else{
                return WSTReturn('修改失败');
            }
        }
    	if(trim($loginPwd)==''){
    		return WSTReturn("无效的密码！", -1);
    	}
    	$data['loginPwd'] = md5($loginPwd.$user["loginSecret"]);
    	$rc = $this->update($data,['userId'=>$reset_userId]);
    	if(false !== $rc){
            session('REST_userId',null);
            session('REST_Time',null);
            session('REST_success',null);
            session('findPass',null);
    		return WSTReturn("修改成功", 1);
    	}
    	return $rs;
    }
    
    /**
     * 获取用户可用积分
     */
    public function getFieldsById($userId,$fields){
    	return $this->where(['userId'=>$userId,'dataFlag'=>1])->field($fields)->find();
    }
}
