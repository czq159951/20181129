function getPayUrl(){
	var params = {};
		params.payObj = $.trim($("#payObj").val());
		params.payCode = $.trim($("#payCode").val());
		params.auctionId = $.trim($("#auctionId").val());
	if(params.payCode==""){
		WST.msg('请先选择支付方式', {icon: 5});
		return;
	}

	jQuery.post(WST.AU('auction://'+params.payCode+'/get'+params.payCode+"url"),params,function(data) {
		var json = WST.toJson(data);
		if(json.status==1){
			if(params.payCode=="weixinpays" || params.payCode=="wallets"){
				location.href = json.url;
			}else if(params.payCode=="unionpays"){
				location.href = WST.AU('auction://unionpays/tounionpays',params);
			}else{
				$("#alipayform").html(json.result);
			}
		}else{
			WST.msg(json.msg, {icon: 5,time:1500},function(){});
		}
	});
}

function payByWallet(){
    var params = WST.getParams('.j-ipt');
	var load = WST.load({msg:'正在核对支付密码，请稍后...'});
    if(window.conf.IS_CRYPT=='1'){
        var public_key=$('#token').val();
        var exponent="10001";
   	    var rsa = new RSAKey();
        rsa.setPublic(public_key, exponent);
        params.payPwd = rsa.encrypt(params.payPwd);
    }
	$.post(WST.AU('auction://wallets/paybywallet'),params,function(data,textStatus){
		layer.close(load);   
		var json = WST.toJson(data);
	    if(json.status==1){
	    	WST.msg(json.msg, {icon: 1,time:1500},function(){
	    		if(params.payObj=="bao"){
	    			window.location = WST.AU('auction://wallets/paySuccess');
	    		}else{
	    			window.location = WST.AU('auction://users/checkPayStatus',{"id":params.auctionId});
	    		}
	    	});
	    }else{
	    	WST.msg(json.msg,{icon:2,time:1500});
	    }
	});
}

function setPaypwd(){
	layerbox =	layer.open({
		title:['设置支付密码','text-align:left'],
		type: 1,
		area: ['450px', '240px'],
		content: $('.j-paypwd-box'),
		btn: ['设置支付密码，并支付订单', '关闭'],
		yes: function(index, layero){
			var newPass = $.trim($("#payPwd").val());
			var reNewPass = $.trim($("#reNewPass").val());
			if(newPass==""){
				WST.msg("请输入支付密码！");
				return false;
			}
			if(reNewPass==""){
				WST.msg("请输入确认支付密码！");
				return false;
			}
			if(newPass!=reNewPass){
				WST.msg("密码不一致！");
				return false;
			}
		    if(window.conf.IS_CRYPT=='1'){
		        var public_key=$('#token').val();
		        var exponent="10001";
		   	    var rsa = new RSAKey();
		        rsa.setPublic(public_key, exponent);
		        newPass = rsa.encrypt(newPass);
		        reNewPass = rsa.encrypt(reNewPass);
		    }
			var load = WST.load({msg:'正在提交支付密码，请稍后...'});
			$.post(WST.U('home/users/payPassEdit'),{newPass:newPass,reNewPass:reNewPass},function(data,textStatus){
				layer.close(load);   
				var json = WST.toJson(data);
			    if(json.status==1){
			    	WST.msg(json.msg, {icon: 1,time:1500},function(){
			    		layer.close(layerbox);
		                payByWallet();
			    	});
			    }else{
			    	WST.msg(json.msg,{icon:2,time:1500});
			    }
			});
			
	    	return false;
	  	},
	  	btn2: function(index, layero){}
	});
}

var invoicebox;
function changeInvoice(t,str,obj){
	var param = {};
	param.isInvoice = $('#isInvoice').val();
	param.invoiceId = $('#invoiceId').val();
	var loading = WST.load({msg:'正在请求数据，请稍后...'});
	$.post(WST.U('home/invoices/index'),param,function(data){
		layer.close(loading);
		// layer弹出层
		invoicebox =	layer.open({
			title:'发票信息',
			type: 1,
			area: ['628px', '420px'], //宽高
			content: data,
			success :function(){
				if(param.invoiceId>0){
				    $('.inv_codebox').show();
				    $('#invoice_num').val($('#invoiceCode_'+param.invoiceId).val());
				 }
			},
		});
	});
}
function layerclose(){
  layer.close(invoicebox);
}