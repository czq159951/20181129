<?php /*a:2:{s:64:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/accreds/list.html";i:1536647730;s:56:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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
<link rel="stylesheet" type="text/css" href="/static/plugins/webuploader/webuploader.css?v=<?php echo $v; ?>" />

<link href="__ADMIN__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
<script>
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',app('request')->root(true)); ?>","ROOT":"","APP":"/index.php","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'<?php echo implode(",",session("WST_STAFF.privileges")); ?>',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","ROUTES":'<?php echo WSTRoute(); ?>',"MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>","__HTTP__":"<?php echo WSTProtocol(); ?>"}
</script>
<script language="javascript" type="text/javascript" src="/static/js/common.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="j-loader"><img src="__ADMIN__/img/ajax-loader.gif"/></div>

<?php if(WSTGrant('RZGL_01')): ?>
<div class="wst-toolbar">
   <button class="btn btn-primary f-right" onclick="javascript:toEdit(0)"><i class='fa fa-plus'></i>新增</button>
   <div style="clear:both"></div>
</div>
<?php endif; ?>
<div class='wst-grid'>
 <div id="mmg" class="mmg"></div>
 <div id="pg" style="text-align: right;"></div>
</div>

<div id='accredBox' style='display:none'>
    <form id='accredForm' method="post" autocomplete="off">

    <table class='wst-form wst-box-top'>
       <tr>
          <th width='150'>认证名称<font color='red'>*</font>：</th>
          <td><input type='text' id='accredName' name="accredName"  class='ipt' maxLength='20'/></td>
       </tr>
       <tr>
          <th>图标：</th>
          <td>
            <div id='adFilePicker'>上传图标</div><span id='uploadMsg'></span>
            <input type='hidden' id='accredImg' name="accredImg" class="ipt" />
          </td>
       </tr>
       <tr>
          <th>预览图：</th>
          <td><div style="min-height:70px;" id="preview"></div></td>
       </tr>
    </table>

    </form>

  </div>
<script>
  $(function(){initGrid()});
</script>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/js/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/accreds/accreds.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script type='text/javascript' src='/static/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>'></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>