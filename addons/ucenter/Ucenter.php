<?php
namespace addons\ucenter;  // 注意命名空间规范


use think\addons\Addons;
use addons\ucenter\model\Ucenter as DM;

/**
 * Ucenter
 * @author shangtao
 */
class Ucenter extends Addons{
	public $ucOpen = true;
	public function __construct() {
		parent::__construct();
		if(file_exists(WSTRootPath()."/addons/ucenter/api/config_ucenter.php")){
			include_once ('api/config_ucenter.php');
		}
		if(!defined('UC_API')) {
			$this->ucOpen = false;
		}
	}
    // 该插件的基础信息
    public $info = [
        'name' => 'Ucenter',   // 插件标识
        'title' => 'Ucenter整合插件',  // 插件名称
        'description' => '实现用户一个账号，在一处登录，全站通行',    // 插件简介
        'status' => 0,  // 状态
        'author' => 'shangtao',
        'version' => '1.0.1'
    ];

	
    /**
     * 插件安装方法
     * @return bool
     */
    public function install(){
    	$m = new DM();
    	$flag = $m->install();
    	WSTClearHookCache();
    	cache('hooks',null);
        return $flag;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall(){
    	$m = new DM();
    	$flag = $m->uninstall();
    	WSTClearHookCache();
    	cache('hooks',null);
        return $flag;
    }
    
	/**
     * 插件启用方法
     * @return bool
     */
    public function enable(){
    	WSTClearHookCache();
    	cache('hooks',null);
        return true;
    }
    
    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable(){
    	WSTClearHookCache();
    	cache('hooks',null);
    	return true;
    }

    /**
     * 插件设置方法
     * @return bool
     */
    public function saveConfig(){
    	$m = new DM();
    	$m->initConfig();
    	WSTClearHookCache();
    	cache('hooks',null);
    	return true;
    }
   
    /**
     * 用户注册后执行
     */
	public function afterUserRegist($params){
		if(!$this->ucOpen)return;
    	$m = new DM();
    	$m->afterUserRegist($params);
    }
    
    /**
     * 用户登录前执行
     */
    public function beforeUserLogin($params){
    	if(!$this->ucOpen)return;
    	$m = new DM();
    	$rs = $m->beforeUserLogin($params);
    	$params["user"] = $rs;
    }
    
    /**
     * 用户登录后执行
     */
    public function afterUserLogin($params){
    	if(!$this->ucOpen)return;
    	$m = new DM();
    	$m->afterUserLogin($params);
    }
    
    /**
     * 用户登录后执行
     */
    public function afterUserLogout($params){
    	if(!$this->ucOpen)return;
    	//同步到UC
	    $ucenter = new \addons\ucenter\api\UcenterApi();
	    $uid = $ucenter->synlogout();
	    $rd = ['status'=>'1','msg'=>$uid."成功退出"];//$uids返回在页面上，才同步成功登陆
	    exit(json_encode($rd));
    }
    
    /**
     * base 控制器中执行【home】
     */
    public function homeControllerBase(){
    	if(!$this->ucOpen)return;
    	$m = new DM();
    	$m->homeControllerBase();
    }
    
    /**
     * 修改邮箱前【home】
     */
    public function homeControllerUsersEditEmail($params){
    	if(!$this->ucOpen)return;
    	//获取用户信息
    	$userId = (int)session('WST_USER.userId');
    	$m = new \shangtao\common\model\Users();
    	$data = $m->getById($userId);
    	if($data["userFrom"]==100){
	    	if($data['userEmail']!='')$data['userEmail'] = WSTStrReplace($data['userEmail'],'*',2,'@');
	    	$process = 'One';
	    	$m = new \think\addons\Controller();
	    	$m->assign('process',$process);
	    	$m->assign('data',$data);
	    	
	    	if($data['userEmail']){
	    		exit($m->fetch('../../../addons/ucenter/view/home/users/user_edit_email'));
	    	}else{
	    		exit($m->fetch('../../../addons/ucenter/view/home/users/user_email'));
	    	}
    	}
    	
    }
    
    /**
     * 修改密码后【home】
     */
    public function afterEditPass($params){
    	if(!$this->ucOpen)return;
    	$m = new DM();
    	$m->afterEditPass($params);
    }
    
    
    /**
     * 修改密码后【home】
     */
    public function afterEditEmail($params){
    	if(!$this->ucOpen)return;
    	$m = new DM();
    	$m->afterEditEmail($params);
    }
    
    /**
     * 用户删除后执行
     */
    public function afterDelUser($params){
    	if(!$this->ucOpen)return;
    	$m = new DM();
    	$user = $m->getUserInfo($params["userId"]);
    	if($user["ucUId"]>0){
	    	//同步到UC
		    $ucenter = new \addons\ucenter\api\UcenterApi();
		    $uid = $ucenter->delete($user["ucUid"]);
    	}
	    
    }
    
    
    /**
     * 管理员添加用户
     */
    public function adminAfterAddUser($params){
    	if(!$this->ucOpen)return;
    	$m = new DM();
    	$params["user"]["userId"] = $params["userId"];
    	$m->afterUserRegist($params);
    }
    
    /**
     * 管理员修改用户资料
     */
    public function adminAfterEditUser($params){
    	if(!$this->ucOpen)return;
    	$m = new DM();
    	$m->afterEditUser($params);
    }
    
    /**
     * 管理员重置用户密码资料
     */
    public function adminAfterEditUserPass($params){
    	if(!$this->ucOpen)return;
    	$m = new DM();
    	$m->afterEditUser($params);
    }
    
    /**
     * 管理员删除用户
     */
    public function adminAfterDelUser($params){
    	if(!$this->ucOpen)return;
    	$m = new DM();
    	$m->adminAfterDelUser($params);
    }
    
    
}