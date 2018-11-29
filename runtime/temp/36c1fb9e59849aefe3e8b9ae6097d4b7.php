<?php /*a:4:{s:71:"/www/beidou/mart/zsbd_mart/shangtao/mobile/view/default/goods_list.html";i:1536569711;s:65:"/www/beidou/mart/zsbd_mart/shangtao/mobile/view/default/base.html";i:1536569719;s:67:"/www/beidou/mart/zsbd_mart/shangtao/mobile/view/default/footer.html";i:1536569719;s:73:"/www/beidou/mart/zsbd_mart/shangtao/mobile/view/default/goods_search.html";i:1536569711;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title>商品列表 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="__MOBILE__/css/swiper.min.css">
<link rel="stylesheet"  href="__MOBILE__/css/goods_list.css?v=<?php echo $v; ?>">

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
		<i class="ui-icon-return" onclick="history.back()"></i>
		<div class="wst-se-search">
		    <i class="ui-icon-search" onclick="javascript:WST.searchPage('goods',1);"></i>
		    <form action＝"" class="input-form" onclick="javascript:WST.searchPage('goods',1);">
			<input type="search" value="<?php echo $keyword; ?>" placeholder="按关键字搜索商品" onsearch="WST.search(0)" autocomplete="off" disabled="disabled">
			</form>
		</div>
		<span class="wst-se-icon" onclick="javascript:switchList(this);"></span>
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
     <input type="hidden" name="" value="<?php echo $catId; ?>" id="catId" autocomplete="off">
	 <input type="hidden" name="" value="<?php echo $brandId; ?>" id="brandId" autocomplete="off">
     <input type="hidden" name="" value="" id="condition" autocomplete="off">
	 <input type="hidden" name="" value="" id="desc" autocomplete="off">
	 <input type="hidden" name="" value="" id="currPage" autocomplete="off">
	 <input type="hidden" name="" value="" id="isFreeShipping" autocomplete="off">
     <input type="hidden" name="" value="" id="totalPage" autocomplete="off">

     
     <div id="backgroundTier" onclick="javascript:closeScreenTier();" style="display:none;"></div>
     <div id="screen">
     <div class="screen-top">
     	<div class="screen-box1">
     		<p class="title">价格区间</p>
     		<div class="ui-row-flex option-box1">
     			<input class="ui-col ui-flex ui-flex-pack-start section" id="minPrice" type="text" placeholder="最低价" autocomplete="off">
     			<span class="across"></span>
     			<input class="ui-col ui-flex ui-flex-pack-end section section-end" id="maxPrice" type="text" placeholder="最高价" autocomplete="off">
     			<span class="across" style="border-color: transparent;"></span>
     			<span class="across" style="border-color: transparent;"></span>
     		</div>
     	</div>
          <div class="screen-box1" id="freeed" style="display:none;">
               <p class="title">是否包邮</p>
               <div class="ui-row-flex option-box1">
                        <div class="transport selected" onclick="javascript:cancelFree(this);" style="background-color:white;">
                            <p id="freeValue"></p>
                        </div>
               </div>
          </div>
     	<div class="screen-box1" id="free">
     		<p class="title">是否包邮</p>
     		<div class="ui-row-flex option-box1">
				    <div class="transport" onclick="javascript:isFreeShipping(this);" f="1">
				        <p>包邮</p>
				    </div>
				    <div class="transport" onclick="javascript:isFreeShipping(this);" f="0">
				        <p>不包邮</p>
				    </div>
     		</div>
     	</div>
		<ul class="ui-tab-content">
             <li id="screenAttred"></li>
         </ul>
          <ul class="ui-tab-content">
	        <li id="screenAttr"></li>
	    </ul>
     </div>
     	<div id="indexbnts" class="index-bnts">	
     		<div   onclick="javascript:resetAll();" class="left J_ping">重置</div>	
     		<div onclick="javascript:screenGoodsList();" report-eventparam="B" report-eventid="MFilter_Confirm" class="right J_ping">确定</div>
     	</div>
     </div>
     <section class="ui-container">
     	<div class="ui-row-flex wst-shl-head">
		    <div class="ui-col ui-col sorts active" status="down" onclick="javascript:orderCondition(this,0);">
		   		 <p class="pd0">销量</p><i class="down2"></i>
		    </div>
		    <div class="ui-col ui-col sorts" status="down" onclick="javascript:orderCondition(this,1);">
		   		 <p class="pd0">价格</p><i class="down"></i>
		    </div>
		    <div class="ui-col ui-col sorts" status="down" onclick="javascript:orderCondition(this,2);">
		   		 <p class="pd0">人气</p><i class="down"></i>
		   		 <span class="bar"></span>
		    </div>
		    <div class="ui-col ui-col sorts" status="down" onclick="javascript:screenTier();">
		    	<i class="screen"></i><p class="pd1">筛选</p>
		    </div>
		</div>
		<ul class="ui-tab-content">
	        <li id="goods-list"></li>
	    </ul>
     </section>
     
<script id="list" type="text/html">
{{# if(d && d.length>0){ }}
{{# for(var i=0; i<d.length; i++){ }}
<div class="wst-in-goods {{# if((i)%2==0){ }}left{{# }else{ }}right{{# } }}" onclick="javascript:WST.intoGoods({{ d[i].goodsId }});">
<div class="img j-imgAdapt"><a href="javascript:void(0);" onclick="javascript:WST.intoGoods({{ d[i].goodsId }});"><img src="/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="/{{ d[i].goodsImg }}" title="{{ d[i].goodsName }}"/></a></div>
<div class="name ui-nowrap-multi">{{ d[i].goodsName }}</div>
<div class="tags ui-nowrap-multi">
{{# if(d[i].isSelf==1){ }}<span class='tag'>自营</span>{{# } }}
{{# if(d[i].isFreeShipping==1){ }}<span class='tag'>包邮</span>{{# } }}
{{ d[i]['tags']!=undefined?d[i]['tags'].join(' '):'' }}&nbsp;
</div>
<div class="info"><span class="price">¥ <span>{{ d[i].shopPrice }}</span></span></div>
<div class="info2"><span class="price">好评率{{ d[i].praiseRate }}</span><span class="deal">成交数:{{ d[i].saleNum }}</span></div>
</div>
{{# } }}
{{# }else{ }}
<div class="wst-prompt-icon"><img src="__MOBILE__/img/nothing-goods.png"></div>
<div class="wst-prompt-info">
	<p>对不起，没有相关商品。</p>
</div>
{{# } }}
</script>
<script id="screenListed" type="text/html">
{{# if(d && d.length>0){ }}
{{# for (var i=0; i<d.length;i++){ }}

          <div class="screen-box">
               <p class="title">{{d[i].attrName}}</p>
               <div class="option-box">
                    <span onclick="javascript:cancelSeled(this);"  id="a_{{d[i]['attrId']}}" d="{{d[i]['attrId']}}" k="{{i}}" class="attrs after-color selected" style="background-color:white;">{{d[i].attr}}</span>
               </div>
          </div>

{{# } }}
{{# } }}
</script>
<script id="screenList" type="text/html">
{{# if(d && d.length>0){ }}
{{# for (var i=0; i<d.length;i++){ }}

        <input type="hidden" id="v_{{d[i]['attrId']}}" class="vsed" value=""/>
     	<div class="screen-box no">
     		<p class="title">{{d[i].attrName}}{{# if(d[i]['attrVal'].length>4){ }}<i class="arrow-base arrow" onclick="javascript:showAll(this)" d="{{d[i]['attrId']}}" s=0></i>{{# } }}</p>
     		<div class="option-box">
                    <span onclick="javascript:cancelSeled(this);"  id="a_{{d[i]['attrId']}}" d="{{d[i]['attrId']}}" class="attrs after-color selected" style="display:none;background-color:white;"></span>
     		{{# for (var j=0;j<d[i]['attrVal'].length;j++){ }}
     			<span onclick="javascript:selectAttr(this);" d="{{d[i]['attrId']}}" v="{{d[i]['attrVal'][j]}}" n="{{d[i].attrName}}" class="attrs after-color attr-box">{{d[i]['attrVal'][j]}}</span>
            {{# } }}
     		</div>
     	</div>

{{# } }}
{{# } }}
</script>
<script type="text/html" id="sc">
	<input type="hidden" id="vs" class="sipt" value='{{d}}'/>
</script>
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
<div class="wst-toTop" style="display: block;bottom: 0.68rem;">
	<a href="<?php echo url('mobile/goods/history'); ?>"><i class="wst-toHistoryimg"></i></a>
</div>


<script type='text/javascript' src='__MOBILE__/js/swiper.jquery.min.js'></script>
<script type='text/javascript' src='__MOBILE__/js/goods_list.js?v=<?php echo $v; ?>'></script>

</body>
</html>