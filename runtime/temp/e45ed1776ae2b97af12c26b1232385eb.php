<?php /*a:1:{s:50:"addons/distribut/view/home/index/goods_detail.html";i:1536627275;}*/ ?>
<div style="position:absolute;left:300px;top:15px;font-size:13px;color:red;">
分享可获得佣金
<img src="/addons/distribut/view/images/icon_tstb.png" onclick="showTips()" class="showTips" style="height:18px;vertical-align:middle;position:relative;top:-3px;cursor:pointer;"/>
</div>
<script type="text/javascript">         
bShare.addEntry({  
    url: "<?php echo url('home/goods/detail',array('goodsId'=>$addonParams['goods']['goodsId'],'shareUserId'=>base64_encode(session('WST_USER.userId'))),true,true); ?>",
	title:"<?php echo $addonParams['goods']['goodsName']; ?>",
	summary:"<?php echo $addonConfig['goodsShareTitle']; ?>",  
    pic: '<?php echo WSTDomain(); ?>/<?php echo $addonParams["goods"]["goodsImg"]; ?>'
});  
            

function showTips(){
	var tip = "<div>分佣比例说明：<div>购买者分成：<?php echo $addonConfig['buyerRate']; ?>%</div><div>第二级分成：<?php echo $addonConfig['secondRate']; ?>%</div><div>第三级分成：<?php echo $addonConfig['thirdRate']; ?>%</div></div>"
	layer.tips(tip, '.showTips', {
	  tips: [2, '#FF4400'],
	  time: 4000
	});
}

</script>

