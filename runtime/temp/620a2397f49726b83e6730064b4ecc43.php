<?php /*a:4:{s:50:"/home/mart/shangtao/mobile/view/default/index.html";i:1534762866;s:49:"/home/mart/shangtao/mobile/view/default/base.html";i:1534762856;s:51:"/home/mart/shangtao/mobile/view/default/footer.html";i:1534762864;s:57:"/home/mart/shangtao/mobile/view/default/goods_search.html";i:1534762902;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title>首页 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="__MOBILE__/css/swiper.min.css">
<link rel="stylesheet"  href="__MOBILE__/css/index.css?v=<?php echo $v; ?>">

<script type='text/javascript' src='__MOBILE__/frozenui/js/zepto.min.js'></script>
<script type='text/javascript' src='__MOBILE__/frozenui/js/frozen.js'></script>
<script type='text/javascript' src='__MOBILE__/js/laytpl/laytpl.js?v=<?php echo $v; ?>'></script>
<script src="__MOBILE__/js/echo.min.js"></script>
<script type='text/javascript' src='__MOBILE__/js/common.js?v=<?php echo $v; ?>'></script>
<script>
window.conf = {"ROOT":"","MOBILE":"__MOBILE__","APP":"/index.php","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","SMS_VERFY":"<?php echo WSTConf('CONF.smsVerfy'); ?>","SMS_OPEN":"<?php echo WSTConf('CONF.smsOpen'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>","IS_LOGIN":"<?php if((int)session('WST_USER.userId')>0): ?>1<?php else: ?>0<?php endif; ?>","ROUTES":'<?php echo WSTRoute(); ?>',"IS_CRYPTPWD":"<?php echo WSTConf('CONF.isCryptPwd'); ?>",HTTP:"<?php echo WSTProtocol(); ?>"}
</script>
</head>
<body ontouchstart="">

    <header class="ui-header ui-header-positive wst-in-header" id="j-header">
    </header>
    <div class="wst-in-search">
    	<div class="classify"><a href="<?php echo url('mobile/goodscats/index'); ?>"><i></i></a></div>
    	<div class="searchs" id="j-searchs">
		    <i class="ui-icon-search" onclick="javascript:WST.searchPage('goods',1);"></i>
		    <form action＝"" class="input-form" onclick="javascript:WST.searchPage('goods',1);">
		    <input type="search" placeholder="按关键字搜索商品" onsearch="WST.search(0)" autocomplete="off" disabled="disabled">
			</form>
			<div class="wst-clear"></div>
		</div>
		<div class="user"><a href="<?php echo url('mobile/messages/index'); ?>"><?php if(session('WST_USER.userId') >0): ?><i><?php if(($news['message']['num']>0)): ?><span class="number"><?php echo $news['message']['num']; ?></span><?php endif; ?></i><?php else: ?>登录<?php endif; ?></a></div>
	</div>


	        
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


<input type="hidden" name="" value="-1" id="currPage" autocomplete="off">
<section class="ui-container">
        <div class="ui-slider" style="padding-top:50%;">
		    <ul class="ui-slider-content" style="<?php if(($ads['count']>0)): ?><?php echo $ads['width']; endif; ?>">
		    	<?php $wstTagAds =  model("common/Tags")->listAds("mo-ads-index",99,86400); foreach($wstTagAds as $key=>$vo){?>
		        <li class="advert1"><span><a href="<?php echo $vo['adURL']; ?>"><img style="width:100%; height:100%; display:block;" src="/<?php echo WSTImg($vo['adFile'],2); ?>"></a></span></li>
		        <?php } ?>
		    </ul>
		</div>
		<div class="ui-row wst-in-choose">
		    <?php $_result=WSTMobileBtns(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$btn): $mod = ($i % 2 );++$i;?>
		    <div class="ui-col ui-col-20">
		    <a href="<?php echo url($btn['btnUrl']); ?>">
		    <p><img width='56' src="/<?php echo $btn['btnImg']; ?>" style='margin-top:7px;'/></p>
		    <span><?php echo $btn['btnName']; ?></span>
		    </a></div>
		    <?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
		<div class="wst-in-activity">
			<?php $wstTagAds =  model("common/Tags")->listAds("mo-index-long",4,86400); foreach($wstTagAds as $key=>$vo){?><a class="advert4" href="<?php echo $vo['adURL']; ?>"><div class="img"><img src="/<?php echo WSTImg($vo['adFile'],2); ?>"/></div></a><?php } ?>
			<div class="wst-in-news">
			<span class="new">商城&nbsp;<p>快讯</p></span>
			<div class="article">
			<div class="swiper-container swiper-container1">
	          <div class="swiper-wrapper">
	          		<?php $wstTagArticle =  model("common/Tags")->listArticle("new",6,86400); foreach($wstTagArticle as $key=>$vo){?>
	                <div class="swiper-slide" style="width:100%;">
	                	<a class="words" href="<?php echo url('mobile/news/view',['articleId'=>$vo['articleId']]); ?>"><p class="ui-nowrap-flex"><?php echo $vo['articleTitle']; ?></p></a>
	                </div>
	                <?php } ?>
	          </div>
	        </div>
			</div>
			<span class="more" onclick="location.href='<?php echo url('mobile/news/view'); ?>'">更多</span>
			<div class="wst-clear"></div>
			</div>
		</div>
		<div class="wst-in-adst">
			<?php $wstTagAds =  model("common/Tags")->listAds("mo-index-left",1,86400); foreach($wstTagAds as $key=>$vo){?>
			<a class="advert2" href="<?php echo $vo['adURL']; ?>"><img src="/<?php echo WSTImg($vo['adFile'],2); ?>" style="height:2rem;"/></a>
			<?php } $wstTagAds =  model("common/Tags")->listAds("mo-index-right",2,86400); foreach($wstTagAds as $key=>$vo){?>
			<a class="advert2" href="<?php echo $vo['adURL']; ?>"><img src="/<?php echo WSTImg($vo['adFile'],2); ?>" style="height:0.995rem;"/></a>
			<?php } ?>
			<div class="wst-clear"></div>
		</div>
		<div class="wst-in-adsb">
		<div class="swiper-container swiper-container2">
          <div class="swiper-wrapper">
          	<?php $wstTagAds =  model("common/Tags")->listAds("mo-index-small",10,86400); foreach($wstTagAds as $key=>$vo){?>
                <div class="swiper-slide" style="width:33.333333%;">
                  <div class="goodsinfo-container">
                    <a class="advert3" href="<?php echo $vo['adURL']; ?>"><img src="/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="/<?php echo WSTImg($vo['adFile'],2); ?>"></a>
                  </div>
                </div>
            <?php } ?>
          </div>
        </div>
        </div>
		<div id="goods-list"></div>
</section>
<script id="list" type="text/html">
<div class="wst-in-title colour{{ d.currPage }}" onclick="javascript:getGoodsList({{ d.catId }});">
	<div class="line"></div><div class="name"><p><span><i class="icon"></i>{{ d.catName }}</span></p></div>
</div>
	{{# if(d.ads && d.ads.length>0){ }}
		<div class="wst-in-adscats"><a href="{{ d.ads[0].adURL }}"><img src="/{{ d.ads[0].adFile }}"/></a></div>
	{{# } }}
	{{# if(d.goods.length>0){ }}
		{{# for(var i=0; i<d.goods.length; i++){ }}
			<div class="wst-in-goods {{# if((i)%2==0){ }}left{{# }else{ }}right{{# } }}">
				<div class="img j-imgAdapt"><a href="javascript:void(0);" onclick="javascript:WST.intoGoods({{ d.goods[i].goodsId }});"><img src="/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="/{{ d.goods[i].goodsImg }}" title="{{ d.goods[i].goodsName }}"/></a></div>
				<div class="name ui-nowrap-multi" onclick="javascript:WST.intoGoods({{ d.goods[i].goodsId }});">{{ d.goods[i].goodsName }}</div>
				<div class="info"><span class="price">¥ <span>{{ d.goods[i].shopPrice }}</span></span></div>
				<div class="info2"><span class="price">好评率{{ d.goods[i].praiseRate }}</span><span class="deal">成交数:{{ d.goods[i].saleNum }}</span></div>
			</div>
		{{# } }}
	{{# } }}
<div class="wst-clear"></div>
</script>


    <div class="wst-co-search" id="wst-goods-search">
    <header class="ui-header ui-header-positive wst-se-header2" style="border-bottom: 1px solid #f6f6f8;">
		<i class="ui-icon-return" onclick="javascript:WST.searchPage('goods',0);"></i>
		<div class="wst-se-search">
		    <i class="ui-icon-search" onclick="javascript:WST.search(0);"></i>
		    <form action＝"" class="input-form">
			<input type="search" value="" placeholder="按关键字搜索商品" onsearch="WST.search(0)" autocomplete="off" id="wst-search">
			</form>
		</div>
		<a class="btn" href="javascript:void(0);" onclick="javascript:WST.search(0);">搜索</a>
	</header>
	<div class="list">
		<p class="search"><i></i>热门搜索</p>
		<?php $hotWordsSearch = WSTConf("CONF.hotWordsSearch");
		if($hotWordsSearch!='')$hotWordsSearch = explode(',',$hotWordsSearch); ?>
		<div class="term">
			<?php if(is_array($hotWordsSearch) || $hotWordsSearch instanceof \think\Collection || $hotWordsSearch instanceof \think\Paginator): $i = 0; $__LIST__ = $hotWordsSearch;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$hot): $mod = ($i % 2 );++$i;?>
			<a href="<?php echo url('mobile/goods/lists',['keyword'=>$hot]); ?>"><?php echo $hot; ?></a>
			<?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
	</div>
	</div>
	<script type='text/javascript' src='__MOBILE__/js/jquery.min.js'></script>
	<script>
	jQuery.noConflict();
	document.addEventListener('touchmove', function(event) {
	    //阻止背景页面滚动,
	    if(!jQuery("#wst-goods-search").is(":hidden")){
	        event.preventDefault();
	    }
	})
	</script>


<script type='text/javascript' src='__MOBILE__/js/swiper.jquery.min.js'></script>
<script type='text/javascript' src='__MOBILE__/js/index.js?v=<?php echo $v; ?>'></script>

</body>
</html>