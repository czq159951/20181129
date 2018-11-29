$(function(){
	WST.slides('.wst-slide');
	$('#index-tab').TabPanel({tab:0,callback:function(no){}});
	$('.goodsImg2').lazyload({ effect: "fadeIn",failurelimit : 10,skip_invisible : false,threshold: 100,placeholder:WST.conf.ROOT+'/'+WST.conf.GOODS_LOGO});//商品默认图片
	
});


/*签到*/
function inSign(){
	$("#j-sign").attr('disabled', 'disabled');
	$.post(WST.U('home/userscores/signScore'),{},function(data){
		var json = WST.toJson(data);
		if(json.status==1){
			$("#j-sign .plus").html('+'+json.data.score);
			$("#currentScore").html(json.data.totalScore);
			$("#j-sign").addClass('active');
			setInterval(function(){
				$("#j-sign").addClass('actives').html('已签到');
			},600);
			WST.msg(json.msg, {icon: 1});
		}else{
			WST.msg(json.msg, {icon: 5});
			$("#j-sign").removeAttr('disabled');
		}
	});
}

