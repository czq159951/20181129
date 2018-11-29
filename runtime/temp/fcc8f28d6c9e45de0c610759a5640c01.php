<?php /*a:4:{s:59:"/home/mart/shangtao/mobile/view/default/goods_category.html";i:1534762820;s:49:"/home/mart/shangtao/mobile/view/default/base.html";i:1534762856;s:51:"/home/mart/shangtao/mobile/view/default/footer.html";i:1534762864;s:57:"/home/mart/shangtao/mobile/view/default/goods_search.html";i:1534762902;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title>商品分类 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="__MOBILE__/css/swiper.min.css">
<link rel="stylesheet" href="__MOBILE__/css/goods_category.css?v=<?php echo $v; ?>">

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

	<header class="ui-header ui-header-positive wst-se-header2" style="border-bottom: 1px solid #f6f6f8;">
		<i class="ui-icon-return" onclick="history.back()"></i>
		<div class="wst-se-search" onclick="javascript:WST.searchPage('goods',1);">
		    <i class="ui-icon-search" onclick="javascript:WST.searchPage('goods',1);"></i>
		    <form action＝"" class="input-form">
			<input type="search" value="" placeholder="按关键字搜索商品" onsearch="WST.search(0)" autocomplete="off" disabled="disabled">
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


	<section class="ui-container">
		<div class="ui-scrollerl" id="ui-scrollerl">
		    <ul>
		    	<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$go): $mod = ($k % 2 );++$k;?>
		        <li id="goodscate" class="wst-goodscate <?php if(($k==1)): ?>wst-goodscate_selected<?php endif; ?>" onclick="javascript:showRight(this,<?php echo $k-1; ?>);"><?php echo str_replace('、', '<br/>', $go['catName']); ?></li>
		        <?php endforeach; endif; else: echo "" ;endif; ?>
		    </ul>
		</div>
		<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$go): $mod = ($k % 2 );++$k;?>
		<div class="wst-scrollerr goodscate1" <?php if(($k!=1)): ?>style="display:none;"<?php endif; ?>>
			<ul class="wst-ca-ads"><li>
			<div class="swiper-container category-ads<?php echo $k; ?>" key="<?php echo $k; ?>">
			  <?php $number=0 ?>
	          <div class="swiper-wrapper">
	          		<?php $wstTagAds =  model("common/Tags")->listAds("mo-category-$k",99,86400); foreach($wstTagAds as $key=>$ca){$number = $number + 1; ?>
	                <div class="swiper-slide" style="width:100%;">
	                	<a href="<?php echo $ca['adURL']; ?>" class="img"><img style="width:100%; height:100%; display:block;" src="/<?php echo WSTImg($ca['adFile'],2); ?>"></a>
	                </div>
	                <?php } ?>
	          </div>
	          <?php if(($number>1)): ?><div class="swiper-pagination pagination-ads<?php echo $k; ?>"></div><?php endif; ?>
	        </div>
			</li></ul>
			<?php if((isset($go['childList']))): if(is_array($go['childList']) || $go['childList'] instanceof \think\Collection || $go['childList'] instanceof \think\Paginator): $i = 0; $__LIST__ = $go['childList'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$go1): $mod = ($i % 2 );++$i;?>
		    <ul>
		        <div class="wst-gc-ads">
		     		<a href="javascript:void(0);" onclick="javascript:getGoodsList(<?php echo $go1['catId']; ?>);"><div class="title"><?php echo $go1['catName']; ?></div></a>
		     	</div>
		        <li>
			        <div class="wst-goodscat">
			        	<?php if(is_array($go1['childList']) || $go1['childList'] instanceof \think\Collection || $go1['childList'] instanceof \think\Paginator): $i = 0; $__LIST__ = $go1['childList'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$go2): $mod = ($i % 2 );++$i;?>
			        	<span>
			        		<a href="javascript:void(0);" onclick="javascript:getGoodsList(<?php echo $go2['catId']; ?>);" >
			        			<img src="/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="/<?php echo $go2['catImg']; ?>" class="goods-cat-img" title="<?php echo $go2['catName']; ?>"/>
			        			<p class="ui-nowrap-flex"><?php echo $go2['catName']; ?></p>
			        		</a>
			        	</span>
			        	<?php endforeach; endif; else: echo "" ;endif; ?>
			        </div>
			        <div class="wst-clear"></div>
		        </li>
		        <div class="wst-clear"></div>
		    </ul>
		    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
		</div>
		<?php endforeach; endif; else: echo "" ;endif; ?>
		<div class="wst-clear"></div>
	</section>


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
<script type='text/javascript' src='__MOBILE__/js/jquery.min.js'></script>
<script type='text/javascript' src='__MOBILE__/js/goods_category.js?v=<?php echo $v; ?>'></script>

</body>
</html>