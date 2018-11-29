jQuery.noConflict();
$(document).ready(function(){
  WST.initFooter('user');
  // 弹出层
  var w = WST.pageWidth();
  $("#frame").css('top',0);
  $("#frame").css('right',-w);
});
//资金流水列表
function getRecordList(){
	  $('#Load').show();
	    loading = true;
	    var param = {};
	    param.type = $('#type').val() || -1;
	    param.pagesize = 10;
	    param.page = Number( $('#currPage').val() ) + 1;
	    $.post(WST.U('mobile/logMoneys/pageQuery'), param, function(data){
	        var json = WST.toJson(data.data);
	        var html = '';
	        if(json && json.data && json.data.length>0){
	          var gettpl = document.getElementById('scoreList').innerHTML;
	          laytpl(gettpl).render(json.data, function(html){
	            $('#score-list').append(html);
	          });

	          $('#currPage').val(json.current_page);
	          $('#totalPage').val(json.last_page);
	        }else{
	           html += '<div class="wst-prompt-icon"><img src="'+ window.conf.MOBILE +'/img/nothing-relevant.png"></div>';
	  	       html += '<div class="wst-prompt-info">';
	  	       html += '<p>暂无相关信息</p>';
	  	       html += '</div>';
	          $('#score-list').html(html);
	        }
	        loading = false;
	        $('#Load').hide();
	        echo.init();//图片懒加载
	    });
	}
// 验证支付密码资金
function check(){
  var isSetPayPwd = $('#isSet').val();
  if(isSetPayPwd==0){
  		$('#wst-event2').html('去设置');
  		WST.dialog('您未设置支付密码','location.href="'+WST.U('mobile/users/editPayPass')+'"');
		return;
	}else{
		showPayBox();
	}
  	
}
// 支付密码对话框
function showPayBox(){
    $("#wst-event3").attr("onclick","javascript:checkSecret()");
    $("#payPwdBox").dialog("show");
}
function checkSecret(){
	var payPwd = $.trim($('#payPwd').val());
	if(payPwd==''){
		WST.msg('请输入支付密码','info');
		return;
	}
    if(window.conf.IS_CRYPTPWD==1){
        var public_key=$('#key').val();
        var exponent="10001";
   	    var rsa = new RSAKey();
        rsa.setPublic(public_key, exponent);
        var payPwd = rsa.encrypt(payPwd);
    }
	$.post(WST.U('mobile/logmoneys/checkPayPwd'),{payPwd:payPwd},function(data){
		var json = WST.toJson(data);
		if(json.status==1){
			$("#payPwdBox").dialog("hide");
			location.href=WST.U('mobile/cashconfigs/index');
		}else{
			WST.msg(json.msg);
		}
	})
}
//资金流水
function toRecord(){
	location.href = WST.U('mobile/logmoneys/record');
}
/********************  提现层 *************************/
function getCash(){
	$('#money').val('');
	$('#cashPayPwd').val('');
	$.post(WST.U('mobile/cashconfigs/pageQuery'),{},function(data){
		var json = WST.toJson(data);
		var html = '<option value="">请选择</option>';
		if(json.status==1){
			$(json.data.data).each(function(k,v){
				html +='<option value='+v.id+'>'+v.accUser+'|'+v.accNo+'</option>';
			});
			$('#accId').html(html);
			// 判断是否禁用按钮
			if($('#userMoney').attr('money')<$('#userMoney').attr('cash'))$('#submit').attr('disabled','disabled');
			dataShow();
		}else{
			WST.msg(json.msg,'info');
		}
	})
}
// 申请提现
function drawMoney(){
	var accId = $('#accId').val();
	var money = $('#money').val();
	var payPwd = $('#cashPayPwd').val();

	if(accId==''){
		WST.msg('请选择提现账号','info');
		return;
	}
	if(money==''){
		WST.msg('请输入提现金额','info');
		return
	}
	if(payPwd==''){
		WST.msg('请输入支付密码','info');
		return
	}
    if(window.conf.IS_CRYPTPWD==1){
        var public_key=$('#key').val();
        var exponent="10001";
   	    var rsa = new RSAKey();
        rsa.setPublic(public_key, exponent);
        var payPwd = rsa.encrypt(payPwd);
    }
	var param = {};
	param.accId = accId;
	param.money = money;
	param.payPwd = payPwd;
	$.post(WST.U('mobile/cashdraws/drawMoney'),param,function(data){
		var json = WST.toJson(data);
		if(json.status==1){
			WST.msg('提现申请已提交','success');
			setTimeout(function(){
				location.reload();
			},1000);
		}else{
			WST.msg(json.msg,'info');
		}
	})
}
//弹框
function dataShow(){
    jQuery('#cover').attr("onclick","javascript:dataHide();").show();
    jQuery('#frame').animate({"right": 0}, 500);
}
function dataHide(){
    var dataHeight = $("#frame").css('height');
    var dataWidth = $("#frame").css('width');
    jQuery('#frame').animate({'right': '-'+dataWidth}, 500);
    jQuery('#cover').hide();
}