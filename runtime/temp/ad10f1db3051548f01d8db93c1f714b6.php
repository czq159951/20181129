<?php /*a:3:{s:42:"addons/groupon/view/mobile/index/list.html";i:1536627263;s:65:"/www/beidou/mart/zsbd_mart/shangtao/mobile/view/default/base.html";i:1536569719;s:67:"/www/beidou/mart/zsbd_mart/shangtao/mobile/view/default/footer.html";i:1536569719;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title>团购活动 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="/addons/groupon/view/mobile/index/css/list.css?v=<?php echo $v; ?>">

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
			<input type="search" value="<?php echo $keyword; ?>" placeholder="按关键字搜索商品" onsearch="searchGoods()" autocomplete="off" id="wst-search">
			</form>
		</div>
       	<span class="wst-se-icon" onclick="javascript:dataShow();"></span>
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
	 <input type="hidden" name="" value="<?php echo $goodsCatId; ?>" id="goodsCatId" autocomplete="off">
	 <input type="hidden" name="" value="" id="currPage" autocomplete="off">
     <input type="hidden" name="" value="" id="totalPage" autocomplete="off">
     <section class="ui-container">
		<ul class="ui-tab-content" id="groupon-container" sc="<?php echo date('Y-m-d H:i:s'); ?>">
	        <li id="goods-list"></li>
	    </ul>
     </section>
<script id="list" type="text/html">
{{# if(d.data && d.data.length>0){ }}
{{# for(var i=0,goods=d.data; i<goods.length; i++){ }}
		   <div class="ui-row-flex wst-shl-list goods_{{ d.current_page }}" onclick="goGoods({{ goods[i].grouponId }})" sv="{{ goods[i].startTime }}" ev="{{ goods[i].endTime }}" st="{{ goods[i].status }}">
				<div class="ui-col">
				<div class="img j-imgAdapt"><a href="javascript:void(0);" onclick="goGoods({{ goods[i].grouponId }})">
				<img src="/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="/{{ goods[i].goodsImg }}" title="{{ goods[i].goodsName }}"></a></div>
				</div>
				<div class="ui-col ui-col-2 info">
					<div class="title ui-nowrap">{{ goods[i].goodsName }}</div>
					<p class="prices"><span class="discount">{{ goods[i].zhekou }}折</span><span class="price">¥{{ goods[i].grouponPrice }}</span><span class="price2">¥{{ goods[i].marketPrice }}</span>&nbsp;</p>
					<p>已售：{{ goods[i].orderNum }}件</p>
					<p class="time"><i></i><span class="countDown_{{ d.current_page }}"></span></p>
				</div>
			</div>
{{# } }}
{{# }else{ }}
<div class="wst-prompt-icon"><img src="/addons/groupon/view/mobile/index/img/groupon-goods.png"></div>
<div class="wst-prompt-info">
	<p>对不起，没有相关团购商品。</p>
</div>
{{# } }}
</script>



<div class="wst-cover" id="cover"></div>

<div class="wst-fr-box" id="frame">
    <div class="title"><span>商品分类</span><i class="ui-icon-close-page" onclick="javascript:dataHide();"></i><div class="wst-clear"></div></div>
    <div class="content">
       <div class="ui-scrollerl">
            <ul>
                <?php if(is_array($data['goodscats']) || $data['goodscats'] instanceof \think\Collection || $data['goodscats'] instanceof \think\Paginator): $k = 0; $__LIST__ = $data['goodscats'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gc): $mod = ($k % 2 );++$k;?>
                	<li id="goodscate" class="wst-goodscate <?php if(($k==1)): ?>wst-goodscate_selected<?php endif; ?>" onclick="javascript:showRight(this,<?php echo $k-1; ?>);"><?php echo str_replace('、', '<br/>', $gc['catName']); ?></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        <?php if(is_array($data['goodscats']) || $data['goodscats'] instanceof \think\Collection || $data['goodscats'] instanceof \think\Paginator): $k = 0; $__LIST__ = $data['goodscats'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gc): $mod = ($k % 2 );++$k;?>
        <div class="wst-scrollerr goodscate1" <?php if(($k!=1)): ?>style="display:none;"<?php endif; ?>>
        <?php if((isset($gc['childList']))): if(is_array($gc['childList']) || $gc['childList'] instanceof \think\Collection || $gc['childList'] instanceof \think\Paginator): $k = 0; $__LIST__ = $gc['childList'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gc1): $mod = ($k % 2 );++$k;?>
            <ul>
                <li class="wst-goodsca">
                    <a href="javascript:void(0);" onclick="javascript:goodsCat(<?php echo $gc1['catId']; ?>);"><span>&nbsp;<?php echo $gc1['catName']; ?></span></a>
                    <a href="javascript:void(0);" onclick="javascript:goodsCat(<?php echo $gc1['catId']; ?>);"><i class="ui-icon-arrow"></i></a>
                </li>
                <li>
                    <div class="wst-goodscat">
                        <?php if(is_array($gc1['childList']) || $gc1['childList'] instanceof \think\Collection || $gc1['childList'] instanceof \think\Paginator): $i = 0; $__LIST__ = $gc1['childList'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gc2): $mod = ($i % 2 );++$i;?>
                        <span><a href="javascript:void(0);" onclick="javascript:goodsCat(<?php echo $gc2['catId']; ?>);"><?php echo $gc2['catName']; ?></a></span>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </li>
            </ul>
        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
        </div>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        <div class="wst-clear"></div>
    </div>
</div>


<script type='text/javascript' src='__MOBILE__/js/jquery.min.js'></script>
<script type='text/javascript' src='/addons/groupon/view/mobile/index/js/list.js?v=<?php echo $v; ?>'></script>
<script type='text/javascript' src='/addons/groupon/view/mobile/index/js/time.js?v=<?php echo $v; ?>'></script>

</body>
</html>