<?php /*a:7:{s:64:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/index.html";i:1536649844;s:63:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/base.html";i:1536627231;s:62:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/top.html";i:1536627233;s:75:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/self_shop_header.html";i:1536627232;s:65:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/header.html";i:1536627233;s:69:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/right_cart.html";i:1536627233;s:65:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/footer.html";i:1536653987;}*/ ?>
<!doctype html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo WSTConf('CONF.mallName'); ?> - <?php echo WSTConf('CONF.mallSlogan'); ?><?php echo WSTConf('CONF.mallTitle'); ?></title>

<meta name="description" content="<?php echo WSTConf('CONF.seoMallDesc'); ?>">
<meta name="Keywords" content="<?php echo WSTConf('CONF.seoMallKeywords'); ?>">

<link href="__STYLE__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet">

<link href="__STYLE__/css/index.css?v=<?php echo $v; ?>" rel="stylesheet">

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



<div class="wst-ads" style="position:relative;" >
	<div class="wst-slide" id="wst-slide">
		
		<ul class="wst-slide-items">
			<?php $wstTagAds =  model("common/Tags")->listAds("ads-index",99,86400); foreach($wstTagAds as $key=>$vo){?>
				<a href="<?php echo $vo['adURL']; ?>" <?php if(($vo['isOpen'])): ?>target='_blank'<?php endif; if(($vo['adURL']!='')): ?>onclick="WST.recordClick(<?php echo $vo['adId']; ?>)"<?php endif; ?>><li style="background: url(/<?php echo $vo['adFile']; ?>) no-repeat  scroll center top;background-size:cover;" ></li></a>
			<?php } ?>
		</ul>
		<div class="wst-slide-numbox">
			<div style="position:absolute;right:0;top:-420px;">
				<div class='wst-right-panel' <?php if(!isset($hideCategory)): ?>style="display:none;" <?php endif; ?>>
		      	<?php $signScore=explode(",",WSTConf('CONF.signScore')); if((WSTConf('CONF.signScoreSwitch')==1 && $signScore[0]>0)): ?>
		    		<div class="ws-right-user">
		    			<div class="top">
		    				<img class="usersImg" data-original="<?php echo WSTUserPhoto(session('WST_USER.userPhoto')); ?>">
		    				<div class="name">
		    					<a href="<?php echo Url('home/users/index'); ?>"><p class="uname"><?php if(session('WST_USER.userId') >0): ?><?php echo session('WST_USER.userName')?session('WST_USER.userName'):session('WST_USER.loginName'); else: ?>请先登录<?php endif; ?></p></a>
		    					<?php if((session('WST_USER.signScoreTime')==date('Y-m-d'))): ?>
		    					<button id="j-sign" class="sign actives"><i class="plus">+</i>已签到</button>
		    					<?php else: ?>
		    					<button id="j-sign" class="sign" onclick="javascript:inSign();"><i class="plus">+</i>签到领积分</button>
		    					<?php endif; ?>
		    				</div>
		    			</div>
		    			<div class="bottom">
		    				<p class="left">当前积分：<span id="currentScore"><?php if(($object['userScore'] >0)): ?><?php echo $object['userScore']; else: ?>0<?php endif; ?></span></p><p class="right"><a href="<?php echo Url('home/userscores/index'); ?>" onclick="WST.position(13,0)">积分明细</a></p>
		    			</div>
		    			<div class="wst-clear"></div>
		    		</div>
		    	    <?php endif; ?>
		    	    
		    		<div id="wst-right-ads">
						<?php if((WSTConf('WST_ADDONS.auction')!='') && count(auction_list())>0): $auction=auction_list(); ?>
		    			<div class="aution_out">
		    				<p class="aution_tit">拍卖活动</p>
		    				<div class="aution_list" sc="<?php echo date('Y-m-d H:i:s'); ?>">
								<?php if(is_array($auction) || $auction instanceof \think\Collection || $auction instanceof \think\Paginator): $i = 0; $__LIST__ = $auction;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$au): $mod = ($i % 2 );++$i;?>
			    				<div class="aution_main" sv="<?php echo $au['startTime']; ?>" ev="<?php echo $au['endTime']; ?>">
		    						<a class="aution_item" target='_blank' href="<?php echo addon_url('auction://goods/detail','id='.$au['auctionId']); ?>">
		    							<img title="<?php echo $au['goodsName']; ?>" alt="<?php echo $au['goodsName']; ?>" class='goodsImg' data-original="/<?php echo WSTImg($au['goodsImg']); ?>"/>
										<div class="aution_time">
											距离结束：
											<span class="aution_h">12</span>
											:
											<span class="aution_i">23</span>
											:
											<span class="aution_s">55</span>
										</div>	    						
		    						</a>
			    				</div>
								<?php endforeach; endif; else: echo "" ;endif; ?>
			    				<div class="wst-clear"></div>
		    				</div>
		    				<span class="au_l_btn"><</span>
		    				<span class="au_r_btn">></span>
		    			</div>
						<?php else: $wstTagAds =  model("common/Tags")->listAds("index-art",1,86400); foreach($wstTagAds as $key=>$vo){?>
			              <a <?php if(($vo['isOpen'])): ?>target='_blank'<?php endif; if(($vo['adURL']!='')): ?>onclick="WST.recordClick(<?php echo $vo['adId']; ?>)"<?php endif; ?> href="<?php echo $vo['adURL']; ?>" onfocus="this.blur()">
			                <img data-original="/<?php echo $vo['adFile']; ?>" class="goodsImg" />
			              </a>
			    	   <?php } endif; ?>
		            <div class="index-user-tab">
		             <div id='index-tab' class="wst-tab-box">
		    	          
		    	          <div class="wst-tab-nav">
		    	             	<div class="tab">招商入驻</div>
		    	             	<div class="tab">商城快讯</div>
		    	          	</div>
		    	          <div class="wst-tab-content" style='width:99%;'>
		    	          	<div class="wst-tab-item" style="position: relative;">
		    	               <a class='apply-btn' target='_blank' href='<?php echo Url("home/shops/join"); ?>' onclick="WST.currentUrl('<?php echo url("home/shops/join"); ?>');"></a>
		    	               <a class='shop-login' href='<?php echo Url("home/shops/login"); ?>' onclick="WST.currentUrl();">登录商家中心</a>
		    	              </div>
		    	              <div class="wst-tab-item" style="position: relative;display:none">
		    	              <div id="wst-right-new-list"<?php if((!session('WST_USER.userId'))): ?>class="visitor-new-list"<?php endif; ?> >
		    	                <?php $wstTagArticle =  model("common/Tags")->listArticle("new",5,86400); foreach($wstTagArticle as $key=>$vo){?>
		    	                <div><a href="<?php echo url('home/news/view',['id'=>$vo['articleId']]); ?>"><?php echo $vo['articleTitle']; ?></a></div>
		    	                <?php } ?>
		    	              </div>
		    	              </div>
		    	              
		    	          </div>
		    	      </div> 
		    	    </div>
		           
		          <span class="wst-clear"></span>
		        </div>
		      </div>
			</div>
			<div class="wst-slide-controls">
			  	<?php $wstTagAds =  model("common/Tags")->listAds("ads-index",99,86400); foreach($wstTagAds as $k=>$vo){if($k+1 == 1): ?>
				  		 <span class="curr"><?php echo $k+1; ?></span>
				  	<?php else: ?>
				  		 <span class=""><?php echo $k+1; ?></span>
				  	<?php endif; } ?>
			</div>
		</div>
	</div>
