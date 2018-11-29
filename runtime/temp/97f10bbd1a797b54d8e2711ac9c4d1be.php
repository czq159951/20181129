<?php /*a:3:{s:50:"addons/distribut/view/mobile/index/goods_list.html";i:1536627276;s:65:"/www/beidou/mart/zsbd_mart/shangtao/mobile/view/default/base.html";i:1536569719;s:67:"/www/beidou/mart/zsbd_mart/shangtao/mobile/view/default/footer.html";i:1536569719;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title>商品列表 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="__MOBILE__/css/swiper.min.css">
<link rel="stylesheet"  href="/addons/distribut/view/mobile/index/goods_list.css?v=<?php echo $v; ?>">

<script type='text/javascript' src='__MOBILE__/frozenui/js/zepto.min.js'></script>
<script type='text/javascript' src='__MOBILE__/frozenui/js/frozen.js'></script>
<script type='text/javascript' src='__MOBILE__/js/laytpl/laytpl.js?v=<?php echo $v; ?>'></script>
<script src="__MOBILE__/js/echo.min.js"></script>
<script type='text/javascript' src='__MOBILE__/js/common.js?v=<?php echo $v; ?>'></script>
<script>
window.conf = {"ROOT":"","MOBILE":"__MOBILE__","APP":"","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","SMS_VERFY":"<?php echo WSTConf('CONF.smsVerfy'); ?>","SMS_OPEN":"<?php echo WSTConf('CONF.smsOpen'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>","IS_LOGIN":"<?php if((int)session('WST_USER.userId')>0): ?>1<?php else: ?>0<?php endif; ?>","ROUTES":'<?php echo WSTRoute(); ?>',"IS_CRYPTPWD":"<?php echo WSTConf('CONF.isCryptPwd'); ?>",HTTP:"<?php echo WSTProtocol(); ?>"}
</script>
</head>
<body ontouchstart="">

    <header class="ui-header ui-header-positive wst-se-header2" style="border-bottom: 1px solid #f6f6f8;">
		<a href="<?php echo url('mobile/index/index'); ?>"><i class="ui-icon-return"></i></a>
		<div class="wst-se-search">
		    <i class="ui-icon-search" onclick="javascript:searchGoods();"></i>
		    <form action＝"" class="input-form">
			<input type="search" value="<?php echo $keyword; ?>" placeholder="按关键字搜索商品" onsearch="searchGoods(0)" autocomplete="off" id="wst-search">
			</form>
		</div>
	</header>


        
        <div class="ui-loading-wrap wst-Load" id="Load">
		    <i class="ui-loading"></i>
		</div>
		
		<div class="ui-loading-block" id="Loadl">
		    <div class="ui-loading-cnt">
		        <i class="ui-loading-bright"></i>
		        <p id="j-Loadl">正在加载中...</p>
		    </div>
		</div>
        <footer class="ui-footer wst-footer-btns" style="height:43px; border-top: 1px solid #e8e8e8;" id="footer">
	        <div class="wst-toTop" id="toTop">
			  <i class="wst-toTopimg"></i>
			</div>
			<?php $cartNum = WSTCartNum(); ?>
            <div class="ui-row-flex wst-menus">
			    <div class="ui-col ui-col"><a href="<?php echo url('mobile/index/index'); ?>"><p id="home"></p></a></div>
			    <div class="ui-col ui-col"><a href="<?php echo url('mobile/goodscats/index'); ?>"><p id="category"></p></a></div>
			    <?php echo hook('mobileDocumentBottomNav'); ?>
			    <div class="ui-col ui-col carsNum"><a href="<?php echo url('mobile/carts/index'); ?>"><p id="cart">
                </p></a><?php if(($cartNum>0)): ?><i><?php  echo $cartNum; ?></i><?php endif; ?></div>
                <div class="ui-col ui-col J_followbox"><a href="<?php echo url('mobile/favorites/goods'); ?>"><p id="follow"></p></a></div>
			    <div class="ui-col ui-col"><a href="<?php echo url('mobile/users/index'); ?>"><p id="user"></p></a></div>
			</div>
        </footer>
        <?php echo hook('initCronHook'); ?>


     <input type="hidden" name="" value="<?php echo $keyword; ?>" id="keyword" autocomplete="off">
     <input type="hidden" name="" value="" id="orderBy" autocomplete="off">
	 <input type="hidden" name="" value="" id="order" autocomplete="off">
	 <input type="hidden" name="" value="" id="currPage" autocomplete="off">
     <input type="hidden" name="" value="" id="totalPage" autocomplete="off">
     <section class="ui-container">
     	<div class="ui-row-flex wst-shl-head">
		    <div class="ui-col ui-col sorts active" status="down" onclick="javascript:orderCondition(this,0);">
		   		 <p class="pd0">销量</p><i class="down2"></i>
		    </div>
		    <div class="ui-col ui-col sorts" status="down" onclick="javascript:orderCondition(this,1);">
		   		 <p class="pd0">价格</p><i class="down"></i>
		    </div>
		    <div class="ui-col ui-col sorts" status="down" onclick="javascript:orderCondition(this,3);">
		   		 <p class="pd0">人气</p><i class="down"></i>
		    </div>
		    <div class="ui-col ui-col sorts" status="down" onclick="javascript:orderCondition(this,4);">
		   		 <p>上架时间</p><i class="down"></i>
		    </div>
		</div>
		<ul class="ui-tab-content">
	        <li id="goods-list"></li>
	    </ul>
     </section>
<script id="list" type="text/html">
{{# if(d && d.length>0){ }}
{{# for(var i=0; i<d.length; i++){ }}
<div class="wst-in-goods" onclick="javascript:WST.intoGoos({{ d[i].goodsId }});">
<div class="img j-imgAdapt"><a href="javascript:void(0);" onclick="javascript:WST.intoGoods({{ d[i].goodsId }});"><img src="/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="/{{ d[i].goodsImg }}" title="{{ d[i].goodsName }}"/></a></div>
<div class="name ui-nowrap-multi">{{ d[i].goodsName }}</div>
<div class="info"><span class="price">¥{{ d[i].shopPrice }}</span><span class="deal">成交数:{{ d[i].saleNum }}</span></div>
</div>
{{# } }}
<div class="wst-clear"></div>
{{# }else{ }}
<div class="wst-prompt-icon"><img src="__MOBILE__/img/nothing-goods.png"></div>
<div class="wst-prompt-info">
	<p>对不起，没有相关商品。</p>
</div>
{{# } }}
</script>



<script type='text/javascript' src='__MOBILE__/js/swiper.jquery.min.js'></script>
<script type='text/javascript' src='/addons/distribut/view/mobile/index/goods_list.js?v=<?php echo $v; ?>'></script>

</body>
</html>