<?php /*a:2:{s:68:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/articlecats/list.html";i:1536647730;s:56:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',app('request')->root(true)); ?>","ROOT":"","APP":"","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'<?php echo implode(",",session("WST_STAFF.privileges")); ?>',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","ROUTES":'<?php echo WSTRoute(); ?>',"MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>","__HTTP__":"<?php echo WSTProtocol(); ?>"}
</script>
<script language="javascript" type="text/javascript" src="/static/js/common.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="j-loader"><img src="__ADMIN__/img/ajax-loader.gif"/></div>

<div id='alertTips' class='alert alert-success alert-tips fade in'>
  <div id='headTip' class='head'><i class='fa fa-lightbulb-o'></i>操作说明（点击隐藏）</div>
  <ul class='body'>
      <li>本功能主要用于文章分类的管理。分类可分为“普通菜单”和“系统系统”。</li>
      <li>“系统菜单”一般在商城前台有进行调用，非开发者请勿直接删除“系统菜单”，以免造成数据丢失。</li>
  </ul>
</div>
<style>.mmGrid{border-bottom:0px;}</style>
<?php if(WSTGrant('WZFL_01')): ?>
<div class="wst-toolbar">
  <button class="btn btn-primary f-right" onclick='javascript:toEdit(0)'><i class='fa fa-plus'></i>新增</button>
  <div style='clear:both'></div>
</div>
<?php endif; ?>
<div class='wst-grid'>
<div class='mmGrid layui-form' id="maingrid"></div>
</div>
<div id='articlecatBox' style='display:none' class="layui-form">
  <form id='articlecatForm' autocomplete="off">
  <input type='hidden' id='parentId' name="parentId" class='ipt' />
  <table class='wst-form wst-box-top'>
     <tr>
        <th width='100'>分类名称<font color='red'>*</font>：</th>
        <td><input type='text' id='catName' name="catName" class='ipt' maxLength='20' style='width:200px;'/></td>
     </tr>
     <tr>
        <th width='100'>是否显示<font color='red'>*</font>：</th>
        <td height='24'>
           <input type="checkbox" id="isShow" name="isShow" value="1" class="ipt" lay-skin="switch" lay-filter="isShow1" lay-text="显示|隐藏">
        </td>
     </tr>
     <tr>
        <th width='100'>排序号<font color='red'>*</font>：</th>
        <td><input type='text' id='catSort' name='catSort' class='ipt' style='width:60px;' onkeypress='return WST.isNumberKey(event);' onkeyup="javascript:WST.isChinese(this,1)" maxLength='10' value='0'/></td>
     </tr>
  </table>
  </form>
</div>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/js/wstgridtree.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/articlecats/articlecats.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script>
$(function(){initGrid();})
</script>

<?php echo hook('initCronHook'); ?>
</body>
</html>