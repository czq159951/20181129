<?php /*a:3:{s:57:"/home/mart/shangtao/mobile/view/default/goods_detail.html";i:1534762864;s:49:"/home/mart/shangtao/mobile/view/default/base.html";i:1534762856;s:51:"/home/mart/shangtao/mobile/view/default/dialog.html";i:1534762864;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title>商品详情 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="__MOBILE__/css/swiper.min.css">
<link rel="stylesheet"  href="__MOBILE__/css/goods_detail.css?v=<?php echo $v; ?>">
<link rel="stylesheet"  href="__MOBILE__/js/share/nativeShare.css?v=<?php echo $v; ?>">

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

	<?php $cartNum = WSTCartNum(); ?>
	<header class="ui-header ui-header-positive wst-header" id="goods-header" style="display:none;">
	    <i class="ui-icon-return" onclick="history.back()"></i>
	    <ul class="ui-tab-nav">
	        <li class="switch active" onclick="javascript:pageSwitch(this,1);">商品</li>
	        <li class="switch" onclick="javascript:pageSwitch(this,2);">详情</li>
	        <li class="switch" id="appr" onclick="javascript:pageSwitch(this,3);">评价</li>
    	</ul>
	    <a href="<?php echo url('mobile/carts/index'); ?>"><span class="cart" id="cartNum"><?php if(($cartNum>0)): ?><span><?php  echo $cartNum; ?></span><?php endif; ?></span></a>
	    <?php if(!(Request()->isSsl())){?>
	    <span class="share" onclick="javascript:shareShow();"></span>
	    <?php } ?>
    </header>


    <div class="ui-loading-wrap wst-Load" id="Load">
	    <i class="ui-loading"></i>
	</div>
	<input type="hidden" name="" value="<?php echo $info['goodsId']; ?>" id="goodsId" autocomplete="off">
	<input type="hidden" name="" value="<?php echo $info['goodsType']; ?>" id="goodsType" autocomplete="off">
    <footer class="ui-footer wst-footer-btns" style="height:42px;" id="footer">
        <div class="wst-toTop" id="toTop">
	  	<i class="wst-toTopimg"></i>
		</div>
		<div class="ui-row-flex">
		<div class="ui-col ui-col-3 wst-go-icon">
			<div class="ui-row-flex">
			    <div class="ui-col ui-col" style="border-right: 1px solid rgba(0,0,0,.05);">
					<div class="icon">
					<?php if(($info['shop']['shopQQ'])!=''): ?>
							<a class="J_service" href="<?php echo WSTProtocol(); ?>wpa.qq.com/msgrd?v=3&uin=<?php echo $info['shop']['shopQQ']; ?>&site=qq&menu=yes">
								<span class="img qq"></span><span class="word">客服</span>
							</a>
					<?php else: ?>
							<a class="J_service" href="tel:<?php echo $info['shop']['shopTel']; ?>">
								<span class="img tel"></span><span class="word">客服</span>
							</a>
					<?php endif; ?>
					<?php echo hook('mobileDocumentContact',['type'=>'goodsDetail','shopId'=>$info['shop']['shopId'],'goodsId'=>$info['goodsId']]); ?>
					</div>
			    </div>
			    <div class="ui-col ui-col" style="border-right: 1px solid rgba(0,0,0,.05);">
			    	<div class="icon"><a href="<?php echo url('mobile/shops/home',['shopId'=>$info['shop']['shopId']]); ?>"><span class="img shop"></span><span class="word">店铺</span></a></div>
			    </div>
			    <div class="ui-col ui-col">
			    <?php if(($info['favGood']==0)): ?>
		    	<button class="but" type="button"><span class="img imgfollow nofollow" onclick="javascript:WST.favorites(<?php echo $info['goodsId']; ?>,0);"></span><span style="bottom: 5px;" class="word">关注</span></button>
				<?php else: ?>
		    	<button class="but" type="button"><span class="img imgfollow follow" onclick="javascript:WST.cancelFavorite(<?php echo $info['favGood']; ?>,0);"></span><span style="bottom: 5px;" class="word">关注</span></button>
				<?php endif; ?>
			    </div>
			</div>
		</div>
	    <div class="ui-col ui-col-4 wst-goods_buy">
 			<?php if(($info['goodsType']==1)): ?>
				<button class="wst-goods_buym" type="button" onclick="javascript:cartShow(1);" <?php if(($info['goodsId']==0)): ?>disabled<?php endif; ?>>立即购买</button>
			<?php else: ?>
			    <button class="wst-goods_buyl" type="button" onclick="javascript:cartShow(0);" <?php if(($info['goodsId']==0)): ?>disabled<?php endif; ?>>加入购物车</button>
				<button class="wst-goods_buyr" type="button" onclick="javascript:cartShow(1);" <?php if(($info['goodsId']==0)): ?>disabled<?php endif; ?>>立即购买</button>
			<?php endif; ?>
	    </div>
	    </div>
    </footer>



<?php if(($info['goodsId']>0)): ?>
	 
	 <div class="wst-go-more" id="arrow" style="display: none;"><i class="arrow"></i>
	 	<ul class="ui-row ui-list-active more">
		    <li class="ui-col"><div class="column line"><a href="<?php echo url('mobile/index/index'); ?>"><i class="home"></i><p>首页</p></a></div></li>
		    <li class="ui-col"><div class="column line"><a href="<?php echo url('mobile/goodscats/index'); ?>"><i class="category"></i><p>分类</p></a></div></li>
		    <li class="ui-col"><div class="column line"><a href="<?php echo url('mobile/carts/index'); ?>"><i class="cart"></i><p>购物车</p></a></div></li>
		    <li class="ui-col"><div class="column line"><a href="<?php echo url('mobile/favorites/goods'); ?>"><i class="follow"></i><p>关注</p></a></div></li>
		    <li class="ui-col"><div class="column"><a href="<?php echo url('mobile/users/index'); ?>"><i class="user"></i><p>我的</p></a></div></li>
	 	</ul>
	 </div>
	 <div class="wst-ca-layer" id="layer" onclick="javascript:inMore();"></div>
     <section class="ui-container" id="goods1" style="border-top: 0px solid transparent;">
		<div class="swiper-container">
          <div class="swiper-wrapper">
          		<?php if(is_array($info['gallery']) || $info['gallery'] instanceof \think\Collection || $info['gallery'] instanceof \think\Paginator): $i = 0; $__LIST__ = $info['gallery'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ga): $mod = ($i % 2 );++$i;?>
                <div class="swiper-slide" style="width:100%;">
                	<div class="wst-go-img"><a><img src="/<?php echo WSTImg($ga,2); ?>"></a></div>
                </div>
                <?php endforeach; endif; else: echo "" ;endif; ?>
          </div>
   		  <?php if((count($info['gallery'])>1)): ?><div class="swiper-pagination"></div><?php endif; ?>
   		  <div class="wst-go-return" onclick="history.back()"><i class="ui-icon-prev"></i></div>
   		  <div class="wst-go-mores" onclick="javascript:inMore()"><i>···</i></div>
        </div>
	    <div class="wst-go-name"><?php echo $info['goodsName']; ?></div>
		<div class="ui-row-flex wst-go-price">
		    <div class="ui-col ui-col-2">
		    	<div class="price"><i>¥ </i><?php echo $info['shopPrice']; ?><span class="market">¥ <?php echo $info['marketPrice']; ?></span></div>
		    	<div class="ui-row-flex info">
				    <div class="ui-col ui-col" style="text-align: left;">快递: <?php if($info['isFreeShipping']==1): ?>免运费<?php else: ?><?php echo sprintf("%.2f", $info['shop']['freight']); endif; ?></div>
				    <div class="ui-col ui-col" style="text-align: center;">销量: <?php echo $info['saleNum']; ?></div>
				    <div class="ui-col ui-col" style="text-align: right;"><?php echo $info['shop']['areas']['areaName1']; ?><?php echo $info['shop']['areas']['areaName2']; ?></div>
				</div>
		    </div>
		    <?php echo hook('mobileDocumentGoodsDetailTips',["goods"=>$info]); ?>
		</div>
		<ul class="ui-list ui-list-text wst-go-ul ui-list-active">
			<?php if(WSTConf('CONF.isOrderScore')==1): ?>
		    <li>
		        <div class="ui-list-info">
		            <h5 class="ui-nowrap"><span class="word">积分</span><span class="line">|</span>购买即可获得<?php echo intval($info['shopPrice']*(float)WSTConf('CONF.moneyToScore')); ?>积分</h5>
		        </div>
		        <span class="icon">···</span>
		    </li>
		    <?php endif; ?>
		    <li id='j-promotion' style='display:none;'>
		        <div class="ui-list-info">
		            <h5 class="ui-nowrap">
		            	<div style="float: left;">
			            	<span class="word">促销</span>
			            	<span class="line">|</span>
			            </div>
		            	<?php echo hook('mobileDocumentGoodsPromotionDetail',['goods'=>$info]); ?>
		            </h5>
		        </div>
		    </li>
		    
		    <?php echo hook('mobileDocumentGoodsPropDetail'); ?>


		    <li style="display: none;">
		        <div class="ui-list-info">
		            <h5 class="ui-nowrap"><span class="word">优惠</span><span class="line">|</span></h5>
		        </div>
		        <span class="icon">···</span>
		    </li>
		    <?php if(!empty($info['attrs'])): ?>
		    <li onclick="javascript:dataShow();">
		        <div class="ui-list-info">
		            <h5 class="ui-nowrap">产品参数</h5>
		        </div>
		        <span class="icon">···</span>
		    </li>
		    <?php endif; ?>

		    <li onclick="javascript:pageSwitch($('#appr'),3);">
		        <div class="ui-list-info">
		            <h5 class="ui-nowrap">商品评价( <span class="red"><?php echo $info['appraiseNum']; ?></span> )</h5>
		        </div>
		        <span class="icon">···</span>
		    </li>
		</ul>
		<ul class="ui-list ui-list-one ui-list-link wst-go-shop">
		    <div class="info">
		    	<div class="img"><a><img src="/<?php echo WSTImg($info['shop']['shopImg'],3); ?>" title="<?php echo $info['shop']['shopName']; ?>"></a></div>
		    	<div class="name"><p class="ui-nowrap-flex name1"><?php echo $info['shop']['shopName']; ?></p><p class="ui-nowrap-flex name2"><span>主营: <?php echo $info['shop']['cat']; ?></span></p></div>
		    	<div class="wst-clear"></div>
		    </div>
		    <div class="ui-row-flex score">
			    <div class="ui-col ui-col" style="text-align:left;">商品评分: <span class="red"><?php echo $info['shop']['goodsScore']; ?></span></div><span class="line">|</span>
			    <div class="ui-col ui-col" style="text-align:center;">时效评分: <span class="red"><?php echo $info['shop']['timeScore']; ?></span></div><span class="line">|</span>
			    <div class="ui-col ui-col" style="text-align:right;">服务评分: <span class="red"><?php echo $info['shop']['serviceScore']; ?></span></div>
			</div>
			<div class="ui-row-flex button">
				<div class="ui-col ui-col"><a href="<?php echo url('mobile/shops/shopGoodsList',['shopId'=>$info['shop']['shopId']]); ?>" class="goods">全部商品</a></div>
			    <div class="ui-col ui-col"><a href="<?php echo url('mobile/shops/home',['shopId'=>$info['shop']['shopId']]); ?>" class="shop">进入店铺</a></div>
			</div>
		</ul>
		<div class="title gc-title" onclick="goConsult()">
			<span class='gc-tit-icon2'></span>
			购买咨询
		</div>
		<div class="gc-title-list">
		  <?php if(($info['consult']['consultContent'])): ?>
		  <li>
	        <div class="question-box cf">
	          <span class="question-pic"></span>
	          <div class="question-content">
	              <span><?php echo $info['consult']['consultContent']; ?></span>
	          </div>
	          <div class="wst-clear"></div>
	        </div>
	        <?php if(($info['consult']['reply'])): ?>
	        <div class="question-box cf">
	          <span class="question-pic answer-pic"></span>
	          <div class="question-content answer-content">
	            <span><?php echo $info['consult']['reply']; ?></span>
	          </div>
	          <div class="wst-clear"></div>
	        </div>
			<?php endif; ?>
	      </li>
	      <?php else: ?>
	      <p class="prompt">暂无商品咨询~</p>
	      <?php endif; ?>
		</div>
		
		<div class="wst-shl-ads">
	     	<div class="title">猜你喜欢</div>
	     	<?php $wstTagGoods =  model("common/Tags")->listGoods("guess",$info['shop']['catId'],6,0); foreach($wstTagGoods as $key=>$vo){?>
	     	<div class="wst-go-goods" onclick="javascript:WST.intoGoods(<?php echo $vo['goodsId']; ?>);">
	     		<div class="img j-imgAdapt">
	     			<a href="javascript:void(0);" onclick="javascript:WST.intoGoods(<?php echo $vo['goodsId']; ?>);"><img src="/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="/<?php echo WSTImg($vo['goodsImg'],3); ?>" title="<?php echo $vo['goodsName']; ?>"></a>
	     		</div>
	     		<p class="name ui-nowrap-multi"><?php echo $vo['goodsName']; ?></p>
	     		<div class="info"><span class="ui-nowrap-flex price">¥ <?php echo $vo['shopPrice']; ?></span></div>
	     	</div>
	     	<?php } ?>
		    <div class="wst-clear"></div>
	    </div>
	    <div class="wst-go-top" style="display: none;">上拉查看图文详情</div>
     </section>
    
    <section class="ui-container" id="goods2" style="display: none;">
    	<div class="wst-go-details"><?php echo $info['goodsDesc']; ?></div>
    </section>
    
    <input type="hidden" name="" value="<?php echo $info['goodsId']; ?>" id="goodsId" autocomplete="off">
    <input type="hidden" name="" value="" id="evaluateType" autocomplete="off">
    <input type="hidden" name="" value="" id="currPage" autocomplete="off">
    <input type="hidden" name="" value="" id="totalPage" autocomplete="off">
    <section class="ui-container" id="goods3" style="display: none;">
		<div class="ui-row-flex wst-ev-term">
		    <div class="ui-col ui-col active" onclick="javascript:evaluateSwitch(this,'');"><p>全部</p><p class="number"><?php echo $info['appraises']['sum']; ?></p></div>
		    <div class="ui-col ui-col" onclick="javascript:evaluateSwitch(this,'best');"><p>好评</p><p class="number"><?php echo $info['appraises']['best']; ?></p></div>
		    <div class="ui-col ui-col" onclick="javascript:evaluateSwitch(this,'good');"><p>中评</p><p class="number"><?php echo $info['appraises']['good']; ?></p></div>
		    <div class="ui-col ui-col" onclick="javascript:evaluateSwitch(this,'bad');"><p>差评</p><p class="number"><?php echo $info['appraises']['bad']; ?></p></div>
		    <div class="ui-col ui-col" onclick="javascript:evaluateSwitch(this,'pic');"><p>晒图</p><p class="number"><?php echo $info['appraises']['pic']; ?></p></div>
		</div>
    	<div id="evaluate-list" style="margin-top: 10px;"></div>
    </section>
<script id="list" type="text/html">
{{# if(d && d.length>0){ }}
{{# for(var i=0; i<d.length; i++){ }}
	<div class="ui-whitespace wst-go-evaluate">
		<div class="info">
			<p>
				<img src="{{ d[i].userPhoto }}" class="portrait">
				<span class="name">{{ d[i].loginName }}</span>
				{{# if(d[i].userTotalScore){ }}
            		<img src="/{{ d[i].userTotalScore }}" class="ranks">
            	{{# } }}
				<span class="time">{{ d[i].createTime }}</span>
				<div class="wst-clear"></div>
			</p>
        </div>
        <div class="content">
			<p>
            	{{# var score = (d[i].goodsScore+d[i].serviceScore+d[i].timeScore)/3; }}
				{{# for(var j=1; j<6; j++){ }}
					{{# if(j <= score.toFixed(0)){ }}
                	<i class="bright"></i>
            		{{# }else{ }}       
                	<i class="dark"></i>
               		{{# } }}
            	{{# } }}
			</p>
       		<p class="content2">{{ d[i].content }}</p>
				{{# if(d[i].images){ }}
					{{# for(var m=0; m<d[i].images.length; m++){ }}
                   		<img src="/{{ d[i].images[m] }}">
                	{{# } }}
            	{{# } }}
           	<p class="word">{{ d[i].goodsSpecNames }}</p>
            <div class="wst-clear"></div>
        </div>
		{{# if(d[i].shopReply){ }}
            <div class="reply"><p>卖家回复：{{ d[i].shopReply }}</p></div>
        {{# } }}
    </div>
{{# } }}
{{# }else{ }}
	<div class="wst-prompt-icon"><img src="__MOBILE__/img/nothing-evaluate.png"></div>
	<div class="wst-prompt-info">
		<p>对不起，没有相关评论。</p>
	</div>
{{# } }}
</script>
<?php else: ?>
	<div class="wst-prompt-icon"><img src="__MOBILE__/img/nothing-goods.png"></div>
	<div class="wst-prompt-info">
		<p>对不起，没有找到商品。</p>
	</div>
<?php endif; ?>



<div class="ui-dialog" id="wst-di-prompt">
    <div class="ui-dialog-cnt">
        <div class="ui-dialog-bd">
            <p id="wst-dialog" class="wst-dialog-t">提示</p>
            <p class="wst-dialog-l"></p>
            <button id="wst-event1" type="button" class="ui-btn-s wst-dialog-b1" data-role="button">取消</button>&nbsp;&nbsp;
            <button id="wst-event2" type="button" class="ui-btn-s wst-dialog-b2">确定</button>
        </div>
    </div>      
</div>

<div class="ui-dialog" id="wst-di-share" onclick="WST.dialogHide('share');">
     <div class="wst-prompt"></div>
</div><!-- 对话框模板 -->

<div class="wst-cover" id="cover"></div>

<?php if(!empty($info['attrs'])): ?>
<div class="wst-fr-box" id="frame">
	<div class="title"><span>产品参数</span><i class="ui-icon-close-page" onclick="javascript:dataHide();"></i><div class="wst-clear"></div></div>
	<div class="content" id="content">
		<?php if(is_array($info['attrs']) || $info['attrs'] instanceof \think\Collection || $info['attrs'] instanceof \think\Paginator): $i = 0; $__LIST__ = $info['attrs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$at): $mod = ($i % 2 );++$i;?>
			<?php echo $at['attrName']; ?>：<?php echo $at['attrVal']; ?><br/>
		<?php endforeach; endif; else: echo "" ;endif; ?>
	</div>
	<div class="determine"><button class="button" onclick="javascript:dataHide();">确定</button></div>
</div>
<?php endif; if(!(Request()->isSsl())){?>
<div class="wst-cart-box" id="frame-share" style="padding-top:10px;">
	<div class="content" id="nativeShare" style="padding-bottom:50px;">
		<div class="bshare-custom icon-medium-plus">
			<div class="ui-form-item ui-form-item-show" style="font-size:0.15rem;">    
				<a title="分享到QQ空间" class="bshare-qzone"><span style="margin-top:5px;"></span><span>分享到QQ空间</span></a>
			</div>
			<div class="ui-form-item ui-form-item-show" style="font-size:0.15rem;">    
				<a title="分享到QQ好友" class="bshare-qqim"><span style="margin-top:5px;"></span><span>分享到QQ好友</span></a>
			</div>
			<div class="ui-form-item ui-form-item-show" style="font-size:0.15rem;">
				<a title="分享到新浪微博" class="bshare-sinaminiblog"><span style="margin-top:5px;"></span><span>分享到新浪微博</span></a>
			</div>
			<div class="ui-form-item ui-form-item-show" style="font-size:0.15rem;">
				<a title="分享到腾讯微博" class="bshare-qqmb"><span style="margin-top:5px;"></span><span>分享到腾讯微博</span></a>
			</div>
			<div class="ui-form-item ui-form-item-show" style="font-size:0.15rem;">
				<a title="分享到人人网" class="bshare-renren"><span style="margin-top:5px;"></span><span>分享到人人网</span></a>
			</div>
		</div>
	</div>
	<div class="determine"><button class="button" onclick="javascript:shareHide();">取消</button></div>
</div>
<script type='text/javascript' src='__MOBILE__/js/share/nativeShare.js?v=<?php echo $v; ?>'></script>
<script>
	var config = {
		url: "<?php echo url('mobile/goods/detail','goodsId='.$info['goodsId'].'&shareUserId='.base64_encode(session('WST_USER.userId')),true,true); ?>",
		title:"<?php echo $info['goodsName']; ?>",
	  	desc:"<?php echo $info['goodsName']; ?>",
	  	img:"<?php echo WSTDomain(); ?>/<?php echo $info['goodsImg']; ?>"
	};
	var share_obj = new nativeShare('nativeShare',config);
</script>
<?php } ?>
<?php echo hook('mobileDocumentGoodsDetail',['goods'=>$info,'getParams'=>input()]); ?>


<div class="wst-cart-box" id="frame-cart">
	<div class="title">
     	<div class="picture"><div class="img"><a href="javascript:void(0);"><img src="/<?php echo WSTImg($info['goodsImg'],3); ?>" title="<?php echo $info['goodsName']; ?>" id="specImage"></a></div></div>
		<i class="ui-icon-close-page" onclick="javascript:cartHide();"></i>
		<p class="ui-nowrap-multi"><?php echo $info['goodsName']; ?></p>
		<p class="ui-nowrap-flex price"><span id="j-shop-price">¥<?php echo $info['shopPrice']; ?></span><span id="j-market-price" class="price2">¥<?php echo $info['marketPrice']; ?></span></p>
		<div class="wst-clear"></div>
	</div>
	<div class="standard" id="standard">
	<?php if(!empty($info['spec'])): if(is_array($info['spec']) || $info['spec'] instanceof \think\Collection || $info['spec'] instanceof \think\Paginator): $i = 0; $__LIST__ = $info['spec'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sp): $mod = ($i % 2 );++$i;?>
	<div class="spec">
		<p><?php echo $sp['name']; ?></p>
		<?php if(is_array($sp['list']) || $sp['list'] instanceof \think\Collection || $sp['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $sp['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sp2): $mod = ($i % 2 );++$i;if($sp2['itemImg']!=''): ?>
			<img class="j-option img" data-val="<?php echo $sp2['itemId']; ?>" data-image="/<?php echo WSTImg($sp2['itemImg'],3); ?>" src="/<?php echo WSTImg($sp2['itemImg'],3); ?>" title="<?php echo $sp2['itemName']; ?>">
		<?php else: ?>
			<span class="j-option" data-val="<?php echo $sp2['itemId']; ?>"><?php echo $sp2['itemName']; ?></span>
		<?php endif; endforeach; endif; else: echo "" ;endif; ?>
		<div class="wst-clear"></div>
	</div>
	<?php endforeach; endif; else: echo "" ;endif; endif; ?>
	<div class="number">
		<p>数量</p>
		<div class="stock">库存：<span id="goods-stock">0</span><?php echo $info['goodsUnit']; ?></div>
	  	<div class="wst-buy_l">
           <input class="wst-buy_l1" type="button" value="-" onclick='javascript:WST.changeIptNum(-1,"#buyNum")'><input id="buyNum" class="wst-buy_l2" data-min='1' data-max='' type="number" value="1" autocomplete="off" onkeyup='WST.changeIptNum(0,"#buyNum")'><input class="wst-buy_l3" type="button" value="+" onclick='javascript:WST.changeIptNum(1,"#buyNum")'>
      	</div>
		<div class="wst-clear"></div>
	</div>
	</div>
	<div class="determine"><button class="button" onclick="javascript:addCart();">确定</button></div>
</div>


<script>

var goodsInfo = {
	id:<?php echo $info['goodsId']; ?>,	
	isSpec:<?php echo $info['isSpec']; ?>,
	goodsStock:<?php echo $info['goodsStock']; ?>,
	marketPrice:<?php echo $info['marketPrice']; ?>,
	goodsPrice:<?php echo $info['shopPrice']; if(isset($info['saleSpec'])): ?>
	,sku:<?php echo json_encode($info['saleSpec']); endif; ?>
}

//弹框
function shareShow(){
	jQuery('#cover').attr("onclick","javascript:shareHide();").show();
	jQuery('#frame-share').animate({"bottom": 0}, 500);
}
function shareHide(){
	var cartHeight = parseInt($("#frame-cart").css('height'))+52+'px';
	jQuery('#frame-share').animate({'bottom': '-'+cartHeight}, 500);
	jQuery('#cover').hide();
}
function goConsult(){
	location.href=WST.U('mobile/goodsconsult/index',{goodsId:goodsInfo.id})
}
/************ 兼容safari *****************/
function isTouchDevice(){
    try{
        document.createEvent("TouchEvent");
        return true;
    }catch(e){
        return false;
    }
}
function touchScroll(id){
    if(isTouchDevice()){
        var el=document.getElementById(id);
        var scrollStartPos=0;

        document.getElementById(id).addEventListener("touchstart", function(event) {
            scrollStartPos=this.scrollTop+event.touches[0].pageY;
            // event.preventDefault();
        },false);

        document.getElementById(id).addEventListener("touchmove", function(event) {
            this.scrollTop=scrollStartPos-event.touches[0].pageY;
            // event.preventDefault();
        },false);
    }
}
touchScroll("standard");
</script>
<script type='text/javascript' src='__MOBILE__/js/swiper.jquery.min.js'></script>
<script type='text/javascript' src='__MOBILE__/js/jquery.min.js'></script>
<script type='text/javascript' src='__MOBILE__/js/goods_detail.js?v=<?php echo $v; ?>'></script>

</body>
</html>