<?php /*a:1:{s:41:"addons/coupon/view/home/index/coupon.html";i:1536627271;}*/ ?>
<link href="/addons/coupon/view/home/index/coupon.css" rel="stylesheet">
<script type='text/javascript' src='/addons/coupon/view/home/index/index.js'></script>
<div class='item hide' id='couponProp'>
    <div class='dt'>优 惠 券&nbsp;</div>
    <div class='dd' style='float:left'>
      <div id='couponPropBox'></div>
      <div id='couponPropMoreBox'></div>
    </div>
    <span class='j-coupon-show'>
      更多
      <img src="/addons/coupon/view/home/index/img/arrow_left.png" alt="" />
    </span>
    <div class="wst-clear"></div>
    <script id="couponlist1" type="text/html">
    {{# for(var i=0;i<d.length;++i){}} 
    	<div class='prop-item'>
          <img src='/addons/coupon/view/home/index/img/shop-coupon.png' style='vertical-align:middle;margin-top:0px'/>
          <span class='text'>
          {{#if(d[i].useCondition==0){}}
          {{d[i].couponValue}}元优惠券
          {{#}else{}}
          满{{d[i].useMoney}}减{{d[i].couponValue}}
          {{#}}}
          </span>
          <a href='javascript:receive({{d[i].couponId}})'>领取</a>
    	</div>	
    {{#}}}
    </script>
</div>
<script>
$(function(){goodsDetailCouponInit()})
</script>