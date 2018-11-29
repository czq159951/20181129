
//跳转支付
function toPay(payCode){
	var params = {};
	params.orderNo = $.trim($("#orderNo").val());
	params.payFrom = 2;
	var client = (payCode=="weixinpays")?"wx":"";
	$.post(WST.AU('pintuan://'+payCode+client+'/get'+payCode+"url"),params,function(data) {
		var json = WST.toJson(data);
		if(json.status==1){
			if(payCode=="unionpays"){
				location.href = WST.AU('pintuan://unionpays/tounionpays',params);
			}else if(payCode=="weixinpays" && client=="wx"){
				location.href = WST.AU('pintuan://weixinpayswx/topay',params);
			}else{
				location.href = json.url;
			}
		}else{
			WST.msg(json.msg, {icon: 5,time:1500},function(){});
		}
	});
}

//余额支付
function walletPay(type){
	var payPwd = $('#payPwd').val();
	if(!payPwd){
		WST.msg('请输入支付密码','info');
		return;
	}
	if(type==0){
		var payPwd2 = $('#payPwd2').val();
		if(payPwd2==''){
	    	WST.msg('确认密码不能为空','info');
	        return false;
	    }
		if(payPwd!=payPwd2){
	    	WST.msg('确认密码不一致','info');
	        return false;
	    }
	}
    if(window.conf.IS_CRYPTPWD==1){
        var public_key=$('#key').val();
        var exponent="10001";
   	    var rsa = new RSAKey();
        rsa.setPublic(public_key, exponent);
        var payPwd = rsa.encrypt(payPwd);
    }
	var params = {};
	if(type==0){
		params.newPass = payPwd;
		$.post(WST.U('wechat/users/editpayPwd'),params,function(data,textStatus){
			WST.noload(); 
			var json = WST.toJson(data);
		    if(json.status==1){
		    	WST.load('成功设置密码，<br>订单支付中···');
		    }else{
		    	WST.msg(json.msg,'info');
		    }
		});
	}else{
		WST.load('正在核对密码···');
	}
    params.payPwd = payPwd;
    params.orderNo = $('#orderNo').val();
    params.payFrom = $('#payFrom').val();
    $('.wst-btn-dangerlo').attr('disabled', 'disabled');
    setTimeout(function(){
	$.post(WST.AU('pintuan://wallets/paybywallet'),params,function(data,textStatus){
		WST.noload(); 
		var json = WST.toJson(data);
	    if(json.status==1){
	    	WST.msg(json.msg,'success');
	        setTimeout(function(){
		    	location.href=WST.AU('pintuan://pintuan/wxpulist');
	        },2000);
	    }else{
	    	WST.msg(json.msg,'info');
	        setTimeout(function(){
	            $('.wst-btn-dangerlo').removeAttr('disabled');
	        },2000);
	    }
	});
    },1000);
}