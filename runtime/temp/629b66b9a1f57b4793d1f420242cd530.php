<?php /*a:2:{s:60:"/home/mart/shangtao/mobile/view/default/shop_goods_list.html";i:1534762902;s:49:"/home/mart/shangtao/mobile/view/default/base.html";i:1534762856;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title>店铺商品列表 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="__MOBILE__/css/swiper.min.css">
<link rel="stylesheet"  href="__MOBILE__/css/shop_home.css?v=<?php echo $v; ?>">
<style>body {background-color: #f6f6f8;}</style>

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

    <header class="ui-header ui-header-positive wst-se-header2" style="padding-left: 0;border-bottom: 1px solid #f6f6f8;">
		<i class="ui-icon-return" onclick="history.back()"></i>
		<div class="wst-se-search" onclick="javascript:WST.searchPage('shops',1);" style="width: 76%;">
		    <i class="ui-icon-search" onclick="javascript:WST.searchPage('shops',1);"></i>
		    <form action＝"" class="input-form">
			<input type="search" value="<?php echo $goodsName; ?>" placeholder="按关键字搜索本店商品" onsearch="WST.search(2)" autocomplete="off" disabled="disabled">
			</form>
		</div>
       	<span class="wst-se-icon" onclick="javascript:dataShow();"></span>
	</header>



<div class="wst-cover" id="cover"></div>

<div class="wst-fr-box" id="frame">
    <div class="title">商品分类<i class="ui-icon-close-page" onclick="javascript:dataHide();"></i><div class="wst-clear"></div></div>
    <div class="content" id="content">


       <div class="ui-scrollerl" id="ui-scrollerl">
            <ul>
                <?php if(is_array($data['shopcats']) || $data['shopcats'] instanceof \think\Collection || $data['shopcats'] instanceof \think\Paginator): $k = 0; $__LIST__ = $data['shopcats'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$go): $mod = ($k % 2 );++$k;?>
                <li id="goodscate" class="wst-goodscate <?php if(($k==1)): ?>wst-goodscate_selected<?php endif; ?>" onclick="javascript:showRight(this,<?php echo $k-1; ?>);"><?php echo $go['catName']; ?></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        <?php if(is_array($data['shopcats']) || $data['shopcats'] instanceof \think\Collection || $data['shopcats'] instanceof \think\Paginator): $k = 0; $__LIST__ = $data['shopcats'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$go): $mod = ($k % 2 );++$k;?>
        <div class="wst-scrollerr goodscate1" <?php if(($k!=1)): ?>style="display:none;"<?php endif; ?>>

            <ul>
                <li class="wst-goodsca">
                    <a href="javascript:void(0);" onclick="javascript:getGoodsList(<?php echo $go['catId']; ?>);"><span>&nbsp;<?php echo $go['catName']; ?></span></a>
                    <a href="javascript:void(0);" onclick="javascript:getGoodsList(<?php echo $go['catId']; ?>);"><i class="ui-icon-arrow"></i></a>
                </li>
                <li>
                    <div class="wst-goodscat">
                        <?php if(is_array($go['children']) || $go['children'] instanceof \think\Collection || $go['children'] instanceof \think\Paginator): $i = 0; $__LIST__ = $go['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$go1): $mod = ($i % 2 );++$i;?>
                        <span><a href="javascript:void(0);" onclick="javascript:getGoodsList(<?php echo $go['catId']; ?>,<?php echo $go1['catId']; ?>);"><?php echo $go1['catName']; ?></a></span>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </li>
            </ul>
			<div class="wst-clear"></div>
        </div>
        <?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>


<input type="hidden" name="" value="" id="condition" autocomplete="off">
<input type="hidden" name="" value="" id="desc" autocomplete="off">
<input type="hidden" name="" value="<?php echo $shopId; ?>" id="shopId" autocomplete="off">
<input type="hidden" name="" value="<?php echo $goodsName; ?>" id="keyword" autocomplete="off">
<input type="hidden" name="" value="<?php echo $ct1; ?>" id="ct1" autocomplete="off">
<input type="hidden" name="" value="<?php echo $ct2; ?>" id="ct2" autocomplete="off">
<input type="hidden" name="" value="0" id="currPage" autocomplete="off">
<input type="hidden" name="" value="0" id="totalPage" autocomplete="off">

     <section class="ui-container">
        <div class="ui-row-flex wst-shl-head">
            <div class="ui-col ui-col sorts active" status="down" onclick="javascript:orderCondition(this,2);">
                 <p class="pd0">销量</p><i class="down2"></i>
            </div>
            <div class="ui-col ui-col sorts" status="down" onclick="javascript:orderCondition(this,3);">
                 <p class="pd0">价格</p><i class="down"></i>
            </div>
            <div class="ui-col ui-col sorts" status="down" onclick="javascript:orderCondition(this,1);">
                 <p class="pd0">人气</p><i class="down"></i>
            </div>
            <div class="ui-col ui-col sorts" status="down" onclick="javascript:orderCondition(this,6);">
                 <p>上架时间</p><i class="down"></i>
            </div>
        </div>



        <script id="shopList" type="text/html">
         {{# for(var i=0; i<d.length; i++){ }}
             <div class="wst-in-goods {{# if((i)%2==0){ }}left{{# }else{ }}right{{# } }}" onclick="WST.intoGoods({{d[i].goodsId}})">
             <div class="img j-imgAdapt" onclick="WST.intoGoods({{d[i].goodsId}})">
             <a href="javascript:void(0)" onclick="WST.intoGoods({{d[i].goodsId}})">
             <img src="/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="/{{d[i].goodsImg }}" title="{{d[i].goodsName}}"/>
             </a>
             </div>
             <div class="name ui-nowrap-multi">{{ d[i].goodsName}}</div>
			 <div class="info"><span class="price">¥ <span>{{ d[i].shopPrice }}</span></span></div>
             </div>
          {{# } }}
        </script>

        <ul class="ui-tab-content">
            <li id="shops-list">

            </li>
        </ul>


     </section>


    <div class="wst-co-search" id="wst-shops-search" style="background-color: #f6f6f8;">
    <header class="ui-header ui-header-positive wst-se-header2" style="border-bottom: 1px solid #f6f6f8;">
		<i class="ui-icon-return" onclick="javascript:WST.searchPage('shops',0);"></i>
		<div class="wst-se-search">
		    <i class="ui-icon-search" onclick="javascript:WST.search(2);"></i>
		    <form action＝"" class="input-form">
			<input type="search" value="" placeholder="按关键字搜索本店商品" onsearch="WST.search(2)" autocomplete="off" id="wst-search">
			</form>
		</div>
	</header>
	<div class="classify">
		<ul class="ui-list ui-list-text ui-list-link ui-list-active shops">
		    <li onclick="javascript:getGoodsList(0);">
		        <h4 class="ui-nowrap">全部商品</h4>
		    </li>
		</ul>
		<ul class="ui-list ui-list-text ui-list-active shops2">
            <?php if(is_array($data['shopcats']) || $data['shopcats'] instanceof \think\Collection || $data['shopcats'] instanceof \think\Paginator): $k = 0; $__LIST__ = $data['shopcats'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$g): $mod = ($k % 2 );++$k;?>
		    <li onclick="javascript:getGoodsList(<?php echo $g['catId']; ?>);">
		        <h4 class="ui-nowrap"><?php echo $g['catName']; ?></h4>
		        <div class="ui-txt-info">查看全部</div>
		    </li>
		    <?php endforeach; endif; else: echo "" ;endif; ?>
		</ul>
	</div>
	</div>


<script type='text/javascript' src='__MOBILE__/js/jquery.min.js'></script>
<script type='text/javascript' src='__MOBILE__/js/swiper.jquery.min.js'></script>
<script type='text/javascript' src='__MOBILE__/js/shop_goods_list.js'></script>

</body>
</html>