</div>

<div class='wst-main'>
   
	<?php if((WSTConf('WST_ADDONS.coupon')!='') && count(coupon_list())>0): $coupon=coupon_list('',['s.shopImg'],4); ?>
	<div class="coupon_out">
		<a href="<?php echo addon_url('coupon://coupons/index'); ?>" class="fl coupon_bg">
			<p class="coupon_tit">领券中心</p>
			<p class="coupon_desc">为您汇总所有优惠券</p>
			<p class="r_btn">立即查看</p>
			<img src="__STYLE__/img/coupon_bg.png" alt="" />
		</a>
		<div style="float:right;width: 76%;height: 124px">
			<?php if(is_array($coupon) || $coupon instanceof \think\Collection || $coupon instanceof \think\Paginator): $i = 0;$__LIST__ = is_array($coupon) ? array_slice($coupon,0,4, true) : $coupon->slice(0,4, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cn): $mod = ($i % 2 );++$i;?>
			<a href="<?php echo addon_url('coupon://coupons/index'); ?>" class="fl coupon_item">
				<img src="/<?php echo $cn['shopImg']; ?>" alt="" style="
				position: absolute;
			    width: 70px;
			    height: 70px;
			    left:8px;
			    top: 30px;" />
				<p class="coupon_tit coupon_item_color">￥<?php echo $cn['couponValue']; ?></p>
				<p class="coupon_desc coupon_item_color f16">
					<?php if($cn['useCondition']==1): ?>
						满<?php echo $cn['useMoney']; ?>减<?php echo $cn['couponValue']; else: ?>
						无门槛券
					<?php endif; ?>
				</p>
				<p class="r_btn">立即领取</p>
				<img src="__STYLE__/img/coupon_item_bg.png" alt="" />
			</a>
			<?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
		<div class="wst-clear"></div>
	</div>
	<?php endif; ?>
   
   <div class="ads_wall">
   		<div class="ads_wall_l fl">
   			
   			<?php $wstTagAds =  model("common/Tags")->listAds("wall-left-top",1,86400); foreach($wstTagAds as $key=>$aw){?>
   			<a <?php if(($aw['isOpen'])): ?>target='_blank'<?php endif; if(($aw['adURL']!='')): ?>onclick="WST.recordClick(<?php echo $aw['adId']; ?>)"<?php endif; ?> href="<?php echo $aw['adURL']; ?>" onfocus="this.blur()" class="ads_wall_item_top">
   				<img data-original="/<?php echo $aw['adFile']; ?>" class="goodsImg" />
   				<div class="ads_wall_more">
   					<div class="ads_wall_line fl"></div>
   					<p class="fl">查看更多 >></p>
   					<div class="wst-clear"></div>
   				</div>
   			</a>
   			<?php } $wstTagAds =  model("common/Tags")->listAds("wall-left-bottom",1,86400); foreach($wstTagAds as $key=>$aw){?>
   			<a <?php if(($aw['isOpen'])): ?>target='_blank'<?php endif; if(($aw['adURL']!='')): ?>onclick="WST.recordClick(<?php echo $aw['adId']; ?>)"<?php endif; ?> href="<?php echo $aw['adURL']; ?>" onfocus="this.blur()" class="ads_wall_item_bottom">
   				<img data-original="/<?php echo $aw['adFile']; ?>" class="goodsImg" />
   				<div class="ads_wall_more">
   					<div class="ads_wall_line fl"></div>
   					<p class="fl">查看更多 >></p>
   					<div class="wst-clear"></div>
   				</div>
   			</a>
   			<?php } ?>
   		</div>
   		<div class="ads_wall_c fl">
   			
   			<?php $wstTagAds =  model("common/Tags")->listAds("wall-center",1,86400); foreach($wstTagAds as $key=>$aw){?>
   			<a <?php if(($aw['isOpen'])): ?>target='_blank'<?php endif; if(($aw['adURL']!='')): ?>onclick="WST.recordClick(<?php echo $aw['adId']; ?>)"<?php endif; ?> href="<?php echo $aw['adURL']; ?>" onfocus="this.blur()">
   				<img data-original="/<?php echo $aw['adFile']; ?>" class="goodsImg" />
   				<div class="ads_wall_more" style="left:0;right:0;">
   					<p>查看更多 >></p>
				</div>
   			</a>
   			<?php } ?>
   		</div>
   		<div class="ads_wall_r fr">
   			
   			<?php $wstTagAds =  model("common/Tags")->listAds("wall-right-top",1,86400); foreach($wstTagAds as $key=>$aw){?>
   			<a <?php if(($aw['isOpen'])): ?>target='_blank'<?php endif; if(($aw['adURL']!='')): ?>onclick="WST.recordClick(<?php echo $aw['adId']; ?>)"<?php endif; ?> href="<?php echo $aw['adURL']; ?>" onfocus="this.blur()" class="ads_wall_item_top">
   				<img data-original="/<?php echo $aw['adFile']; ?>" class="goodsImg" />
   				<div class="ads_wall_more">
   					<div class="ads_wall_line wall_r_line fl"></div>
   					<p class="fl">查看更多 >></p>
   					<div class="wst-clear"></div>
   				</div>
   			</a>
   			<?php } $wstTagAds =  model("common/Tags")->listAds("wall-right-bottom",1,86400); foreach($wstTagAds as $key=>$aw){?>
   			<a <?php if(($aw['isOpen'])): ?>target='_blank'<?php endif; if(($aw['adURL']!='')): ?>onclick="WST.recordClick(<?php echo $aw['adId']; ?>)"<?php endif; ?> href="<?php echo $aw['adURL']; ?>" onfocus="this.blur()" class="ads_wall_item_bottom">
   				<img data-original="/<?php echo $aw['adFile']; ?>" class="goodsImg" />
   				<div class="ads_wall_more">
   					<div class="ads_wall_line wall_r_line fl"></div>
   					<p class="fl">查看更多 >></p>
   					<div class="wst-clear"></div>
   				</div>
   			</a>
   			<?php } ?>
   		</div>
   		<div class="wst-clear"></div>
   </div>
   
   <div class="brand_street_out">
   	   <p class="bs_tit">品牌街</p>
	   <ul class="brand_street">
	   		<?php $wstTagBrand =  model("common/Tags")->listBrand(0,20,0); foreach($wstTagBrand as $key=>$brd){?>
		   	<li>
		   		<a href="<?php echo Url('home/goods/lists',['brand'=>$brd['brandId'],'cat'=>$brd['catId']]); ?>">
		   			<img data-original="/<?php echo $brd['brandImg']; ?>" class="goodsImg" />
		   		</a>
		   	</li>
		   	<?php } ?>
		   	<div class="wst-clear"></div>
	   </ul>
	</div>
	<div class="rec_area">
		<div class="ral fl">
			<?php if((WSTConf('WST_ADDONS.groupon')!='') && count(groupon_list())>0): $groupon=groupon_list(); ?>
			<div class="ral_box">
				<a href="<?php echo addon_url('groupon://goods/lists'); ?>">
					<p class="ral_box_tit">爱上团购</p>
					<div class="ral_line"></div>
					<p class="ral_desc">尽享美好生活</p>
				</a>
			</div>
			<img data-original="__STYLE__/img/groupon_bg.png" class="goodsImg" />
			<div class="groupon_list_out">
				<div class="groupon_view">
					<ul class="groupon_list">
						<?php if(is_array($groupon) || $groupon instanceof \think\Collection || $groupon instanceof \think\Paginator): $i = 0; $__LIST__ = $groupon;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gn): $mod = ($i % 2 );++$i;?>
						<li>
							<a href="<?php echo addon_url('groupon://goods/detail','id='.$gn['grouponId']); ?>">
								<img data-original="/<?php echo $gn['goodsImg']; ?>" class="goodsImg" />
							</a>
						</li>
						<?php endforeach; endif; else: echo "" ;endif; ?>
						<div class="wst-clear"></div>
					</ul>
				</div>
				<div class="groupon_btns">
					<?php if(is_array($groupon) || $groupon instanceof \think\Collection || $groupon instanceof \think\Paginator): $gn_k = 0; $__LIST__ = $groupon;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gn): $mod = ($gn_k % 2 );++$gn_k;?>
					<span <?php if(($gn_k==1)): ?>class="curr"<?php endif; ?>></span>
					<?php endforeach; endif; else: echo "" ;endif; ?>
					<div class="wst-clear"></div>
				</div>
			</div>
			<?php else: $wstTagAds =  model("common/Tags")->listAds("rbnh-left-ads",1,86400); foreach($wstTagAds as $key=>$rbnh){?>
			<a <?php if(($rbnh['isOpen'])): ?>target='_blank'<?php endif; if(($rbnh['adURL']!='')): ?>onclick="WST.recordClick(<?php echo $rbnh['adId']; ?>)"<?php endif; ?> href="<?php echo $rbnh['adURL']; ?>" onfocus="this.blur()">
				<img data-original="/<?php echo $rbnh['adFile']; ?>" class="goodsImg" />
			</a>
			<?php } endif; ?>
		</div>
		<div class="rac fl">
			<div class="rac_t">
				<p class="rac_t_tit">最新上架</p>
				<ul class="rac_t_main">
					<?php $wstTagGoods =  model("common/Tags")->listGoods("new",0,3,0); foreach($wstTagGoods as $key=>$racb){?>
					<li>
						<a class="rac_t_img" href="<?php echo Url('home/goods/detail','goodsId='.$racb['goodsId']); ?>">
							<img width="166" data-original="/<?php echo $racb['goodsImg']; ?>" class="goodsImg" />
						</a>
						<a href="<?php echo Url('home/goods/detail','goodsId='.$racb['goodsId']); ?>">
						<div class="rac_t_info">
							<p class="c14_333 rac_gname"><?php echo $racb['goodsName']; ?></p>
							<p class="rac_price">
								<span class="f16 rac_price_color">
									<span class="f12">￥</span><?php echo $racb['shopPrice']; ?>
								</span>
								&nbsp;
								<span class="f14 c666 del_line">
									<span class="f10">￥</span><?php echo $racb['marketPrice']; ?>
								</span>
							</p>
						</div>
						</a>
					</li>
					<?php } ?>
					<div class="wst-clear"></div>
				</ul>
			</div>
			<div class="rac_b">
				<div class="rac_b_l fl">
					<p class="rac_b_tit">精品促销</p>
					<?php $wstTagGoods =  model("common/Tags")->listGoods("best",0,1,0); foreach($wstTagGoods as $key=>$racb){?>
					<div class="rac_b_main rac_bg">
						<div class="rac_b_info">
							<p class="c14_333 mb10 rac_gname"><?php echo WSTMSubStr($racb['goodsName'],0,10,'utf-8'); ?></p>
							<p class="c14_333 rac_desc"><?php echo WSTMSubStr($racb['goodsTips'],0,20,'utf-8'); ?></p>
						</div>
						<a href="<?php echo Url('home/goods/detail','goodsId='.$racb['goodsId']); ?>">
							<img width="132" height="150" data-original="/<?php echo $racb['goodsImg']; ?>" class="goodsImg" />
						</a>
					</div>
					<?php } ?>
				</div>
				<div class="rac_b_r fr">
					<p class="rac_b_tit">热销商品</p>
					<?php $wstTagGoods =  model("common/Tags")->listGoods("hot",0,1,0); foreach($wstTagGoods as $key=>$racb){?>
					<div class="rac_b_main">
						<div class="rac_b_info">
							<p class="c14_333 mb10 rac_gname"><?php echo WSTMSubStr($racb['goodsName'],0,10,'utf-8'); ?></p>
							<p class="c14_333 rac_desc"><?php echo WSTMSubStr($racb['goodsTips'],0,20,'utf-8'); ?>
							</p>
						</div>
						<a href="<?php echo Url('home/goods/detail','goodsId='.$racb['goodsId']); ?>">
							<img width="132" height="150" data-original="/<?php echo $racb['goodsImg']; ?>" class="goodsImg" />
						</a>
					</div>
					<?php } ?>
				</div>
				<div class="wst-clear"></div>
			</div>
		</div>
		<div class="rar fr">
			<p class="rar_tit">推荐商品</p>
			<div class="rar_glist">
				<?php $wstTagGoods =  model("common/Tags")->listGoods("recom",0,2,0); foreach($wstTagGoods as $key=>$racb){?>
				<a href="<?php echo Url('home/goods/detail','goodsId='.$racb['goodsId']); ?>" class="rar_gitem">
					<p class="rar_gname"><?php echo WSTMSubStr($racb['goodsName'],0,10,'utf-8'); ?></p>
					<div class="rar_line"></div>
					<p class="rar_gdesc"><?php echo WSTMSubStr($racb['goodsTips'],0,20,'utf-8'); ?></p>
					<p class="rar_price">
						<span class="f16 rac_price_color">
							<span class="f12">￥</span><?php echo $racb['shopPrice']; ?>
						</span>
					</p>
					<div class="rar_img">
						<img  data-original="/<?php echo $racb['goodsImg']; ?>" class="goodsImg" />
					</div>
				</a>
				<?php } ?>
			</div>
		</div>
		<div class="wst-clear"></div>
	</div>
	

	<?php if((WSTConf('WST_ADDONS.integral')!='') && count(integral_list())>0): $integral=integral_list(); ?>
	<div class="intergral_out">
   	   <p class="itl_tit">积分商城</p>
   	   <div class="itl_main">
   	   	 <a href="<?php echo addon_url('integral://goods/lists'); ?>" class="itl_bg fl">
   	   	 	<img src="__STYLE__/img/integral_bg.png" alt="" />
   	   	 </a>
   	   	 <?php if(is_array($integral) || $integral instanceof \think\Collection || $integral instanceof \think\Paginator): $i = 0;$__LIST__ = is_array($integral) ? array_slice($integral,0,2, true) : $integral->slice(0,2, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$itl): $mod = ($i % 2 );++$i;?>
   	   	 <a href="<?php echo addon_url('integral://goods/detail','id='.$itl['id']); ?>" class="itl_item fl">
   	   	 	<p class="itl_name"><?php echo $itl['goodsName']; ?></p>
   	   	 	<p class="itl_price_box">
   	   	 		<span class="itl_price">￥<?php echo $itl['goodsPrice']; ?></span> + <span class="itl_score"><?php echo $itl['integralNum']; ?>积分</span>
   	   	 	</p>
   	   	 	<span class="itl_btn">立即兑换</span>
   	   	 	<img  data-original="/<?php echo $itl['goodsImg']; ?>" class="goodsImg" />
   	   	 </a>
   	   	 <?php endforeach; endif; else: echo "" ;endif; ?>

   	   	 <div class="wst-clear"></div>
   	   </div>
	</div>
	<?php endif; if((WSTConf('WST_ADDONS.distribut')!='') && count(distribut_list())>0): $distribut=distribut_list(); ?>
	<p class="distribute_tit">分销商品</p>
	<div class="distribute_out">
		<div class="dis_left_bg fl">
			<a href="<?php echo addon_url('distribut://goods/glist'); ?>">
				<img src="__STYLE__/img/index_distribute_bg.png" />
			</a>
		</div>
		<ul class="dis_list fl">
			<?php if(is_array($distribut) || $distribut instanceof \think\Collection || $distribut instanceof \think\Paginator): $i = 0;$__LIST__ = is_array($distribut) ? array_slice($distribut,0,4, true) : $distribut->slice(0,4, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dis): $mod = ($i % 2 );++$i;?>
			<li>
				<a href="<?php echo Url("home/goods/detail","goodsId=".$dis["goodsId"]); ?>">
				<img class='goodsImg' data-original="/<?php echo WSTImg($dis['goodsImg']); ?>"  title="<?php echo $dis['goodsName']; ?>"/>
				<div class="dis_gprice">
					<div class="f16"><span class="f12">￥</span><?php echo $dis['shopPrice']; ?></div>
				</div>
				</a>
			</li>
			<?php endforeach; endif; else: echo "" ;endif; ?>
			<div class="wst-clear"></div>
		</ul>
		<div class="wst-clear"></div>
	</div>
	<?php endif; ?>

	
	<div class="shop_street_out">
   	   <p class="ss_tit">店铺街</p>
	   <ul class="shop_street">
		   	<li>
		   		<div class="ss_desc">
		   			<a href="<?php echo url('home/shops/shopStreet'); ?>">
		   				<p class="ssd_tit">店铺汇聚</p>
		   				<p class="ssd_desc">更多店铺等你来<br>总有一家适合你</p>
		   			</a>
		   		</div>
		   		<img src="__STYLE__/img/shop_street_bg.png" alt="" />
		   	</li>
		   <?php if(is_array($shopStreet) || $shopStreet instanceof \think\Collection || $shopStreet instanceof \think\Paginator): $i = 0;$__LIST__ = is_array($shopStreet) ? array_slice($shopStreet,0,4, true) : $shopStreet->slice(0,4, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$st): $mod = ($i % 2 );++$i;?>
		   	<li>
		   		<a href="<?php echo url('home/shops/home',['shopId'=>$st['shopId']]); ?>" target="_blank" class="ss_entry">>>进入店铺</a>
		   		<p class="ss_shopname"><?php echo $st['shopName']; ?></p>
		   		<p class="ss_shopaddr"><?php echo $st['shopAddress']; ?></p>
		   		<img src="/<?php echo $st['shopStreetImg']; ?>" alt="" />
		   	</li>
		   	<?php endforeach; endif; else: echo "" ;endif; ?>
		   	<div class="wst-clear"></div>
	   </ul>
	</div>


	<?php 
		$validate = [0,3,6,9];
		$newArr = [];
		foreach($floors as $_k=>$_v){
			// echo "$_k";
			if(in_array($_k,$validate)){
				// echo "-1<hr />";
				$newArr[] = $_v;
				$_newArr = [];// 两个分类
			}else{
				// echo "-2<hr />";
				$_newArr[] = $_v;
				if(count($_newArr)==2){
					$newArr[] = $_newArr;
				}
			}
		}
		$floors = $newArr;
		// dump(count($floors));die;
		$oneCatFloor = [1,3,5,7];
		$floorCount = 1;// 楼层数
	 ?>


	<div class='wst-container'>
		<?php if(is_array($floors) || $floors instanceof \think\Collection || $floors instanceof \think\Paginator): $l = 0;$__LIST__ = is_array($floors) ? array_slice($floors,0,7, true) : $floors->slice(0,7, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($l % 2 );++$l;if((in_array($l,$oneCatFloor))): $adsCode = "ads-".$l."-1"; $wstTagAds =  model("common/Tags")->listAds("$adsCode",1,86400); foreach($wstTagAds as $key=>$tad){?>
			<div style="width:1200px;height:110px;margin:40px auto;overflow: hidden">
				<a href="<?php echo $tad['adURL']; ?>" <?php if(($tad['adURL']!='')): ?>onclick="WST.recordClick(<?php echo $tad['adId']; ?>)"<?php endif; ?> >
					<img class='goodsImg' data-original="/<?php echo $tad['adFile']; ?>">
				</a>
			</div>
		<?php } ?>

		
		<div class="floor_box">
			<div class="floor-header fh1 c<?php echo $floorCount; ?>" id="c<?php echo $floorCount; ?>">
				<div class="floor-header-f<?php echo $floorCount; ?> fh1l_titbox">
					<p class="floor-left-title"><a name="<?php echo $l; ?>F"></a><?php echo $l; ?>F</p>
					<p class="floor-right-title fh1_tit one_flimit" title="<?php echo $vo['catName']; ?>"><?php echo $vo['catName']; ?></p>
				</div>
				<ul class="tab">
					<li class="tab-item<?php echo $floorCount; ?> j-tab-selected<?php echo $floorCount; ?>" id="fl_<?php echo $floorCount; ?>_0" onmouseover="gpanelOver(this);" c="<?php echo $floorCount; ?>">
						<a href="<?php echo Url('home/goods/lists','cat='.$vo['catId']); ?>">热门</a>
					</li>
					
					<?php if(is_array($vo['children']) || $vo['children'] instanceof \think\Collection || $vo['children'] instanceof \think\Paginator): $l2 = 0;$__LIST__ = is_array($vo['children']) ? array_slice($vo['children'],0,7, true) : $vo['children']->slice(0,7, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($l2 % 2 );++$l2;?>
					<li class="tab-item<?php echo $floorCount; ?>" id="fl_<?php echo $floorCount; ?>_<?php echo $l2; ?>" onmouseover="gpanelOver(this);" c=<?php echo $floorCount; ?>>
						<a href="<?php echo Url('home/goods/lists','cat='.$vo1['catId']); ?>" title="<?php echo $vo1['catName']; ?>"><?php echo WSTMSubstr($vo1['catName'],0,4,"utf-8",true); ?></a>
					</li>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
			</div>
			<div class="floor_main">
				<div class="fml fl">
					<?php $wstTagAds =  model("common/Tags")->listAds("index-floor-left-$l",99,86400); foreach($wstTagAds as $key=>$floor_left){?>
						<a href="<?php echo $floor_left['adURL']; ?>" 
							<?php if(($floor_left['isOpen'])): ?>target='_blank'<?php endif; if(($floor_left['adURL']!='')): ?>onclick="WST.recordClick(<?php echo $floor_left['adId']; ?>)"<?php endif; ?>>
								<img src="/<?php echo $floor_left['adFile']; ?>" alt="" />
						</a>
					<?php } ?>
				</div>
				<div class="fmr fr">
					<div class="fmr_glist" id="fl_<?php echo $floorCount; ?>_0_pl">
						
				 <?php $wstTagGoods =  model("common/Tags")->listGoods("hot",$vo['catId'],10,86400); foreach($wstTagGoods as $key=>$cs){?>
						<a target='_blank' href="<?php echo Url('home/goods/detail','goodsId='.$cs['goodsId']); ?>" class="fmr_gitem fl" title="<?php echo $cs['goodsName']; ?>">
							<div class="fmr_img">
								<img title="<?php echo $cs['goodsName']; ?>" class='goodsImg' data-original="/<?php echo WSTImg($cs['goodsImg']); ?>"/>
							</div>
							<p class="fmr_gname"><?php echo WSTMSubstr($cs['goodsName'],0,33); ?></p>
							<p class="f16 rac_price_color tc">
								<span class="f12">￥</span><?php echo $cs['shopPrice']; ?>
							</p>
						</a>
						<?php } ?>
						<div class="wst-clear"></div>
					</div>
					<?php if(is_array($vo['children']) || $vo['children'] instanceof \think\Collection || $vo['children'] instanceof \think\Paginator): $l2 = 0; $__LIST__ = $vo['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($l2 % 2 );++$l2;?>
					<div class="fmr_glist" id="fl_<?php echo $floorCount; ?>_<?php echo $l2; ?>_pl" style="display:none">
						
				 <?php $wstTagGoods =  model("common/Tags")->listGoods("recom",$vo1['catId'],10,86400); foreach($wstTagGoods as $key=>$vo2){?>
						<a target='_blank' href="<?php echo Url('home/goods/detail','goodsId='.$vo2['goodsId']); ?>" class="fmr_gitem fl" title="<?php echo $vo2['goodsName']; ?>">
							<div class="fmr_img">
								<img title="<?php echo $vo2['goodsName']; ?>" class='goodsImg' data-original="/<?php echo WSTImg($vo2['goodsImg']); ?>"/>
							</div>
							<p class="fmr_gname"><?php echo WSTMSubstr($vo2['goodsName'],0,33); ?></p>
							<p class="f16 rac_price_color tc">
								<span class="f12">￥</span><?php echo $vo2['shopPrice']; ?>
							</p>
						</a>
						<?php } ?>
						<div class="wst-clear"></div>
					</div>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</div>
				<div class="wst-clear"></div>
			</div>
		</div>
		<?php ++$floorCount; else: ?>
		
		<div class="floor_box floor_box2">
			<div class="fb2_l fl">
				<div class="floor-header fh2 c<?php echo $l; ?>" id="c<?php echo $l; ?>">
					<div class="floor-header-f1 fh2l_titbox">
						<p class="floor-left-title"><a name="<?php echo $l; ?>F"></a><?php echo $l; ?>F</p>
						<p class="floor-right-title fh2_tit two_fmilit" title="<?php echo $vo[0]['catName']; ?>"><?php echo $vo[0]['catName']; ?></p>
					</div>
					<ul class="tab">
						<li class="tab-item<?php echo $floorCount; ?> j-tab-selected<?php echo $floorCount; ?>" id="fl_<?php echo $floorCount; ?>_0" onmouseover="gpanelOver(this);" c="<?php echo $floorCount; ?>">
							<a href="<?php echo Url('home/goods/lists','cat='.$vo[0]['catId']); ?>">热门</a>
						</li>
						
						<?php if(is_array($vo[0]['children']) || $vo[0]['children'] instanceof \think\Collection || $vo[0]['children'] instanceof \think\Paginator): $l2 = 0;$__LIST__ = is_array($vo[0]['children']) ? array_slice($vo[0]['children'],0,3, true) : $vo[0]['children']->slice(0,3, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($l2 % 2 );++$l2;?>
						<li class="tab-item<?php echo $floorCount; ?>" id="fl_<?php echo $floorCount; ?>_<?php echo $l2; ?>" onmouseover="gpanelOver(this);" c=<?php echo $floorCount; ?>>
							<a href="<?php echo Url('home/goods/lists','cat='.$vo1['catId']); ?>" title="<?php echo $vo1['catName']; ?>"><?php echo WSTMSubstr($vo1['catName'],0,4,"utf-8",true); ?></a>
						</li>
						<?php endforeach; endif; else: echo "" ;endif; ?>
						<li class="tab-item<?php echo $floorCount; ?>">
							<a class="fb2_more" href="<?php echo Url('home/goods/lists','cat='.$vo[0]['catId']); ?>">>>
							</a>
						</li>
					</ul>
				</div>
				<div class="fb2_l_l fl">
					<div class="fh2l fh2l_<?php echo $floorCount; ?>">
				        <p class="fh2l_tit"><?php echo WSTMSubstr($vo[0]['catName'],0,4,'utf-8'); ?> ></p>
				        <div class="fh2l_line"></div>
				        <p class="fh2l_desc"><?php echo $vo[0]['subTitle']!='' ? $vo[0]['subTitle'] : '&nbsp;'; ?></p>
				        <div class="floor_silder">
				            <ul>
				                <?php $wstTagGoods =  model("common/Tags")->listGoods("best",$vo[0]['catId'],3,86400); foreach($wstTagGoods as $gb_key=>$gb){?>
				            	<li class="<?php if(($gb_key==0)): ?>img_first<?php elseif(($gb_key==1)): ?>img_second<?php else: ?>img_third<?php endif; ?>
				            	">
				                    <a title="<?php echo $gb['goodsName']; ?>" target="_blank" href="<?php echo Url('home/goods/detail','goodsId='.$gb['goodsId']); ?>">
				                        <p class="caption"><?php echo WSTMSubstr($gb['goodsName'],0,8,'utf-8'); ?></p>
				                        <p class="sub_tit"><?php echo WSTMSubstr($gb['goodsTips'],0,20,'utf-8'); ?></p>
				                        <img width="130" height="130" data-original="/<?php echo $gb['goodsImg']; ?>" class="goodsImg" />
				                    </a>
				                    <div class="color_mask"></div>
				                </li>
								<?php } ?>
				            </ul>
				            <div class="turn_show clearfix">
				                <div class="prev_btn index_iconfont"><</div>
				                <div class="show_num">
				                	<?php $wstTagGoods =  model("common/Tags")->listGoods("best",$vo[0]['catId'],3,86400); foreach($wstTagGoods as $gb_key=>$gb){?>
				                    <span <?php if(($gb_key==0)): ?>class="curr"<?php endif; ?>></span>
				                    <?php } ?>
				                </div>
				                <div class="next_btn index_iconfont">></div>
				            </div>
				        </div>
				    </div>
				</div>
				<div class="fb2_l_r fr">
					<div class="fmr_glist" id="fl_<?php echo $floorCount; ?>_0_pl">
						
				 <?php $wstTagGoods =  model("common/Tags")->listGoods("hot",$vo[0]['catId'],4,86400); foreach($wstTagGoods as $key=>$cs){?>
						<a target='_blank' href="<?php echo Url('home/goods/detail','goodsId='.$cs['goodsId']); ?>" title="<?php echo $cs['goodsName']; ?>" class="fb2_gitem fl">
							<div class="fb2_img">
								<img title="<?php echo $cs['goodsName']; ?>" class='goodsImg' data-original="/<?php echo WSTImg($cs['goodsImg']); ?>"/>
							</div>
							<p class="fmr_gname"><?php echo WSTMSubstr($cs['goodsName'],0,33); ?></p>
							<p class="f16 rac_price_color tc">
								<span class="f12">￥</span><?php echo $cs['shopPrice']; ?>
							</p>
						</a>


						<?php } ?>
						<div class="wst-clear"></div>
					</div>
					<?php if(is_array($vo[0]['children']) || $vo[0]['children'] instanceof \think\Collection || $vo[0]['children'] instanceof \think\Paginator): $l2 = 0; $__LIST__ = $vo[0]['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($l2 % 2 );++$l2;?>
					<div class="fmr_glist" id="fl_<?php echo $floorCount; ?>_<?php echo $l2; ?>_pl" style="display:none">
						
				 <?php $wstTagGoods =  model("common/Tags")->listGoods("recom",$vo1['catId'],4,86400); foreach($wstTagGoods as $key=>$vo2){?>
						<a  target='_blank' href="<?php echo Url('home/goods/detail','goodsId='.$vo2['goodsId']); ?>"
title="<?php echo $vo2['goodsName']; ?>" class="fb2_gitem fl">
							<div class="fb2_img">
								<img title="<?php echo $vo2['goodsName']; ?>" class='goodsImg' data-original="/<?php echo WSTImg($vo2['goodsImg']); ?>"/>
							</div>
							<p class="fmr_gname"><?php echo WSTMSubstr($vo2['goodsName'],0,33); ?></p>
							<p class="f16 rac_price_color tc">
								<span class="f12">￥</span><?php echo $vo2['shopPrice']; ?>
							</p>
						</a>
						<?php } ?>
						<div class="wst-clear"></div>
					</div>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</div>
				<div class="wst-clear"></div>
		    </div>
		    <?php ++$floorCount; if((isset($vo[1]))): ?>
			<div class="fb2_r fl">
				<div class="floor-header fh2 c<?php echo $l; ?>" id="c<?php echo $l; ?>">
					<div class="floor-header-f3 fh2l_titbox">
						<p class="floor-left-title"><a name="<?php echo $l; ?>F"></a><?php echo $l; ?>F</p>
						<p class="floor-right-title fh2_tit two_fmilit" title="<?php echo $vo[1]['catName']; ?>"><?php echo $vo[1]['catName']; ?></p>
					</div>
					<ul class="tab">
						<li class="tab-item<?php echo $floorCount; ?> j-tab-selected<?php echo $floorCount; ?>" id="fl_<?php echo $floorCount; ?>_0" onmouseover="gpanelOver(this);" c="<?php echo $floorCount; ?>">
							<a href="<?php echo Url('home/goods/lists','cat='.$vo[1]['catId']); ?>">热门</a>
						</li>
						
						<?php if(is_array($vo[1]['children']) || $vo[1]['children'] instanceof \think\Collection || $vo[1]['children'] instanceof \think\Paginator): $l2 = 0;$__LIST__ = is_array($vo[1]['children']) ? array_slice($vo[1]['children'],0,3, true) : $vo[1]['children']->slice(0,3, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($l2 % 2 );++$l2;?>
						<li class="tab-item<?php echo $floorCount; ?>" id="fl_<?php echo $floorCount; ?>_<?php echo $l2; ?>" onmouseover="gpanelOver(this);" c=<?php echo $floorCount; ?>>
							<a href="<?php echo Url('home/goods/lists','cat='.$vo1['catId']); ?>" title="<?php echo $vo1['catName']; ?>"><?php echo WSTMSubstr($vo1['catName'],0,4,"utf-8",true); ?></a>
						</li>
						<?php endforeach; endif; else: echo "" ;endif; ?>
						<li class="tab-item<?php echo $floorCount; ?>">
							<a class="fb2_more" href="<?php echo Url('home/goods/lists','cat='.$vo[1]['catId']); ?>">>>
							</a>
						</li>
					</ul>
				</div>
				<div class="fb2_r_l fl">
					<div class="fh2l fh2l_<?php echo $floorCount; ?>">
				        <p class="fh2l_tit"><?php echo WSTMSubstr($vo[1]['catName'],0,4,'utf-8'); ?> ></p>
				        <div class="fh2l_line"></div>
				        <p class="fh2l_desc"><?php echo $vo[1]['subTitle']!='' ? $vo[1]['subTitle'] : '&nbsp;'; ?></p>
				        <div class="floor_silder">
				            <ul>
				            	<?php $wstTagGoods =  model("common/Tags")->listGoods("best",$vo[1]['catId'],3,86400); foreach($wstTagGoods as $gb_key=>$gb){?>
				            	<li class="<?php if(($gb_key==0)): ?>img_first<?php elseif(($gb_key==1)): ?>img_second<?php else: ?>img_third<?php endif; ?>
				            	">
				                    <a title="<?php echo $gb['goodsName']; ?>" target="_blank" href="<?php echo Url('home/goods/detail','goodsId='.$gb['goodsId']); ?>">
				                        <p class="caption"><?php echo WSTMSubstr($gb['goodsName'],0,8,'utf-8'); ?></p>
				                        <p class="sub_tit"><?php echo WSTMSubstr($gb['goodsTips'],0,20,'utf-8'); ?></p>
				                        <img width="130" height="130" data-original="/<?php echo $gb['goodsImg']; ?>" class="goodsImg" />
				                    </a>
				                    <div class="color_mask"></div>
				                </li>
								<?php } ?>
				                
				            </ul>
				            <div class="turn_show clearfix">
				                <div class="prev_btn index_iconfont"><</div>
				                <div class="show_num">
				                	<?php $wstTagGoods =  model("common/Tags")->listGoods("best",$vo[1]['catId'],3,86400); foreach($wstTagGoods as $gb_key=>$gb){?>
				                    <span <?php if(($gb_key==0)): ?>class="curr"<?php endif; ?>></span>
				                    <?php } ?>
				                </div>
				                <div class="next_btn index_iconfont">></div>
				            </div>
				        </div>
				    </div>
				</div>
				<div class="fb2_r_r fr">
					<div class="fmr_glist" id="fl_<?php echo $floorCount; ?>_0_pl">
						
				 <?php $wstTagGoods =  model("common/Tags")->listGoods("hot",$vo[1]['catId'],4,86400); foreach($wstTagGoods as $key=>$cs){?>
						<a target='_blank' href="<?php echo Url('home/goods/detail','goodsId='.$cs['goodsId']); ?>" title="<?php echo $cs['goodsName']; ?>" class="fb2_gitem fl">
							<div class="fb2_img">
								<img title="<?php echo $cs['goodsName']; ?>" class='goodsImg' data-original="/<?php echo WSTImg($cs['goodsImg']); ?>"/>
							</div>
							<p class="fmr_gname"><?php echo WSTMSubstr($cs['goodsName'],0,33); ?></p>
							<p class="f16 rac_price_color tc">
								<span class="f12">￥</span><?php echo $cs['shopPrice']; ?>
							</p>
						</a>


						<?php } ?>
						<div class="wst-clear"></div>
					</div>
					<?php if(is_array($vo[1]['children']) || $vo[1]['children'] instanceof \think\Collection || $vo[1]['children'] instanceof \think\Paginator): $l2 = 0; $__LIST__ = $vo[1]['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($l2 % 2 );++$l2;?>
					<div class="fmr_glist" id="fl_<?php echo $floorCount; ?>_<?php echo $l2; ?>_pl" style="display:none">
						
				 <?php $wstTagGoods =  model("common/Tags")->listGoods("recom",$vo1['catId'],4,86400); foreach($wstTagGoods as $key=>$vo2){?>
						<a  target='_blank' href="<?php echo Url('home/goods/detail','goodsId='.$vo2['goodsId']); ?>"
title="<?php echo $vo2['goodsName']; ?>" class="fb2_gitem fl">
							<div class="fb2_img">
								<img title="<?php echo $vo2['goodsName']; ?>" class='goodsImg' data-original="/<?php echo WSTImg($vo2['goodsImg']); ?>"/>
							</div>
							<p class="fmr_gname"><?php echo WSTMSubstr($vo2['goodsName'],0,33); ?></p>
							<p class="f16 rac_price_color tc">
								<span class="f12">￥</span><?php echo $vo2['shopPrice']; ?>
							</p>
						</a>
						<?php } ?>
						<div class="wst-clear"></div>
					</div>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</div>
				<div class="wst-clear"></div>
			</div>
			<?php ++$floorCount; endif; ?>
			<div class="wst-clear"></div>
		</div>
		<?php endif; endforeach; endif; else: echo "" ;endif; ?>
		
		<div class="like_goods_list">
			<div class="lg_tit">猜你喜欢</div>
			<div class="lg_glist">
		 	<?php $wstTagGoods =  model("common/Tags")->listGoods("guess",0,10,86400); foreach($wstTagGoods as $key=>$cs){?>
				<a target='_blank' href="<?php echo Url('home/goods/detail','goodsId='.$cs['goodsId']); ?>" class="fmr_gitem fl" title="<?php echo $cs['goodsName']; ?>">
					<div class="fmr_img">
						<img title="<?php echo $cs['goodsName']; ?>" class='goodsImg' data-original="/<?php echo WSTImg($cs['goodsImg']); ?>"/>
					</div>
					<p class="fmr_gname"><?php echo WSTMSubstr($cs['goodsName'],0,33); ?></p>
					<p class="f16 rac_price_color tc">
						<span class="f12">￥</span><?php echo $cs['shopPrice']; ?>
					</p>
				</a>
			<?php } ?>
				<div class="wst-clear"></div>
			</div>
		</div>
	</div>
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


<script async="async" type='text/javascript' src='__STYLE__/js/index.js?v=<?php echo $v; ?>'></script>

</body>
</html>