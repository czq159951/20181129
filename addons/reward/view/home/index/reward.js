function initGoodsDetailReward(){
	$('#j-promotion').show();
	$('.j-reward-tip').hover(function(e){
		$('.j-reward-detail').show();
	},function(){
		$('.j-reward-detail').hide();
	});
	$('.j-reward-detail').hover(function(e){
		$('.j-reward-detail').show();
	},function(){
		$('.j-reward-detail').hide();
	});
}