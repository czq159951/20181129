<?php /*a:7:{s:71:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/goods_detail.html";i:1536627233;s:63:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/base.html";i:1536627231;s:62:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/top.html";i:1536627233;s:75:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/self_shop_header.html";i:1536627232;s:65:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/header.html";i:1536627233;s:69:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/right_cart.html";i:1536627233;s:65:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/footer.html";i:1536653987;}*/ ?>
<!doctype html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $goods['goodsName']; ?> - <?php echo WSTConf('CONF.mallName'); ?><?php echo WSTConf('CONF.mallTitle'); ?></title>

<meta name="description" content="<?php echo $goods['goodsName']; ?>">
<meta name="Keywords" content="<?php echo $goods['goodsSeoKeywords']; ?>">

<link href="__STYLE__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet">

<link href="__STYLE__/css/goods.css?v=<?php echo $v; ?>" rel="stylesheet">

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


<div class='wst-w' style='margin-bottom:0px'>
<div class='wst-filters'>
   <div class='item' style="border:1px solid transparent;border-left:2px solid #df2003;padding-left: 8px">
      <a class='link' href="<?php echo url('home/index/index'); ?>">首页</a>
      <i class="arrow"></i>
   </div>
   <?php $_result=WSTPathGoodsCat($goods['goodsCatId']);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
   <div class='wst-lfloat'>
    <div class='item dorpdown'>
     <div class='drop-down'>
        <a class='link' href='<?php echo Url("home/goods/lists",["cat"=>$vo["catId"]]); ?>'><?php echo $vo['catName']; ?></a>
     </div>
     <div class="dorp-down-layer">
         <?php $_result=WSTGoodsCats($vo['parentId']);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
         <div class="<?php echo $vo['parentId']>0 ? 'cat2' : 'cat1'; ?>"><a href='<?php echo Url("home/goods/lists","cat=".$vo2["catId"]); ?>'><?php echo $vo2['catName']; ?></a></div>
         <?php endforeach; endif; else: echo "" ;endif; ?>
     </div>
  </div>
  <i class="arrow"></i>
   </div>
   <?php endforeach; endif; else: echo "" ;endif; ?>
   <div class='wst-clear'></div>
