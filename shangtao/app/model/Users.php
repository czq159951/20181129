<?php
namespace shangtao\app\model;
use think\Db;
/**
 * 用户类
 */
class Users extends Base{
    protected $pk = 'userId';
    /**
     * 登录验证
     * loginKey 64位加密传过来，密匙->例如:base64(base64(账号)._.base64(密码))
     * loginRemark:标记是android还是ios
     *
     * status:-1:账号不存在!  -2:账号已被停用! -3:账号或密码不正确! 1:登录成功~
     * msg:登录信息
     * user:{userId,loginName,userName,userPhoto}
     */
    public function login(){
        $rv = array('status'=>-1,'msg'=>'账号不存在!');
        $loginKey = input('loginKey');
        $code = input("verifyCode");


        if(!WSTVerifyCheck($code) && strpos(WSTConf("CONF.captcha_model"),"4")>=0){
            return WSTReturn('验证码错误!',-1);
        }


        $loginKey = base64_decode($loginKey);
        $loginKey = explode('_',$loginKey);

        // WSTAddslashes 处理转义字符 $loginName = WSTAddslashes(base64_decode($loginKey[0]));

        $loginName = base64_decode($loginKey[0]);
        $loginPwd = base64_decode($loginKey[1]);

        if($loginName=='' || $loginPwd=='')return $rv;
        $m = model('users');

        $urs = $this->field('userId,loginName,loginSecret,loginPwd,userName,userSex,userPhoto,userStatus,userScore,userType,wxUnionId')
                    ->where("loginName='{$loginName}' or userPhone='{$loginName}' or userEmail='{$loginName}'")
                    ->where('dataFlag=1')
                    ->find();

        if(empty($urs))return $rv;//账号不存在!




        if($urs['userStatus']==0)return array('status'=>-2,'msg'=>'账号不存在!');//账号已被停用!

        if(md5($loginPwd.$urs['loginSecret'])!=$urs['loginPwd'])return array('status'=>-3,'msg'=>'账号或密码不正确!');//账号或密码不正确!


        //【微信绑定】判断是否有传unionId
        $unionId = input('unionId');
        if($unionId!=''){
            // 判断该unionId是否已经绑定其他账号
            $has = $this->where(['wxUnionId'=>$unionId,'dataFlag'=>1,'userStatus'=>1])->find();
            if(!empty($has)){
                return WSTReturn('该微信已绑定其他账号',-1);
            }
            // 判断该账号是否已绑定其他微信
            if($urs['wxUnionId'])return WSTReturn('该账号已绑定其他微信号',-1);
            // 绑定unionId
            $this->where("loginName='{$loginName}' or userPhone='{$loginName}' or userEmail='{$loginName}'")
                    ->where('dataFlag=1')
                    ->setField('wxUnionId',$unionId);
        }
        //【QQ绑定】判断是否有传qqOpenId
        $qqOpenId = input('qqOpenId');
        if($qqOpenId!=''){
            // 判断该qqOpenId是否已经绑定其他账号
            $has = $this->alias('u')
                        ->join('third_users tu','tu.userId=u.userId','inner')
                        ->where(['tu.thirdOpenId'=>$qqOpenId,'u.dataFlag'=>1])
                        ->find();
            if(!empty($has)){
                return WSTReturn('该QQ已绑定其他账号',-1);
            }
            $tuModel = Db::name('third_users');
            // 判断该账号是否已绑定其他QQ
            $hasBindQq = $tuModel->where(['userId'=>$urs['userId'],'thirdCode'=>'qq'])->find();
            if(!empty($hasBindQq))return WSTReturn('该账号已绑定其他qq号',-1);
            $bindQqData = [];
            $bindQqData['userId'] = $urs['userId'];
            $bindQqData['thirdCode'] = 'qq';
            $bindQqData['thirdOpenId'] = $qqOpenId;
            $bindQqData['createTime'] = date('Y-m-d H:i:s');
            // 绑定qqOpenId
            $bindRs = $tuModel->insert($bindQqData);
        }
         //【支付宝绑定】判断是否有传支付宝user_id
        $alipayId = input('alipayId');
        if($alipayId!=''){
            // 判断该alipayId是否已经绑定其他账号
            $has = $this->alias('u')
                        ->join('third_users tu','tu.userId=u.userId','inner')
                        ->where(['tu.thirdOpenId'=>$alipayId,'u.dataFlag'=>1])
                        ->find();
            if(!empty($has)){
                return WSTReturn('该支付宝用户已绑定其他账号',-1);
            }
            $tuModel = Db::name('third_users');
            // 判断该账号是否已绑定其他支付宝账号
            $hasBindAlipay = $tuModel->where(['userId'=>$urs['userId'],'thirdCode'=>'alipay'])->find();
            if(!empty($hasBindAlipay))return WSTReturn('该账号已绑定其他支付宝账号',-1);
            $bindAlipayData = [];
            $bindAlipayData['userId'] = $urs['userId'];
            $bindAlipayData['thirdCode'] = 'alipay';
            $bindAlipayData['thirdOpenId'] = $alipayId;
            $bindAlipayData['createTime'] = date('Y-m-d H:i:s');
            // 绑定alipayId
            $bindRs = $tuModel->insert($bindAlipayData);
        }


        unset($urs['loginSecret'],$urs['loginPwd'],$urs['userStatus']);
        $rv['status'] = 1;
        $rv['msg'] = '登录成功~';
        $rv['data'] = $urs;
        //记录登录信息
        $data = array();
        $data["userId"] = $urs['userId'];
        $data["loginTime"] = date('Y-m-d H:i:s');

        // 用户登录地址 $data["loginIp"] = get_client_ip();
        $data["loginIp"] = request()->ip();


        //登录来源、登录设备

        $data["loginSrc"] = 2;
        $data["loginRemark"] = Input('loginRemark','android');
        
        /**************** 记录登录日志  **************/
        Db::name('log_user_logins')->insert($data);

        //记录tokenId
        $m = Db::name('app_session');

        /*************************   制作key  **********************/
        $key = sprintf('%011d',$urs['userId']);

        $tokenId = $this->to_guid_string($key.time());


        $data = array();
        $data['userId'] = $urs['userId'];
        $data['tokenId'] = $tokenId;
        $data['startTime'] = date('Y-m-d H:i:s');
        $data['deviceId'] = input('deviceId');
        $m->insert($data);
        $rv['data']['tokenId'] = $tokenId;

        // 判断是否为客服账号
        hook('afterUserLogin',['user'=>&$urs,'isApp'=>1]);
        //删除上一条登录记录
        $m->where('tokenId!="'.$tokenId.'" and userId='.$urs['userId'])->delete();
        return $rv;
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
     * 用户注册
     * registerKey 64位加密传过来，密匙->例如:base64(base64(账号)._.base64(密码))
     * loginRemark:标记是android还是ios
     * deviceId:设备Id
     *
     */
    public function register(){
        $rv = array('status'=>-1,'msg'=>'账号已存在!');
        $registerKey = input('registerKey');
        $registerKey = base64_decode($registerKey);
        $registerKey = explode('_',$registerKey);
        $loginName = base64_decode($registerKey[0]);
        $loginPwd = base64_decode($registerKey[1]);
        
        $startTime = (int)session('VerifyCode_userPhone_Time');
        if((time()-$startTime)>120){
        	return WSTReturn("验证码已超过有效期!");
        }
        $loginName2 = session('VerifyCode_userPhone');
        if($loginName!=$loginName2){
        	return WSTReturn("注册手机号与验证手机号不一致!");
        }
        // 检测手机号
        if(!WSTIsPhone($loginName))return WSTReturn('请填写有效的手机号码');
        //检测账号是否存在
        $rs = WSTCheckLoginKey($loginName);
        
        $data = array();
        $nameType = (int)input("post.nameType");
        $mobileCode = input("post.mobileCode");

        //只允许手机号码注册
        $data['userPhone'] = $loginName;
        $verify = session('VerifyCode_userPhone_Verify');
        if($mobileCode=="" || $verify != $mobileCode){
            return WSTReturn("验证码错误!");
        }
        $loginName = WSTRandomLoginName($loginName);
        
        //【微信注册】判断是否有传unionId
        $unionId = input('unionId');
        if($unionId!=''){
            // 判断该unionId是否已经绑定其他账号
            $has = $this->where(['wxUnionId'=>$unionId,'dataFlag'=>1,'userStatus'=>1])->find();
            if(!empty($has)){
                return WSTReturn('该微信已绑定其他账号',-1);
            }
            $data['wxUnionId'] = $unionId;
        }

        //【QQ绑定】判断是否有传qqOpenId
        $qqOpenId = input('qqOpenId');
        if($qqOpenId!=''){
            // 判断该qqOpenId是否已经绑定其他账号
            $has = $this->alias('u')
                        ->join('third_users tu','tu.userId=u.userId','inner')
                        ->where(['tu.thirdOpenId'=>$qqOpenId,'u.dataFlag'=>1])
                        ->find();
            if(!empty($has)){
                return WSTReturn('该QQ已绑定其他账号',-1);
            }
        }
        //【支付宝绑定】判断是否有传支付宝user_di
        $alipayId = input('alipayId');
        if($alipayId!=''){
            // 判断该alipayId是否已经绑定其他账号
            $has = $this->alias('u')
                        ->join('third_users tu','tu.userId=u.userId','inner')
                        ->where(['tu.thirdOpenId'=>$alipayId,'u.dataFlag'=>1])
                        ->find();
            if(!empty($has)){
                return WSTReturn('该支付宝用户已绑定其他账号',-1);
            }
        }
        if($rs['status']==1){
            $data['loginName'] = $loginName;
            $data['userName'] = '手机用户'.substr($data['userPhone'],-4);
            $data["loginSecret"] = rand(1000,9999);
            $data['loginPwd'] = md5($loginPwd.$data['loginSecret']);
            $data['userType'] = 0;
            $data['createTime'] = date('Y-m-d H:i:s');
            $data['dataFlag'] = 1;
            $userId = $this->data($data)->save();
            if(false !== $userId){
                // 执行【QQ绑定】
                if($qqOpenId!=''){
                    $tuModel = Db::name('third_users');
                    $bindQqData = [];
                    $bindQqData['userId'] = $this->userId;
                    $bindQqData['thirdCode'] = 'qq';
                    $bindQqData['thirdOpenId'] = $qqOpenId;
                    $bindQqData['createTime'] = date('Y-m-d H:i:s');
                    // 绑定qqOpenId
                    $bindRs = $tuModel->insert($bindQqData);
                }
                // 执行【支付宝账号绑定】
                if($alipayId!=''){
                    $tuModel = Db::name('third_users');
                    $bindAlipayData = [];
                    $bindAlipayData['userId'] = $this->userId;
                    $bindAlipayData['thirdCode'] = 'alipay';
                    $bindAlipayData['thirdOpenId'] = $alipayId;
                    $bindAlipayData['createTime'] = date('Y-m-d H:i:s');
                    // 绑定alipayId
                    $bindRs = $tuModel->insert($bindAlipayData);
                }
                $data = array();
                $userId = $this->userId;
                $data["userId"] = $userId;
                $data["loginTime"] = date('Y-m-d H:i:s');
                $data["loginIp"] = request()->ip();
                $data["loginSrc"] = 2;
                $data["loginRemark"] = input('loginRemark');
                Db::name('log_user_logins')->insert($data);
                //记录tokenId
                $data = array();
                $key = sprintf('%011d',$userId);
                $tokenId = $this->to_guid_string($key.time());
                $rv['status']= 1;
                $rv['msg']= '注册成功~';
                $data['userId'] = $userId;
                $data['tokenId'] = $tokenId;
                $data['startTime'] = date('Y-m-d H:i:s');
                $data['deviceId'] = input('deviceId');
                Db::name('app_session')->insert($data);
                $user = $this->where("userId=".$userId)->field("userId,loginName,userName,userSex,userType,userPhoto,userScore")->find();
                $rv['data'] = $user;
                $rv['data']['tokenId'] = $tokenId;
            }
        }
        return $rv;
    }


    /***********************************************************  ***************************************************************/

    /**
     * 修改用户密码
     */
    public function editPass($id){
        $data = array();
        $data["loginPwd"] = input("post.newPass");
        if(!$data["loginPwd"]){
            return WSTReturn('密码不能为空',-1);
        }
        $rs = $this->where('userId='.$id)->find();
        //核对密码
        if($rs['loginPwd']){
            if($rs['loginPwd']==md5(input("post.oldPass").$rs['loginSecret'])){
                $data["loginPwd"] = md5(input("post.newPass").$rs['loginSecret']);
                $rs = $this->update($data,['userId'=>$id]);
                if(false !== $rs){
                    return WSTReturn("密码修改成功", 1);
                }else{
                    return WSTReturn($this->getError(),-1);
                }
            }else{
                return WSTReturn('原始密码错误',-1);
            }
        }else{
            $data["loginPwd"] = md5(input("post.newPass").$rs['loginSecret']);
            $rs = $this->update($data,['userId'=>$id]);
            if(false !== $rs){
                return WSTReturn("密码修改成功", 1);
            }else{
                return WSTReturn($this->getError(),-1);
            }
        }
    }
    /**
     * 修改用户支付密码
     */
    public function editPayPass(){
        $id = $this->getUserId();
        $data = array();
        $data["payPwd"] = input("post.newPass");
        if(!$data["payPwd"]){
            return WSTReturn('支付密码不能为空',-1);
        }
        $rs = $this->where('userId='.$id)->find();
        //核对密码
        if($rs['payPwd']){
            if($rs['payPwd']==md5(input("post.oldPass").$rs['loginSecret'])){
                $data["payPwd"] = md5($data["payPwd"].$rs['loginSecret']);
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
            $data["payPwd"] = md5($data["payPwd"].$rs['loginSecret']);
            $rs = $this->update($data,['userId'=>$id]);
            if(false !== $rs){
                return WSTReturn("支付密码设置成功", 1);
            }else{
                return WSTReturn("支付密码设置失败",-1);
            }
        }
    }
   /**
    *  获取用户信息
    */
    public function getById(){
        $id = $this->getUserId();
        $rs = $this->field('loginSecret,loginPwd,userQQ,userEmail,trueName,lastIP,lastTime,dataFlag,userStatus,createTime,wxOpenId,wxUnionId,distributMoney,isBuyer,brithday',true)
                   ->where(['userId'=>(int)$id])
                   ->find();
        $rs['ranks'] = WSTUserRank($rs['userTotalScore']);
        return $rs;
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
                return WSTReturn("编辑成功", 1);
            }
        }catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('编辑失败',-1);
        }   
    }

    /**
     * 绑定手机
     */
    public function editPhone($userPhone){
        $userId = $this->getUserId();
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
     * 重置用户密码
     */
    public function resetPass(){
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
    /**
     * 验证用户支付密码
     */
    function checkPayPwd(){
        $userId = $this->getUserId();
        $rs = $this->field('payPwd,loginSecret')->find($userId);
        $payPwd = input('payPwd');
        if($rs['payPwd']==md5($payPwd.$rs['loginSecret'])){
            return WSTReturn('',1);
        }
        return WSTReturn('支付密码错误',-1);
    }
    /**
    * 用户注销
    *
    */
    public function logout(){
        $tokenId = input('tokenId');
        $rs = Db::name('app_session')->where("tokenId='{$tokenId}'")->delete();
        if($rs!==false)return WSTReturn('注销成功',1);
        return WSTReturn('发生未知错误',-1);
    }
}
