var layer = layui.layer;
var laytpl, form,laypage;
$(function(){
	form = layui.form;
  	form.render();
  	form.on('switch(mailOpen)', function(data){
	  	if(this.checked){
	  		WST.showHide(1,'.mailOpenTr')
	  	}else{
	  		WST.showHide(0,'.mailOpenTr')
	  	}
	});
	form.on('switch(seoMallSwitch)', function(data){
	  	if(this.checked){
	  		WST.showHide(0,'#close');
	  	}else{
	  		WST.showHide(1,'#close');
	  	}
	});
	form.on('switch(signScoreSwitch)', function(data){
	  	if(this.checked){
	  		WST.showHide(1,'#signScore,#signScores')
	  	}else{
	  		WST.showHide(0,'#signScore,#signScores')
	  	}
	});
	form.on('switch(isOpenScorePay)', function(data){
	  	if(this.checked){
	  		WST.showHide(1,'#scoreToMoneyTr')
	  	}else{
	  		WST.showHide(0,'#scoreToMoneyTr')
	  	}
	});
	form.on('switch(isOrderScore)', function(data){
	  	if(this.checked){
	  		WST.showHide(1,'#moneyToScoreTr')
	  	}else{
	  		WST.showHide(0,'#moneyToScoreTr')
	  	}
	});
	form.on('switch(isAppraisesScore)', function(data){
	  	if(this.checked){
	  		WST.showHide(1,'#appraisesScoreTr')
	  	}else{
	  		WST.showHide(0,'#appraisesScoreTr')
	  	}
	});
	form.on('switch(isAppraisesScore)', function(data){
	  	if(this.checked){
	  		WST.showHide(1,'#appraisesScoreTr')
	  	}else{
	  		WST.showHide(0,'#appraisesScoreTr')
	  	}
	});
	form.on('switch(isCryptPwd)', function(data){
	  	if(this.checked){
	  		WST.showHide(1,'.pwdCryptKeyTr')
	  	}else{
	  		WST.showHide(0,'.pwdCryptKeyTr')
	  	}
	});
    var element = layui.element;
	element.on('tab(msgTab)', function(data){
	   if(data.index==4)initUploads();
	});
});
var isInitUpload = false;
function initUploads(){
	if(isInitUpload)return;
	var uploads = ['watermarkFile','mallLogo','shopLogo','shopAdtop','userLogo','goodsLogo'],key;
	for(var i=0;i<uploads.length;i++){
		key = uploads[i];
		WST.upload({
			  k:key,
		  	  pick:'#'+key+"Picker",
		  	  formData: {dir:'sysconfigs'},
		  	  accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
		  	  callback:function(f){
		  		  var json = WST.toAdminJson(f);
		  		  if(json.status==1){
		  			 $('#'+this.k+'Msg').empty().hide();
		  			 $('#'+this.k+'Prevw').attr('src',WST.conf.ROOT+'/'+json.savePath+json.name);
		  			 $('#'+this.k).val(json.savePath+json.name);
		  		  }
			  },
			  progress:function(rate){
				  $('#'+this.k+'Msg').show().html('已上传'+rate+"%");
			  }
		    });
	}
	isInitUpload = true;
}
function checkTip(ids,obj){
   var ids = ids.split(',');
   if(!$('#'+ids[0])[0].checked && !$('#'+ids[1])[0].checked)$('.'+obj).each(function(){
   	   $(this).attr('checked',false);
   })
}
function edit(){
	if(!WST.GRANT.SCPZ_02)return;
	var params = WST.getParams('.ipt');
	if(params.mailOpen==1){
		var fieldObj = ['mailSmtp','mailPort','mailAddress','mailUserName','mailPassword','mailSendTitle'];
		var fieldTip = ['请填写SMTP服务器','SMTP端口','SMTP发件人邮箱','SMTP登录账号','SMTP登录密码','发件人名称'];
		for(var i=0;i<fieldObj.length;i++){
			if(params[fieldObj[i]]==''){
				WST.msg(fieldTip[i],{icon:1});
				return;
			}
		}
	}
	var signScore = '';
	for(var i=0;i<30;i++){
		if(i>0 && params.signScore0!=0){
			if(!params['signScore'+i] || params['signScore'+i]==0){
				params['signScore'+i] = params['signScore'+(i-1)];
			}
		}
		if(!params.signScore0 || params.signScore0==0){
			signScore += '0,';
		}else{
			if(!params['signScore'+i])params['signScore'+i] = 0;
			signScore +=  params['signScore'+i] + ',';
		}
	}
	params.signScore = signScore;
	var strTitle = ['用户下单','支付订单','取消订单','拒收订单','申请退款','订单投诉','用户提现'];
	var strTip = ['SubmitOrderTip','PayOrderTip','CancelOrderTip','RejectOrderTip',
	               'RefundOrderTip','ComplaintOrderTip','CashDrawsTip'];
	var strUser = ['submitOrderTipUsers','payOrderTipUsers','cancelOrderTipUsers','rejectOrderTipUsers',
	               'refundOrderTipUsers','complaintOrderTipUsers','cashDrawsTipUsers'];
	var ids = [],wxId = '',smsId;
	for(var i=0;i<strUser.length;i++){
        ids = [];
		$('.'+strUser[i]).each(function(){
           if($(this)[0].checked)ids.push($(this).val());
		});
		wxId = 'wx'+strTip[i];
		smsId = 'sms'+strTip[i];
		params[wxId] = $('#'+wxId)[0].checked?1:0;
		params[smsId] = $('#'+smsId)[0].checked?1:0;
		params[strUser[i]] = ids.join(',');
		if(params[wxId]==0 && params[smsId]==0 && ids.length>0){
			WST.msg('请选择'+strTitle[i]+'提醒方式',{icon:1});
			return;
		}
		if((params[wxId]==1 || params[smsId]==1) && ids.length==0){
			WST.msg('请选择'+strTitle[i]+'提醒人',{icon:1});
			return;
		}
	}
	var loading = WST.msg('正在保存数据，请稍后...', {icon: 16,time:60000});
    $.post(WST.U('admin/sysconfigs/edit'),params,function(data,textStatus){
          layer.close(loading);
          var json = WST.toAdminJson(data);
          if(json.status==1){
        	  WST.msg(json.msg,{icon:1});
          }
   });
}


$(function(){
	$('#watermarkColor').colpick({
	layout:'hex',
	submit:1,
	colorScheme:'dark',
	onChange:function(hsb,hex,rgb,el,bySetColor) {
		$(el).css('border-color','#'+hex);
	},
	onSubmit:function(hsb,hex,rgb,el,bySetColor){
		if(!bySetColor) $(el).val('#'+hex);
		$(el).colpickHide();
	}
	}).keyup(function(){
		$(this).colpickSetColor(this.value);
		$(this).colpickHide();
	});

});