<?php /*a:4:{s:78:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/shops/shopcats/list.html";i:1536627232;s:69:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/shops/base.html";i:1536627232;s:62:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/top.html";i:1536627233;s:65:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/footer.html";i:1536653987;}*/ ?>
<!doctype html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>商品分类 - 卖家中心<?php echo WSTConf('CONF.mallTitle'); ?></title>
<link href="__STYLE__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="__STYLE__/css/shop.css?v=<?php echo $v; ?>" rel="stylesheet">

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
            
<div class="wst-body"> 
<div class="wst-shop-head"><span>商品分类</span></div>
<div class="wst-clear"></div>
   <div style='text-align:right;height: 36px;'>
   	 <span></span>
   	 <a href='javascript:addCat(1);' style='float:right;margin:9px 30px 0px 0px;'><img class="wst-lfloat" style="margin:-3px 5px 0px 0px;" src="__STYLE__/img/seller_icon_xz.png">新增</a>
   </div>
   <form autocomplete="off">
   <table id="cat_list_tab" class='wst-list wst-form'>
   <thead>
   <tr class="wst-colour">
     <th class="wst-fre-th">名称</th>
     <th width='60'>排序号</th>
     <th width='80' style="line-height: normal;">是否显示<br/><span style="font-weight:normal;color:red;">(双击可修改)</span></th>
     <th width="150">操作</th>
   </tr>
   </thead>
   <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
   <tbody>
   <tr id='tr_<?php echo $i; ?>' isLoad='1'>
     <td class="wst-fre-td">
	     <span class='wst-tree-open active' onclick='javascript:treeCatOpen(this,<?php echo $vo['catId']; ?>)'><img class="wst-lfloat" style="margin-top:-3px;" src="__STYLE__/img/seller_icon_zk.png"></span>
	     <input type='text' style='width:400px;' value='<?php echo $vo['catName']; ?>' dataId="<?php echo $vo['catId']; ?>" onchange='javascript:editCatName(this)'/>
     </td>
     <td><input class='catsort' type='text' style='width:35px;' value="<?php echo $vo['catSort']; ?>" dataId="<?php echo $vo['catId']; ?>" onchange='javascript:editCatSort(this)' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberKey(event)"/></td>
     <?php if($vo['isShow']==0): ?>
	  <td style="cursor:pointer;" ondblclick="changeCatStatus(1,<?php echo $vo['catId']; ?>,0)"><span class='wst-state_no'><img class="wst-lfloat" style="margin-top:-3px;" src="__STYLE__/img/seller_icon_error.png"></span></td>
     <?php else: ?>
      <td style="cursor:pointer;" ondblclick="changeCatStatus(0,<?php echo $vo['catId']; ?>,0)"><span class='wst-state_yes'><img class="wst-lfloat" style="margin-top:-3px;" src="__STYLE__/img/seller_icon_right.png"></span></td>
     <?php endif; ?>
     <td>
     <a href="javascript:void(0);" onclick='javascript:addCat(this,<?php echo $vo["catId"]; ?>,<?php echo $i; ?>);' class='add btn' title='新增'>[新增]</a>
     <a href="javascript:void(0);" onclick="javascript:delCat(<?php echo $vo['catId']; ?>,0)" class='del btn' title='删除'>[删除]</a>&nbsp;
     </td>
   </tr>
   <?php if(isset($vo['childNum'])): if($vo['childNum'] > 0): if(is_array($vo['child']) || $vo['child'] instanceof \think\Collection || $vo['child'] instanceof \think\Paginator): $i2 = 0; $__LIST__ = $vo['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i2 % 2 );++$i2;?>
	  <tr id='tr_<?php echo $i; ?>_<?php echo $i2; ?>' class="tr_<?php echo $i; ?> tree_<?php echo $vo['catId']; ?>" isLoad='1'>
	     <td class="wst-fre-td">
	     <span class="wst-tree-second"></span>
	     <input type='text' style='width:400px;' value='<?php echo $vo2['catName']; ?>' dataId="<?php echo $vo2['catId']; ?>" onchange='javascript:editCatName(this)'/>
	     </td>
	     <td><input class='catsort' type='text' style='width:35px;' value="<?php echo $vo2['catSort']; ?>" dataId="<?php echo $vo2['catId']; ?>" onchange='javascript:editCatSort(this)' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberKey(event)"/></td>
	     <?php if($vo2['isShow']==0): ?>
		  <td style="cursor:pointer;" onclick="changeCatStatus(1,<?php echo $vo2['catId']; ?>,<?php echo $vo['catId']; ?>)"><span class='wst-state_no'><img class="wst-lfloat" style="margin-top:-3px;" src="__STYLE__/img/seller_icon_error.png"></span></td>
	     <?php else: ?>
	      <td style="cursor:pointer;" onclick="changeCatStatus(0,<?php echo $vo2['catId']; ?>,<?php echo $vo['catId']; ?>)"><span class='wst-state_yes'><img class="wst-lfloat" style="margin-top:-3px;" src="__STYLE__/img/seller_icon_right.png"></span></td>
	     <?php endif; ?>
	     <td>
	     <a href="javascript:delCat(<?php echo $vo2['catId']; ?>,0)" class='del btn' title='删除'>[删除]</a>&nbsp;
	     </td>
	  </tr>
      <?php endforeach; endif; else: echo "" ;endif; endif; endif; ?>
   </tbody>
   <?php endforeach; endif; else: echo "" ;endif; ?>
</table>
</form>
<div class='wst-tbar-group' style='height: 76px;text-align: center'>
     <button class='wst-shop-but hide' style='margin-top:40px;;width:80px;height: 30px;' type="button" onclick='javascript:batchSaveCats()'>保&nbsp;存</button>
     <button class='wst-shop-but hide' style='margin-top:40px;margin-left:5px;width:80px;height: 30px;;' type="button" onclick='javascript:location.reload()'>取&nbsp;消</button>
     <a style='float:right;margin:30px 30px 0px 0px;' href='javascript:addCat(1);'><img class="wst-lfloat" style="margin:-3px 5px 0px 0px;" src="__STYLE__/img/seller_icon_xz.png">新增</a>
</div>
</div>



<script id="cat_p_tr" type="text/html">
<tbody class='tbody_new'>
	<tr class="tr_new" isload="1">
		<td class="wst-fre-td">
			<span class="wst-tree-open"><img class="wst-lfloat" style="margin-top:-3px;" src="__STYLE__/img/seller_icon_zk.png"></span>
			<input class="catname" type="text" style="width:400px;height:22px;margin-left:6px;" dataid="">
		</td>
		<td>
			<input class="catsort" type="text" style="width:35px;" value="0" onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberKey(event)">
		</td>
		<td style="cursor:pointer;">
			<input class="catshow" type="checkbox" checked=""/>
		</td>
		<td>
			<a href="javascript:void(0);" onclick="addCat(this,0,0);" class="add btn" title="新增">[新增]</a>
			<a href="javascript:void(0);" class="del btn" title="删除" onclick="delCatObj(this,1)">[删除]</a>&nbsp;
		</td>
	</tr>
</tbody>
</script>

<script id="cat_c_tr" type="text/html">
<tr class="{{d.className}}" isload="1" catid="{{d.p}}">
	<td class="wst-fre-td">
		<span class="wst-tree-second"></span>
		<input class="catname" type="text" style='width:400px' dataid="">
	</td>
	<td>
		<input class="catsort" type="text" style="width:35px;" value="0" onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberKey(event)">
	</td>
	<td style="cursor:pointer;">
		<input class="catshow" type="checkbox" checked=""/>
	</td>
	<td>
		<a href="javascript:void(0);" class="del btn" title="删除" onclick="delCatObj(this,2)">[删除]</a>&nbsp;
	</td>
</tr>
</script>


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

<script type='text/javascript' src='__STYLE__/shops/shopcats/shopcats.js?v=<?php echo $v; ?>'></script>
<script>
function getMenus(menuId,menuUrl){
    $.post(WST.U('home/index/getMenuSession'), {menuId:menuId}, function(data){
    	location.href=WST.U(menuUrl);
    });
}
</script>
</body>
</html>