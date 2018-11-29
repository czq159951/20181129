<?php /*a:7:{s:75:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/shops/goods/edit.html";i:1536627232;s:69:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/shops/base.html";i:1536627232;s:62:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/top.html";i:1536627233;s:76:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/shops/goods/edit0.html";i:1536627232;s:76:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/shops/goods/edit1.html";i:1536627232;s:76:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/shops/goods/edit2.html";i:1536627232;s:65:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/footer.html";i:1536653987;}*/ ?>
<!doctype html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=($object['goodsId']>0)?"编辑":"新增";?>商品-卖家中心<?php echo WSTConf('CONF.mallTitle'); ?></title>
<link href="__STYLE__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="__STYLE__/css/shop.css?v=<?php echo $v; ?>" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="/static/plugins/webuploader/webuploader.css?v=<?php echo $v; ?>" />
<link rel="stylesheet" type="text/css" href="/static/plugins/webuploader/batchupload.css?v=<?php echo $v; ?>" />
<link href="/static/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">

<script type="text/javascript" src="/static/js/jquery.min.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="/static/plugins/layer/layer.js?v=<?php echo $v; ?>"></script>
	  
<script type='text/javascript' src='/static/js/common.js?v=<?php echo $v; ?>'></script>

<script type='text/javascript' src='__STYLE__/js/common.js?v=<?php echo $v; ?>'></script>
<script type='text/javascript' src='/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>'></script>
<script>
window.conf = {"ROOT":"","APP":"","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>", "SMS_VERFY":"<?php echo WSTConf('CONF.smsVerfy'); ?>","PHONE_VERFY":"<?php echo WSTConf('CONF.phoneVerfy'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>","IS_LOGIN":"<?php if((int)session('WST_USER.userId')>0): ?>1<?php else: ?>0<?php endif; ?>","TIME_TASK":"1","MESSAGE_BOX":"<?php echo WSTShopMessageBox(); ?>","ROUTES":'<?php echo WSTRoute(); ?>',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>"}
	<?php echo WSTLoginTarget(1); ?>
$(function() {
	WST.initShopCenter();
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



	
<div class='wst-lite-bac'>
<div class='wst-lite-container'>
   <div class='wst-logo'><a href='<?php echo app('request')->root(true); ?>'><img src="/<?php echo WSTConf('CONF.mallLogo'); ?>" height="80" width='160'></a></div>
   <div class="wst-lite-tit"><span>卖家中心</span><a class="wst-lite-in" href='<?php echo app('request')->root(true); ?>'>返回商城首页</a></div>
   <div class="wst-lite-sea">
      <div class='search'>
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
          
	      <input type="text" id='search-ipt' class='search-ipt' value='<?php echo isset($keyword)?$keyword:""; ?>'/>
	      <div id='search-btn' class="search-btn" onclick='javascript:WST.search(this.value)'></div>
      </div>
   </div>
   <div class="wst-clear"></div>
</div>
<div class="wst-clear"></div>
</div>

<div class="wst-wrap">
          <div class='wst-header'>
			<div class="wst-shop-nav">
				<div class="wst-nav-box">
				    <?php $homeMenus = WSTHomeMenus(1); if(is_array($homeMenus['menus']) || $homeMenus['menus'] instanceof \think\Collection || $homeMenus['menus'] instanceof \think\Paginator): $i = 0; $__LIST__ = $homeMenus['menus'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
						<a href="/<?php echo $vo['menuUrl']; ?>?homeMenuId=<?php echo $vo['menuId']; ?>"><li class="liselect wst-lfloat <?php if(($vo['menuId'] == $homeMenus['menuId1'])): ?>wst-nav-boxa<?php endif; ?>"><?php echo $vo['menuName']; ?></li></a>
					<?php endforeach; endif; else: echo "" ;endif; ?>
					<div class="wst-clear"></div>
				</div>
			</div>
			<div class="wst-clear"></div>
		</div>
          <div class='wst-nav'></div>
          <div class='wst-main'>
            <div class='wst-menu'>
              <?php if(isset($homeMenus['menus'][$homeMenus['menuId1']]['list'])): if(is_array($homeMenus['menus'][$homeMenus['menuId1']]['list']) || $homeMenus['menus'][$homeMenus['menuId1']]['list'] instanceof \think\Collection || $homeMenus['menus'][$homeMenus['menuId1']]['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $homeMenus['menus'][$homeMenus['menuId1']]['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menus): $mod = ($i % 2 );++$i;?>
              	<span class='wst-menu-title'><?php echo $menus['menuName']; ?><img src="__STYLE__/img/user_icon_sider_zhankai.png"></span>
              	<ul>
                <?php if(isset($menus['list'])): if(is_array($menus['list']) || $menus['list'] instanceof \think\Collection || $menus['list'] instanceof \think\Paginator): $k = 0; $__LIST__ = $menus['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($k % 2 );++$k;?>
                  	<li class="<?php if(($homeMenus['menuId3']==$menu['menuId'])): ?>wst-menua<?php endif; ?> wst-menuas" onclick="getMenus('<?php echo $menu['menuId']; ?>','<?php echo $menu['menuUrl']; ?>')">
                  	<?php echo $menu['menuName']; ?>
                  	<span id="mId_<?php echo $menu['menuId']; ?>"></span>
                  	</li>
                	<?php endforeach; endif; else: echo "" ;endif; endif; ?>
              	</ul>
              	<?php endforeach; endif; else: echo "" ;endif; endif; ?>
              
             
             
            </div>
            <div class='wst-content'>
            
<style>
label{margin-right:10px;}
#specsAttrBox .webuploader-container{width:80px;height:25px;line-height:25px;overflow:hidden;}
</style>
<div id='tab' class="wst-tab-box">
	<ul class="wst-tab-nav">
	   <li>商品信息</li>
	   <li>规格属性</li>
	   <li>商品相册</li>
	</ul>
    <div class="wst-tab-content" style='width:99%;margin-bottom: 10px;border:0px;'>
      <form id='editform' autocomplete='off'>
        <div class="wst-tab-item" style="position: relative;">
        <style>
.webuploader-pick {background: #e45050 none repeat scroll 0 0;}
</style>
<input type='hidden' id='goodsId' class='j-ipt' value='<?php echo $object["goodsId"]; ?>'/>
<table class='wst-form'>
  <tr>
     <th width='150'>商品名称<font color='red'>*</font>：</th>
     <td width='300'>
        <input type='text' class='j-ipt' id='goodsName' value='<?php echo $object["goodsName"]; ?>' maxLength='100' data-rule='商品名称:required;'/>
     </td>
     <td rowspan='6'>
        <div id='goodsImgBox'>
        <img src='/<?php if($object["goodsImg"]!=''): ?><?php echo $object["goodsImg"]; else: ?><?php echo WSTConf('CONF.goodsLogo'); endif; ?>' id='preview' width='150' height='150'>
        </div>
        <div id='goodsImgPicker'>请上传商品图片</div><span id='uploadMsg'></span>
        <input type='hidden' id='goodsImg' class='j-ipt' data-target='#msg_goodsImg' value='<?php if($object["goodsId"]>0): ?><?php echo $object["goodsImg"]; endif; ?>' data-rule="商品图片: required;"/>
        <span class='msg-box' id='msg_goodsImg'></span>
     </td>
  </tr>
  <tr>
     <th>商品类型<font color='red'>*</font>：</th>
     <td>
       <select id='goodsType' class='j-ipt' onchange="changeGoodsType(this.value)" <?php if($object["goodsId"]>0): ?>disabled<?php endif; ?>>
         <option value='0' <?php if(($object["goodsType"]==0)): ?>selected<?php endif; ?>>实物商品</option>
         <option value='1' <?php if(($object["goodsType"]==1)): ?>selected<?php endif; ?>>虚拟商品</option>
       </select>
     </td>
  </tr>
  <tr>
     <th>商品编号<font color='red'>*</font>：</th>
     <td><input type='text' class='j-ipt' id='goodsSn' value='<?php echo $object["goodsSn"]; ?>' maxLength='20' data-rule='商品编号:required;'/></td>
  </tr>
  <tr>
  <th width='150'>商品货号<font color='red'>*</font>：</th>
     <td width='300'>
        <input type='text' class='j-ipt' id='productNo' value='<?php echo $object["productNo"]; ?>' maxLength='20' data-rule='商品货号:required;'/>
     </td>
  </tr>
  <tr>
     <th>市场价格<font color='red'>*</font>：</th>
     <td><input type='text' class='j-ipt' id='marketPrice' value='<?php echo $object["marketPrice"]; ?>' maxLength='10' data-rule='市场价格:required;price' data-rule-price="[/^(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*))$/, '价格必须大于0']" onblur="javascript:WST.limitDecimal(this,2)" onkeypress="return WST.isNumberdoteKey(event)" onkeyup="javascript:WST.isChinese(this,1)"/></td>
  </tr>
  <tr>
     <th>店铺价格<font color='red'>*</font>：</th>
     <td><input type='text' class='j-ipt' id='shopPrice' value='<?php echo $object["shopPrice"]; ?>' maxLength='10' data-rule='店铺价格:required;price' data-rule-price="[/^(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*))$/, '价格必须大于0']" onblur="javascript:WST.limitDecimal(this,2)" onkeypress="return WST.isNumberdoteKey(event)" onkeyup="javascript:WST.isChinese(this,1)"/></td>
  </tr>
  <tr id='goodsStockTr' <?php if(($object["goodsType"]==1)): ?>style='display:none'<?php endif; ?>>
     <th>商品库存<font color='red'>*</font>：</th>
     <td><input type='text' class='j-ipt' id='goodsStock' value='<?php echo $object["goodsStock"]; ?>' maxLength='10' data-rule='商品库存:required;integer[+0]' onkeypress="return WST.isNumberKey(event)" onkeyup="javascript:WST.isChinese(this,1)"/></td>
  </tr>
  <tr>
     <th>预警库存<font color='red'>*</font>：</th>
     <td colspan='2'><input type='text' class='j-ipt' id='warnStock' value='<?php echo $object["warnStock"]; ?>' maxLength='10' data-rule='预警库存:required;integer[+0]' onkeypress="return WST.isNumberKey(event)" onkeyup="javascript:WST.isChinese(this,1)"/></td>
  </tr>
  <tr>
     <th>商品单位<font color='red'>*</font>：</th>
     <td colspan='2'><input type='text' class='j-ipt' id='goodsUnit' value='<?php echo $object["goodsUnit"]; ?>' maxLength='10' data-rule='商品单位:required;'/></td>
  </tr>
  <tr>
     <th>SEO关键字：</th>
     <td colspan='2'><input type='text' class='j-ipt' id='goodsSeoKeywords' maxLength='100' value='<?php echo $object["goodsSeoKeywords"]; ?>' style='width:70%'/></td>
  </tr>
  <tr>
     <th>商品促销信息：</th>
     <td colspan='2'><textarea class='j-ipt' id='goodsTips' maxLength='100' style='width:500px;height:50px'><?php echo $object["goodsTips"]; ?></textarea></td>
  </tr>
  <?php echo hook('homeDocumentShopEditGoods',['goodsId'=>$object["goodsId"]]); ?>
  <tr>
     <th>商品状态<font color='red'>*</font>：</th>
     <td colspan='2'>
      <div class="radio-box">
        <label><input type='radio' name='isSale' id="isSale-1" class='j-ipt wst-radio' value='1' <?php if($object['isSale']==1): ?>checked<?php endif; ?>/><label for="isSale-1" class="mt-1"></label>上架</label>&nbsp;&nbsp;&nbsp;&nbsp;
        <label><input type='radio' name='isSale' id="isSale-0" class='j-ipt wst-radio' value='0' <?php if($object['isSale']==0): ?>checked<?php endif; ?>/><label for="isSale-0" class="mt-1"></label>下架</label>
      </div>
     </td>
  </tr>
  <tr>
     <th>商品属性：</th>
     <td colspan='2'>
      <div class="checkbox-box">
        <label>
	        <input id="isRecom" name='isRecom' class="j-ipt wst-checkbox" <?php if($object['isRecom']==1): ?>checked<?php endif; ?> value="1" type="checkbox"/><label class="mt-1" for="isRecom"></label>推荐
	    </label>
	    <label>
	        <input id="isBest" name="isBest" class="j-ipt wst-checkbox" <?php if($object['isBest']==1): ?>checked<?php endif; ?> value="1" type="checkbox"/><label class="mt-1" for="isBest"></label>精品
	    </label>
	    <label>
	        <input id="isNew" name="isNew" class="j-ipt wst-checkbox" <?php if($object['isNew']==1): ?>checked<?php endif; ?> value="1" type="checkbox"/><label class="mt-1" for="isNew"></label>新品
	    </label>
	    <label>
	        <input id="isHot" name="isHot" class="j-ipt wst-checkbox" <?php if($object['isHot']==1): ?>checked<?php endif; ?> value="1" type="checkbox"/><label class="mt-1" for="isHot"></label>热销
	    </label>  
      </div>     
     </td>
  </tr>
  <tr>
     <th>是否包邮：</th>
     <td colspan='2'>
     <div class="radio-box">
        <label><input type='radio' name='isFreeShipping' id="isFreeShipping-1" class='j-ipt wst-radio' value='1' <?php if($object['isFreeShipping']==1): ?>checked<?php endif; ?>/><label for="isFreeShipping-1" class="mt-1"></label>包邮</label>&nbsp;&nbsp;&nbsp;&nbsp;
        <label><input type='radio' name='isFreeShipping' id="isFreeShipping-0" class='j-ipt wst-radio' value='0' <?php if($object['isFreeShipping']==0): ?>checked<?php endif; ?>/><label for="isFreeShipping-0" class="mt-1"></label>不包邮</label>
     </div>
     </td>
  </tr>
  <tr>
     <th>商城分类<font color='red'>*</font>：</th>
     <td colspan='2'>
         <select id="cat_0" class='ipt j-goodsCats' level="0" onchange="WST.ITGoodsCats({id:'cat_0',val:this.value,isRequire:true,className:'j-goodsCats',afterFunc:'lastGoodsCatCallback'});getBrands('brandId',this.value)">
	      	<option value="">-请选择-</option>
	      	<?php $_result=WSTGoodsCats(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	        <option value="<?php echo $vo['catId']; ?>"><?php echo $vo['catName']; ?></option>
	        <?php endforeach; endif; else: echo "" ;endif; ?>
	     </select>
     </td>
  </tr>
  <tr>
     <th>本店分类：</th>
     <td colspan='2'>
         <select id="shopCatId1" class='j-ipt' onchange="getShopsCats('shopCatId2',this.value,'');">
            <option value="">-请选择-</option>
            <?php $_result=WSTShopCats(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <option value="<?php echo $vo['catId']; ?>" <?php if($object['shopCatId1']==$vo['catId']): ?>selected<?php endif; ?>><?php echo $vo['catName']; ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>
         </select>
         <select id='shopCatId2' class='j-ipt'>
             <option value=''>请选择</option>
         </select>
     </td>
  </tr>
  <tr>
     <th>品牌：</th>
     <td colspan='2'>
         <select id="brandId" class='j-ipt'>
            <option value="0">-请选择-</option>
         </select>
     </td>
  </tr>
  <tr>
     <th>商品描述<font color='red'>*</font>：</th>
     <td colspan='2'>
         <textarea rows="2" cols="60" id='goodsDesc' class='j-ipt' name='goodsDesc' data-rule='商品描述:required;'><?php echo $object['goodsDesc']; ?></textarea>
     </td>
  </tr>
  <tr>
     <td colspan='3' align='center' style='text-align:center;padding-top:10px;'>
        <a class="s-btn" onclick='javascript:save()'>保&nbsp;存</a>
        <a class="s-btn2" onclick="javascript:resetForm()">重&nbsp;置</a>
     </td>
  </tr>
</table>
        </div>
        <div class="wst-tab-item" style="position: relative;display:none">
        <div id='specsAttrBox'></div>
<div id='specTips' style='display:none'>
<div class='wst-tips-box' style='margin-left:0px;'>1.若改动商品规格时，销售规则表将会重新绘制，填写销售规格表前前选择好商品规格</div>
</div>
<div id='specBtns' style='margin:0px auto;text-align:center;display:none'>
<a class="s-btn" onclick='javascript:save()'>保&nbsp;存</a>
<a class="s-btn2" onclick="javascript:resetForm()">重&nbsp;置</a>
</div>
        </div>
        <div class="wst-tab-item" style="position: relative;display:none">
        <style>
.wst-batchupload .placeholder .webuploader-pick {
    background: #e45050 none repeat scroll 0 0;
}
.wst-batchupload .statusBar .btns .uploadBtn {
    background: #e45050 none repeat scroll 0 0;
}
.wst-batchupload .statusBar .btns .uploadBtn:hover{
    background: #e42525;
}
.wst-batchupload .webuploader-pick{
    height: 40px;line-height: 40px;
}
</style>
<div id="batchUpload" class="wst-batchupload">
    <div class="queueList filled">
        <div id="dndArea" class="placeholder <?php if(!empty($object['gallery'])): ?>element-invisible<?php endif; ?>">
            <div id="filePicker"></div>
            <p>或将照片拖到这里，单次最多可选50张，每张最大不超过5M</p>
        </div>
        <ul class="filelist" >
            <?php if(is_array($object['gallery']) || $object['gallery'] instanceof \think\Collection || $object['gallery'] instanceof \think\Paginator): $i = 0; $__LIST__ = $object['gallery'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
		    <li  class="state-complete" style="border: 1px solid rgb(59, 114, 165);">
		       <p class="title"></p>
		       <p class="imgWrap">
		          <img src="/<?php echo $vo; ?>">
		       </p>
		       <input type="hidden" v="<?php echo $vo; ?>" iv="<?php echo $vo; ?>" class="j-gallery-img">
		       <span class="btn-del">删除</span>
		    </li>
		    <?php endforeach; endif; else: echo "" ;endif; ?>
	    </ul>
    </div>
    <div class="statusBar" <?php if(empty($object['gallery'])): ?>style="display: none;"<?php endif; ?>>
        <div class="progress" style="display: none;">
            <span class="text">0%</span>
            <span class="percentage" style="width: 0%;"></span>
        </div>
        <div class="info"></div>
        <div class="btns">
            <div id="filePicker2"></div><div class="uploadBtn">开始上传</div>
        </div>
    </div>
</div>
<div style='margin:0px auto;text-align:center;border-top:1px solid #cccccc;padding-top:10px;'>
<a class="s-btn" onclick='javascript:save()'>保&nbsp;存</a>
<a class="s-btn2" onclick="javascript:resetForm()">重&nbsp;置</a>
</div>

        </div>
     </form>
    </div>
</div>

            </div>
          </div>
          <div style='clear:both;'></div>
          <br/>
        </div>

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


<script type='text/javascript' src='/static/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>'></script>
<script src="/static/plugins/kindeditor/kindeditor.js?v=<?php echo $v; ?>" type="text/javascript" ></script>
<script type="text/javascript" src="/static/plugins/validator/jquery.validator.min.js?v=<?php echo $v; ?>"></script>
<script type='text/javascript' src='/static/plugins/webuploader/batchupload.js?v=<?php echo $v; ?>'></script>
<script type='text/javascript' src='__STYLE__/shops/goods/goods.js?v=<?php echo $v; ?>'></script>
<script>
var initBatchUpload = false,editor1 = null,specNum = 0,src='<?php echo $src; ?>';
<?php unset($object['goodsDesc']); ?>
var OBJ = <?=json_encode($object)?>;
$(function(){initEdit()});
</script>

<script>
function getMenus(menuId,menuUrl){
    $.post(WST.U('home/index/getMenuSession'), {menuId:menuId}, function(data){
    	location.href=WST.U(menuUrl);
    });
}
</script>
</body>
</html>