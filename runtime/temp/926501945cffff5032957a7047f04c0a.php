<?php /*a:2:{s:62:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/areas/list.html";i:1536647730;s:56:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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

<input type='hidden' id='h_areaId' value='<?php echo $pArea["areaId"]; ?>'/>
<input type='hidden' id='h_parentId' value='<?php echo $pArea["parentId"]; ?>'/>
<div class="wst-toolbar">
  <?php if(($pArea['areaId'] != 0)): ?>
      上级地区：<?php echo $pArea['areaName']; ?>
  <button class="btn f-right" onclick='javascript:toReturn(0)'><i class="fa fa-angle-double-left"></i>返回</button>
  <?php endif; if(WSTGrant('DQGL_01')): ?>
  <button class="btn btn-primary f-right btn-mright" onclick='javascript:toEdit(0,<?php echo $pArea["areaId"]; ?>)'><i class='fa fa-plus'></i>新增</button>
  <?php endif; ?>
  <div style='clear:both'></div>
</div>
<form lay-filter='gridForm' class='layui-form wst-grid'>
<div id="mmg" class="mmg"></div>
</form>
<div id="pg" style="text-align: right;"></div>
<div id='areasBox' style='display:none'>
  <form id='areaForm' autocomplete="off" class='layui-form'>
  <input type='hidden' class='ipt' id='areaId' />
  <input type='hidden' class='ipt' id='parentId' />
  <table class='wst-form wst-box-top'>
     <tr>
        <th width='100'>地区名称<font color='red'>*</font>：</th>
        <td><input type='text' id='areaName' name="areaName" class='ipt' maxLength='20' style='width:200px;' onblur='javascript:letterOnblur(this)'/></td>
     </tr>
     <tr>
        <th width='100'>是否显示<font color='red'>*</font>：</th>
        <td height='24'>
           <input type="checkbox" id='isShow' name='isShow' value="1" lay-skin="switch" lay-filter="switchTest" class="ipt" lay-text="显示|隐藏">
        </td>
     </tr>
     <tr>
        <th width='100'>排序字母<font color='red'>*</font>：</th>
        <td><input type='text' id='areaKey' name='areaKey' class='ipt' style='width:60px;'  maxLength='1'/></td>
     </tr>
     <tr>
        <th width='100'>排序号<font color='red'>*</font>：</th>
        <td><input type='text' id='areaSort' name='areaSort' class='ipt' style='width:60px;' onkeypress='return WST.isNumberKey(event);' onkeyup="javascript:WST.isChinese(this,1)" maxLength='10' value='0'/></td>
     </tr>
  </table>
  </form>
</div>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/js/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/areas/areas.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script>
$(function(){initGrid();})
</script>

<?php echo hook('initCronHook'); ?>
</body>
</html>