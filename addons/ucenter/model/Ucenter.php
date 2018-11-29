<?php
namespace addons\ucenter\model;
use think\addons\BaseModel as Base;
use think\Db;
/**
 * 第三方登录业务处理
 */
class Ucenter extends Base{
	
	/**
	 * 绑定勾子
	 */
	public function install(){
		Db::startTrans();
		try{
			$hooks = array("afterUserRegist","beforeUserLogin","afterUserLogin","afterUserLogout","homeControllerBase",
							"afterEditPass","afterEditEmail","homeControllerUsersEditEmail","adminAfterAddUser","adminAfterEditUser",
							"adminAfterEditUserPass","adminAfterDelUser"
						);
			$this->bindHoods("Ucenter", $hooks);
			installSql("ucenter");//传入插件名
			Db::commit();
			return true;
		}catch (\Exception $e) {
			Db::rollback();
			return false;
		}
	}
	
	/**
	 * 解绑勾子
	 */
	public function uninstall(){
		Db::startTrans();
		try{
			$hooks = array("afterUserRegist","beforeUserLogin","afterUserLogin","afterUserLogout","homeControllerBase",
							"afterEditPass","afterEditEmail","homeControllerUsersEditEmail","adminAfterAddUser","adminAfterEditUser",
						"adminAfterEditUserPass","adminAfterDelUser"
			);
			$this->unbindHoods("Ucenter", $hooks);
			uninstallSql("ucenter");//传入插件名
			Db::commit();
			return true;
		}catch (\Exception $e) {
			Db::rollback();
			return false;
		}
	}
	
	
	/**
	 * 获取配置
	 */
	public function getAddonConfig(){
		$addon = Db::name('addons')->where("name","Ucenter")->field("config")->find();
		$config = json_decode($addon["config"],true);
		return $config;
	}
	
	public function initConfig(){
		try {
			$cfg = $this->getAddonConfig();
			$code = $cfg['uc_config'];
			$config = $this->getConfigInfo($code);
			$code = "<?php\n ".$config.";\n?>";
			file_put_contents(WSTRootPath()."/addons/ucenter/api/config_ucenter.php", $code);
			clearstatcache();
			if(!file_exists(WSTRootPath()."/addons/ucenter/api/config_ucenter.php")){
				return false;
			}
			return true;
		} catch (Exception $e) {
			return false;
		}
		
	}
	