</div>
</div>
<div class='wst-w'>
   <div class='wst-container' style='width: 1198px;border:1px solid #e6e6e6;'>
      <div class='goods-img-box'>
          <div class="goods-img spec-preview" id="preview">
            <img title="<?php echo $goods['goodsName']; ?>" alt="<?php echo $goods['goodsName']; ?>" src="/<?php echo WSTImg($goods['goodsImg']); ?>" class="cloudzoom" data-cloudzoom="zoomImage:'/<?php echo $goods['goodsImg']; ?>'" height="350" width="350">
          </div>
          <div class="goods-pics">
            <a class="prev">&lt;</a>
            <a class="next">&gt;</a>
            <div class="items">
               <ul>
               <?php if(is_array($goods['gallery']) || $goods['gallery'] instanceof \think\Collection || $goods['gallery'] instanceof \think\Paginator): $i = 0; $__LIST__ = $goods['gallery'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                   <li><img title="<?php echo $goods['goodsName']; ?>" alt="<?php echo $goods['goodsName']; ?>" class='cloudzoom-gallery' src="/<?php echo WSTImg($vo); ?>" data-cloudzoom="useZoom: '.cloudzoom', image:'/<?php echo WSTImg($vo); ?>', zoomImage:'/<?php echo $vo; ?>' " width="60" height="60"></li>
               <?php endforeach; endif; else: echo "" ;endif; ?>
         </ul>  
      </div>
      <div class="wst-clear"></div>
         </div>
         <div class="goods-term-box">
            <div class="wst-favorite">
               <?php if(($goods['favGood']>0)): ?>
                 <a href='javascript:void(0);' onclick='WST.cancelFavorite(this,0,<?php echo $goods["goodsId"]; ?>,<?php echo $goods['favGood']; ?>)' class='favorite j-fav'>已关注</a>
               <?php else: ?>
                 <a href='javascript:void(0);' onclick='WST.addFavorite(this,0,<?php echo $goods["goodsId"]; ?>,<?php echo $goods["goodsId"]; ?>)' class='favorite j-fav2 j-fav3'>关注商品</a>
               <?php endif; ?>
               </div>
           <div class="wst-contrast" onclick="javascript:contrastGoods(1,<?php echo $goods['goodsId']; ?>,1)"><i></i>对比</div>
           <a  href='javascript:informs(<?php echo $goods["goodsId"]; ?>)' class="j-inform">举报</a>
           <div class="wst-clear"></div>
         </div>
      </div>
      <div class='intro'>
          <div class='intro-name'>
          <h2><?php echo $goods['goodsName']; ?></h2> 
          <span class='tips'><?php echo $goods['goodsTips']; ?></span>  
          </div>    
          <div class='summary'>
            <div class="infol">
             <div class='item'>
               <div class='dt'>市 场 价&nbsp;</div>
               <div class='dd market-price' id='j-market-price'>￥<?php echo $goods['marketPrice']; ?></div>
             </div>
             <div class='item'>
               <div class='dt'>价&nbsp;&nbsp;格&nbsp;</div>
               <div class='dd price'>￥<span id='j-shop-price'><?php echo $goods['shopPrice']; ?></span></div>
             </div>
             <div class='goods-intro-bg'>
               <div class='item'>
                 <ul class="ginfo_b">
                   <li>
                     <div class='dt'>销&nbsp;&nbsp;量&nbsp;</div>
                     <span class='appraise-num'><?php echo $goods['saleNum']; ?></span>
                   </li>
                   <li>
                     <div class='dt'>累计评价&nbsp;</div>
                     <span class='appraise-num'><?php echo $goods['appraiseNum']; ?></span>
                   </li>
                   <li>
                     <div class='dt'>商品评分&nbsp;</div>
                     <div class='dd'>
                        <?php $__FOR_START_1265063044__=0;$__FOR_END_1265063044__=$goods['scores']['totalScores'];for($i=$__FOR_START_1265063044__;$i < $__FOR_END_1265063044__;$i+=1){ ?>
                          <img src="/static/plugins/raty/img/star-on.png">
                        <?php } $__FOR_START_162860477__=1;$__FOR_END_162860477__=6-$goods['scores']['totalScores'];for($i=$__FOR_START_162860477__;$i < $__FOR_END_162860477__;$i+=1){ ?>
                          <img src="/static/plugins/raty/img/star-off.png">
                        <?php } ?>
                     </div>
                   </li>
                   <div class="wst-clear"></div>
                 </ul>
               </div>
             </div>
             </div>             
             <div class='wst-clear'></div>
          </div>
          
          <div class="sale_box">
            
             <div class='item' id='j-promotion' style='display:none'>
               <div class='dt'>促&nbsp;&nbsp;销&nbsp;</div>
               <div class='dd'>
                 <?php echo hook('homeDocumentGoodsPromotionDetail',['goods'=>$goods]); ?>
               </div>
             </div>
             <?php echo hook('homeDocumentGoodsPropDetail',['goods'=>$goods,'getParams'=>input()]); ?>
          </div>

          <div class='spec'>
             <?php if(!empty($goods['spec'])): if(is_array($goods['spec']) || $goods['spec'] instanceof \think\Collection || $goods['spec'] instanceof \think\Paginator): $i = 0; $__LIST__ = $goods['spec'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
             <div class='item'>
               <div class='dt'><?php echo $vo['name']; ?>&nbsp;</div>
               <div class='dd'>
               <?php if(is_array($vo['list']) || $vo['list'] instanceof \think\Collection || $vo['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;if($vo2['itemImg']!=''): ?>
                  <div class='j-option img' data-val="<?php echo $vo2['itemId']; ?>" style='width:28px;height:28px;padding:0px;'><img class="cloudzoom-gallery" width="28" height="28" src="/<?php echo WSTImg($vo2['itemImg']); ?>" data-cloudzoom="useZoom: '.cloudzoom', image:'/<?php echo WSTImg($vo2['itemImg']); ?>', zoomImage:'/<?php echo $vo2['itemImg']; ?>' "  title="<?php echo $vo2['itemName']; ?>" alt="<?php echo $vo2['itemName']; ?>"/><i></i></div>
                  <?php else: ?>
                  <div class='j-option' data-val="<?php echo $vo2['itemId']; ?>"><?php echo $vo2['itemName']; ?><i></i></div>
                  <?php endif; endforeach; endif; else: echo "" ;endif; ?>
               </div>
               <div class='wst-clear'></div>
             </div>
             <?php endforeach; endif; else: echo "" ;endif; endif; ?>
          </div>
          <div class='buy'>
             <div class='item'>
                <div class='dt'>数&nbsp;&nbsp;量&nbsp;</div>
                <div class='dd'>
                  <a href='#none' class='buy-btn' id='buy-reduce' style='color:#ccc;' onclick='javascript:WST.changeIptNum(-1,"#buyNum","#buy-reduce,#buy-add")'>-</a>
                  <input type='text' id='buyNum' class='buy-num' value='1' data-min='1' autocomplete="off" onkeyup='WST.changeIptNum(0,"#buyNum","#buy-reduce,#buy-add")' onkeypress="return WST.isNumberKey(event);" maxlength="6"/>
                  <a href='#none' class='buy-btn' id='buy-add' onclick='javascript:WST.changeIptNum(1,"#buyNum","#buy-reduce,#buy-add")'>+</a>
                    &nbsp; &nbsp;（库存：<span id='goods-stock'>0</span>&nbsp;<?php echo $goods['goodsUnit']; ?>）
                </div>
             </div>
             <div class='item'>
                <div class='dt'>服&nbsp;&nbsp;务&nbsp;</div>
                <div class='dd'>服务由  <a class="c14_005" href='<?php echo Url("home/shops/home","shopId=".$shop["shopId"]); ?>' target='_blank'><?php echo $shop['shopName']; ?></a> 发货并提供 售后服务。</div>
             </div>
             <div class='item' style='padding-left:95px;margin-top:20px;'>
               <?php if($goods['read']): if($goods['goodsType']==0): ?>
                 <a id='addBtn' href='javascript:void(0);' class='addBtn un-buy' >加入购物车</a>
                 <?php endif; ?>
                 <a id='buyBtn' href='javascript:void(0);' class='buyBtn un-buy'>立即购买</a>
               <?php else: if($goods['goodsType']==0): ?>
                 <a id='addBtn' href='javascript:addCart(0,"#buyNum")' class='addBtn' >加入购物车</a>
                 <?php endif; ?>
                 <a id='buyBtn' href='javascript:addCart(1,"#buyNum")' class='buyBtn'>立即购买</a>
               <?php endif; ?>
              <div class='wst-clear'></div>
            </div>
       
            <div class="wst-relative" style="margin-top: 20px;margin-left: 10px;">
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
              <?php echo hook('homeDocumentGoodsDetail',['goods'=>$goods,'getParams'=>input()]); ?>
              </div>
            </div>
      </div>

      <div class='seeing'>
         <div class='head'>看了又看</div>
         <div class='body'>
          <?php $wstTagGoods =  model("common/Tags")->listGoods("visit",$goods['goodsCatId'],6,0); foreach($wstTagGoods as $key=>$visit){?>
          <a href="<?php echo Url('home/goods/detail','goodsId='.$visit['goodsId']); ?>">
          <div class="see-item">
            <div class="see-img">
              <img title="<?php echo $visit['goodsName']; ?>" class="goodsImg" alt="<?php echo $visit['goodsName']; ?>" data-original="/<?php echo $visit['goodsImg']; ?>">
              <span class="see-price">￥<?php echo $visit['shopPrice']; ?></span>
            </div>
          </div>
          </a>
          <?php } ?>

          


         </div>
      </div>
      <div class='wst-clear'></div>
   </div>
</div>
<div class='wst-w'>
   <div class='wst-container'>
       <div class='wst-side'>
           <div class='shop-intro'>
                <div class="shop_imgbox">
                  <img class="shopsImg" data-original="/<?php echo $shop['shopImg']; ?>" title="<?php echo $shop['shopName']; ?>" style="vertical-align: middle;width:120px;height:120px;">
                </div>
                <div class='title shop-name'><?php echo $shop['shopName']; ?></div>
                <div class='title'>店主：<?php echo $shop['shopKeeper']; ?></div>
                <div class='title'>联系：
                  <?php echo hook('homeDocumentContact',['type'=>'goodsDetail','shopId'=>$shop['shopId'],'goodsId'=>$goods['goodsId']]); ?>
                  <a href="tencent://message/?uin=<?php echo $shop['shopQQ']; ?>&Site=QQ交谈&Menu=yes">
                    <img 
                        style="vertical-align: bottom;"
                        border="0" 
                        src="<?php echo WSTProtocol(); ?>wpa.qq.com/pa?p=1:<?php echo $shop['shopQQ']; ?>:7" alt="QQ交谈" width="60" height="20" />
                  </a>
                </div>
                <div class='title'>地址：<?php echo $shop['shopAddress']; ?></div>
                <div class='body'>
                    <div class='itemScore'>
                    描述
                    <br />
                    <span class="c12_e40">
                    <?php if(($shop['goodsScore']!=0)): ?><?php echo $shop['goodsScore']; else: ?>5<?php endif; ?></div>
                    </span>
                    <div class='itemScore'>
                    服务
                    <br />
                    <span class="c12_e40">
                    <?php if(($shop['serviceScore']!=0)): ?><?php echo $shop['goodsScore']; else: ?>5<?php endif; ?></div>
                    </span>
                    <div class='itemScore'>
                    物流
                    <br />
                    <span class="c12_e40">
                    <?php if(($shop['timeScore']!=0)): ?><?php echo $shop['goodsScore']; else: ?>5<?php endif; ?></div>
                    </span>
                    <div class="wst-clear"></div>
                </div>
                <div class='footer'>
                   <a class="home" href='<?php echo Url("home/shops/home","shopId=".$shop["shopId"]); ?>' target='_blank'>进店逛逛</a>
                   <?php if(($goods['favShop']>0)): ?>
                   <a class="j-fav" href='javascript:void(0);' onclick='WST.cancelFavorite(this,1,<?php echo $shop["shopId"]; ?>,<?php echo $goods['favShop']; ?>)'>已关注</a>
                   <?php else: ?>
                   <a class="j-fav2" href='javascript:void(0);' onclick='WST.addFavorite(this,1,<?php echo $shop["shopId"]; ?>,<?php echo $goods['favShop']; ?>)'>关注店铺</a>
                   <?php endif; ?>
                   <div class='wst-clear'></div>
                </div>
           </div>
       <div class="goods-side">
        <div class="guess-like">
          <div class="title">猜你喜欢</div>
          <?php $wstTagGoods =  model("common/Tags")->listGoods("guess",$goods['goodsCatId'],3,0); foreach($wstTagGoods as $key=>$vo){?>
          <div class="item">
            <div class="img"><a target='_blank' href="<?php echo Url('home/goods/detail','goodsId='.$vo['goodsId']); ?>"><img title="<?php echo $vo['goodsName']; ?>" alt="<?php echo $vo['goodsName']; ?>" data-original="/<?php echo WSTImg($vo['goodsImg']); ?>" class="goodsImg" /></a></div>
            <div class="p-name"><a class="wst-hide wst-redlink"><?php echo $vo['goodsName']; ?></a></div>
            <div class="p-price">￥<?php echo $vo['shopPrice']; ?><span class="v-price">￥<?php echo $vo['marketPrice']; ?></span></div>
          </div>
          <?php } ?>
        </div>
        <div class="hot-goods">
          <div class="title">热销商品</div>
          <?php $wstTagGoods =  model("common/Tags")->listGoods("hot",0,3,0); foreach($wstTagGoods as $key=>$vo){?>
          <div class="item">
            <div class="img"><a target='_blank' href="<?php echo Url('home/goods/detail','goodsId='.$vo['goodsId']); ?>"><img title="<?php echo $vo['goodsName']; ?>" alt="<?php echo $vo['goodsName']; ?>" data-original="/<?php echo WSTImg($vo['goodsImg']); ?>" class="goodsImg" /></a></div>
            <div class="p-name"><a class="wst-hide wst-redlink"><?php echo $vo['goodsName']; ?></a></div>
            <div class="p-price">￥<?php echo $vo['shopPrice']; ?><span class="v-price">￥<?php echo $vo['marketPrice']; ?></span></div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
    <div class='goods-desc'>
        <div id='tab' class="wst-tab-box">
        <ul id='goodsTabs' class="wst-tab-nav">
           <li>商品介绍</li>
           <li>商品评价<span class='appraise-num'>（<?php echo $goods['appraiseNum']; ?>）</span></li>
           <li>商品咨询</li>
           <li id="wx_qrcode">
             手机购买
            <img src="__STYLE__/img/detail_qr_icon.png" style="vertical-align:sub" alt="">
            <div class="wx_qrcode_box">
              <div class="qrcode"></div>
              <p style="margin-top:-25px;">微信扫购有惊喜</p>
            </div>
           </li>
           <?php if(!$goods['read']): ?>
           <a id='addCart2' href='javascript:addCart(1,"#buyNum")'>立即购买</a>
           <?php endif; ?>
        </ul>
        <div class="wst-tab-content" style='width:99%;margin-bottom: 10px;min-height:1312px;'>
             <div class="wst-tab-item goods-desc-box" style="position: relative;">
               <?php if(($goods['attrs'])): ?>
               <ul class='wst-attrs-list'>
                  <?php if((isset($goods['brandName']) && $goods['brandName']!='')): ?>
                    <div class="brand_name">品牌：<span><?php echo $goods['brandName']; ?></span></div>
                  <?php endif; if(is_array($goods['attrs']) || $goods['attrs'] instanceof \think\Collection || $goods['attrs'] instanceof \think\Paginator): $i = 0; $__LIST__ = $goods['attrs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <li title='<?php echo $vo['attrVal']; ?>'><?php echo $vo['attrName']; ?>：<?php echo $vo['attrVal']; ?></li>
                  <?php endforeach; endif; else: echo "" ;endif; ?>
                  <div class="wst-clear"></div>
               </ul>
               <?php endif; ?>
               <?php echo htmlspecialchars_decode($goods['goodsDesc']); ?>
             </div>
             <input type="hidden" id="filtertype" value="all" />
             <script id="tblist" type="text/html">
              <div class="appr-filter">
                <ul class="appr-filterbox">
                  <li><a href="javascript:void(0)" onclick="apprfilter('all')" id='all'>全部评价(<span id="totalNum">0</span>)</a></li>
                  <li><a href="javascript:void(0)" onclick="apprfilter('pic')" id='pic'>晒图(<span id="picNum">0</span>)</a></li>
                  <li><a href="javascript:void(0)" onclick="apprfilter('best')" id='best'>好评(<span id="bestNum">0</span>)</a></li>
                  <li><a href="javascript:void(0)" onclick="apprfilter('good')" id='good'>中评(<span id="goodNum">0</span>)</a></li>
                  <li><a href="javascript:void(0)" onclick="apprfilter('bad')" id='bad'>差评(<span id="badNum">0</span>)</a></li>
                </ul>
              </div>
               {{# for(var i = 0; i < d.length; i++){ }}
               <div class="appraises-box">
               <div class="wst-appraises-right">
                  <div class="userinfo">
                    <img data-original="{{#  if(d[i]['userPhoto'] != null){  }}{{WST.userPhoto(d[i]['userPhoto'])}}{{# } }}" class="apprimg" />
                    <div class="appraiser">
                        {{d[i]['loginName']}}
                    </div>
                  </div>
                  <p>{{d[i].rankName}}</p>
               </div>
                <div class="wst-appraises-left">
                  <div class="appr-starbox">
                    {{#  for(var j=0;j<d[i].avgScore;++j){ }}
                      <div class="appr-star"></div>
                    {{# }  }}
                    {{#for(var g=0;g<5-d[i].avgScore;++g){ }}
                        <div class="appr-star-off"></div>
                    {{# }  }}
                  </div>
                  <div class='wst-clear'></div>
                  <p class="app-content">
                    {{d[i]['content']}}
                    <div class="goods-spec-box">
                        {{d[i]['goodsSpecNames']}}
                    </div>
                  </p>
                  {{#  if(WST.blank(d[i]['images'])!=''){ var img = d[i]['images'].split(','); var length = img.length;  }}
                  <div id="img-file-{{i}}">
                  {{#  for(var g=0;g<length;g++){  }}
                         <img src="/{{img[g].replace('.','_thumb.')}}" layer-src="/{{img[g]}}"  style="width:80px;height:80px;" />
                  {{#  } }}
                  </div>
                  {{# }  }}
                  <span class="apprtime">{{d[i]['createTime']}}</span>
                  {{# if(d[i]['shopReply']!='' && d[i]['shopReply']!=null){ }}
                  <div class="reply-box">
                     <p class="reply-content"><a href="javascript:void(0)" onclick="goShop({{d[i]['shopId']}})">{{d[i]['shopName']}}</a>：{{d[i]['shopReply']}}</p>
                     <p class="reply-time">{{d[i]['replyTime']}}</p>
                 </div>
                 {{# } }}

               </div>
             <div class="wst-clear"></div>
              </div> 
               {{# } }}
               </script>
             <div class="wst-tab-item" style="position: relative;display:none;">
                <div class="appraise-head">
                  <div class="app-head-l">
                    <div class="app-head-lbox">
                      <strong class="text">好评度</strong>
                      <div class='percent'>
                        <i class="best_percent">0</i><span>%</span>
                      </div>
                    </div>

                  </div>
                  <div class="app-head-r">
                    <div class="app-head-rbox">
                      <div class="app-hr-item">
                        <div class="app-hr-text">好评(<i class="best_percent">0</i>%)</div>
                        <div class="percentbox">
                            <div class="percentbg" id="best_percentbg" style="width:0%"></div>
                        </div>
                      </div>
                      <div class="app-hr-item">
                        <div class="app-hr-text">中评(<i class="good_percent">0</i>%)</div>
                        <div class="percentbox">
                            <div class="percentbg" id="good_percentbg" style="width:0%"></div>
                        </div>
                      </div>
                      <div class="app-hr-item">
                        <div class="app-hr-text">差评(<i class="bad_percent">0</i>%)</div>
                        <div class="percentbox">
                            <div class="percentbg" id="bad_percentbg" style="width:0%"></div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
                <div id='ga-box'>

                </div>
                <div id='pager' style='text-align:center;'></div>
             </div>
             
             <div class="wst-tab-item" style="position: relative;display:none;">
                <div class="appr-filter">
                  <input type='hidden' id="consultType" value="0" />
                  <ul class="appr-filterbox">
                    <li><a class="gc-filter curr" href="javascript:void(0)" onclick="filterConsult(this,'0')">全部</a></li>
                    <?php $_result=WSTDatas('COUSULT_TYPE');if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <li><a class="gc-filter" href="javascript:void(0)" onclick="filterConsult(this,'<?php echo $vo['dataVal']; ?>')"><?php echo $vo['dataName']; ?></a></li>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                  </ul>
                </div>
                <div class="consult-searchbox">
                    <p class="search-tips">温馨提示：因每位咨询者购买情况、咨询时间等不同，以下回复对咨询者3天内有效，其他网友仅供参考。</p>
                    <div class="searchout">
                      <input type="text" class="search-text" id="consultKey" placeholder="请输入关键词" />
                      <button class="csbtn" onClick="queryConsult(0)">搜索</button>
                    </div>
                </div>
                <div class="wst-clear"></div>
                <div class="consult-listbox" id="consultBox">
                  
                </div>
                <script id="gclist" type="text/html">
                  <ul class='consult-list'>
                    {{# for(var i=0;i<d.length;++i){  }}
                      <li>
                          <ul class="consult-item">
                            
                            <li class='ask'>
                              <p class="wst-lfloat">咨询内容：</p>
                              <div class="wst-lfloat cs-content">{{d[i]['consultContent']}}&nbsp;<span class="c999">({{(WST.blank(d[i]['loginName'])=='')?'游客':d[i]['loginName']}})</span></div>
                              <div class="wst-rfloat">{{d[i]['createTime']}}</div>
                              <div class="wst-clear"></div>
                            </li>
                            
                            {{# if(d[i]['reply']!=''){  }}
                              <li class='answer'>
                                <p class="wst-lfloat">商家回复：</p>
                                <div class="wst-lfloat cs-content">
                                 {{d[i]['reply']}}
                                </div>
                                <div class="wst-rfloat">{{d[i]['replyTime']}}</div>
                                <div class="wst-clear"></div>
                              </li>
                            {{# }  }} 
                            
                          </ul>
                      </li>
                    {{# }  }}
                  </ul>
                </script>
                <div id="consult-pager" style="text-align:right;margin-top:10px;">
                    商品咨询分页占位
                </div>
                
                <div class="consult-publish">
                  <h5>发表咨询</h5>
                  <div class="explain">
                    声明：您可在购买前对产品包装、颜色、运输、库存等方面进行咨询，我们有专人进行回复！因厂家随时会更改一些产品的包装、颜色、产地等参数，所以该回复仅在当时对提问者有效，其他网友仅供参考！咨询回复的工作时间为：周一至周五，9:00至18:00，请耐心等待工作人员回复。
                  </div>
                  <ul>
                    <li>
                      <span class='fbold'>咨询类型：</span>
                      <?php $_result=WSTDatas('COUSULT_TYPE');if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                      <label>
                      <input type="radio" name="pointType" value="<?php echo $vo['dataVal']; ?>" /><?php echo $vo['dataName']; ?>
                      </label>
                      <?php endforeach; endif; else: echo "" ;endif; ?>
                      
                    </li>
                    <li>
                      <span class='fbold'>咨询内容：</span>
                      <textarea id="consultContent" placeholder='3~200个字符长度' maxlength='200'></textarea>
                    </li>
                    <li>
                      <button id="consultCommit" onclick="consultCommit()">提交</button>
                    </li>
                  </ul>
                </div>
              </div>
        </div>
    </div>
    <div class='wst-clear'></div>
  </div>
  <div class='wst-clear'></div>
</div>

<div class="wst-cont-frame" id="j-cont-frame">
  <div class="head"><span>对比栏</span><a href="javascript:void(0);" class="close" onclick="javascript:contrastGoods(0,0,0)">关闭</a></div>
  <div class="list">
    <div class="goods" id="contrastList"></div>
    <div class="term-contrast">
      <p><a class="contrast" href="<?php echo Url('home/goods/contrast'); ?>" target="_blank">对比</a></p>
      <p><a href="javascript:void(0);" onclick="javascript:contrastDel(0)" class="empty">清空</a></p>
    </div>
  </div>
  <script id="colist" type="text/html">
    {{# if(d.data && d.data.length>0){ }}
    {{# for(var i=0; i<d.data.length; i++){ }}
    <div class="term">
      <div class="img"><a href="{{WST.U('home/goods/detail','goodsId='+d.data[i].goodsId)}}" target="_blank"><img class="contImg" data-original="/{{ d.data[i].goodsImg }}" title="{{ d.data[i].goodsName }}" width="50" height="50"></a></div>
      <div class="info"><a href="{{WST.U('home/goods/detail','goodsId='+d.data[i].goodsId)}}" target="_blank"><p class="name">{{ d.data[i].goodsName }}</p></a><p class="price"><span>￥{{ d.data[i].shopPrice }}</span><a href="javascript:contrastDel({{ d.data[i].goodsId }});" >删除</a></p></div>
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
<style>
	.copyright a{
		margin-left:5px;
	}
</style>


<script>
var goodsInfo = {
  id:<?php echo $goods['goodsId']; ?>, 
  isSpec:<?php echo $goods['isSpec']; ?>,
  goodsStock:<?php echo $goods['goodsStock']; ?>,
  marketPrice:"<?php echo $goods['marketPrice']; ?>",
  goodsPrice:"<?php echo $goods['shopPrice']; ?>"
  <?php if(isset($goods['saleSpec'])): ?>
  ,sku:<?php echo json_encode($goods['saleSpec']); endif; ?>
}
</script>
<script type='text/javascript' src='__STYLE__/js/cloudzoom.js?v=<?php echo $v; ?>'></script>
<script type='text/javascript' src='__STYLE__/js/goods.js?v=<?php echo $v; ?>'></script>
<script type='text/javascript' src='__STYLE__/js/qrcode.js?v=<?php echo $v; ?>'></script>
<script>
$(function(){
  var qr = qrcode(8, 'H');
  var url = '<?php echo url("wechat/goods/detail","","html",true); ?>?goodsId=<?php echo $goods["goodsId"]; ?>';
  qr.addData(url);
  qr.make();
  $('.qrcode').html(qr.createImgTag());
});
function goShop(id){
  location.href=WST.U('home/shops/home','shopId='+id);
}
</script>

</body>
</html>