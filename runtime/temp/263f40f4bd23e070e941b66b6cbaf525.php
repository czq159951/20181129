<?php /*a:4:{s:79:"/www/beidou/mart/zsbd_mart/shangtao/mobile/view/default/articles/news_list.html";i:1536569719;s:65:"/www/beidou/mart/zsbd_mart/shangtao/mobile/view/default/base.html";i:1536569719;s:67:"/www/beidou/mart/zsbd_mart/shangtao/mobile/view/default/footer.html";i:1536569719;s:67:"/www/beidou/mart/zsbd_mart/shangtao/mobile/view/default/dialog.html";i:1536569719;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title>商城快讯 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="/shangtao/mobile/view/default/css/articles.css?v=<?php echo $v; ?>">

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

	<div id="info_list" style="margin-top: 50px;">
    <header style="background:#ffffff;" class="ui-header ui-header-positive ui-border-b wst-header">
        <i class="ui-icon-return" onclick="history.back()"></i><h1>商城快讯</h1>
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


<input type="hidden" name="" value="" id="currPage" autocomplete="off">
<input type="hidden" name="" value="" id="totalPage" autocomplete="off">
<input type="hidden" name="" value="<?php echo $catId; ?>" id="catId" autocomplete="off">

    <section class="ui-container" id="shopBox">
      <div class="ui-tab">
          <ul class="ui-tab-nav order-tab">
            <?php if(is_array($catInfo) || $catInfo instanceof \think\Collection || $catInfo instanceof \think\Paginator): $i = 0; $__LIST__ = $catInfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <li class="tab-item <?php if($catId==$vo['catId']): ?>tab-curr<?php endif; ?>" catId="<?php echo $vo['catId']; ?>"><?php echo $vo['catName']; ?></li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
          </ul>
    </div>

        <section  id="newsListBox" >
        
        </section>
         
    <div style="height:50px;"></div>
    </section>
    <script id="newsList" type="text/html">
	{{# var imgSuffix = "<?php echo WSTConf('CONF.wstMobileImgSuffix'); ?>";}}
    {{# for(var i=0;i<d.length;i++){ }}
        {{# if(d[i].TypeStatus==1){ }}
             <div class="news-item wst-model" onclick="viewNews({{d[i].articleId}})" >
              <div class="ui-row-flex">
                  <div class="ui-col">
                    <div class="img j-imgAdapt wst-bor-mix-img" >
                      <a href="javascript:void(0);" >
                          {{# if(d[i].coverImg) { }}
                         <img src="/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="/{{WST.replaceImg(d[i].coverImg,imgSuffix)}}">
                         {{# } else { }}
                         <img src="/<?php echo WSTConf('CONF.goodsLogo'); ?>" >
                         {{#   } }}
                      </a>
                    </div>
                  </div>
                  <div class="ui-col ui-col-3" >
                    <div class="ui-row-flex ui-row-flex-ver wst-info" >
                        <div class="ui-nowrap-multi" style="-webkit-line-clamp: 1;">{{d[i].articleTitle}}</div>
                        <div class="ui-nowrap-multi wst-mix-cont" style="-webkit-line-clamp: 3;">{{d[i].articleContent}}</div>
                    </div>
                  </div>
                </div>
                <div class="ui-row-flex ui-whitespace wst-model wst-mix-info ">
                  <div class="ui-col ui-col ui-flex-pack-center ui-flex-align-start">• 已有{{d[i].visitorNum}}人浏览</div>
                  <div class="ui-col ui-col ui-flex-pack-center ui-flex-align-end wst-right-align">• {{d[i].createTime}}</div>
                </div>
              </div>
        {{# } }}
        {{# if(d[i].TypeStatus==2){ }}
             <div class="news-item wst-model" onclick="viewNews({{d[i].articleId}})">
              <div class="ui-row-flex">
               <div class="ui-col ui-col-3">
                 <div class="ui-row-flex ui-row-flex-ver wst-info" >
                     <div class="ui-nowrap-multi" style="-webkit-line-clamp: 1;">{{d[i].articleTitle}}{{d[i].TypeStatus}}</div>
                     <div class="ui-nowrap-multi wst-mix-cont" style="-webkit-line-clamp: 3;">{{d[i].articleContent}}</div>
                 </div>
               </div>
               <div class="ui-col">
                <div class="img j-imgAdapt wst-bor-mix-img">
                  <a href="javascript:void(0);" >
                     {{# if(d[i].coverImg) { }}
                     <img src="/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="/{{WST.replaceImg(d[i].coverImg,imgSuffix)}}">
                     {{# } else { }}
                     <img src="/<?php echo WSTConf('CONF.goodsLogo'); ?>" >
                     {{#   } }}
                  </a>
                </div>
               </div>
              </div>
              <div class="ui-row-flex ui-whitespace wst-model wst-mix-info ">
                  <div class="ui-col ui-col ui-flex-pack-center ui-flex-align-start">• 已有{{d[i].visitorNum}}人浏览</div>
                  <div class="ui-col ui-col ui-flex-pack-center ui-flex-align-end wst-right-align">• {{d[i].createTime}}</div>
              </div>
            </div>
        {{# } }}
        {{# if(d[i].TypeStatus==3){ }}
             <div class="ui-row-flex ui-whitespace ui-row-flex-ver wst-model"  style="height:auto;overflow:hidden;" onclick="viewNews({{d[i].articleId}})">
              <div class="wst-max-info">
                    <div class="ui-nowrap-multi" style="-webkit-line-clamp: 1;" >{{d[i].articleTitle}}</div>
              </div>
              <div class="wst-max-info">
                    <div class="ui-nowrap-multi wst-mix-cont" style="-webkit-line-clamp: 1;padding-top: 0px;" >{{d[i].articleContent}}</div>
              </div>
              <div class="max-img">
                  <a href="javascript:void(0);">
                      {{# if(d[i].coverImg) { }}
                     <img src="/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="/{{WST.replaceImg(d[i].coverImg,imgSuffix)}}">
                     {{# } else { }}
                     <img src="/<?php echo WSTConf('CONF.goodsLogo'); ?>" >
                     {{#   } }}
                  </a>
             </div>
             <div class="max-remind wst-mix-info ui-row">
                <div class="ui-col ui-col-50 ui-flex ui-flex-ver ui-flex-pack-center ui-flex-align-start">• 已有{{d[i].visitorNum}}人浏览</div>
                <div class="ui-col ui-col-50 ui-flex ui-flex-ver ui-flex-pack-center ui-flex-align-end">• {{d[i].createTime}}</div>
             </div>
            </div>  
        {{# } }}
         {{# if(d[i].TypeStatus==4){ }}
             <div class="news-item wst-model" onclick="viewNews({{d[i].articleId}})">
              <div class="ui-row-flex" style="height:100px;">
               <div class="ui-col">
                 <div class="ui-row-flex ui-row-flex-ver wst-info" >
                     <div class="ui-nowrap-multi" style="-webkit-line-clamp: 1;">{{d[i].articleTitle}}</div>
                     <div class="ui-nowrap-multi wst-mix-cont" style="-webkit-line-clamp: 3;">{{d[i].articleContent}}</div>
                 </div>
               </div>
               
              </div>
              <div class="ui-row-flex ui-whitespace wst-model wst-mix-info ">
                  <div class="ui-col ui-col ui-flex-pack-center ui-flex-align-start">• 已有{{d[i].visitorNum}}人浏览</div>
                  <div class="ui-col ui-col ui-flex-pack-center ui-flex-align-end wst-right-align">• {{d[i].createTime}}</div>
              </div>
            </div>
        {{# } }}
          
        {{# } }}
    </script>
    </div>
    





<div class="wst-cover" id="cover"></div>

<div class="wst-fr-box" id="frame">
    <div class="title"><span>商城快讯</span><i class="ui-icon-close-page" onclick="javascript:dataHide();"></i><div class="wst-clear"></div></div>
    <div class="content" id="content">

        
        <div class="ui-whitespace news-content-box">
           <div class="ui-nowrap ui-whitespace news-title" id="articleTitle"></div>
           <div class="ui-whitespace news-time" id="createTime"></div>
           <div class="ui-whitespace view-content" id="articleContent">
            
           </div>
        </div>
        <div class="wst-like ui-whitespace ui-flex ui-flex-pack-center"  id="like1"  onclick="javascript:like();">
          <input type="hidden" name="" id="articleId" value=""  >
          <span class="icon-like1"><p class="like_num" id="likeNum"></p></span>
        </div>
        <div class="wst-like ui-whitespace ui-flex ui-flex-pack-center"  id="like" >
          <input type="hidden" name="" id="articleId" value=""  >
          <span class="icon-like2"><p class="like_num" id="likeNum2"></p></span>
        </div>

    </div>
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


<script>
$(function(){
  // Tab切换卡
  $('.tab-item').click(function(){
      $(this).addClass('tab-curr').siblings().removeClass('tab-curr');
      var catId = $(this).attr('catId');
      $('#catId').val(catId);
      reFlashList();
  });
  {<?php if(!empty($articleId)): ?>}
  viewNews(<?php echo $articleId; ?>)
  {<?php endif; ?>}
})
</script>
<script type='text/javascript' src='__MOBILE__/js/jquery.min.js'></script>
<script type='text/javascript' src='__MOBILE__/articles/news_list.js?v=<?php echo $v; ?>'></script>

</body>
</html>