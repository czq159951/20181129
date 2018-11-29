<?php /*a:1:{s:47:"addons/distribut/view/home/index/shop_home.html";i:1536627275;}*/ ?>

<script type="text/javascript">
	bShare.addEntry({  
	    url: "<?php echo url('home/shops/home',array('shopId'=>$addonParams['shop']['shopId'],'shareUserId'=>base64_encode(session('WST_USER.userId'))),true,true); ?>",
		title:"<?php echo $addonParams['shop']['shopName']; ?>",
		summary:"<?php echo $addonConfig['shopShareTitle']; ?>", 
	    pic: '<?php echo WSTDomain(); ?>/<?php echo $addonParams["shop"]["shopImg"]; ?>'
	}); 
</script>

