<?php /*a:3:{s:87:"/www/beidou/mart/zsbd_mart/shangtao/mobile/view/default/users/favorites/list_goods.html";i:1536569710;s:65:"/www/beidou/mart/zsbd_mart/shangtao/mobile/view/default/base.html";i:1536569719;s:67:"/www/beidou/mart/zsbd_mart/shangtao/mobile/view/default/dialog.html";i:1536569719;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title>我的关注 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="/shangtao/mobile/view/default/css/favorites.css?v=<?php echo $v; ?>">

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

    <header style="background:#ffffff;" class="ui-header ui-header-positive ui-border-b wst-header">
        <i class="ui-icon-return" onclick="history.back()"></i><h1>关注商品</h1>
    </header>


	<div class="ui-loading-wrap wst-Load" id="Load">
	    <i class="ui-loading"></i>
	</div>
	<footer class="ui-footer wst-footer-btns" style="height:45px; border-top: 1px solid #e0e0e0;" id="footer">
        <div class="wst-toTop" id="toTop">
              <i class="wst-toTopimg"><span>顶部</span></i>
            </div>
	
    <div class="ui-row-flex ui-whitespace">
        <div class="ui-col ui-col-2 favorite-tc">
            <label class="ui-checkbox">
                <input class="sactive" type="checkbox"  onclick="javascript:checkAll(this);">
            </label>
            全选
        </div>

        <div class="ui-col">
            <div class="ui-btn-wrap f-btn">
            <button class="ui-btn ui-btn-danger" onclick="WST.dialog('确认要取消关注吗','cancelFavorite()');">
                取消关注
            </button>
            </div>
        </div>
    </div>
	</footer>


    <input type="hidden" name="" value="" id="currPage" autocomplete="off">
    <input type="hidden" name="" value="" id="totalPage" autocomplete="off">
    <script id="fGoods" type="text/html">
        {{# for(var i=0; i<d.length; i++){ }}
              <div class="ui-row-flex wst-shl-list" >
              <label class="ui-checkbox">
              <input class="active" type="checkbox" gId="{{d[i].favoriteId}}" onclick="javascript:WST.changeIconStatus($(this), 1);">
              </label>

              <div class="ui-col">
              <div class="img j-imgAdapt">
              <a href="javascript:void(0);" onclick="javascript:WST.intoGoods({{d[i].goodsId}});">
              <img src="/{{WST.conf.GOODS_LOGO}}" data-echo="/{{d[i].goodsImg}}" title="{{d[i].shopName}}"></a></div>
              </div>
              <div class="ui-col ui-col-2 info">
              <div class="title ui-nowrap-multi ui-whitespace" onclick="javascript:WST.intoGoods({{d[i].goodsId}});">{{d[i].goodsName}}</div>
              <p class="price"><span>¥ </span>{{d[i].shopPrice}}</p>
              <p class="deal">成交数：{{d[i].saleNum}}</p><span class="add-cart" onclick="addCart({{d[i].goodsId}})"></span>
              </div>
              </div>
         {{# } }}
    </script>
    <section class="ui-container info-prompt">
		<ul class="ui-tab-content">
            <li id="goods-list"></li>
        </ul>
    </section>
    </div>



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


<script type='text/javascript' src='__MOBILE__/users/favorites/favorites.js?v=<?php echo $v; ?>'></script>

</body>
</html>