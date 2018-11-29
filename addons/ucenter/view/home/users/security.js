var time = 0;
var isSend = false;
var myorm;
var emailForm;
var phoneForm;
var getemailForm;
var getphoneForm;
$(function(){
    $('#phoneVerify').validator({
      valid: function(form){
    	  var n=$('#VerifyId').val();
    	  getPhoneVerifys(n);
      }
    });
})
function vePayForm(){
	//修改密码
	myorm = $('#payform').validator({
          valid: function(form){
            var params = WST.getParams('.ipt');
            var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
            $.post(WST.U('home/users/payPassEdit'),params,function(data,textStatus){
              layer.close(loading);
              var json = WST.toJson(data);
              if(json.status=='1'){
                  WST.msg(json.msg,{icon:1,time:2000},function(){
                	   location.href=WST.U('home/users/security');
                  });
              }else{
                    WST.msg(json.msg,{icon:2});
              }
            });
      }
    })
}
function veMyorm(){
  //修改密码
  myorm = $('#myorm').validator({
            fields: {
                newPass: {
                  rule:"required;length[6~20]",
                  msg:{required:"请输入新密码"},
                  tip:"请输入新密码"
                },
                reNewPass: {
                  rule:"required;length[6~20];match[newPass]",
                  msg:{required:"请再次输入新密码",match:"两次输入密码不匹配"},
                  tip:"请再次输入新密码"
                },
            },
          valid: function(form){
            var params = WST.getParams('.ipt');
            var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
            $.post(WST.U('home/users/passEdit'),params,function(data,textStatus){
              layer.close(loading);
              var json = WST.toJson(data);
              if(json.status=='1'){
                  WST.msg("操作成功",{icon:1,time:2000},function(){
                    location.href=WST.U('home/users/security');
                  });
              }else{
                    WST.msg(json.msg,{icon:2});
              }
            });
      }
    })
}
function veemailForm(){
    //绑定邮箱
	emailForm = $('#emailForm').validator({
    	rules: {
            remote: function(element){
            	return $.post(WST.U('home/users/checkEmail'),{"loginName":element.value},function(data,textStatus){
            	});
            }	
    	},
        fields: {
        	userEmail: {
		        rule:"required;email;remote;",
		        msg:{required:"请输入邮箱",email:"请输入有效的邮箱"},
		        tip:"请输入邮箱",
            },
            secretCode: {
                rule:"required",
                msg:{required:"请输入校验码"},
                tip:"请输入校验码",
  	            target:"#secretErr"
              },
            loginPwd: {
              rule:"required",
              msg:{required:"请输入登录密码"},
              tip:"请输入登录密码"
            }
        },
        
      valid: function(form){
        var params = WST.getParams('.ipt');
        var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
        $.post(WST.U('addon/ucenter-ucenter-emailedit'),params,function(data,textStatus){
          layer.close(loading);
          var json = WST.toJson(data);
          if(json.status=='1'){
             var redirect = WST.U('home/users/doneEmailBind');
             var edit = $('#editEmail').val();
             if(edit)redirect=WST.U('home/users/editEmail3');
  			     WST.msg('验证通过',{icon:1},function(){location.href=redirect});
          }else{
                WST.msg(json.msg,{icon:2});
          }
        });
      }
    });
}
function sendEmail(edit){
  var url = 'home/users/getEmailVerify';
  if(isSend )return;
  if(!$('#verifyCode').isValid())return;
  if(!edit){
      if(!$('#userEmail').isValid())return;
  }else{
      url = 'home/users/getEmailVerifyt';
  }
  var loading = WST.msg('正在发送邮件，请稍后...', {icon: 16,time:60000});
  var params = WST.getParams('.ipt');
  $.post(WST.U(url),params,function(data,textStatus){
    layer.close(loading);
    var json = WST.toJson(data);
    if(json.status=='1'){
      WST.msg('邮箱已发送，请注册查收');
      isSend = true;
      time = 120;
      $('#timeSend').attr('disabled', 'disabled').css('background','#e8e6e6');
      $('#timeSend').html('发送验证邮件(120)');
      var task = setInterval(function(){
        time--;
        $('#timeSend').html('发送验证邮件('+time+")");
        if(time==0){
          isSend = false;           
          clearInterval(task);
          $('#timeSend').html("重新发送验证邮件");
          $('#timeSend').removeAttr('disabled').css('background','#e23e3d');
        }
      },1000);
    }else{
          WST.msg(json.msg,{icon:2});
          WST.getVerify('#verifyImg');
    }
  });
}




function vephoneForm(){
    //绑定手机号
	phoneForm = $('#phoneForm').validator({
      valid: function(form){
        var me = this;
        // ajax提交表单之前，先禁用submit
        me.holdSubmit();
        var params = WST.getParams('.ipt');
        var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
        $.post(WST.U('home/users/phoneEdito'),params,function(data,textStatus){
          layer.close(loading);
          var json = WST.toJson(data);
          if(json.status=='1'){
  	           location.href=WST.U('home/users/editPhoneSu','pr='+json.process);
          }else{
                WST.msg(json.msg,{icon:2});
                WST.getVerify('#verifyImg'); 
          }
        });
      }
    });
}

function vegetemailForm(){
    //修改邮箱
	getemailForm = $('#getemailForm').validator({
		fields: {
            loginPwd: {
              rule:"required",
              msg:{required:"请输入登录密码"},
              tip:"请输入登录密码"
            }
        },
      valid: function(form){
    	 
        var params = WST.getParams('.ipt');
        var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
        $.post(WST.U('addon/ucenter-ucenter-checkemailedit'),params,function(data,textStatus){
          layer.close(loading);
          var json = WST.toJson(data);
          if(json.status=='1'){
              WST.msg('验证通过',{icon:1},function(){location.href=WST.U('addon/ucenter-ucenter-editemail2')})
          }else{
                WST.msg(json.msg,{icon:2});
                WST.getVerify('#verifyImg');
          }
        });
      }
    });
}

function editEmailForm(){
    //绑定邮箱
	emailForm = $('#emailForm').validator({
    	rules: {
            remote: function(element){
            	return $.post(WST.U('home/users/checkEmail'),{"loginName":element.value},function(data,textStatus){
            	});
            }	
    	},
        fields: {
        	userEmail: {
		        rule:"required;email;remote;",
		        msg:{required:"请输入邮箱",email:"请输入有效的邮箱"},
		        tip:"请输入邮箱",
            },
            secretCode: {
                rule:"required",
                msg:{required:"请输入校验码"},
                tip:"请输入校验码",
  	            target:"#secretErr"
              }
    	},
        
      valid: function(form){
        var params = WST.getParams('.ipt');
        var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
        $.post(WST.U('addon/ucenter-ucenter-emaileditt'),params,function(data,textStatus){
          layer.close(loading);
          var json = WST.toJson(data);
          if(json.status=='1'){
        	  	var redirect=WST.U('addon/ucenter-ucenter-editemail3');
  				WST.msg('验证通过',{icon:1},function(){location.href=redirect});
          }else{
                WST.msg(json.msg,{icon:2});
          }
        });
      }
    });
}
