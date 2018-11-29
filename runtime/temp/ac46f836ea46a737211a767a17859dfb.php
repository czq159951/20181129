<?php /*a:2:{s:69:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/templatemsgs/list.html";i:1536647730;s:56:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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
<style>
body{overflow:hidden;}
</style>

<link href="__ADMIN__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
<script>
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',app('request')->root(true)); ?>","ROOT":"","APP":"/index.php","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'<?php echo implode(",",session("WST_STAFF.privileges")); ?>',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","ROUTES":'<?php echo WSTRoute(); ?>',"MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>","__HTTP__":"<?php echo WSTProtocol(); ?>"}
</script>
<script language="javascript" type="text/javascript" src="/static/js/common.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="j-loader"><img src="__ADMIN__/img/ajax-loader.gif"/></div>

<div class="layui-tab layui-tab-brief " lay-filter="msgTab">
	<ul class="layui-tab-title">
	  <li <?php if($src==0): ?>class="layui-this"<?php endif; ?> onclick="initGridMsg(0)">消息模板</li>
	  <li <?php if($src==1): ?>class="layui-this"<?php endif; ?> onclick="initGridEmail(0)">邮件模板</li>
	  <li <?php if($src==2): ?>class="layui-this"<?php endif; ?> onclick="initGridSMS(0)">短信模板</li>
	</ul>
	<div class="layui-tab-content " style="padding: 0px 0;">
	 	<div id="template_msg" class="layui-tab-item <?php if($src==0): ?>layui-show<?php endif; ?>">
           <div class='wst-grid' style='margin-top:5px'>
			<div id="mmg1" class="mmg1 layui-form"></div>
			<div id="pg1" style="text-align: right;"></div>
		  </div>
        </div>
	 	<div id="template_email" class="layui-tab-item <?php if($src==1): ?>layui-show<?php endif; ?>">
           <div class='wst-grid' style='margin-top:5px'>
			<div id="mmg2" class="mmg2 layui-form"></div>
			<div id="pg2" style="text-align: right;"></div>
		  </div>
        </div>
	 	<div id="template_sms" class="layui-tab-item <?php if($src==2): ?>layui-show<?php endif; ?>">
	 	   <div id='alertTips' class='alert alert-success alert-tips fade in'>
		   <div id='headTip' class='head'><i class='fa fa-lightbulb-o'></i>操作说明（点击隐藏）</div>
		   <ul class='body'>
		    <li>本功能主要用于管理短信发送的模板格式。</li>
		    <li>若短信服务商未要求预先定义模板，则发送时以本系统模板为主（例如中国网建）。</li>
		    <li>若短信服务商要求必须预先定义模板的(例如阿里云-云通信)，则本系统中的模板仅作为建模参考，发送格式以短信服务商上的模板为主。</li>
		   </ul>
		  </div>
		  <div class='wst-grid'>
			<div id="mmg3" class="mmg3 layui-form"></div>
			<div id="pg3" style="text-align: right;"></div>
		  </div>
        </div>
    </div>
</div>
<script>
	$(function(){
		h = WST.pageHeight();
		<?php if($src==1): ?>
			initGridEmail(0);
		<?php elseif($src==2): ?>
			initGridSMS(0);
		<?php else: ?>
			initGridMsg(0);
		<?php endif; ?>
	});
</script>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/js/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/templatemsgs/templatemsgs.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>