<?php
namespace addons\ucenter\controller;

use think\addons\Controller;
use addons\ucenter\model\Ucenter as M;
/**
 * 第三方登录控制器
 */
class Ucenter extends Controller{
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
    
    /**
     * 绑定邮箱
     */
    public function emailEdit(){

    	$USER = session('WST_USER');
    	if(empty($USER) && $USER['userId']==''){
    		$this->redirect("home/users/login");
    	}
    	$bindTime = session('email.time');
    	$code = session('email.key');
    	$bindEmail = session('email.val');
 
    	$m = new M();
    	if(!$m->checkLoginPass())return WSTReturn('密码错误',-1);
    	 
    	if(time()>floatval($bindTime)+30*60){
    		return WSTReturn('验证码已失效！',-1);
    	}
    	$rs = WSTCheckLoginKey($bindEmail,(int)session('WST_USER.userId'));
    	
    	if($rs["status"]!=1){
    		return WSTReturn('邮箱已存在!',-1);
    	}
    	$secretCode = input('secretCode');
    	
    	if($code!=$secretCode)return WSTReturn('校验码错误',-1);
    	
    	$m = new \shangtao\common\model\Users();
    	$rs = $m->editEmail((int)session('WST_USER.userId'),$bindEmail);
    	if($rs['status'] == 1){
    		// 清空session
    		session('email',null);
    		return WSTReturn('验证通过',1);
    	}
    	return WSTReturn('绑定邮箱失败！',-1);
    }
    
    
    
    /**
     * 修改邮箱
     */
    public function emailEditt(){
    	$USER = session('WST_USER');
    	if(empty($USER) && $USER['userId']!=''){
    		$this->redirect("home/users/login");
    	}
    	if(empty(session('checkEmailEditBind'))){
    		return WSTReturn('操作失效，绑定失败！',-1);
    	}
    	$bindTime = session('email.time');
    	$code = session('email.key');
    	$bindEmail = session('email.val');
    	
    
    	if(time()>floatval($bindTime)+30*60){
    		return WSTReturn('验证码已失效！',-1);
    	}
    	$rs = WSTCheckLoginKey($bindEmail,(int)session('WST_USER.userId'));
    
    	if($rs["status"]!=1){
    		return WSTReturn('邮箱已存在!',-1);
    	}
    	$secretCode = input('secretCode');
    
    	if($code!=$secretCode)return WSTReturn('校验码错误',-1);
    
    	$m = new \shangtao\common\model\Users();
    	$rs = $m->editEmail((int)session('WST_USER.userId'),$bindEmail);
    	if($rs['status'] == 1){
    		// 清空session
    		session('email',null);
    		session('checkEmailEditBind',null);
    		return WSTReturn('验证通过',1);
    	}
    	
    	return WSTReturn('验证失败!',-1);
    }
    
    /**
     * 修改邮箱第二步
     */
    public function editEmail2(){
    	if(!empty(session('checkEmailEditBind'))){
    		$this->assign('process','Two');
    		return $this->fetch('home/users/user_edit_email');
    	}else{
    		//获取用户信息
    		$userId = (int)session('WST_USER.userId');
    		$m = new \shangtao\common\model\Users();
    		$data = $m->getById($userId);
    		if($data['userEmail']!='')$data['userEmail'] = WSTStrReplace($data['userEmail'],'*',2,'@');
    		$process = 'One';
    		$this->assign('process',$process);
    		$this->assign('data',$data);
    		return $this->fetch('home/users/user_edit_email');
    	}
    	
    }
    
    /**
     * 修改邮箱第三步
     */
    public function editEmail3(){
    	$this->assign('process','Three');
    	return $this->fetch('home/users/user_edit_email');
    }
    
    
    /**
     * 查询并加载用户资料
     */
    public function checkEmailEdit(){
    	$m = new M();
    	if($m->checkLoginPass()){
    		$loginPwd = input("post.loginPwd");
    		session('checkEmailEditBind',$loginPwd);
    		return WSTReturn('验证通过',1);
    	}else{
    		return WSTReturn('密码错误',-1);
    	}
    }
}