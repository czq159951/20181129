<?php /*a:3:{s:54:"/home/mart/shangtao/mobile/view/default/self_shop.html";i:1534762870;s:49:"/home/mart/shangtao/mobile/view/default/base.html";i:1534762856;s:51:"/home/mart/shangtao/mobile/view/default/footer.html";i:1534762864;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title>自营店铺 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="__MOBILE__/css/swiper.min.css">
<link rel="stylesheet"  href="__MOBILE__/css/self_shop.css?v=<?php echo $v; ?>">

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


<input type="hidden" name="" value="<?php echo $data['shop']['shopId']; ?>" id="shopId" autocomplete="off">
<input type="hidden" name="" value="-1" id="currPage" autocomplete="off">
<input type='hidden' name="" value="0" id="totalPage" autocomplete="off">
<input type="hidden" name="" value="<?php echo $data['shop']['shopName']; ?>" id="shopName" autocomplete="off">
     <section class="ui-container">
     	<div class="wst-sh-banner"
     	 <?php if($data['shop']['shopMoveBanner']!=''): ?>
   		 	style="background:url(/<?php echo WSTImg($data['shop']['shopMoveBanner'],2); ?>) no-repeat center top;background-size:cover;"
   		 <?php else: if((WSTConf('CONF.shopAdtop'))): ?>
   		 		style="background:url(/<?php echo WSTImg(WSTConf('CONF.shopAdtop'),2); ?>) no-repeat center top;background-size:cover;"
   		 	<?php endif; endif; ?>>
     	    <header class="ui-header ui-header-positive wst-se-header2 wst-se-header3">
				<i class="ui-icon-return" onclick="history.back()"></i>
				<div class="wst-se-search wst-se-search2" onclick="javascript:WST.searchPage('shops',1);">
				    <i class="ui-icon-search" onclick="javascript:WST.searchPage('shops',1);"></i>
				    <form action＝"" class="input-form">
					<input type="search" value="<?php echo $keyword; ?>" placeholder="按关键字搜索本店商品" onsearch="WST.search(2)" autocomplete="off" disabled="disabled">
					</form>
				</div>
		       	<span class="wst-se-icon wst-se-icon0" onclick="javascript:dataShow();"></span>
		       	 <?php $cartNum = WSTCartNum(); ?>
		       	<a href="<?php echo url('mobile/carts/index'); ?>"><span class="wst-se-icon wst-se-icon2"><?php if(($cartNum>0)): ?><i><?php echo $cartNum; ?></i><?php endif; ?></span></a>
			</header>
     	</div>
     	 <div class="shop-banner">
     	 	<div class="shop-photo">
                <div class="photo">
                    <img src="/<?php echo $data['shop']['shopImg']; ?>">
                    <p class="name"><?php echo $data['shop']['shopName']; ?></p>
                </div>
                <span class="introduce" onclick="toShopInfo(<?php echo $data['shop']['shopId']; ?>)">
                    <?php echo hook('mobileDocumentContact',['type'=>'shopHome','shopId'=>$data['shop']['shopId']]); ?>
                    店铺介绍
                </span>
                <?php if(($data['shop']['longitude'] && $data['shop']['latitude'])): ?>
                   <span class="introduce" onclick="javascript:init(<?php echo $data['shop']['longitude']; ?>,<?php echo $data['shop']['latitude']; ?>);">店铺位置</span>
                <i class="location-icon"></i>
                <?php endif; ?>
                <div class="wst-clear"></div>
            </div>
            <div class="shop-info" <?php if((!$data['shop']['shopNotice'])): ?>style="padding-bottom:0.1rem;border-bottom: 0.05rem solid #f2f1f1;"<?php endif; ?>>
            	<div class="ui-row-flex">
				    <div class="ui-col ui-col-2">
				     <a class="shop-btn j-shopfollow <?php if(($isFavor>0)): ?>follow<?php endif; ?>" id="fBtn" onclick="<?php if(($isFavor>0)): ?>WST.cancelFavorite(<?php echo $isFavor; ?>,1)<?php else: ?>WST.favorites(<?php echo $data['shop']['shopId']; ?>,1)<?php endif; ?>"></a>
	                 <p id="followNum" style="color: #656565;font-size: 0.15rem;font-weight: bold;"><?php echo $followNum; ?></p>
	                 <p style="color: #cbcbcb;">收藏数</p>
				    </div>
				    <div class="ui-col ui-col-2"></div>
				    <div class="ui-col ui-col-3">
					    <p style="color: #656565;font-size: 0.15rem;font-weight: bold;padding-left:0.3rem;"><?php echo $data['shop']['scores']['areas']['areaName1']; ?><?php echo $data['shop']['scores']['areas']['areaName2']; ?></p>
					    <p style="color: #cbcbcb;padding-left:0.3rem;">所在地</p>
				    </div>
				</div>
            </div>
            <?php if(($data['shop']['shopNotice'])): ?>
            <div class="shop-notice">
            	<p class="title">店铺公告</p>
            	<p style="color: #aeaeae;"><?php echo $data['shop']['shopNotice']; ?></p>
            </div>
            <?php endif; ?>
            <div class="wst-clear"></div>
         </div>
         <?php if(!empty($data['shop']['shopAds'])): ?>
         <div class="shop-ads">
            <div class="ui-slider">
            <ul class="ui-slider-content" style="width: 300%">
                <?php if(is_array($data['shop']['shopAds']) || $data['shop']['shopAds'] instanceof \think\Collection || $data['shop']['shopAds'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['shop']['shopAds'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ads): $mod = ($i % 2 );++$i;?>
                <li><span><a href="<?php echo $ads['adUrl']; ?>"><img style="width:100%; height:100%; display:block;" src="/<?php echo $ads['adImg']; ?>"></a></span></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            </div>
         </div>
         <?php endif; ?>
         <div class="wst-shl-ads" >
            <div class="title">店主推荐</div>
           <div class="wst-gol-adsb">
            <div class="swiper-container">
              <div class="swiper-wrapper">
                <?php if(is_array($data['rec']) || $data['rec'] instanceof \think\Collection || $data['rec'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['rec'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$re): $mod = ($i % 2 );++$i;?>
                    <div class="swiper-slide" style="width:33.333333%;">
                    <div style="border-right: 0.01rem solid #f2f1f1;">
                         <div class="wst-gol-img j-imgRec"><a href="javascript:void(0)" onclick="WST.intoGoods(<?php echo $re['goodsId']; ?>)"><img src="/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="/<?php echo WSTImg($re['goodsImg'],3); ?>" title="<?php echo $re['goodsName']; ?>"></a></div>
                         <p>¥<?php echo $re['shopPrice']; ?></p>
                    <div class="wst-clear"></div>
                    </div>
                    </div>
                 <?php endforeach; endif; else: echo "" ;endif; ?>
              </div>
            </div>
            </div>
        </div>

        <div class="wst-shl-ads" >
            <div class="title">热卖商品</div>
           <div class="wst-gol-adsb">
            <div class="swiper-container">
              <div class="swiper-wrapper">
                <?php if(is_array($data['hot']) || $data['hot'] instanceof \think\Collection || $data['hot'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['hot'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$hot): $mod = ($i % 2 );++$i;?>
                    <div class="swiper-slide" style="width:33.333333%;">
                    <div style="border-right: 0.01rem solid #f2f1f1;">
                         <div class="wst-gol-img j-imgRec1"><a href="javascript:void(0)" onclick="WST.intoGoods(<?php echo $hot['goodsId']; ?>)"><img src="/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="/<?php echo WSTImg($hot['goodsImg'],3); ?>" title="<?php echo $hot['goodsName']; ?>"></a></div>
                         <p>¥<?php echo $hot['shopPrice']; ?></p>
                    <div class="wst-clear"></div>
                    </div>
                    </div>
                 <?php endforeach; endif; else: echo "" ;endif; ?>
              </div>
            </div>
            </div>
        </div>
        <script id="gList" type="text/html">
             <div class="wst-in-title">
             <ul class="ui-row shop-floor-title f{{d.currPage}}">
             <li class="ui-col ui-col-80">{{d.catName}}</li>
             <li class="ui-col ui-col-20"><a href="{{WST.U('mobile/shops/shopgoodslist','shopId=1&ct1='+d.catId)}}" class="shop-more">更多</a></li>
             </ul>
            {{# if(d.goods.length>0){ }}
              {{# for(var i=0; i<d.goods.length; i++){ }}
                       <div class="wst-in-goods {{# if((i)%2==0){ }}left{{# }else{ }}right{{# } }}" onclick="javascript:WST.intoGoos({{d.goods[i].goodsId}});">
                       <div class="img j-imgAdapt"><a href="javascript:void(0);" onclick="javascript:WST.intoGoods({{d.goods[i].goodsId}});">
                       <img src="{{# WST.conf.ROOT+'/'+WST.conf.GOODS_LOGO}}" data-echo="/{{d.goods[i].goodsImg}}" title="{{d.goods[i].goodsName}}"/></a></div>
                       <div class="name ui-nowrap-multi">{{d.goods[i].goodsName}}</div>
					   <div class="info"><span class="price">¥ <span>{{ d.goods[i].shopPrice }}</span></span></div>
					   <div class="info2"><span class="price">¥ {{ d.goods[i].marketPrice }}</span><span class="deal">成交数:{{ d.goods[i].saleNum }}</span></div>
                       </div>
               {{# } }}
             {{# } }}
             <div class="wst-clear"></div>
        </script>

        <!-- 商品列表 -->
        <div id="goods-list"></div>


<div class="wst-cover" id="cover"></div>

<div class="wst-fr-box" id="container">
    <div class="title"><?php echo $data['shop']['shopName']; ?> - 店铺地址<i class="ui-icon-close-page" onclick="javascript:mapHide();"></i><div class="wst-clear"></div></div>
    <div id="map"></div>
</div>

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
                    <a href="javascript:void(0);" onclick="javascript:goGoodsList(<?php echo $go['catId']; ?>);"><span>&nbsp;<?php echo $go['catName']; ?></span></a>
                    <a href="javascript:void(0);" onclick="javascript:goGoodsList(<?php echo $go['catId']; ?>);"><i class="ui-icon-arrow"></i></a>
                </li>
                <li>
                    <div class="wst-goodscat">
                        <?php if(is_array($go['children']) || $go['children'] instanceof \think\Collection || $go['children'] instanceof \think\Paginator): $i = 0; $__LIST__ = $go['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$go1): $mod = ($i % 2 );++$i;?>
                        <span><a href="javascript:void(0);" onclick="javascript:goGoodsList(<?php echo $go['catId']; ?>,<?php echo $go1['catId']; ?>);"><?php echo $go1['catName']; ?></a></span>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </li>
            </ul>


            <ul>
                <li>
                    <div class="wst-goodscats">
                        <span>&nbsp;</span>
                    </div>
                </li>
            </ul>
        </div>
        <?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
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
		<a class="btn" href="javascript:void(0);" onclick="javascript:WST.search(2);">搜索</a>
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
	<script>
	/*分类*/
	function getGoodsList(ct1){
	    $('#ct1').val(ct1);
	    // 跳转店铺商品列表
	    var shopId = $('#shopId').val();
	    location.href=WST.U('mobile/shops/shopgoodslist',{'shopId':shopId,'ct1':ct1},true)
	}
	</script>


<script type='text/javascript' src='__MOBILE__/js/jquery.min.js'></script>
<script type='text/javascript' src='__MOBILE__/js/swiper.jquery.min.js'></script>
<script type='text/javascript' src='__MOBILE__/js/self_shop.js'></script>
<script type="text/javascript" src="<?php echo WSTProtocol(); ?>map.qq.com/api/js?v=2.exp"></script>

<script>
$(function(){
   <?php if(!empty($data['shop']['shopAds'])): ?>
    shopAds();
   <?php endif; ?>
   WST.initFooter('home');
});
</script>

</body>
</html>