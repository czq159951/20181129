<?php /*a:3:{s:72:"/www/beidou/mart/zsbd_mart/shangtao/mobile/view/default/shop_street.html";i:1536569719;s:65:"/www/beidou/mart/zsbd_mart/shangtao/mobile/view/default/base.html";i:1536569719;s:67:"/www/beidou/mart/zsbd_mart/shangtao/mobile/view/default/footer.html";i:1536569719;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title>店铺街 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="__MOBILE__/css/swiper.min.css">
<link rel="stylesheet"  href="__MOBILE__/css/shops_list.css?v=<?php echo $v; ?>">

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
		<div class="wst-se-search" onclick="javascript:WST.searchPage('shops',1);">
		    <i class="ui-icon-search" onclick="javascript:WST.searchPage('shops',1);"></i>
		    <form action＝"" class="input-form">
			<input type="search" value="<?php echo $keyword; ?>" placeholder="按关键字搜索店铺" onsearch="WST.search(1)" autocomplete="off" disabled="disabled">
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
	 <input type="hidden" name="" value="" id="condition" autocomplete="off">
	 <input type="hidden" name="" value="" id="desc" autocomplete="off">
	 <input type="hidden" name="" value="" id="catId" autocomplete="off">
	 <input type="hidden" name="" value="" id="currPage" autocomplete="off">
     <input type="hidden" name="" value="" id="totalPage" autocomplete="off">
	 <input type="hidden" name="" value="" id="accredId" autocomplete="off">
     <input type="hidden" name="" value="" id="totalScore" autocomplete="off">
    
     <div id="backgroundTier" onclick="javascript:closeScreenTier();" style="display:none;"></div>
     <div id="screen">
     <div class="screen-top">
		<ul class="ui-tab-content">
	        <li id="screenAttr"></li>
	    </ul>
		<ul class="ui-tab-content">
	        <li id="graded"></li>
	    </ul>
     </div>
     	<div id="indexbnts" class="index-bnts">	
     		<div   onclick="javascript:resetAll();" class="left J_ping">重置</div>	
     		<div onclick="javascript:closeScreenTier();" report-eventparam="B" report-eventid="MFilter_Confirm" class="right J_ping">确定</div>
     	</div>
     </div>
     <section class="ui-container">
     	<div class="wst-shl-ads">
     	   <div class="title">名铺抢购</div>
		   <div class="wst-shl-adsb">
			<div class="swiper-container">
	          <div class="swiper-wrapper">
	          	<?php $wstTagAds =  model("common/Tags")->listAds("mo-ads-street",4,86400); foreach($wstTagAds as $key=>$vo){?>
	                <div class="swiper-slide" style="width:33.333333%;">
	                    <a href="<?php echo $vo['adURL']; ?>" class="adsImg"><img src="/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="/<?php echo WSTImg($vo['adFile'],3); ?>"></a>
	                </div>
	            <?php } ?>
	          </div>
	        </div>
	        </div>
     	</div>
     	<div class="ui-row-flex wst-shl-head">
     		<div class="ui-col ui-col-4 ui-row-flex">
				    <div class="ui-col ui-col-1">
			           	<div class="ui-select wst-shl-select choice active">
			                <select onchange="javascript:orderSelect(this.value);">
			                    <option value="">主营</option>
			                    <?php if(is_array($goodscats) || $goodscats instanceof \think\Collection || $goodscats instanceof \think\Paginator): $i = 0; $__LIST__ = $goodscats;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$g): $mod = ($i % 2 );++$i;?>
			                    	<option value="<?php echo $g['catId']; ?>"><?php echo $g['catName']; ?></option>
			                    <?php endforeach; endif; else: echo "" ;endif; ?>
			                </select>
			            </div>
				    </div>
				    <div class="ui-col ui-col evaluate" >
				   		 <p class="choice sorts" status="down" onclick="javascript:orderCondition(this,1);">好评度&nbsp;&nbsp;&nbsp;&nbsp;<i class="down"></i></p>
				    </div>
				    <div class="ui-col ui-col evaluate" >
				   		 <p class="choice sorts" status="down" onclick="javascript:orderCondition(this,2);">距离&nbsp;&nbsp;&nbsp;&nbsp;<i class="down" style="right: 24px;"></i></p>
				   		 <i class="bar"style=""></i>
				    </div>
            </div>
		    <div class="ui-col evaluate">
		    	<i class="screen-icon" onclick="javascript:screenTier();">筛选</i>
		    </div>
		</div>
		<ul class="ui-tab-content">
	        <li id="shops-list"></li>
	    </ul>
     </section>
<script id="list" type="text/html">
{{# if(d && d.length>0){ }}
{{# for(var i=0; i<d.length; i++){ }}
	<div class="ui-row-flex ui-whitespace ui-row-flex-ver wst-shl-list">
            <div class="ui-col">
                <div class="ui-row-flex">
                    <div class="ui-col ui-col-2" >
                      <div class="img j-imgAdapt"><a href="javascript:void(0);" onclick="goShopHome({{ d[i].shopId }})">
				  	    <img src="/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="/{{ d[i].shopImg }}" title="{{ d[i].shopName }}"></a>
				  	  </div>
                    </div>
                    <div class="ui-col  info" onclick="goToShop({{d[i].shopId}})">
                        <div class="title ui-nowrap">{{d[i].shopName}}</div>
                         <p class="ui-nowrap">主营：{{ d[i].catshops }}</p>
					  	 <p><span>店铺评分：</span>
						 {{# for(var j=1; j<6; j++){ }}
			                {{# if(j <= d[i].totalScore){ }}
			                    <i class="bright"></i>
							{{# }else{ }}
			                    <i class="dark"></i>
							{{# } }}
						 {{# } }}
						</p>
                        
                    </div>
                    <div class="ui-col ui-col-2 f-goshops" onclick="goShopHome({{d[i].shopId}})">
                       <a href="javascript:void(0);" onclick="goToShop({{d[i].shopId}})"><span class="wst-action">进入店铺</span></a>
                        {{# if(d[i].lat>0 && d[i].lng>0 && d[i].distince){ }}<p class="wst-distance">{{d[i].distince}}km</p>{{# } }}
                    </div>
                </div>
            </div>
            <div class="ui-col" style="margin-top:5px;">
                <div class="ui-row-flex goods-box">
                    {{# var gLength = Math.min(d[i].goods.length,4) }}
                    {{# for(var g=0;g<gLength;++g){  }}
                    <div class="goods-item" >
                       {{# if(d[i].goods[g].goodsImg){ }}
                       <a href="javascript:void(0);" onclick="WST.intoGoods({{d[i].goods[g].goodsId}})">
                          <img  src="/<?php echo WSTConf('CONF.goodsLogo'); ?>"  data-echo="/{{WST.replaceImg(d[i].goods[g].goodsImg,'_m_thumb')}}"  >
                          <i class="goodsPrice ui-nowrap" >¥ {{d[i].goods[g].shopPrice}}</i>
                       </a>
                       {{# } }}
                    </div>
                    {{# } }}
                </div>
            </div>
            <div class="wst-clear"></div>
        </div>
{{# } }}
{{# }else{ }}
<div class="wst-prompt-icon"><img src="__MOBILE__/img/nothing-follow-shps.png"></div>
<div class="wst-prompt-info">
	<p>对不起，没有相关店铺。</p>
</div>
{{# } }}
</script>
<script id="accredList" type="text/html">

     	<div class="accred-box screen-box no">
        <input type="hidden"  class="vsed" value=""/>
     		<p class="title">店铺服务{{# if(d.length>3){ }}<i class="arrow-base arrow" onclick="javascript:showAll(this)"  s=0></i>{{# } }}</p>
	         <div class="option-box">
	                <span id="cancelAccred" onclick="javascript:cancelAccred(this);" class="attrs after-color selected" d="" style="background-color: rgb(255, 255, 255);display:none;"></span>
				{{# if(d && d.length>0){ }}
				{{# for (var i=0; i<d.length;i++){ }}
			     			<span onclick="javascript:selectAccred(this);" class="attrs after-color  accred-lines" d="{{d[i].accredId}}">{{d[i].accredName}}</span>
				{{# } }}
				{{# } }}
		     </div>
     	</div>

</script>
<script id="scoreList" type="text/html">
         
     	<div class="score-box  screen-box no">
        <input type="hidden"  class="vsed" value=""/>
     		<p class="title">好评率</p>
	         <div class="option-box">
	                <span id="cancelScore" onclick="javascript:cancelScore(this);" class="attrs after-color selected" d="" style="background-color: rgb(255, 255, 255);display:none;"></span>
				{{# for(var i in d){ }}

			     	<span onclick="javascript:selectScore(this);" class="attrs after-color wrap-lines" d="{{i}}" style="padding: 0.05rem 0.01rem;">{{d[i]}}</span>
				{{# } }}
		     </div>
     	</div>

</script>


    <div class="wst-co-search" id="wst-shops-search" style="background-color: #f6f6f8;">
    <header class="ui-header ui-header-positive wst-se-header2" style="border-bottom: 1px solid #f6f6f8;">
		<i class="ui-icon-return" onclick="javascript:WST.searchPage('shops',0);"></i>
		<div class="wst-se-search">
		    <i class="ui-icon-search" onclick="javascript:WST.search(1);"></i>
		    <form action＝"" class="input-form">
			<input type="search" value="" placeholder="按关键字搜索店铺" onsearch="WST.search(1)" autocomplete="off" id="wst-search">
			</form>
		</div>
		<a class="btn" href="javascript:void(0);" onclick="javascript:WST.search(1);">搜索</a>
	</header>
	<div class="classify">
		<ul class="ui-list ui-list-text ui-list-link ui-list-active shops">
		    <li onclick="javascript:searchCondition(0);">
		        <h4 class="ui-nowrap">全部店铺</h4>
		    </li>
		</ul>
		<ul class="ui-list ui-list-text ui-list-active shops2">
            <?php if(is_array($goodscats) || $goodscats instanceof \think\Collection || $goodscats instanceof \think\Paginator): $i = 0; $__LIST__ = $goodscats;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$g): $mod = ($i % 2 );++$i;?>
		    <li onclick="javascript:searchCondition(<?php echo $g['catId']; ?>);">
		        <h4 class="ui-nowrap"><?php echo $g['catName']; ?></h4>
		        <div class="ui-txt-info">查看全部</div>
		    </li>
		    <?php endforeach; endif; else: echo "" ;endif; ?>
		</ul>
	</div>
	</div>
	<script type='text/javascript' src='__MOBILE__/js/jquery.min.js'></script>


<script type='text/javascript' src='__MOBILE__/js/swiper.jquery.min.js'></script>
<script type='text/javascript' src='__MOBILE__/js/shops_list.js?v=<?php echo $v; ?>'></script>

</body>
</html>