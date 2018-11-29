<?php /*a:7:{s:71:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/goods_search.html";i:1536627217;s:63:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/base.html";i:1536627231;s:62:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/top.html";i:1536627233;s:75:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/self_shop_header.html";i:1536627232;s:65:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/header.html";i:1536627233;s:69:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/right_cart.html";i:1536627233;s:65:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/footer.html";i:1536627233;}*/ ?>
<!doctype html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>商品搜索 -<?php echo WSTConf('CONF.mallName'); ?><?php echo WSTConf('CONF.mallTitle'); ?></title>

<meta name="description" content="<?php echo WSTConf('CONF.seoMallDesc'); ?>，商品关键字搜索">
<meta name="Keywords" content="<?php echo $keyword; ?>">

<link href="__STYLE__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet">

<link href="__STYLE__/css/goodslist.css?v=<?php echo $v; ?>" rel="stylesheet">

<script type="text/javascript" src="/static/js/jquery.min.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="/static/plugins/layer/layer.js?v=<?php echo $v; ?>"></script>	
<script type='text/javascript' src='/static/js/common.js?v=<?php echo $v; ?>'></script>

<script type='text/javascript' src='__STYLE__/js/common.js?v=<?php echo $v; ?>'></script>


<?php if(((int)session('WST_USER.userId')<=0)): ?>
<link href="/static/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="__STYLE__/css/login.css?v=<?php echo $v; ?>" rel="stylesheet">
<script type="text/javascript" src="/static/plugins/validator/jquery.validator.min.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="/static/js/rsa.js"></script>
<script type='text/javascript' src='__STYLE__/js/login.js?v=<?php echo $v; ?>'></script>
<?php endif; ?>
<script>
window.conf = {"ROOT":"","APP":"","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","SMS_VERFY":"<?php echo WSTConf('CONF.smsVerfy'); ?>","SMS_OPEN":"<?php echo WSTConf('CONF.smsOpen'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>","IS_LOGIN":"<?php if((int)session('WST_USER.userId')>0): ?>1<?php else: ?>0<?php endif; ?>","TIME_TASK":"1","ROUTES":'<?php echo WSTRoute(); ?>',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","HTTP":"<?php echo WSTProtocol(); ?>","MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>"}
$(function() {
	WST.initVisitor();
});
</script>
</head>

<body>

	
<?php $wstTagAds =  model("common/Tags")->listAds("index-top-ads",99,86400); foreach($wstTagAds as $key=>$tads){if(($tads['adFile']!='')): ?>
<div class="index-top-ads">
  <a href="<?php echo $tads['adURL']; ?>" <?php if(($tads['isOpen'])): ?>target='_blank'<?php endif; if(($tads['adURL']!='')): ?>onclick="WST.recordClick(<?php echo $tads['adId']; ?>)"<?php endif; ?> onfocus="this.blur();">
    <img src="/<?php echo $tads['adFile']; ?>"></a>
  <a href="javascript:;" class="close-ads" onclick="WST.closeAds(this)"></a>
</div>
<?php endif; } ?>

<div class="wst-header">
    <div class="wst-nav">
		<ul class="headlf">
		<?php if(session('WST_USER.userId') >0): ?>
		   <li class="drop-info">
			  <div class="drop-infos">
			  <a href="<?php echo Url('home/users/index'); ?>">欢迎您，<?php echo session('WST_USER.userName')?session('WST_USER.userName'):session('WST_USER.loginName'); ?></a>
			  </div>
			  <div class="wst-tag dorpdown-user">
			  	<div class="wst-tagt">
			  	   <div class="userImg" >
				  	<img class='usersImg' data-original="<?php echo WSTUserPhoto(session('WST_USER.userPhoto')); ?>"/>
				   </div>	
				  <div class="wst-tagt-n">
				    <div>
					  	<span class="wst-tagt-na"><?php echo session('WST_USER.userName')?session('WST_USER.userName'):session('WST_USER.loginName'); ?></span>
					  	<?php if((int)session('WST_USER.rankId') > 0): ?>
					  		<img src="/<?php echo session('WST_USER.userrankImg'); ?>" title="<?php echo session('WST_USER.rankName'); ?>"/>
					  	<?php endif; ?>
				  	</div>
				  	<div class='wst-tags'>
			  	     <span class="w-lfloat"><a onclick='WST.position(15,0)' href='<?php echo Url("home/users/edit"); ?>'>用户资料</a></span>
			  	     <span class="w-lfloat" style="margin-left:10px;"><a onclick='WST.position(16,0)' href='<?php echo Url("home/users/security"); ?>'>安全设置</a></span>
			  	    </div>
				  </div>
			  	  <div class="wst-tagb" >
			  		<a onclick='WST.position(5,0)' href='<?php echo Url("home/orders/waitReceive"); ?>'>待收货订单</a>
			  		<a onclick='WST.position(60,0)' href='<?php echo Url("home/logmoneys/usermoneys"); ?>'>我的余额</a>
			  		<a onclick='WST.position(49,0)' href='<?php echo Url("home/messages/index"); ?>'>我的消息</a>
			  		<a onclick='WST.position(13,0)' href='<?php echo Url("home/userscores/index"); ?>'>我的积分</a>
			  		<a onclick='WST.position(41,0)' href='<?php echo Url("home/favorites/goods"); ?>'>我的关注</a>
			  		<a style='display:none'>咨询回复</a>
			  	  </div>
			  	<div class="wst-clear"></div>
			  	</div>
			  </div>
			</li>
			<li class="spacer">|</li>
			<li class="drop-info">
			<a href='<?php echo Url("home/messages/index"); ?>' target='_blank' onclick='WST.position(49,0)'>消息（<span id='wst-user-messages'>0</span>）</a>
			</li>
			<li class="spacer">|</li>
			<li class="drop-info">
			  <div><a href="javascript:WST.logout();">退出</a></div>
			</li>
			<?php else: ?>
			<li class="drop-info">
			  <div>欢迎来到<?php echo WSTMSubstr(WSTConf('CONF.mallName'),0,13); ?><a href="<?php echo Url('home/users/login'); ?>" onclick="WST.currentUrl();">&nbsp;&nbsp;请&nbsp;登录</a></div>
			</li>
			<li class="spacer">|</li>
			<li class="drop-info">
			  <div><a href="<?php echo Url('home/users/regist'); ?>" onclick="WST.currentUrl();">免费注册</a></div>
			</li>
			<?php endif; ?>
		</ul>
		<ul class="headrf" style='float:right;'>
		    <li class="j-dorpdown" style="width: 86px;">
				<div class="drop-down" style="padding-left:0px;">
					<a href="<?php echo Url('home/users/index'); ?>" target="_blank">我的订单<i class="di-right"><s>◇</s></i></a>
				</div>
				<div class='j-dorpdown-layer order-list'>
				   <div><a href='<?php echo Url("home/orders/waitPay"); ?>' onclick='WST.position(3,0)'>待付款订单</a></div>
				   <div><a href='<?php echo Url("home/orders/waitReceive"); ?>' onclick='WST.position(5,0)'>待发货订单</a></div>
				   <div><a href='<?php echo Url("home/orders/waitAppraise"); ?>' onclick='WST.position(6,0)'>待评价订单</a></div>
				</div>
			</li>	
			<?php if((WSTDatas('ADS_TYPE',4))): ?>
			<li class="spacer">|</li>
			<li class="j-dorpdown">
				<div class="drop-down drop-down2 pdr5"><i class="di-left"></i><a href="#" target="_blank">手机商城</a></div>
				<div class='j-dorpdown-layer sweep-list'>
				   <div class="qrcodea">
					   <div id='qrcodea' class="qrcodeal"></div>
					   <div class="qrcodear">
					   	<p>扫描二维码</p><span>下载手机客户端</span>
					   	<br/>
					   	<a >Android</a>
					   	<br/>
					   	<a>iPhone</a>
					   </div>
				   </div>
				</div>
			</li>
			<?php endif; if((WSTConf('CONF.wxenabled')==1)): ?>
			<li class="spacer">|</li>
			<li class="j-dorpdown" style="width:78px;">
				<div class="drop-down" style="padding:0 5px;"><a href="#" target="_blank">关注我们</a></div>
				<div class='j-dorpdown-layer des-list' style="width:120px;">
					<div style="height:114px;"><?php if((WSTConf('CONF.wxAppLogo'))): ?><img src="/<?php echo WSTConf('CONF.wxAppLogo'); ?>" style="height:114px;"><?php endif; ?></div>
					<div>关注我们</div>
				</div>
			</li>
			<?php endif; ?>
			<li class="spacer">|</li>
			<li class="j-dorpdown">
				<div class="drop-down drop-down4 pdr5"><a href="#" target="_blank">我的收藏</a></div>
				<div class='j-dorpdown-layer foucs-list'>
				   <div><a href="<?php echo Url('home/favorites/goods'); ?>" onclick='WST.position(41,0)'>商品收藏</a></div>
				   <div><a href="<?php echo Url('home/favorites/shops'); ?>" onclick='WST.position(46,0)'>店铺收藏</a></div>
				</div>
			</li>
			<li class="spacer">|</li>
			<li class="j-dorpdown">
				<div class="drop-down drop-down5 pdr5" ><a href="#" target="_blank">客户服务</a></div>
				<div class='j-dorpdown-layer des-list'>
				   <div><a href='<?php echo Url("home/helpcenter/view","id=1"); ?>' target='_blank'>帮助中心</a></div>
				   <div><a href='<?php echo Url("home/helpcenter/view","id=8"); ?>' target='_blank'>售后服务</a></div>
				   <div><a href='<?php echo Url("home/helpcenter/view","id=3"); ?>' target='_blank'>常见问题</a></div>
				    <?php echo hook('homeDocumentContact',['type'=>'shopService']); ?>
				</div>
			</li>
			<li class="spacer">|</li>
			<?php if(session('WST_USER.userId') > 0): if(session('WST_USER.userType') == 0 or !session('WST_USER.shopId')): ?>
				<li class="j-dorpdown">
				<div class="drop-down pdl5" ><a href="#" target="_blank">商家管理<i class="di-right"><s>◇</s></i></a></div>
				<div class='j-dorpdown-layer foucs-list'>
				   <div><a href="<?php echo url('home/shops/login'); ?>" onclick="WST.currentUrl();">商家登录</a></div>
				   <div><a href="<?php echo url('home/shops/join'); ?>" rel="nofollow" onclick="WST.currentUrl('<?php echo url("home/shops/join"); ?>');">商家入驻</a></div>
				</div>
				</li>
				<?php else: 
               		$shopMenuUrls = model('home/HomeMenus')->getShopMenusUrl();
               		$roleId = (int)session('WST_USER.roleId');
				 ?>
				<li class="j-dorpdown">
				    <div class="drop-down pdl5" >
				       <a href="<?php echo Url('home/shops/index'); ?>" rel="nofollow" target="_blank">卖家中心<i class="di-right"><s>◇</s></i></a>
				    </div>
				    <div class='j-dorpdown-layer product-list last-menu'>
						<?php if($roleId==0 || in_array('home/orders/waitdelivery',$shopMenuUrls)): ?>
					   		<div><a href='<?php echo Url("home/orders/waitdelivery"); ?>' onclick='WST.position(24,1)'>待发货订单</a></div>
					   	<?php endif; if($roleId==0 || in_array('home/ordercomplains/shopcomplain',$shopMenuUrls)): ?>
					   		<div><a href='<?php echo Url("home/ordercomplains/shopcomplain"); ?>' onclick='WST.position(25,1)'>投诉订单</a></div>
					   	<?php endif; if($roleId==0 || in_array('home/goods/sale',$shopMenuUrls)): ?>
				   			<div><a href='<?php echo Url("home/goods/sale"); ?>' onclick='WST.position(32,1)'>商品管理</a></div>
				   		<?php endif; if($roleId==0 || in_array('home/shopcats/index',$shopMenuUrls)): ?>
				   			<div><a href='<?php echo Url("home/shopcats/index"); ?>' onclick='WST.position(30,1)'>商品分类</a></div>
				   		<?php endif; ?>
					</div>
				</li>
				<?php endif; else: ?>
				<li class="j-dorpdown">
				<div class="drop-down pdl5" ><a href="#" target="_blank">商家管理<i class="di-right"><s>◇</s></i></a></div>
				<div class='j-dorpdown-layer foucs-list'>
				   <div><a href="<?php echo url('home/shops/login'); ?>" onclick="WST.currentUrl();">商家登录</a></div>
				   <div><a href="<?php echo url('home/shops/join'); ?>" rel="nofollow" onclick="WST.currentUrl('<?php echo url("home/shops/join"); ?>');">商家入驻</a></div>
				</div>
				</li>
				
			<?php endif; ?>
			</li>
		</ul>
		<div class="wst-clear"></div>
  </div>
</div>
<script>
$(function(){
	//二维码
	//参数1表示图像大小，取值范围1-10；参数2表示质量，取值范围'L','M','Q','H'
	var a = qrcode(8, 'H');
	var url = window.location.host+window.conf.APP;
	a.addData(url);
	a.make();
	$('#qrcodea').html(a.createImgTag());
});
function goShop(id){
  location.href=WST.U('home/shops/home','shopId='+id);
}
</script>
<script type='text/javascript' src='__STYLE__/js/qrcode.js'></script>


	<?php if(isset($selfShop)): ?>
		<div class='wst-search-container'>
   
    <div class="wst-shop-h">
    <div class="wst-shop-img"><a href="<?php echo url('home/shops/home',array('shopId'=>$data['shop']['shopId'])); ?>"><img class="shopsImg" data-original="/<?php echo $data['shop']['shopImg']; ?>" title="<?php echo $data['shop']['shopName']; ?>"></a></div>
    <div class="wst-shop-info">
      <p><?php echo $data['shop']['shopName']; ?>
        
        <?php echo hook('homeDocumentContact',['type'=>'shopHome','shopId'=>$data['shop']['shopId']]); ?>
      </p>
      <div class="wst-shop-info2">
      <?php if(is_array($data['shop']['accreds']) || $data['shop']['accreds'] instanceof \think\Collection || $data['shop']['accreds'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['shop']['accreds'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ac): $mod = ($i % 2 );++$i;?>
      <img src="/<?php echo $ac['accredImg']; ?>"><span><?php echo $ac['accredName']; ?></span>
      <?php endforeach; endif; else: echo "" ;endif; if(($data['shop']['shopQQ'])): ?>
      <a href="tencent://message/?uin=<?php echo $data['shop']['shopQQ']; ?>&Site=QQ交谈&Menu=yes">
        <img border="0" style="width:65px;height:24px;" src="<?php echo WSTProtocol(); ?>wpa.qq.com/pa?p=1:<?php echo $data['shop']['shopQQ']; ?>:7">
      </a>
      <?php endif; if(($data['shop']['shopWangWang'])): ?>
      <a href="<?php echo WSTProtocol(); ?>www.taobao.com/webww/ww.php?ver=3&touid=<?php echo $data['shop']['shopWangWang']; ?>&siteid=cntaobao&status=1&charset=utf-8" target="_blank">
      <img border="0" src="<?php echo WSTProtocol(); ?>amos.alicdn.com/realonline.aw?v=2&uid=<?php echo $data['shop']['shopWangWang']; ?>&site=cntaobao&s=1&charset=utf-8" alt="和我联系" class='wangwang'/>
      </a>
      <?php endif; ?>
      </div>
      <div class="wst-shop-info3">
        <span class="wst-shop-eva">商品评价：<span class="wst-shop-red"><?php echo $data['shop']['scores']['goodsScore']; ?></span></span>
        <span class="wst-shop-eva">时效评价：<span class="wst-shop-red"><?php echo $data['shop']['scores']['timeScore']; ?></span></span>
        <span class="wst-shop-eva">服务评价：<span class="wst-shop-red"><?php echo $data['shop']['scores']['serviceScore']; ?></span></span>
        <?php if(($data['shop']['favShop']>0)): ?>
        <a href='javascript:void(0);' onclick='cancelFavorite(this,1,<?php echo $data['shop']['shopId']; ?>,<?php echo $data['shop']['favShop']; ?>)' class="wst-shop-evaa j-fav">已关注</a>
        <?php else: ?>
        <a href='javascript:void(0);' onclick='addFavorite(this,1,<?php echo $data['shop']['shopId']; ?>,<?php echo $data['shop']['favShop']; ?>)' class="wst-shop-evaa j-fav2">关注店铺</a>
        <?php endif; if(($data['shop']['longitude'] && $data['shop']['latitude'])): ?>
        <a href='javascript:void(0);' onclick='javascript:init();' class="wst-shop-evaa  wst-shop-location j-fav3">店铺位置</a>
        <?php endif; ?>
        <span class="wst-shop-eva">用手机逛本店  &nbsp;&nbsp;|</span>
        <a class="wst-shop-code"><span class="wst-shop-codes hide"><div id='qrcode' style='width:142px;height:142px;'></div></span></a>
        
      </div>
    </div>
    <div class="wst-shop-sea">
      <input type="text" id="goodsName" value="<?php echo $goodsName; ?>" placeholder="输入商品名称">
      <a class="search" href="javascript:void(0);" onclick="javascript:WST.goodsSearch($('#goodsName').val());">搜全站</a>
      <a class="search" href="javascript:void(0);" onclick="javascript:searchShopsGoods(0);">搜本店</a>
      <div class="wst-shop-word">
      <?php if(is_array($data['shop']['shopHotWords']) || $data['shop']['shopHotWords'] instanceof \think\Collection || $data['shop']['shopHotWords'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['shop']['shopHotWords'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$shw): $mod = ($i % 2 );++$i;?>
      <a href='<?php echo Url("home/shops/home",array('shopId'=>$data['shop']['shopId'],'goodsName'=>$shw)); ?>'><?php echo $shw; ?></a><?php if($i< count($data['shop']['shopHotWords'])): ?>&nbsp;|&nbsp;<?php endif; endforeach; endif; else: echo "" ;endif; ?>
      </div>
      <?php if(!(Request()->isSsl())){?>
      	<div style="clear: both;"></div>
      	<div class="bshare-custom icon-medium-plus">
          <a title="分享到QQ空间" class="bshare-qzone"></a>
          <a title="分享到新浪微博" class="bshare-sinaminiblog"></a>
          <a title="分享到QQ好友" class="bshare-qqim"></a>
          <a title="分享到腾讯微博" class="bshare-qqmb"></a>
          <a title="分享到微信" class="bshare-weixin"></a>
        </div>
        <script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/buttonLite.js#style=-1&pophcol=2&lang=zh"></script>
        <script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/bshareC0.js"></script>
    <?php } ?>
		</div>
		<div style="clear: both;"></div>
		</div>
      <?php echo hook('homeDocumentShopHomeHeader',['shop'=>$data['shop'],'getParams'=>input()]); ?>
    </div>
    <div class="wst-clear"></div>
    </div>
</div>
  <?php if(($data['shop']['shopBanner'])): ?><image class="wst-shop-tu" src="/<?php echo $data['shop']['shopBanner']; ?>"></image><?php endif; ?>
<div class="wst-clear"></div>
<div class="s-wst-nav-menus">
      <div id="s-wst-nav-items">
           <ul>
               <li class="s-nav-li s-cat-head"style="background-color:#DF2003"><a href="<?php echo Url('home/shops/home',['shopId'=>$data['shop']['shopId']]); ?>" target="_blank" ><em></em>本店商品分类</a></li>
               <?php if(is_array($data['shopcats']) || $data['shopcats'] instanceof \think\Collection || $data['shopcats'] instanceof \think\Paginator): $l = 0;$__LIST__ = is_array($data['shopcats']) ? array_slice($data['shopcats'],0,6, true) : $data['shopcats']->slice(0,6, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cat1): $mod = ($l % 2 );++$l;?>
               <li class="s-nav-li">
                    <a href="<?php echo url('home/shops/home',['shopId'=>$data['shop']['shopId'],'ct1'=>$cat1['catId']]); ?>" target="_blank"><?php echo $cat1['catName']; ?></a>
               </li>
               <?php endforeach; endif; else: echo "" ;endif; ?>
               <li class="s-nav-li"> <a class="homepage" href="<?php echo url('/'); ?>" target="_blank">返回商城首页</a></li>
           </ul>
      </div>
      
      <span class="wst-clear"></span>
    </div>
</div>
<div class="wst-clear"></div>
<script>
    $(document).keypress(function(e) { 
          if(e.which == 13) {  
            searchShopsGoods();  
          }
    }); 
</script>
	<?php else: ?>
		<div class='wst-search-container'>
   <div class='wst-logo'>
   <a href='<?php echo app('request')->root(true); ?>' title="<?php echo WSTConf('CONF.mallName'); ?>" >
      <img src="http://www.weaxue.cn/upload/sysconfigs/2018-09/5b94cceec874b.png" height="120" width='240' title="<?php echo WSTConf('CONF.mallName'); ?>" alt="<?php echo WSTConf('CONF.mallName'); ?>">
   </a>
   </div>
   <div class="wst-search-box">
      <div class='wst-search'>
      	  <input type="hidden" id="search-type" value="<?php echo isset($keytype)?1:0; ?>"/>
          <ul class="j-search-box">
        	  <li class="j-search-type">
              搜<span><?php if(isset($keytype)): ?>店铺<?php else: ?>商品<?php endif; ?></span>&nbsp;<i class="arrow"> </i>
            </li>
        	  <li class="j-type-list">
        	  <?php if(isset($keytype)): ?>
              <div data="0">商品</div>
              <?php else: ?>
        	  <div data="1">店铺</div>
              <?php endif; ?>
        	  </li>
          </ul>
	      <input type="text" id='search-ipt' class='search-ipt' placeholder='<?php echo WSTConf("CONF.adsGoodsWordsSearch"); ?>' value='<?php echo isset($keyword)?$keyword:""; ?>'/>
	      <input type='hidden' id='adsGoodsWordsSearch' value='<?php echo WSTConf("CONF.adsGoodsWordsSearch"); ?>'>
	      <input type='hidden' id='adsShopWordsSearch' value='<?php echo WSTConf("CONF.adsShopWordsSearch"); ?>'>
	      <div id='search-btn' class="search-btn" onclick='javascript:WST.search(this.value)'>搜索</div>
      </div>
      <div class="wst-search-keys">
      <?php $searchKeys = WSTSearchKeys(); if(is_array($searchKeys) || $searchKeys instanceof \think\Collection || $searchKeys instanceof \think\Paginator): $i = 0; $__LIST__ = $searchKeys;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
       <a href='<?php echo Url("home/goods/search","keyword=".$vo); ?>'><?php echo $vo; ?></a>
       <?php if($i< count($searchKeys)): ?>&nbsp;&nbsp;|&nbsp;&nbsp;<?php endif; endforeach; endif; else: echo "" ;endif; ?>
      </div>
   </div>
   <div class="wst-cart-box">
   <a href="<?php echo url('home/carts/index'); ?>" target="_blank" onclick="WST.currentUrl('<?php echo url("home/carts/index"); ?>');"><span class="word j-word">我的购物车<span class="num" id="goodsTotalNum">0</span></span></a>
   	<div class="wst-cart-boxs hide">
   		<div id="list-carts"></div>
   		<div id="list-carts2"></div>
   		<div id="list-carts3"></div>
	   	<div class="wst-clear"></div>
   	</div>
   </div>

<script id="list-cart" type="text/html">
{{# for(var i = 0; i < d.list.length; i++){ }}
	<div class="goods" id="j-goods{{ d.list[i].cartId }}">
	   	<div class="imgs"><a href="{{ WST.U('home/goods/detail','goodsId='+d.list[i].goodsId) }}"><img class="goodsImgc" data-original="/{{ d.list[i].goodsImg }}" title="{{ d.list[i].goodsName }}"></a></div>
	   	<div class="number"><p><a  href="{{ WST.U('home/goods/detail','goodsId='+d.list[i].goodsId) }}">{{WST.cutStr(d.list[i].goodsName,26)}}</a></p><p>数量：{{ d.list[i].cartNum }}</p></div>
	   	<div class="price"><p>￥{{ d.list[i].shopPrice }}</p><span><a href="javascript:WST.delCheckCart({{ d.list[i].cartId }})">删除</a></span></div>
	</div>
{{# } }}
</script>
</div>
<div class="wst-clear"></div>

<div class="wst-nav-menus">
   <div class="nav-w" style="position: relative;">
      <div class="w-spacer"></div>
      <div class="dorpdown <?php if(isset($hideCategory)): ?>j-index<?php endif; ?>" id="wst-categorys">
         <div class="dt j-cate-dt">
             <a href="javascript:void(0)">全部商品分类</a>
         </div>
         <div class="dd j-cate-dd" <?php if(!isset($hideCategory)): ?>style="display:none" <?php endif; ?>>
            <div class="dd-inner">
                 <?php $_result=WSTSideCategorys();if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $k = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                 <div id="cat-icon-<?php echo $k; ?>" class="item fore1 <?php if(($key>=12)): ?>over-cat<?php endif; ?>">
                     <h3>
                      <div class="<?php if(($key>=12)): ?> over-cat-icon <?php else: ?> cat-icon-<?php echo $k; endif; ?>"></div>
                      <a href="<?php echo Url('home/goods/lists','cat='.$vo['catId']); ?>" target="_blank"><?php echo $vo['catName']; ?></a>
                     </h3> 
                 </div>
                 <?php endforeach; endif; else: echo "" ;endif; ?>
             </div>
             <div style="display: none;" class="dorpdown-layer" id="index_menus_sub">
                 <?php $_result=WSTSideCategorys();if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $k = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                  <div class="item-sub" i="<?php echo $k; ?>">
                      <div class="item-brands">
                           <div class="brands-inner">
                            <?php $wstTagBrand =  model("common/Tags")->listBrand($vo['catId'],6,86400); foreach($wstTagBrand as $key=>$bvo){?>
                              <a target="_blank" class="img-link" href="<?php echo url('home/goods/lists',['cat'=>$bvo['catId'],'brand'=>$bvo['brandId']]); ?>">
                                  <img width="83" height="65" class='categeMenuImg' data-original="/<?php echo $bvo['brandImg']; ?>">
                              </a>
                            <?php } ?>
                            <div class="wst-clear"></div>
                            </div>
                            <div class='shop-inner'>
                            <?php $wstTagShop =  model("common/Tags")->listShop($vo['catId'],4,86400); foreach($wstTagShop as $key=>$bvo){?>
                              <a target="_blank" class="img-link" href="<?php echo url('home/shops/home',['shopId'=>$bvo['shopId']]); ?>">
                                  <img width="83" height="65" class='categeMenuImg' data-original="/<?php echo $bvo['shopImg']; ?>">
                              </a>
                            <?php } ?>
                            <div class="wst-clear"></div>
                            </div>
                       </div>

                       <div class="subitems">
                          <?php if(isset($vo['list'])){ if(is_array($vo['list']) || $vo['list'] instanceof \think\Collection || $vo['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
                           <dl class="fore2">
                               <dd>
                                  <a 
                                    class="cat2_tit"
                                    target="_blank" 
                                    href="<?php echo Url('home/goods/lists','cat='.$vo2['catId']); ?>">
                                    <?php echo $vo2['catName']; ?>
                                    <i>&gt;</i>
                                  </a>
                                  <?php if(isset($vo2['list'])){ if(is_array($vo2['list']) || $vo2['list'] instanceof \think\Collection || $vo2['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo2['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo3): $mod = ($i % 2 );++$i;?>
                                  <a target="_blank" href="<?php echo Url('home/goods/lists','cat='.$vo3['catId']); ?>"><?php echo $vo3['catName']; ?></a>
                                  <?php endforeach; endif; else: echo "" ;endif; } ?>
                                  <div class="wst-clear"></div>
                               </dd>
                            </dl>
                           <?php endforeach; endif; else: echo "" ;endif; } ?>
                        </div>
                  </div>
                  <?php endforeach; endif; else: echo "" ;endif; ?>
             </div>
        </div>
      </div>
      
      <div id="wst-nav-items">
           <ul>
               <?php $_result=WSTNavigations(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
               <li class="fore1">
                    <a href="<?php echo $vo['navUrl']; ?>" <?php if($vo['isOpen']==1): ?>target="_blank"<?php endif; ?>><?php echo $vo['navTitle']; ?></a>
               </li>
               <?php endforeach; endif; else: echo "" ;endif; ?>
           </ul>
      </div>
      <script>
          $(document).keypress(function(e) { 
          if(e.which == 13) {  
            $('#search-btn').click();  
          }
        }); 
      </script>
</div>
<div class="wst-clear"></div>

	<?php endif; ?>



<div class='wst-hot-sales'>
   <div class='wst-sales-logo'>
   		&nbsp;<span class="wst-hot-icon">热卖推荐</span>
   </div>
   <ul class='wst-sales-box'>
     <?php $wstTagGoods =  model("common/Tags")->listGoods("recom",0,4,0); foreach($wstTagGoods as $key=>$vo){?>
      <li class="item">
     	<div class="img"><a target='_blank' href='<?php echo Url("home/goods/detail","goodsId=".$vo["goodsId"]); ?>'><img class='goodsImg' data-original="/<?php echo WSTImg($vo['goodsImg']); ?>" title="<?php echo $vo['goodsName']; ?>" alt="<?php echo $vo['goodsName']; ?>" /></a></div>
     	<div class="des">
     		<div class="p-sale">已售<?php echo $vo['saleNum']; ?>件</div>
     		<div class="p-name"><a target='_blank' href='<?php echo Url("home/goods/detail","goodsId=".$vo["goodsId"]); ?>'><?php echo $vo['goodsName']; ?></a></div>
     		<div class="p-price">￥<?php echo $vo['shopPrice']; ?></div>
     		<div class="p-buy"><a href="javascript:WST.addCart(<?php echo $vo['goodsId']; ?>)">加入购物车</a></div>
     	</div>
      </li>
     <?php } ?>
   </ul>
</div>
<input type="hidden" id="keyword" class="sipt" value='<?php echo $keyword; ?>'/>
<input type="hidden" id="orderBy" class="sipt" value='<?php echo $orderBy; ?>'/>
<input type="hidden" id="order" class="sipt" value='<?php echo $order; ?>'/>
<input type="hidden" id="areaId" class="sipt" value='<?php echo $areaId; ?>'/>
<div class='wst-filters'>
   <div class='item' style="border-left:2px solid #df2003;padding-left: 8px;">
      <a class='link' href=''>全部结果</a>
      <i class="arrow">></i>
   </div>
   <div class='wst-lfloat keyword'><?php echo $keyword; ?></div>
   <div class='wst-clear'></div>
</div>

<div class="wst-container">
   <?php if(!empty($goodsPage["data"])): ?>
	<div class='goods-side'>
		<div class="guess-like">
		    <div class="title">猜你喜欢</div>
			<?php $wstTagGoods =  model("common/Tags")->listGoods("guess",0,3,0); foreach($wstTagGoods as $key=>$vo){?>
			<div class="item">
				<div class="img"><a target='_blank' href="<?php echo Url('home/goods/detail','goodsId='.$vo['goodsId']); ?>"><img class='goodsImg' data-original="/<?php echo WSTImg($vo['goodsImg']); ?>" alt="<?php echo $vo['goodsName']; ?>" title="<?php echo $vo['goodsName']; ?>"/></a></div>
				<div class="p-name"><a class="wst-hide wst-redlink"><?php echo $vo['goodsName']; ?></a></div>
				<div class="p-price">￥<?php echo $vo['shopPrice']; ?><span class="v-price">￥<?php echo $vo['marketPrice']; ?></span></div>
			</div>
			<?php } ?>
		</div>
		<div class="hot-goods">
			<div class="title">热销商品</div>
			<?php $wstTagGoods =  model("common/Tags")->listGoods("hot",0,3,0); foreach($wstTagGoods as $key=>$vo){?>
			<div class="item">
				<div class="img"><a target='_blank' href="<?php echo Url('home/goods/detail','goodsId='.$vo['goodsId']); ?>"><img class='goodsImg' data-original="/<?php echo WSTImg($vo['goodsImg']); ?>" alt="<?php echo $vo['goodsName']; ?>" title="<?php echo $vo['goodsName']; ?>"/></a></div>
				<div class="p-name"><a class="wst-hide wst-redlink"><?php echo $vo['goodsName']; ?></a></div>
				<div class="p-price">￥<?php echo $vo['shopPrice']; ?><span class="v-price">￥<?php echo $vo['marketPrice']; ?></span></div>
			</div>
			<?php } ?>
		</div>
	</div>
	<div class='goods-main'>
	   <div class='goods-filters'>
	   	  <div class='line'>
	   	  <div class='wst-lfloat chk'>发货地</div>
	        <div class='city wst-address'>
		    <div class='item dorpdown'>
		     <div class='drop-down'>
		        <a class='link' href=''>
		        	<?php if(empty($areaInfo['areaName'])): ?>
		        	请选择
		        	<?php else: ?>
		        		<?php echo $areaInfo['areaName']; endif; ?>
		        </a>
		        <i class="drop-down-arrow"></i>
		     </div>
		     <div class="dorp-down-layer">
		     	<div class="tab-header">
		     	 <ul class="tab">
		     	 	<li class="tab-item1" id="fl_1_1" onclick="gpanelOver(this);" c="1" >
		     	 		<?php if(isset($areaInfo)): ?>
		     	 		<a href='javascript:void(0)'><?php echo $areaInfo[0]['areaName']; ?></a>
		     	 		<?php else: ?>
		     	 		<a href='javascript:void(0)'>请选择</a>
		     	 		<?php endif; ?>
		     	 	</li>

		     	 	<?php if(isset($areaInfo)): ?>
		     	 	<li class="tab-item1" id="fl_1_2" onclick="gpanelOver(this);" c="1" >
						<a href="javascript:void(0)"><?php echo $areaInfo[1]['areaName']; ?></a>
					</li>
					<li class="tab-item1 j-tab-selected1" id="fl_1_3" onclick="gpanelOver(this);" c="1" >
						<a href="javascript:void(0)"><?php echo $areaInfo[2]['areaName']; ?></a>
					</li>
					<?php else: ?>
					<li class="tab-item1" id="fl_1_2" onclick="gpanelOver(this);" c="1" pid="" >
						<a href="javascript:void(0)">请选择</a>
					</li>
					<li class="tab-item1 j-tab-selected1" id="fl_1_3" onclick="gpanelOver(this);" c="1" pid="" >
						<a href="javascript:void(0)">请选择</a>
					</li>
					<?php endif; ?>


					
		     	 </ul>
		     	</div>
		     	 <ul class="area-box" id="fl_1_1_pl" style="display:none;">
		     	 	<?php if(is_array($area1) || $area1 instanceof \think\Collection || $area1 instanceof \think\Paginator): $i = 0; $__LIST__ = $area1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$area1): $mod = ($i % 2 );++$i;?>
					<li onclick="choiceArea(this,<?php echo $area1['areaId']; ?>)" search='1'><a href="javascript:void(0)"><?php echo $area1['areaName']; ?></a></li>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
				<ul class="area-box" id="fl_1_2_pl" style="display:none;">
					<?php if(is_array($area2) || $area2 instanceof \think\Collection || $area2 instanceof \think\Paginator): $i = 0; $__LIST__ = $area2;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$area2): $mod = ($i % 2 );++$i;?>
					<li onclick="choiceArea(this,<?php echo $area2['areaId']; ?>)" search='1'><a href="javascript:void(0)"><?php echo $area2['areaName']; ?></a></li>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>

				<ul class="area-box" id="fl_1_3_pl">
					<?php if(is_array($area3) || $area3 instanceof \think\Collection || $area3 instanceof \think\Paginator): $i = 0; $__LIST__ = $area3;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$area3): $mod = ($i % 2 );++$i;?>
					<li onclick="choiceArea(this,<?php echo $area3['areaId']; ?>)" search='1'><a href="javascript:void(0)"><?php echo $area3['areaName']; ?></a></li>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>

			</div>


			</div>
			</div>
	        <div class='chk'>
			 <div class="checkbox-box-s">
		     <input name='isStock' value='1' class="sipt wst-checkbox-s" onclick="searchFilter(this,4)" type='checkbox' id="stock" <?php if($isStock==1): ?>checked<?php endif; ?>/>
		     <label for="stock"></label>
		     </div>
	                  仅显示有货</div>
	        <div class='chk'>
	         <div class="checkbox-box-s">
		     <input name='isNew' value='1' class="sipt wst-checkbox-s" onclick="searchFilter(this,4)" type='checkbox' id="new" <?php if($isNew==1): ?>checked<?php endif; ?>/>
		     <label for="new"></label>
		     </div>
	                  新品</div>
	        <div class='chk'>
	         <div class="checkbox-box-s">
		     <input name='isFreeShipping' value='1' class="sipt wst-checkbox-s" onclick="searchFilter(this,4)" type='checkbox' id="freeShipping" <?php if($isFreeShipping==1): ?>checked<?php endif; ?>/>
		     <label for="freeShipping"></label>
		     </div>
	                  包邮</div>
	      </div>
	      <div class='line line2'>
	        <a class="<?php if($orderBy == 0): ?>curr <?php endif; ?>" href='javascript:void(0)' onclick='javascript:searchOrder(0)'>销量<span class="<?php if($orderBy != 0): ?>store<?php endif; if($orderBy == 0 and $order == 1): ?>store2<?php endif; if($orderBy == 0 and $order == 0): ?>store3<?php endif; ?>"></span></a>
	        <a class="<?php echo $orderBy==1 ? 'curr' : ''; ?>" href='javascript:void(0)' onclick='javascript:searchOrder(1)'>价格<span class="<?php if($orderBy != 1): ?>store<?php endif; if($orderBy == 1 and $order == 1): ?>store2<?php endif; if($orderBy == 1 and $order == 0): ?>store3<?php endif; ?>"></span></a>
	        <a class="<?php echo $orderBy==2 ? 'curr' : ''; ?>" href='javascript:void(0)' onclick='javascript:searchOrder(2)'>评论数<span class="<?php if($orderBy != 2): ?>store<?php endif; if($orderBy == 2 and $order == 1): ?>store2<?php endif; if($orderBy == 2 and $order == 0): ?>store3<?php endif; ?>"></span></a>
	        <a class="<?php echo $orderBy==3 ? 'curr' : ''; ?>" href='javascript:void(0)' onclick='javascript:searchOrder(3)'>人气<span class="<?php if($orderBy != 3): ?>store<?php endif; if($orderBy == 3 and $order == 1): ?>store2<?php endif; if($orderBy == 3 and $order == 0): ?>store3<?php endif; ?>"></span></a>
	        <a class="<?php echo $orderBy==4 ? 'curr' : ''; ?>" href='javascript:void(0)' onclick='javascript:searchOrder(4)'>上架时间<span class="<?php if($orderBy != 4): ?>store<?php endif; if($orderBy == 4 and $order == 1): ?>store2<?php endif; if($orderBy == 4 and $order == 0): ?>store3<?php endif; ?>"></span></a>
        	<div class="wst-price-ipts">
			<span class="wst-price-ipt1">￥</span><span class="wst-price-ipt2">￥</span>
			<input type="text" class="sipt wst-price-ipt" id="sprice" value="<?php echo $sprice; ?>" style="margin-left:8px;" onkeypress='return WST.isNumberdoteKey(event);' onkeyup="javascript:WST.isChinese(this,1)">
			- <input type="text" class="sipt wst-price-ipt" id="eprice" value="<?php echo $eprice; ?>" onkeypress='return WST.isNumberKey(event);' onkeyup="javascript:WST.isChinese(this,1)">
			</div>
			<button class="wst-price-but" type="submit" style="width:60px;height: 25px;" onclick='javascript:searchOrder()'>确定</button>
	      </div>
	   </div>
	   <div class="goods-list">
	      <?php if(is_array($goodsPage["data"]) || $goodsPage["data"] instanceof \think\Collection || $goodsPage["data"] instanceof \think\Paginator): $i = 0; $__LIST__ = $goodsPage["data"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	      <div class="goods">
	      	<div class="img">
	      		<a target='_blank' href="<?php echo Url('home/goods/detail','goodsId='.$vo['goodsId']); ?>"><img title="<?php echo $vo['goodsName']; ?>" alt="<?php echo $vo['goodsName']; ?>" class='goodsImg2' data-original="/<?php echo WSTImg($vo['goodsImg']); ?>"/></a>
	      	</div>
	      	<?php 
				$img_listarr = explode(',',$vo['gallery']);
				array_unshift($img_listarr,$vo['goodsImg']);
		     ?>
	      	<ul class="img_list">
	      		<?php if(is_array($img_listarr) || $img_listarr instanceof \think\Collection || $img_listarr instanceof \think\Paginator): $ils_k = 0; $__LIST__ = $img_listarr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ils): $mod = ($ils_k % 2 );++$ils_k;if(($ils!='')): ?>
	      			<li class="<?php if(($ils_k==1)): ?>curr<?php endif; ?>"><img title="<?php echo $vo['goodsName']; ?>" alt="<?php echo $vo['goodsName']; ?>" class='goodsImg2' data-original="/<?php echo WSTImg($ils); ?>"/></li>
	      		<?php endif; endforeach; endif; else: echo "" ;endif; ?>
	      		<div class="wst-clear"></div>
	      	</ul>
	      	<div class="p-name">
	      		<a target='_blank' href="<?php echo Url('home/goods/detail','goodsId='.$vo['goodsId']); ?>" class="wst-redlink" title="<?php echo $vo['goodsName']; ?>"><?php echo $vo['goodsName']; ?></a></div>
	      	<div>
	      		<div class="p-price">￥<?php echo $vo['shopPrice']; ?></div>
	      		<div class="p-hsale">
	      			<div class="sale-num">成交数：<span class="wst-fred"><?php echo $vo['saleNum']; ?></span></div>
		      		<a class="p-add-cart" style="display:none;" href="javascript:WST.addCart(<?php echo $vo['goodsId']; ?>);">加入购物车</a>
	      		</div>
	      		<div class='wst-clear'></div>
	      	</div>
	      	<div>
	      		<div class="p-mprice">市场价<span>￥<?php echo $vo['marketPrice']; ?></span></div>
	      		<div class="p-appraise">已有<span class="wst-fred"><?php echo $vo['appraiseNum']; ?></span>人评价</div>
	      		<div class='wst-clear'></div>
	      	</div>
	      	<div class="p-shop">
		      	<a href="<?php echo Url('home/shops/home','shopId='.$vo['shopId']); ?>" target='_blank' class="wst-redlink"><?php echo $vo['shopName']; ?></a>
		      	<a class="contrast" href="javascript:void(0);" onclick="javascript:contrastGoods(1,<?php echo $vo['goodsId']; ?>,1)"><i></i>对比</a>
	      	</div>
	      </div>
	      
	      <?php endforeach; endif; else: echo "" ;endif; ?>
	     <div class='wst-clear'></div>
	   	</div>
	   	<div style="width:980px;">
	  		<div id="wst-pager"></div>
		</div>
		
	</div>
	<div class='wst-clear'></div>
	<?php else: ?>
	<div class="wst-no-goods">很抱歉，没有找到“<span><?php echo $keyword; ?></span>”为关键字的商品搜索结果。</div>
	
	<div class="wst-recommend">
		<div class="title">为您推荐<span style="float: right;"></span></div>
		<div class="tgoods-list">
	      <?php $wstTagGoods =  model("common/Tags")->listGoods("best",0,5,0); foreach($wstTagGoods as $key=>$rvo){?>
	      <div class="goods">
	      	<div class="img"><a target='_blank' href="<?php echo Url('home/goods/detail','goodsId='.$rvo['goodsId']); ?>"><img title="<?php echo $rvo['goodsName']; ?>" alt="<?php echo $rvo['goodsName']; ?>" class='goodsImg' data-original="/<?php echo $rvo['goodsImg']; ?>"/></a></div>
	      	<div class="p-name"><a target='_blank' class="wst-redlink"><?php echo $rvo['goodsName']; ?></a></div>
	      	<div>
	      		<div class="p-price">￥<?php echo $rvo['shopPrice']; ?></div>
	      		<div class="p-hsale">
	      			<div class="sale-num">成交数：<span class="wst-fred"><?php echo $rvo['saleNum']; ?></span></div>
		      		<a class="p-add-cart" style="display:none;" href="javascript:WST.addCart(<?php echo $rvo['goodsId']; ?>);">加入购物车</a>
	      		</div>
	      		<div class='wst-clear'></div>
	      	</div>
	      	<div>
	      		<div class="p-mprice">市场价<span>￥<?php echo $rvo['marketPrice']; ?></span></div>
	      		<div class="p-appraise">已有<span class="wst-fred"><?php echo $rvo['appraiseNum']; ?></span>人评价</div>
	      		<div class='wst-clear'></div>
	      	</div>
	      	<div class="p-shop"><a href="" class="wst-redlink"><?php echo $rvo['shopName']; ?></a></div>
	      </div>
	      <?php } ?>
	     <div class='wst-clear'></div>
	   	</div>
	</div>
	<?php endif; if(cookie("history_goods")!=''): ?>
	<div class="wst-gview">
		<div class="title">您最近浏览的商品</div>
		<div class="view-goods">
	       <?php $wstTagGoods =  model("common/Tags")->listGoods("history",0,7,0); foreach($wstTagGoods as $key=>$vo){?>
			<div class="item">
				<div class="img"><a target='_blank' href="<?php echo Url('home/goods/detail','goodsId='.$vo['goodsId']); ?>"><img class='goodsImg' data-original="/<?php echo WSTImg($vo['goodsImg']); ?>" alt="<?php echo $vo['goodsName']; ?>" title="<?php echo $vo['goodsName']; ?>"/></a></div>
				<div class="p-name"><a class="wst-hide wst-redlink" href="<?php echo Url('home/goods/detail','id='.$vo['goodsId']); ?>"><?php echo $vo['goodsName']; ?></a></div>
				<div class="p-price">￥<?php echo $vo['shopPrice']; ?><span class="v-price">￥<?php echo $vo['marketPrice']; ?></span></div>
			</div>
	       <?php } ?>
	     	<div class='wst-clear'></div>
	   	</div>
	</div>
	<?php endif; ?>
</div>

<div class="wst-cont-frame" id="j-cont-frame">
	<div class="head"><span>对比栏</span><a href="javascript:void(0);" class="close" onclick="javascript:contrastGoods(0,0,0)">关闭</a></div>
	<div class="list">
		<div class="goods" id="contrastList"></div>
		<div class="term-contrast">
			<p><a class="contrast" href="<?php echo Url('home/goods/contrast'); ?>" target="_blank">对比</a></p>
			<p><a href="javascript:void(0);" onclick="javascript:contrastDels(0)" class="empty">清空</a></p>
		</div>
	</div>
	<script id="colist" type="text/html">
		{{# if(d.data && d.data.length>0){ }}
		{{# for(var i=0; i<d.data.length; i++){ }}
		<div class="term">
			<div class="img"><a href="{{WST.U('home/goods/detail','goodsId='+d.data[i].goodsId)}}" target="_blank"><img class="contImg" data-original="/{{ d.data[i].goodsImg }}" title="{{ d.data[i].goodsName }}" width="50" height="50"></a></div>
			<div class="info"><a href="{{WST.U('home/goods/detail','goodsId='+d.data[i].goodsId)}}" target="_blank"><p class="name">{{ d.data[i].goodsName }}</p></a><p class="price"><span>￥{{ d.data[i].shopPrice }}</span><a href="javascript:contrastDels({{ d.data[i].goodsId }});" >删除</a></p></div>
		</div>
		{{# } }}
		{{# } }}
		{{# var data = (d.data)?d.data.length:0 }}
		{{# for(var i=data+1; i<5; i++){ }}
		<div class="term-empty">
			<div class="img">{{ i }}</div>
			<div class="info"><p>您还可以继续添加</p></div>
		</div>
		{{# } }}
    </script>
</div>
<link href="__STYLE__/css/right_cart.css?v=<?php echo $v; ?>" rel="stylesheet">
<div class="j-global-toolbar">
	<div class="toolbar-wrap j-wrap" >
		<div class="toolbar">
			<div class="toolbar-panels j-panel">
				<div style="visibility: hidden;" class="j-content toolbar-panel tbar-panel-cart toolbar-animate-out">
					<h3 class="tbar-panel-header j-panel-header">
						<a href="" class="title"><i></i><em class="title">购物车</em></a>
						<span class="close-panel j-close" title='关闭'></span>
					</h3>
					<div class="tbar-panel-main" >
						<div class="tbar-panel-content j-panel-content">
						    <?php if(session('WST_USER.userId') == 0): ?>
							<div id="j-cart-tips" class="tbar-tipbox hide">
								<div class="tip-inner">
									<span class="tip-text">还没有登录，登录后商品将被保存</span>
									<a href="#none" onclick='WST.loginWindow()' class="tip-btn j-login">登录</a>
								</div>
							</div>
							<?php endif; ?>
							<div id="j-cart-render">
								<div id='cart-panel' class="tbar-cart-list"></div>
								  <script id="list-rightcart" type="text/html">
								  {{#
                                    var shop,goods,specs;
                                    for(var key in d){
                                        shop = d[key];
					                    for(var i=0;i<shop.list.length;i++){
                                           goods = shop.list[i];
						                   goods.goodsImg = WST.conf.ROOT+"/"+goods.goodsImg.replace('.','_thumb.');
						                   specs = '';
						                   if(goods.specNames && goods.specNames.length>0){
							                  for(var j=0;j<goods.specNames.length;j++){
								                 specs += goods.specNames[j].itemName+ " ";
							                  }
						                   }
                                   }}
								   <div class="tbar-cart-item" id="shop-cart-{{shop.shopId}}">
							          <div class="jtc-item-promo">
							            <div class="promo-text">{{shop.shopName}}</div>
							          </div>
								      <div class="jtc-item-goods j-goods-item-{{goods.cartId}}" dataval="{{goods.cartId}}">
								          <div class='wst-lfloat'>
			                                 <input type='checkbox' id='rcart_{{goods.cartId}}' class='rchk' onclick='javascript:checkRightChks({{goods.cartId}},this);' {{# if(goods.isCheck==1){}}checked{{# } }}/></div>
									      <span class="p-img"><a target="_blank" href="{{WST.U('home/goods/detail','goodsId='+goods.goodsId)}}" target="_blank"><img src="{{goods.goodsImg}}" title="{{goods.goodsName}}" height="50" width="50"></a></span>
									      <div class="p-name">
									          <a target="_blank" title="{{(goods.goodsName+((specs!='')?"【"+specs+"】":""))}}" href="{{WST.U('home/goods/detail','goodsId='+goods.goodsId)}}">{{WST.cutStr(goods.goodsName,22)}}<br/>{{specs}}</a>
									      </div>
									      <div class="p-price">
									          <strong>¥<span id='gprice_{{goods.cartId}}'>{{goods.shopPrice}}</span></strong> 
									          <div class="wst-rfloat">
									             <a href="#none" class="buy-btn" id="buy-reduce_{{goods.cartId}}" onclick="javascript:WST.changeIptNum(-1,'#buyNum','#buy-reduce,#buy-add','{{goods.cartId}}','statRightCartMoney')">-</a>
									             <input type="text" id="buyNum_{{goods.cartId}}" class="right-cart-buy-num" value="{{goods.cartNum}}" data-max="{{goods.goodsStock}}" data-min="1" onkeyup="WST.changeIptNum(0,'#buyNum','#buy-reduce,#buy-add',{{goods.cartId}},'statRightCartMoney')" autocomplete="off"  onkeypress="return WST.isNumberKey(event);" maxlength="6"/>
									             <a href="#none" class="buy-btn" id="buy-add_{{goods.cartId}}" onclick="javascript:WST.changeIptNum(1,'#buyNum','#buy-reduce,#buy-add','{{goods.cartId}}','statRightCartMoney')">+</a>
									          </div>
									     </div>
									      <span onclick="javascript:delRightCart(this,{{goods.cartId}});" dataid="{{shop.shopId}}|{{goods.cartId}}" class="goods-remove" title="删除"></span>
									 </div>
								 </div>    
								 {{# } } }} 
                                 </script>   	
							</div>
						</div>
					</div>
					<div class="tbar-panel-footer j-panel-footer">
						<div class="tbar-checkout">
							<div class="jtc-number">已选<strong id="j-goods-count">0</strong>件商品 </div>
							<div class="jtc-sum"> 共计：¥<strong id="j-goods-total-money">0</strong> </div>
							<a class="jtc-btn j-btn" href="#none" onclick='javascript:jumpSettlement()'>去结算</a>
						</div>
					</div>
				</div>

				<div style="visibility: hidden;" data-name="follow" class="j-content toolbar-panel tbar-panel-follow">
					<h3 class="tbar-panel-header j-panel-header">
						<a href="#" target="_blank" class="title"> <i></i> <em class="title">我的关注</em> </a>
						<span class="close-panel j-close" title='关闭'></span>
					</h3>
					<div class="tbar-panel-main">
						<div class="tbar-panel-content j-panel-content">
							<div class="tbar-tipbox2">
								<div class="tip-inner"> <i class="i-loading"></i> </div>
							</div>
						</div>
					</div>
					<div class="tbar-panel-footer j-panel-footer"></div>
				</div>
				<div style="visibility: hidden;" class="j-content toolbar-panel tbar-panel-history toolbar-animate-in">
					<h3 class="tbar-panel-header j-panel-header">
						<a href="#none" class="title"> <i></i> <em class="title">我的足迹</em> </a>
						<span class="close-panel j-close" title='关闭'></span>
					</h3>
					<div class="tbar-panel-main">
						<div class="tbar-panel-content j-panel-content">
							<div class="jt-history-wrap">
								<ul id='history-goods-panel'></ul>
								<script id="list-history-goods" type="text/html">
								{{# 
                                 for(var i = 0; i < d.length; i++){ 
                                  d[i].goodsImg = WST.conf.ROOT+"/"+d[i].goodsImg.replace('.','_thumb.');
                                 }}
								   <li class="jth-item">
										<a target='_blank' href="{{WST.U('home/goods/detail','goodsId='+d[i].goodsId)}}" class="img-wrap"><img src="{{d[i].goodsImg}}" height="100" width="100"> </a>
										<a class="add-cart-button" href="javascript:WST.addCart({{d[i].goodsId}});">加入购物车</a>
										<a href="#none" class="price">￥{{d[i].shopPrice}}</a>
									</li>
								{{# } }}
                                </script>
							</div>
						</div>
					</div>
					<div class="tbar-panel-footer j-panel-footer"></div>
				</div>
			</div>
			
			<div class="toolbar-header"></div>
			
			<div class="toolbar-tabs j-tab">
				
				<div class="toolbar-tab tbar-tab-cart">
					<i class="tab-ico"></i>
					<em class="tab-text">购物车</em>
					<span class="tab-sub j-cart-count hide"></span>
				</div>
				<div class="toolbar-tab tbar-tab-follow" style='display:none'>
					<i class="tab-ico"></i>
					<em class="tab-text">我的关注</em>
					<span class="tab-sub j-count hide">0</span> 
				</div>
				<div class=" toolbar-tab tbar-tab-history ">
					<i class="tab-ico"></i>
					<em class="tab-text">我的足迹</em>
					<span class="tab-sub j-count hide"></span>
				</div>
				<div class="toolbar-tab tbar-tab-message">
				  <a target='_blank' href='<?php echo Url("home/messages/index"); ?>' onclick='WST.position(49,0)'>
					<i class="tab-ico"></i>
					<em class="tab-text">我的消息</em>
					<span class="tab-sub j-message-count hide"></span> 
				  </a>
				</div>
			</div>
			
			<div class="toolbar-footer">
				<div class="toolbar-tab tbar-tab-top"> <a href="#"> <i class="tab-ico  "></i> <em class="footer-tab-text">顶部</em> </a> </div>
				<div class=" toolbar-tab tbar-tab-feedback"  style='display:none'> <a href="#" target="_blank"> <i class="tab-ico"></i> <em class="footer-tab-text ">反馈</em> </a> </div>
			</div>
			<div class="toolbar-mini"></div>
		</div>
		<div id="j-toolbar-load-hook"></div>		
	</div>
</div>
<script type='text/javascript' src='__STYLE__/js/right_cart.js?v=<?php echo $v; ?>'></script>


	<div style="border-top: 1px solid #df2003;padding-bottom:25px;margin-top:35px;min-width:1200px;"></div>
<ul class="wst-footer-info">
	<li><div class="wst-footer-info-img wst-fimg1"></div>
		<div class="wst-footer-info-text">
			<h1>支付宝支付</h1>
			<p>支付宝签约商家</p>
		</div>
	</li>
	<li><div class="wst-footer-info-img wst-fimg2"></div>
		<div class="wst-footer-info-text">
			<h1>正品保证</h1>
			<p>100%原产地</p>
		</div>
	</li>
	<li><div class="wst-footer-info-img wst-fimg3"></div>
		<div class="wst-footer-info-text">
			<h1>退货无忧</h1>
			<p>七天退货保障</p>
		</div>
	</li>
	<li><div class="wst-footer-info-img wst-fimg4"></div>
		<div class="wst-footer-info-text">
			<h1>免费配送</h1>
			<p>满98元包邮</p>
		</div>
	</li>
	<li><div class="wst-footer-info-img wst-fimg5"></div>
		<div class="wst-footer-info-text">
			<h1>货到付款</h1>
			<p>400城市送货上门</p>
		</div>
	</li>
</ul>
<div class="wst-footer-help">
	<div class="wst-footer">
		<div class="wst-footer-hp-ck1">
			<?php $_result=WSTHelps(5,6);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?>
			<div class="wst-footer-wz-ca">
				<div class="wst-footer-wz-pt">
					<span class="wst-footer-wz-pn"><?php echo $vo1["catName"]; ?></span>
					<ul style='margin-left:25px;'>
						<?php if(is_array($vo1['articlecats']) || $vo1['articlecats'] instanceof \think\Collection || $vo1['articlecats'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo1['articlecats'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
						<li style='list-style:disc;color:#999;font-size:12px;'>
						<a href="<?php echo Url('Home/Helpcenter/view',array('id'=>$vo2['articleId'])); ?>"><?php echo WSTMSubstr($vo2['articleTitle'],0,8); ?></a>
						</li>
						<?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
				</div>
			</div>
			<?php endforeach; endif; else: echo "" ;endif; ?>

			<div class="wst-contact">
				<ul>
					<li style="height:30px;">
						<div class="icon-phone"></div><p class="call-wst">服务热线：</p>
					</li>
					<li style="height:38px;">
						<?php if((WSTConf('CONF.serviceTel')!='')): ?><p class="email-wst"><?php echo WSTConf('CONF.serviceTel'); ?></p><?php endif; ?>
					</li>
					<li style="height:85px;">
						<div class="qr-code" style="position:relative;">
						    <?php if((WSTConf('CONF.wxenabled')==1) && WSTConf('CONF.wxAppLogo')): ?>
							<img src="/<?php echo WSTConf('CONF.wxAppLogo'); ?>" style="height:110px;">
							<?php endif; ?>
							<div class="focus-wst">
							    <?php if((WSTConf('CONF.serviceQQ')!='')): ?>
								<p class="focus-wst-qr">在线客服：</p>
								<p class="focus-wst-qra">
						          <a href="tencent://message/?uin=<?php echo WSTConf('CONF.serviceQQ'); ?>&Site=QQ交谈&Menu=yes">
									  <img border="0" src="<?php echo WSTProtocol(); ?>wpa.qq.com/pa?p=1:<?php echo WSTConf('CONF.serviceQQ'); ?>:7" alt="QQ交谈" width="71" height="24" />
								  </a>
								</p>
          						<?php endif; if((WSTConf('CONF.serviceEmail')!='')): ?>
								<p class="focus-wst-qr">商城邮箱：</p>
								<p class="focus-wst-qre"><?php echo WSTConf('CONF.serviceEmail'); ?></p>
								<?php endif; ?>
							</div>
						</div>
					</li>
				</ul>
			</div>


			<div class="wst-clear"></div>
		</div>
		<div class="wst-footer-flink">

		</div>
	    <div class="wst-footer-hp-ck3">
	        <div class="links">
	           <?php $navs = WSTNavigations(1); if(is_array($navs) || $navs instanceof \think\Collection || $navs instanceof \think\Paginator): $i = 0; $__LIST__ = $navs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
               <a href="<?php echo $vo['navUrl']; ?>" <?php if($vo['isOpen']==1): ?>target="_blank"<?php endif; ?>><?php echo $vo['navTitle']; ?></a>
               <?php if($i< count($navs)): ?>&nbsp;&nbsp;|&nbsp;&nbsp;<?php endif; endforeach; endif; else: echo "" ;endif; ?>
	        </div>
	        <div class="copyright">
	        <?php 
	        	if(WSTConf('CONF.mallFooter')!=''){
	         		echo htmlspecialchars_decode(WSTConf('CONF.mallFooter'));
	        	}
	         
				if(WSTConf('CONF.visitStatistics')!=''){
					echo htmlspecialchars_decode(WSTConf('CONF.visitStatistics'))."<br/>";
			    }
			 ?>
	        
	        </div>
	    </div>
	</div>
</div>
<?php echo hook('homeDocumentListener'); ?>
<?php echo hook('initCronHook'); ?>


<script type='text/javascript' src='__STYLE__/js/goodslist.js?v=<?php echo $v; ?>'></script>
<?php if(!empty($goodsPage["data"])): ?>
<script type='text/javascript'>
$(function(){
	<?php if(!isset($areaInfo)): ?>
	$('#fl_1_1').click();
	<?php endif; ?>
	contrastGoods(1,0,2);
})
laypage({
    cont: 'wst-pager',
    pages: <?php echo $goodsPage["last_page"]; ?>, //总页数
    skip: true, //是否开启跳页
    skin: '#e23e3d',
    groups: 3, //连续显示分页数
    curr: function(){ //通过url获取当前页，也可以同上（pages）方式获取
        var page = location.search.match(/page=(\d+)/);
        return page ? page[1] : 1;
    }(), 
    jump: function(e, first){ //触发分页后的回调
        if(!first){ //一定要加此判断，否则初始时会无限刷新
        	var nuewurl = WST.splitURL("page");
        	var ulist = nuewurl.split("?");
        	if(ulist.length>1){
        		location.href = nuewurl+'&page='+e.curr;
        	}else{
        		location.href = '?page='+e.curr;
        	}
            
        }
    }
});
</script>
<?php endif; ?>

</body>
</html>