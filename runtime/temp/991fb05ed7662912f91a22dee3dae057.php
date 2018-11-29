<?php /*a:2:{s:61:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/ads/list2.html";i:1536627199;s:56:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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

<link rel="stylesheet" type="text/css" href="__ADMIN__/js/mmgrid/mmGrid.css?v=<?php echo $v; ?>" />

<link href="__ADMIN__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
<script>
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',app('request')->root(true)); ?>","ROOT":"","APP":"/index.php","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'<?php echo implode(",",session("WST_STAFF.privileges")); ?>',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","ROUTES":'<?php echo WSTRoute(); ?>',"MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>","__HTTP__":"<?php echo WSTProtocol(); ?>"}
</script>
<script language="javascript" type="text/javascript" src="/static/js/common.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="j-loader"><img src="__ADMIN__/img/ajax-loader.gif"/></div>

<div id='alertTips' class='alert alert-success alert-tips fade in'>
  <div id='headTip' class='head'><i class='fa fa-lightbulb-o'></i>操作说明</div>
  <ul class='body'>
    <li>广告位置类型主要用于确定广告在商城里的位置，调用时把广告代码放入相应位置即可显示广告</li>
  </ul>
</div>
<input type="hidden" name="" value="<?php echo $data['positionType']; ?>" id="positionType" autocomplete="off">
<input type="hidden" name="" value="<?php echo $data['positionId']; ?>" id="adPositionId" autocomplete="off">
<form autocomplete="off">
<div class="wst-toolbar">
	广告管理：<?php echo $data['positionName']; ?>
   <button type="button" class="btn f-right btn-fixtop" style="margin-left: 10px;" onclick="javascript:location.href='<?=url("admin/adpositions/index")?>'"><i class="fa fa-angle-double-left"></i>返回</button>
   <?php if(WSTGrant('GGGL_01')): ?> 
   <button class="btn btn-success f-right btn-fixtop" type='button' onclick="javascript:toEdit2(0)"><i class='fa fa-plus'></i>新增</button>
   <?php endif; ?>
   <div style="clear:both"></div>
</div>
</form>
<div class='wst-grid'>
<div id="mmg" class="mmg"></div>
<div id="pg" style="text-align: right;"></div>
</div>
<script>
$(function(){initGrid()});
</script>


<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/js/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/ads/admgrs.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>