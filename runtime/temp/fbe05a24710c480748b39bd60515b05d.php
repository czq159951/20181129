<?php /*a:2:{s:41:"addons/coupon/view/mobile/index/list.html";i:1536627271;s:65:"/www/beidou/mart/zsbd_mart/shangtao/mobile/view/default/base.html";i:1536569719;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title>领券中心 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="/addons/coupon/view/mobile/index/coupon.css?v=<?php echo $v; ?>">

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

    <header class="ui-header ui-header-positive wst-header">
        <a href="<?php echo url('mobile/index/index'); ?>"><i class="ui-icon-return"></i></a>
        <span class="wst-se-icon" onclick="javascript:dataShow();"></span>
    </header>
    <div class="wst-in-search tit">
        领券中心
    </div>
    <div class="wst-in-icon" id="j-icon">
        <span class="cats" style="left:initial;right:2px;" onclick="javascript:dataShow();"></span>
    </div>


    


     <input type="hidden" name="" value="" id="currPage" autocomplete="off">
    <input type="hidden" name="" value="" id="totalPage" autocomplete="off">
    <input type="hidden" name="" value="<?php echo $catId; ?>" id="catId" autocomplete="off">

    <script id="shopList" type="text/html">
    {{# for(var i = 0; i < d.length; i++){ }}
      <div class="coupon_item">
            <div class="coupon_item_left">
                <div class="coupon_left">
                    <i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i>
                </div>
                <div class="coupon_left_main">
                    <div class="coupon_left_main_inner">
                        <p class="coupon_left_txt1"><span class="yan">￥</span>{{d[i].couponValue}}</p>
                        <span class="coupon_left_txt2">
                        {{# if(d[i].useCondition==0){ }}
                            无金额门槛
                        {{# }else{  }}
                            满{{d[i].useMoney}}可用
                        {{# }  }}
                        </span>
                    </div>
                </div>
                <div class="wst-clear"></div>
            </div>
            <div class="coupon_item_right">
                <div class="coupon_item_right_inner">
                    <div class="c_item_r_left">
                        <p class="c_item_txt1 ui-nowrap ui-whitespace">{{d[i].couponValue}}元优惠券</p>
                        <p class="c_item_txt2 ui-nowrap ui-whitespace">
                            {{# if(d[i].useCondition==0){ }}
                                无金额门槛
                            {{# }else{  }}
                                消费满{{d[i].useMoney}}立减{{d[i].couponValue}}
                            {{# }  }}
                        </p>
                        <p class="c_item_txt3 ui-nowrap ui-whitespace">{{d[i].shopName}}</p>
                        <p class="c_item_txt4 ui-nowrap ui-whitespace">{{d[i].startDate}}~{{d[i].endDate}}</p>
                    </div>
                    {{# if(d[i].isOut==1){  }}
                        <a href="javascript:" class="get_btn unuse_btn">已领完</a>
                    {{# }else{  }}
                        <a href="javascript:void(0)" onClick="getCoupon({{d[i].couponId}})"  class="get_btn">立即领取</a>
                    {{# }  }}
                    <div class="wst-clear"></div>
                </div>
            </div>
            <div class="wst-clear"></div>
        </div>
    {{#  } }}
    </script>

    <section class="ui-container" id="shopBox">
        <div class="ui-tab">
            <ul class="ui-tab-nav coupon-tab">
                <li class="tab-item <?php if($catId==''): ?>tab-curr<?php endif; ?>" catId="0" >全部</li>
                <?php $_result=WSTGoodsCats(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cats): $mod = ($i % 2 );++$i;?>
                    <li class="tab-item" catId="<?php echo $cats['catId']; ?>"><?php echo WSTMSubstr($cats['catName'],0,4); ?></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>

        <div id="order-box">
        </div>

    </section>
    </div>



<div class="wst-cover" id="cover"></div>

<div class="wst-fr-box" id="frame">
    <div class="title"><span>商品分类</span><i class="ui-icon-close-page" onclick="javascript:dataHide();"></i><div class="wst-clear"></div></div>
    <div class="content">
        <ul class="ui-row">
            <li class="ui-col ui-col-48 cat_item" id="0">全部</li>
            <?php $_result=WSTGoodsCats(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cats): $mod = ($i % 2 );++$i;?>
                <li class="ui-col ui-col-48 cat_item" id="<?php echo $cats['catId']; ?>"><?php echo $cats['catName']; ?></li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <div class="wst-clear"></div>
    </div>
</div>


<script type='text/javascript' src='__MOBILE__/js/jquery.min.js'></script>
<script type='text/javascript' src='/addons/coupon/view/mobile/index/list.js'></script>


</body>
</html>