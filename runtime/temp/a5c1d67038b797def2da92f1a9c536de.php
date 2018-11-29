<?php /*a:1:{s:39:"addons/distribut/view/mobile/share.html";i:1536627276;}*/ ?>
<div class="wst-cart-box" id="frame-shareTips">
	<div class="title" style="min-height: 30px;">
     	<i class="ui-icon-close-page" onclick="javascript:shareTipsHide();"></i>
		
		<div class="wst-clear"></div>
	</div>
	<div style="padding:10px 20px;">
		<div>
			<div style="font-weight:bold;margin-bottom:5px">分佣比例说明</div>
			<div>购买者分成：<?php echo $addonConfig['buyerRate']; ?>%</div>
			<div>第二级分成：<?php echo $addonConfig['secondRate']; ?>%</div>
			<div>第三级分成：<?php echo $addonConfig['thirdRate']; ?>%</div>
		</div>
	</div>
</div>
<script>
	var config = {
		url:'<?php echo $shareUrl; ?>',
		title:'<?php echo $shareTitle; ?>',
		desc:'<?php echo $shareSummary; ?>',
		img:'<?php echo $shareImg; ?>'
	};
    $(function(){
    	if(bShare){
	    	bShare.addEntry({
			    url: "<?php echo $shareUrl; ?>",
				title:"<?php echo $shareTitle; ?>",
				summary:"<?php echo $shareSummary; ?>",
			    pic: "<?php echo $shareImg; ?>"
			});
	    }
    });
    
	
	//弹框
	function shareTips(){
		jQuery('#cover').attr("onclick","javascript:shareHide();").show();
		jQuery('#frame-shareTips').animate({"bottom": 0}, 500);
	}
	function shareTipsHide(){
		var cartHeight = parseInt($("#frame-shareTips").css('height'))+52+'px';
		jQuery('#frame-shareTips').animate({'bottom': '-'+cartHeight}, 500);
		jQuery('#cover').hide();
	}
</script>