	function getConfigInfo($str){
		$str = trim(stripslashes($str));
		$ms = false;
		preg_match_all('~define\s*\(\s*[\'\"](UC\_\w+?)[\'\"]\s*\,\s*[\'\"]([^\'\"]+?)[\'\"]\s*\)\;~i',$str,$ms,2);
		if (is_array($ms) && count($ms)){
			$uc_config = array();
			foreach ($ms as $k=>$v){
				$uc_config[strtolower($v[1])] = $v[2];
			}
			$uc_config = "define('UC_CONNECT', '". (isset($uc_config['uc_connect'])?$uc_config['uc_connect']:'') ."');
						  define('UC_DBHOST', '". ( isset($uc_config['uc_dbhost'])?$uc_config['uc_dbhost']:'') ."'); 
						  define('UC_DBUSER', '". ( isset($uc_config['uc_dbuser'])?$uc_config['uc_dbuser']:'') ."'); 
						  define('UC_DBPW', '". ( isset($uc_config['uc_dbpw'])?$uc_config['uc_dbpw']:'') ."'); 
						  define('UC_DBNAME', '". ( isset($uc_config['uc_dbname'])?$uc_config['uc_dbname']:'') ."'); 
						  define('UC_DBCHARSET', '". ( isset($uc_config['uc_dbcharset'])?$uc_config['uc_dbcharset']:'') ."'); 
						  define('UC_DBTABLEPRE', '". ( isset($uc_config['uc_dbtablepre'])?$uc_config['uc_dbtablepre']:'') ."'); 
						  define('UC_DBCONNECT', '". ( isset($uc_config['uc_dbconnect'])?$uc_config['uc_dbconnect']:'') ."'); 
						  define('UC_KEY', '". ( isset($uc_config['uc_key'])?$uc_config['uc_key']:'') ."'); 
						  define('UC_API', '". ( isset($uc_config['uc_api'])?$uc_config['uc_api']:'') ."');  
						  define('UC_CHARSET', '". ( isset($uc_config['uc_charset'])?$uc_config['uc_charset']:'') ."'); 
						  define('UC_IP', '". ( isset($uc_config['uc_ip'])?$uc_config['uc_ip']:'') ."'); 
						  define('UC_APPID', '". ( isset($uc_config['uc_appid'])?$uc_config['uc_appid']:'') ."'); 
						  define('UC_PPP', '". ( isset($uc_config['uc_app'])?$uc_config['uc_app']:'') ."');" ;
			return $uc_config;
		}else{
			return "";
		}
	}
	
	
	/**
	 * 用户注册后执行
	 */
	public function afterUserRegist($params){

		$userId = $params["user"]['userId'];
		$loginPwd = input("post.loginPwd");
		$decrypt_data = WSTRSA($loginPwd);
    	if($decrypt_data['status']==1){
    		$loginPwd = $decrypt_data['data'];
    	}else{
			exit(json_encode(WSTReturn('注册失败')));
    	}
		$user = Db::name('users')->where(["userId"=>$userId])->field("userEmail,loginName,userPhone")->find();
		if($user['userPhone']!="" && strpos($user['loginName'],$user['userPhone'])!==false){
			$loginName = $user['userPhone'];
		}else{
			$loginName = $user['loginName'];
		}
		$ucEmail = $user['userEmail'];
		if(!isset($ucEmail) || $ucEmail==""){
			$ucEmail = $loginName.'@shangtao.com';
		}
	
		$ucenter = new \addons\ucenter\api\UcenterApi();
		$uid = $ucenter->register($loginName, $loginPwd, strtolower($ucEmail));
		//注册成功返回id
		if(is_string($uid)){
			$rd = ['status'=>-1,'msg'=>$uid];
			exit(json_encode($rd));
		}else{
			Db::name('users')->where(["userId"=>$userId])->update(["userFrom"=>100,"ucUid"=>$uid]);
		}
	}
	
	/**
	 * 用户登录前执行
	 */
	public function beforeUserLogin($params){
	
		$loginName = input("post.loginName");
		$loginPwd = input("post.loginPwd");
		$decrypt_data = WSTRSA($loginPwd);
    	if($decrypt_data['status']==1){
    		$loginPwd = $decrypt_data['data'];
    	}else{
			exit(json_encode(WSTReturn('登录失败')));
    	}
		//UC同步
		$ucenter = new \addons\ucenter\api\UcenterApi();
		$datau = $ucenter->uclogin($loginName, $loginPwd);

		//激活用户
		$rs = $params["user"];
		
		if($datau['uid']>0 && empty($params["user"])){
			$rs = $this->synActivation($loginName,$loginPwd);
		}else if($datau['uid']>0 && !empty($params["user"])){//密码同步/在第三方修改了密码同步
			$datas = array();
			if($loginPwd != $params["user"]['loginPwd']){
				$datas['loginPwd'] = md5($loginPwd.$params["user"]['loginSecret']);
			}
			if($datau['email'] != $params["user"]['userEmail']){
				$datas['userEmail'] = $datau['email'];//邮箱同步
			}
			Db::name('users')->where(['userId'=>$params["user"]['userId']])->update($datas);
			
		}else if(is_string($datau)){
			$rd = ['status'=>-1,'msg'=>$datau];
			exit(json_encode($rd));
		}
		$rs["datau"] = $datau;
		return $rs;
	}
	
	/**
	 * 用户登录后执行
	 */
	public function afterUserLogin($params){
		//同步登录到UC
		if(isset($params['user']['datau']['uid']) && $params['user']['datau']['uid']>0){
			$ucenter = new \addons\ucenter\api\UcenterApi();
			$uids = $ucenter->synlogin($params['user']['datau']['uid']);
			$rd = ['status'=>'1','msg'=>$uids."登录成功"];//$uids返回在页面上，才同步成功登陆
			exit(json_encode($rd));
		}
	}

