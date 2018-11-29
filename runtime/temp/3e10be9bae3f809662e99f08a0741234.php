<?php /*a:2:{s:47:"/home/mart/shangtao/admin/view/staffs/list.html";i:1536506331;s:40:"/home/mart/shangtao/admin/view/base.html";i:1534832972;}*/ ?>
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
    <li>该功能主要用于设置商城职员信息，可以对职员的操作权限，账号，密码等进行设置。</li>
  </ul>
</div>
<div class="wst-toolbar">
   <input id='key' type='text' class='form-control' placeholder='账号/姓名/编号'>
   <button class="btn btn-primary" onclick='javascript:loadGrid(0)' style="border-bottom: 4px;"><i class='fa fa-search'></i>查询</button>
   <?php if(WSTGrant('ZYGL_01')): ?>
   <button class="btn btn-success f-right" onclick='javascript:toEdit(0)'><i class='fa fa-plus'></i>新增</button>
   <?php endif; ?>
   <div style='clear:both'></div>
</div>
<div class='wst-grid'>
<div id="mmg" class="mmg"></div>
<div id="pg" style="text-align: right;"></div>
</div>
<div id='editPassBox' style='display:none;padding-top:5px;'>
  <form id='editPassFrom' autocomplete="off">
   <table class='wst-form'>
      <tr>
         <th>新密码：</th>
         <td><input type='password' id='newPass' name='newPass' class='ipt' data-rule="新密码: required;length[6~]" maxLength='16'/></td>
      </tr>
      <tr>
         <th>确认密码：</th>
         <td><input type='password' id='newPass2' name='newPass2' class='ipt' data-rule="确认密码: required;match(newPass);" maxLength='16'/></td>
      </tr>
   </table>
  </form>
</div>
<script>
$(function(){initGrid(<?php echo app('session')->get('WST_STAFF.staffId'); ?>);})
</script>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/js/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/staffs/staffs.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>