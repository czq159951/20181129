<?php /*a:2:{s:66:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/logmoneys/list.html";i:1536627214;s:56:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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

<div class="layui-tab layui-tab-brief" lay-filter="msgTab">
   <ul class="layui-tab-title">
     <li class="layui-this" >会员</li>
     <li>商家</li>
     <li>资金流水</li>
   </ul>
   <div class="layui-tab-content" style="padding: 0px 0;">
      <div id="template_user" class="layui-tab-item layui-show">
         <div class="wst-toolbar">
         账号：<input type='text' id='key1' placeholder='账号'/>
         <button class="btn btn-primary" onclick="javascript:loadUserGrid(0)"><i class="fa fa-search"></i>查询</button>
         </div>
         <div class='wst-grid'>
            <div id="mmg1" class="mmg1"></div>
            <div id="pg1" style="text-align: right;"></div>
         </div>
      </div>
      <div id="template_shop" class="layui-tab-item ">
         <div class="wst-toolbar">
         账号：<input type='text' id='key2' placeholder='账号/店铺名称'/>
         <button class="btn btn-primary" onclick="javascript:loadShopGrid(0)"><i class="fa fa-search"></i>查询</button>
         </div>
         <div class='wst-grid'>
            <div id="mmg2" class="mmg2"></div>
            <div id="pg2" style="text-align: right;"></div>
         </div>
      </div>
      <div id="template_flow" class="layui-tab-item ">
         <div class="wst-toolbar">
         <select id='type'>
		    <option value=''>会员类型</option>
	        <option value='0'>会员</option>
	        <option value='1'>商家</option>
	     </select>
         <input type='text' id='key3' placeholder='账号'/>
		  <input type="text" id="startDate" name="startDate" class="ipt laydate-icon" maxLength="20"  />
		 至
		  <input type="text" id="endDate" name="endDate" class="ipt laydate-icon" maxLength="20"  />
         <button class="btn btn-primary" onclick="javascript:loadFlowGrid(0)"><i class="fa fa-search"></i>查询</button>
         </div>
         <div class='wst-grid'>
            <div id="mmg3" class="mmg3"></div>
            <div id="pg3" style="text-align: right;"></div>
         </div>
      </div>
   </div>
</div>
<script>
$(function(){initTab();})
</script>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/js/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/logmoneys/logmoneys.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>