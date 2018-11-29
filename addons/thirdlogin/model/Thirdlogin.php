<?php
namespace addons\thirdlogin\model;
use think\addons\BaseModel as Base;
use think\Db;
/**
 * 第三方登录业务处理
 */
class Thirdlogin extends Base {
	
	/**
	 * 安装
	 */
	public function install(){
		Db::startTrans();
		try{
			$hooks = array("homeDocumentLogin","afterUserRegist","afterUserLogin","mobileDocumentLogin","beforeUserLogin");
			$this->bindHoods("Thirdlogin", $hooks);
			installSql("thirdlogin");//传入插件名
			Db::commit();
			return true;
		}catch (\Exception $e) {
			Db::rollback();
			return false;
		}
	}
	
	/**
	 * 卸载
	 */
	public function uninstall(){
		Db::startTrans();
		try{
			$hooks = array("homeDocumentLogin","afterUserRegist","afterUserLogin","mobileDocumentLogin","beforeUserLogin");
			$this->unbindHoods("Thirdlogin", $hooks);
			uninstallSql("thirdlogin");//传入插件名
			Db::commit();
			return true;
		}catch (\Exception $e) {
			Db::rollback();
			return false;
		}
	}
	
	
	/**
	 * 获取第三方登录方式
	 */
	public function getThirdLogins(){
	
		$addon = Db::name('addons')->where("name","Thirdlogin")->field("config")->find();
		$config = json_decode($addon["config"],true);
		return $config;
	}
	
	
	/**
	 * 检测第三方帐号是否已注册
	 */
	public function checkThirdIsReg($thirdCode,$openId){
		$rs = Db::name('third_users')->where(["thirdCode"=>$thirdCode,"thirdOpenId"=>$openId])->field(["userId","thirdCode","thirdOpenId"])->find();
		if(empty($rs) && $thirdCode=="weixin"){
			$utemp = Db::name('users')->where(["dataFlag"=>1])->find();
			if(isset($utemp['wxOpenId'])){
				$rs = Db::name('users')->where(["dataFlag"=>1])
		            ->where(function($query) use($openId){
		                $query->where(["wxOpenId"=>$openId])->whereOr(["wxUnionId"=>$openId]);
		            })->field(["userId","userName"])->find();
			}
		}
		if($rs["userId"]>0){
			return true;
		}else{
			return false;
		}
	}

	public function checkBind($userId){
		$obj = session('binding_login');
		if(!empty($obj)){
			$where = array();
			$where["userId"] = $userId;
			$where["thirdCode"] = $obj["thirdCode"];
			$rs = Db::name('third_users')->where($where)->field(["userId","thirdCode","thirdOpenId"])->find();
			if(!empty($rs)){
				return false;
			}
		}
		return true;
	}
	
	/**
	 * 绑定帐号
	 */
	public function bindAcc($userId){
		$obj = session('binding_login');
		if(!empty($obj)){
			$where = array();
			$where["thirdOpenId"] = $obj["thirdOpenId"];
			$where["thirdCode"] = $obj["thirdCode"];
			$rs = Db::name('third_users')->where($where)->field(["userId","thirdCode","thirdOpenId"])->find();
			if(empty($rs)){
				$data = array();
				$data["userId"] = $userId;
				$data["thirdOpenId"] = $obj["thirdOpenId"];
				$data["thirdCode"] = $obj["thirdCode"];
				$data["createTime"] = date("Y-m-d H:i:s");
				Db::name('third_users')->insert($data);

				$data = array();
				$data["userName"] = $obj["userName"];
				$data["userPhoto"] = $obj["userPhoto"];

				if(isset($obj["unionId"]) && $obj["unionId"]!=""){
					$data["wxUnionId"] = $obj["unionId"];
				}
				Db::name('users')->where(["userId"=>$userId])->update($data);
				session('binding_login', null);
			}
		}
	}
	
	public function thirdLogin($obj){
		$rs = Db::name('third_users')->where($obj)->field(["userId","thirdCode","thirdOpenId"])->find();
		if(empty($rs) && $obj["thirdCode"]=="weixin"){
			$openId = $obj["thirdOpenId"];
			$utemp = Db::name('users')->where(["dataFlag"=>1])->find();
			if(isset($utemp['wxOpenId'])){
				$rs = Db::name('users')->where(["dataFlag"=>1])
		            ->where(function($query) use($openId){
		                $query->where(["wxOpenId"=>$openId])->whereOr(["wxUnionId"=>$openId]);
		            })->field(["userId","userName"])->find();
			}
			
		}
		if(!empty($rs)){
			$userId = $rs["userId"];
			$user = Db::name('users')->where(["userId"=>$rs["userId"],"dataFlag"=>1, "userStatus"=>1])->find();
			$update = [];
			$ip = request()->ip();
			$update = ["lastTime"=>date('Y-m-d H:i:s'),"lastIP"=>$ip];
			Db::name('users')->where(["userId"=>$userId])->update($update);
			
			//如果是店铺则加载店铺信息
			if($user['userType']>=1){
				$shop = Db::name('shops')->where(["userId"=>$userId,"dataFlag" =>1])->find();
				if(!empty($shop))$user = array_merge($shop,$user);
			}
			//记录登录日志
			$data = array();
			$data["userId"] = $userId;
			$data["loginTime"] = date('Y-m-d H:i:s');
			$data["loginIp"] = $ip;
			$data['loginSrc'] = 0;
			Db::name('log_user_logins')->insert($data);
			
			session('WST_USER',$user);
			return WSTReturn("登录成功","1");
		}else{
			return WSTReturn("登录失败","-1");
		}
	}
}
