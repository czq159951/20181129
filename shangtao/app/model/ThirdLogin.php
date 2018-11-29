<?php
namespace shangtao\app\model;
use think\Db;
use think\Model;
/**
 * 第三方登录业务处理类
 */
class ThirdLogin extends Model{
	private function doLogin($rs,$loginRemark){
		// 存在该用户,生成TokenId返回
		//记录登录信息
		$data = array();
		$data["userId"] = $rs['userId'];
		$data["loginTime"] = date('Y-m-d H:i:s');
		// 用户登录地址 $data["loginIp"] = get_client_ip();
		$data["loginIp"] = request()->ip();
		//登录来源、登录设备
		$data["loginSrc"] = 2;
		$data["loginRemark"] = $loginRemark;
		/**************** 记录登录日志  **************/
		Db::name('log_user_logins')->insert($data);
		//记录tokenId
		$m = Db::name('app_session');
		/*************************   制作key  **********************/
		$key = sprintf('%011d',$rs['userId']);
		$tokenId = $this->to_guid_string($key.time());
		$data = array();
		$data['userId'] = $rs['userId'];
		$data['tokenId'] = $tokenId;
		$data['startTime'] = date('Y-m-d H:i:s');
		$data['deviceId'] = input('deviceId');
		$m->insert($data);
		//删除上一条登录记录
		$m->where('tokenId!="'.$tokenId.'" and userId='.$rs['userId'])->delete();
		$rs['tokenId'] = $tokenId;
		// 返回tokenId及用户数据
		return $rs;
	}

	/**
	 * 根据unionId检测账号是否存在
	 * @param $unionId
	 * @param $loginRemark 登录来源 3:android 4:ios
	 */
	public function wechatIsExists($unionId,$loginRemark){
		$rs = Db::name('users')->field('userId,loginName,loginSecret,loginPwd,userName,userSex,userPhoto,userStatus,userScore,userType')
							   ->where([['wxUnionId','=',$unionId],['dataFlag','=',1],['userStatus','<>',0]])
							   ->find();
		if(!empty($rs)){
			// 执行登录
			return $this->doLogin($rs,$loginRemark);
		}
		return $rs;
	}
	/**
	 * 根据QQ的unionId检测账号是否存在
	 * @param $unionId
	 * @param $loginRemark 登录来源 3:android 4:ios
	 */
	public function qqIsExists($unionId,$loginRemark){
		$rs = Db::name('users')->alias('u')
							   ->join('third_users tu','u.userId=tu.userId','inner')
							   ->field('u.userId,loginName,loginSecret,loginPwd,userName,userSex,userPhoto,userStatus,userScore,userType')
							   ->where([['thirdCode','=','qq'],['thirdOpenId','=',$unionId],['u.dataFlag','=',1],['u.userStatus','<>',0]])
							   ->find();
		if(!empty($rs)){
			// 执行登录
			return $this->doLogin($rs,$loginRemark);
		}
		return $rs;
	}
	/**
	 * 根据支付宝的user_id检测账号是否存在
	 * @param $openId 支付宝user_id
	 * @param $loginRemark 登录来源 3:android 4:ios
	 */
	public function alipayIsExists($openId,$loginRemark){
		$rs = Db::name('users')->alias('u')
							   ->join('third_users tu','u.userId=tu.userId','inner')
							   ->field('u.userId,loginName,loginSecret,loginPwd,userName,userSex,userPhoto,userStatus,userScore,userType')
							   ->where([['thirdCode','=','alipay'],['thirdOpenId','=',$openId],['u.dataFlag','=',1],['u.userStatus','<>',0]])
							   ->find();
		if(!empty($rs)){
			// 执行登录
			return $this->doLogin($rs,$loginRemark);
		}
		return $rs;
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





}
