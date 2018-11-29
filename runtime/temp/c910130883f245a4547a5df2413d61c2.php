<?php /*a:2:{s:47:"/home/mart/shangtao/admin/view/brands/list.html";i:1534832920;s:40:"/home/mart/shangtao/admin/view/base.html";i:1534832972;}*/ ?>
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

<div class="wst-toolbar">
     <select id='catId'>
       <option value='0'>所属商品分类</option>
       <?php if(is_array($gcatList) || $gcatList instanceof \think\Collection || $gcatList instanceof \think\Paginator): $i = 0; $__LIST__ = $gcatList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
       <option value='<?php echo $vo['catId']; ?>'><?php echo $vo['catName']; ?></option>
       <?php endforeach; endif; else: echo "" ;endif; ?>
     </select> 
   <input type='text' id='key' placeholder='品牌名称'/>  
   <button class="btn btn-primary" onclick='javascript:loadGrid(0)'><i class='fa fa-search'></i>查询</button>
   <?php if(WSTGrant('PPGL_01')): ?>
   <button class="btn btn-success f-right" onclick='javascript:toEdit(0)'><i class='fa fa-plus'></i>新增</button>
   <?php endif; ?>

   <div style='clear:both'></div>
</div>
<div class='wst-grid'>
 <div id="mmg" class="mmg"></div>
 <div id="pg" style="text-align: right;"></div>
</div>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/js/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/brands/brands.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script>
$(function(){initGrid();})
</script>

<?php echo hook('initCronHook'); ?>
</body>
</html>