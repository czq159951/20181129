jQuery.noConflict();
function inChoice(n){
	if(n==1){
		$('#login-w').html('登录');
	}else{
		$('#login-w').html('注册新账号');
	}
	WST.showHide('','#choice');
	WST.showHide(1,'#login'+n+',#return');
}
function inReturn(){
	$('#login-w').html('登录账号');
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
	WST.load('登录中···');
    var param = {};
    param.loginName = loginName;
    param.loginPwd = loginPwd;
    param.verifyCode = loginVerfy;
	$('#loginButton').addClass("active").attr('disabled', 'disabled');
    $.post(WST.U('wechat/users/checkLogin'), param, function(data){
        var json = WST.toJson(data);
        if( json.status == 1 ){
        	WST.msg(json.msg,'success');
        	var url = json.url;
            setTimeout(function(){
            	if(WST.blank(url)){
            		location.href = url;
            	}else{
                	location.href = WST.U('wechat/users/index');
            	}
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
	    $.post(WST.U('wechat/users/checkUserPhone'), {userPhone:data}, function(data){
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
    var param = {};
    if($('#defaults').hasClass('ui-icon-unchecked-s')){
    	WST.msg('请阅读用户注册协议','info');
        return false;
    }
	if(regName==''){
    	WST.msg('请输入账号','info');
        return false;
    }
	if(regName.length < 6){
	    WST.msg('账号为6位以上数字或字母','info');
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
	param.mobileCode = phoneCode;
    if(window.conf.IS_CRYPTPWD==1){
        var public_key=$('#key').val();
        var exponent="10001";
   	    var rsa = new RSAKey();
        rsa.setPublic(public_key, exponent);
        var regcoPwd = rsa.encrypt(regcoPwd);
        var regPwd = rsa.encrypt(regPwd);
    }
	WST.load('注册中···');
    param.nameType = nameType;
    param.loginName = regName;
    param.loginPwd = regcoPwd;
    param.reUserPwd = regPwd;
	$('#regButton').addClass("active").attr('disabled', 'disabled');
    $.post(WST.U('wechat/users/register'), param, function(data){
        var json = WST.toJson(data);
        if( json.status == 1 ){
        	WST.msg(json.msg,'success');
        	var url = json.url;
            setTimeout(function(){
            	if(WST.blank(url)){
            		location.href = url;
            	}else{
                	location.href = WST.U('wechat/users/index');
            	}
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
    $.post(WST.U('wechat/users/getPhoneVerifyCode'), param, function(data){
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
//弹框
function wholeShow(type){
    jQuery('#'+type).animate({"right": 0}, 500);
}
function wholeHide(type){
    var dataWidth = $('#'+type).css('width');
    jQuery('#'+type).animate({'right': '-'+dataWidth}, 500);
}
//协议
function inAgree(obj){
    if($('#defaults').hasClass('wst-active')){
    	$(obj).addClass('ui-icon-unchecked-s');
    	$(obj).removeClass('ui-icon-success-block wst-active');
    }else{
    	$(obj).removeClass('ui-icon-unchecked-s');
    	$(obj).addClass('ui-icon-success-block wst-active');
    }
}
$(document).ready(function(){
	var w = WST.pageWidth();
	var h = WST.pageHeight();
    $('#protocol .content').css('overflow-y','scroll').css('height',h-61);
    $("#protocol").css('right',-w);
});