	public function homeControllerBase(){
		
		$ucenter = new \addons\ucenter\api\UcenterApi();
		$data = $ucenter->dislogin();//检测是否有同步的cookie
		$this->synLogin($data);
		
	}
	
	
	/**
	 * 用户修改密码后执行
	 */
	public function afterEditPass($params){
		$userId = $params["userId"];
		$user = Db::name('users')->where(["userId"=>$userId])->field("userId,loginName,userPhone")->find();
		if($user['userPhone']!="" && strpos($user['loginName'],$user['userPhone'])!==false){
			$loginName = $user['userPhone'];
		}else{
			$loginName = $user['loginName'];
		}
		$oldPass = input("post.oldPass");
		$decrypt_data = WSTRSA($oldPass);
    	if($decrypt_data['status']==1){
    		$oldPass = $decrypt_data['data'];
    	}else{
			exit(json_encode(WSTReturn('修改失败')));
    	}
		$newPass = input("post.newPass");
		$decrypt_data = WSTRSA($newPass);
    	if($decrypt_data['status']==1){
    		$newPass = $decrypt_data['data'];
    	}else{
			exit(json_encode(WSTReturn('修改失败')));
    	}
		
		//同步登录到UC 
		$ucenter = new \addons\ucenter\api\UcenterApi();
  		$uid = $ucenter-> ucedit($loginName,$oldPass,$newPass,'',0);
  		$rs = WSTReturn($uid);
		if(is_string($uid))exit(json_encode($rs));
	}
	
	/**
	 * 用户修改邮箱后执行
	 */
	public function afterEditEmail($params){
		$userId = $params["user"]["userId"];
		$userEmail = $params["user"]["userEmail"];
		$loginPwd = session('checkEmailEditBind');
		if($userEmail!=""){
			$user = Db::name('users')->where(["userId"=>$userId])->field("userId,loginName,userPhone,userEmail")->find();
			if($user['userPhone']!="" && strpos($user['loginName'],$user['userPhone'])!==false){
				$loginName = $user['userPhone'];
			}else{
				$loginName = $user['loginName'];
			}
			//同步登录到UC
			$ucenter = new \addons\ucenter\api\UcenterApi();
			$uid = $ucenter-> ucedit($loginName,$loginPwd,'',$user['userEmail'],0);
			$rs = WSTReturn($uid);
			if(is_string($uid))exit(json_encode($rs));
		}
	
	}
	
	/**
	 * 管理员修改用户资料
	 */
	public function afterEditUser($params){
		$userId = $params["userId"];
		$loginPwd = input("post.loginPwd");
		$user = Db::name('users')->where(["userId"=>$userId])->field("userId,loginName,userPhone,userEmail")->find();
		if($user['userPhone']!="" && strpos($user['loginName'],$user['userPhone'])!==false){
			$loginName = $user['userPhone'];
		}else{
			$loginName = $user['loginName'];
		}
		//同步登录到UC
		$ucenter = new \addons\ucenter\api\UcenterApi();
		$uid = $ucenter-> ucedit($loginName,'',$loginPwd,$user['userEmail'],1);
		$rs = WSTReturn($uid);
		if(is_string($uid))exit(json_encode($rs));
		
	
	}
	
	/**
	 * 管理员修改用户资料
	 */
	public function adminAfterDelUser($params){
		$userId = $params["userId"];
		$loginPwd = input("post.loginPwd");
		
		$user = Db::name('users')->where(["userId"=>$userId])->field(["userId","ucUid"])->find();
		//同步登录到UC
		$ucenter = new \addons\ucenter\api\UcenterApi();
		$uid = $ucenter-> ucdelete($user['ucUid']);
		$rs = WSTReturn($uid);
		if(is_string($uid))exit(json_encode($rs));
	
	
	}
	
