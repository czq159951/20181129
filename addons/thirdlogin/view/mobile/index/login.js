function inChoice(n){
	if(n==1){
		$('#login-w').html('关联到已有账号');
	}else{
		$('#login-w').html('关联新账号');
	}
	WST.showHide('','#choice');
	WST.showHide(1,'#login'+n+',#return');
}
function inReturn(){
	$('#login-w').html('关联账号');
	WST.showHide('','#login0,#login1,#return');
	WST.showHide(1,'#choice');
}
function login(){
	var loginName = $('#loginName').val();
	var loginPwd = $('#loginPwd').val();
	var loginVerfy = $('#loginVerfy').val();
	if(loginName==''){
    	WST.msg('请输入账号','info');
        return false;
    }
	if(loginPwd==''){
    	WST.msg('请输入密码','info');
        return false;
    }
	if(loginVerfy==''){
    	WST.msg('请输入验证码','info');
        return false;
    }
    if(window.conf.IS_CRYPTPWD==1){
        var public_key=$('#key').val();
        var exponent="10001";
   	    var rsa = new RSAKey();
        rsa.setPublic(public_key, exponent);
        var loginPwd = rsa.encrypt(loginPwd);
    }
	WST.load('绑定中···');
    var param = {};
    param.loginName = loginName;
    param.loginPwd = loginPwd;
    param.verifyCode = loginVerfy;
	$('#loginButton').addClass("active").attr('disabled', 'disabled');
    $.post(WST.U('mobile/users/checkLogin'), param, function(data){
        var json = WST.toJson(data);
        if( json.status == 1 ){
        	WST.msg(json.msg,'success');
            setTimeout(function(){
            	location.href = WST.U('mobile/users/index');
            },2000);
        }else{
        	WST.msg(json.msg,'warn');
        	WST.getVerify("#verifyImg1");
        	$('#loginButton').removeAttr('disabled').removeClass("active");
        }
        WST.noload();
        data = json = null;
    });
}
var nameType = 3;
function onTesting(obj){
    //不能输入中文
    WST.isChinese(obj,1);
    var data = $(obj).val();
    var  regMobile = /^0?1[3|4|5|8][0-9]\d{8}$/;
    if(regMobile.test(data)){//手机
        $.post(WST.U('mobile/users/checkUserPhone'), {userPhone:data}, function(data){
            var json = WST.toJson(data);
            if( json.status == 1 ){
            }else{
                var dia=$.dialog({
                    title:'',
                    content:'<p style="text-align: center;">手机号已注册</p>',
                    button:["确认"]
                });
            }
            data = json = null;
        });
    }
}
function register(){
	var regName = $('#regName').val();
	var regPwd = $('#regPwd').val();
	var regcoPwd = $('#regcoPwd').val();
	var regVerfy = $('#regVerfy').val();
    var phoneCode = $('#phoneCode').val();
	if(regName==''){
    	WST.msg('请输入账号','info');
        return false;
    }
	if(regPwd==''){
    	WST.msg('请输入密码','info');
        return false;
    }
	if(regPwd.length < 6 || regPwd.length > 16){
	    WST.msg('请输入密码为6-16位字符','info');
	    return false;
	}
	if(regcoPwd==''){
    	WST.msg('确认密码不能为空','info');
        return false;
    }
	if(regPwd!=regcoPwd){
    	WST.msg('确认密码不一致','info');
        return false;
    }
	if(phoneCode==''){
        WST.msg('请输入短信验证码','info');
        return false;
    }
    if(window.conf.IS_CRYPTPWD==1){
        var public_key=$('#key').val();
        var exponent="10001";
   	    var rsa = new RSAKey();
        rsa.setPublic(public_key, exponent);
        var regcoPwd = rsa.encrypt(regcoPwd);
        var regPwd = rsa.encrypt(regPwd);
    }
	WST.load('注册中···');
    var param = {};
    param.nameType = nameType;
    param.loginName = regName;
    param.loginPwd = regcoPwd;
    param.reUserPwd = regPwd;
    param.verifyCode = regVerfy;
    param.mobileCode = phoneCode;
	$('#regButton').addClass("active").attr('disabled', 'disabled');
    $.post(WST.U('mobile/users/register'), param, function(data){
        var json = WST.toJson(data);
        if( json.status == 1 ){
        	WST.msg(json.msg,'success');
            setTimeout(function(){
            	location.href = WST.U('mobile/users/index');
            },2000);
        }else{
        	WST.msg(json.msg,'warn');
            WST.getVerify("#verifyImg0");
        	$('#regButton').removeAttr('disabled').removeClass("active");
        }
        WST.noload();
        data = json = null;
    });
}
var time = 0;
var isSend = false;
function obtainCode(){
	var userPhone = $('#regName').val();
    if(userPhone ==''){
    	WST.msg('请输入帐号为手机号码','info');
	    $('#userPhone').focus();
        return false;
    }
	if(WST.conf.SMS_VERFY==1){
		var smsVerfy = $('#smsVerfy').val();
	    if(smsVerfy ==''){
	    	WST.msg('请输入验证码','info');
		    $('#smsVerfy').focus();
	        return false;
	    }
	}
    var param = {};
	param.userPhone = userPhone;
	param.smsVerfy = smsVerfy;
	if(isSend)return;
	isSend = true;
    $.post(WST.U('mobile/users/getPhoneVerifyCode'), param, function(data){
        var json = WST.toJson(data);
        if( json.status == 1 ){
        	WST.msg(json.msg,'success');
			time = 120;
			$('#obtain').attr('disabled', 'disabled').html('120秒获取');
			var task = setInterval(function(){
				time--;
				$('#obtain').html(''+time+"秒获取");
				if(time==0){
					isSend = false;
					clearInterval(task);
					$('#obtain').removeAttr('disabled').html("重新发送");
				}
			},1000);
        }else{
        	WST.msg(json.msg,'warn');
        	WST.getVerify("#verifyImg3");
        	isSend = false;
        }
        data = json = null;
    });
}