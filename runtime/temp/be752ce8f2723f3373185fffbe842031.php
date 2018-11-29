<?php /*a:2:{s:64:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/styles/index.html";i:1536627198;s:56:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/base.html";i:1536627213;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<title>后台管理中心 - <?php echo WSTConf('CONF.mallName'); ?></title>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link rel="stylesheet" href="__ADMIN__/js/bootstrap/css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="/static/plugins/layui/css/layui.css" type="text/css" />
<link rel="stylesheet" href="/static/plugins/font-awesome/css/font-awesome.min.css" type="text/css" />
<script src="__ADMIN__/js/jquery.min.js"></script>

<link href="__ADMIN__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
<script>
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',app('request')->root(true)); ?>","ROOT":"","APP":"","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'<?php echo implode(",",session("WST_STAFF.privileges")); ?>',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","ROUTES":'<?php echo WSTRoute(); ?>',"MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>","__HTTP__":"<?php echo WSTProtocol(); ?>"}
</script>
<script language="javascript" type="text/javascript" src="/static/js/common.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="j-loader"><img src="__ADMIN__/img/ajax-loader.gif"/></div>

<div class="layui-tab layui-tab-brief" lay-filter="msgTab">
   <ul class="layui-tab-title">
   <?php if(is_array($cats) || $cats instanceof \think\Collection || $cats instanceof \think\Paginator): $i = 0; $__LIST__ = $cats;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
      <li <?php if($key==0): ?>class="layui-this"<?php endif; ?>  onclick="listQuery('<?php echo $vo['styleSys']; ?>')"><?php echo $vo['styleSys']; ?></li>
   <?php endforeach; endif; else: echo "" ;endif; ?>
   </ul>
   <div class="layui-tab-content" style="padding: 10px 0;">
      <?php if(is_array($cats) || $cats instanceof \think\Collection || $cats instanceof \think\Paginator): $i = 0; $__LIST__ = $cats;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
         <div id="style_<?php echo $vo['styleSys']; ?>" class="layui-tab-item <?php if($key==0): ?>layui-show<?php endif; ?>">
         </div>
      <?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
<script id="tblist" type="text/html">
{{# var dl = d['list'];for(var i = 0; i < dl.length; i++){ }}
<div class='style-box' style="margin: 0 auto;float: none;">
   <div class='style-img'>
     <a href='#'>
      <img src='/shangtao/{{d["sys"]}}/view/{{dl[i]["stylePath"]}}/img/screenshot.png'/>
     </a>
   </div>
   <div class='style-txt'>标题：{{dl[i]['styleName']}}</div>
   <div class='style-author'>作者：{{dl[i]['styleAuthor']}}</div>
   <div class='style-author'>介绍：{{# if(dl[i]['styleShopSite']!=''){}}<a target='_blank' href='{{dl[i]['styleShopSite']}}'>访问网址</a>{{# }else{ }}无{{#}}}</div>
   <div class='style-op'>
   {{# if(dl[i]['isUse']==1){}}
   <button class='btn btn-disabled style_{{dl[i]['id']}}' dataid='{{dl[i]['id']}}' type='button' disabled><i class='fa fa-check-circle'></i>应用中</button>
   {{# }else{ }}
   <button class='btn btn-success style_{{dl[i]['id']}}' dataid='{{dl[i]['id']}}' type='button'><i class='fa fa-check-circle'></i>启用</button>
   {{# } }}
   </div>
</div>
{{#}}}
</script>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/styles/styles.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>