	/**
	 * 激活账号/相当于自动注册用户
	 */
	public function synActivation($loginName,$loginPwd){
		if(!empty($loginName)){
			$data['loginName'] = $loginName;
			$data["loginSecret"] = rand(1000,9999);
			$data['loginPwd'] = md5($loginPwd.$data['loginSecret']);
			$data['userType'] = 0;
			$data['userName'] = "";
			$data['userQQ'] = "";
			$data['userScore'] = 0;
			$data['createTime'] = date('Y-m-d H:i:s');
			$data['dataFlag'] = 1;
			$data['userFrom'] = 100;
			$userId = Db::name('users')->insert($data);
			$rs = $this->checkAndGetLoginInfo($loginName);
			return $rs;//返回个人信息
		}
	}
	
	/**
	 * 同步uc
	 */
	public function synLogin($data){
		if($data['type']==1){
			$loginName = $data['uName'];
			$rs = Db::name('users')->where("loginName|userEmail|userPhone",$loginName)
					->where(["dataFlag"=>1, "userStatus"=>1])
					->find();
			if(!empty($rs)){
				if($rs['userPhoto']=='')$rs['userPhoto'] = WSTConf('CONF.userLogo');
				$userId = $rs['userId'];
				//获取用户等级
				$rrs = Db::name('user_ranks')->where('startScore','<=',$rs['userTotalScore'])->where('endScore','>=',$rs['userTotalScore'])->field('rankId,rankName,userrankImg')->find();
				$rs['rankId'] = $rrs['rankId'];
				$rs['rankName'] = $rrs['rankName'];
				$rs['userrankImg'] = $rrs['userrankImg'];
				if(input("post.typ")==2){
					$shoprs=Db::name('users')->where(["dataFlag"=>1, "userStatus"=>1,"userType"=>1,"userId"=>$userId])->find();
					if(empty($shoprs)){
						return WSTReturn('您还没申请店铺!');
					}
				}
				$ip = request()->ip();
				$update = [];
				$update = ["lastTime"=>date('Y-m-d H:i:s'),"lastIP"=>$ip];
				$wxOpenId = session('WST_WX_OPENID');
				if($wxOpenId){
					$update['wxOpenId'] = session('WST_WX_OPENID');
				}
				Db::name('users')->where(["userId"=>$userId])->update($update);
				//如果是店铺则加载店铺信息
				if($rs['userType']>=1){
					$shop = model('shops')->where(["userId"=>$userId,"dataFlag" =>1])->find();
					if(!empty($shop))$rs = array_merge($shop->toArray(),$rs);
				}
				//记录登录日志
				$data = array();
				$data["userId"] = $userId;
				$data["loginTime"] = date('Y-m-d H:i:s');
				$data["loginIp"] = $ip;
				$data['loginSrc'] = 0;
				Db::name('log_user_logins')->insert($data);
	
				$rd = $rs;
				session('WST_USER',$rs);
			}
		}else if($data['type']==-1){
			setcookie('Ucenter_auth','',time()-10000,'/','');//清除同步的cookie
			session('WST_USER',null);
			setcookie("loginPwd", null);
		}
	}
	
	/**
	 * 查询并加载用户资料
	 */
	public function checkAndGetLoginInfo($key){
		if($key=='')return array();
		$rs = Db::name('users')->where(["loginName|userEmail|userPhone"=>['=',$key],'dataFlag'=>1])->find();
		return $rs;
	}
	
	/**
	 * 查询并加载用户资料
	 */
	public function checkLoginPass(){
		$userId = (int)session('WST_USER.userId');
		$loginPwd = input("post.loginPwd");
		
		$rs = Db::name('users')->where(["userId"=>$userId])->field(["userId","loginPwd","loginSecret","userEmail"])->find();
		
		if($rs['loginPwd']==md5($loginPwd.$rs['loginSecret'])){
			return true;
		}else{
			return false;
		}
	}
	
	public function getUserInfo($userId){
		$user = Db::name('users')->where(['userId'=>$userId])->field(["userId","ucUid"])->find();
		return $user;
	}
}
