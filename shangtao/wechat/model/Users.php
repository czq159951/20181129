<?php
namespace shangtao\wechat\model;
use shangtao\common\model\Users as CUsers;
use think\Db;
/**
 * 用户类
 */
class Users extends CUsers{
	/**
	 * 用户自动登录
	 */
	public function accordLogin(){
		$wxOpenId = session('WST_WX_OPENID');
		$rs = $this->where(["dataFlag"=>1, "userStatus"=>1,"wxOpenId"=>$wxOpenId])->order('lastTime desc')->find();
		if(!empty($rs)){
			$userId = $rs['userId'];
			//获取用户等级
			$rrs = WSTUserRank($rs['userTotalScore']);
			$rs['rankId'] = $rrs['rankId'];
			$rs['rankName'] = $rrs['rankName'];
			$rs['userrankImg'] = $rrs['userrankImg'];
			$rs['wxOpenId'] = session('WST_WX_OPENID');
			$ip = request()->ip();
			$update = [];
			$update = ["lastTime"=>date('Y-m-d H:i:s'),"lastIP"=>$ip];
			$update['wxOpenId'] = session('WST_WX_OPENID');
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
			$data['loginSrc'] = 1;
			Db::name('log_user_logins')->insert($data);
			hook('afterUserLogin',['user'=>$rs]);
			session('WST_USER',$rs);
			return WSTReturn("","1");
		}
		return WSTReturn("用户不存在");
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
			return WSTReturn('验证失败');
		}
		$userId = (int)session('WST_USER.userId');
		$rs = $this->field('payPwd,loginSecret')->find($userId);
		if($rs['payPwd']==md5($payPwd.$rs['loginSecret'])){
			return WSTReturn('',1);
		}
		return WSTReturn('支付密码错误',-1);
	}
}
