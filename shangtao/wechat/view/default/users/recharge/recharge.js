jQuery.noConflict();
function inEffect(obj,n){
	$("ul div").removeClass('j-selected');
	$(obj).addClass('j-selected');
}
function changeSelected(n,index,obj){
	$('#'+index).val(n);
	if(n==0){
		$(".j-charge-other").hide();
		$(".j-charge-money").show();
		var needPay =  $("#needPay").val();
		
	}else{
		$(".j-charge-other").show();
		$(".j-charge-money").hide();
		var needPay = $("#needPay_"+n).attr("sum");
	}
	rechargeMoney(needPay);
	inEffect(obj,2);
}
function rechargeMoney(n){
	$("#rechargeMoney").html(n);
}

function toPay(){
	var params = {};
		params.payObj = "recharge";
		params.targetType = 0;
		params.needPay = $.trim($("#needPay").val());
		params.payCode = $("input[name='payCode']:checked").val();
		params.itemId = $.trim($("#itemId").val());
	if(params.itemId==0 && params.needPay<=0){
		WST.msg('请输入充值金额', 'info');
		return;
	}
	if(params.payCode==""){
		WST.msg('请先选择支付方式','info');
		return;
	}
	location.href = WST.U('wechat/weixinpays/toPay',params);
}

$(function(){
	jQuery(".wst-frame2:first").click();
});