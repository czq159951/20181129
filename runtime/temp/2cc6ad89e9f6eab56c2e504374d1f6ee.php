<?php /*a:2:{s:62:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/datas/list.html";i:1536647730;s:56:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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

<link href="__ADMIN__/js/ztree/css/zTreeStyle/zTreeStyle.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
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
    <li>本功能主要提供系统基础数据元的管理功能，开发者可以在此配置系统的基础选项值。</li>
    <li>该功能为开发者功能，普通使用者请勿随意修改，以免影响系统使用。</li>
  </ul>
</div>
<div class="j-layout">
    <div class='j-layout-left'>
        <div class='j-layout-panel layui-colla-title'>数据分类管理</div>
        <ul id="menuTree" class="ztree" style='overflow:auto'></ul>
    </div>
    <div class='j-layout-center' style='border:1px solid #ccc;float:left;margin-left:5px;'>
      <div class='j-layout-panel layui-colla-title'>数据管理</div>
      <?php if(WSTGrant('SJGL_01')): ?>
      <div class="wst-toolbar" style='display:none'>
          <button class="btn btn-primary f-right" onclick='javascript:toEdit(0)'><i class='fa fa-plus'></i>新增</button>
          <div style='clear:both'></div>
      </div>
      <?php endif; ?>
      <div id="maingrid"  style='display:none'>
        <div id="mmg" class="mmg"></div>
        <div id="pg" style="text-align: right;"></div>
      </div>
    </div>
</div>
<div id='menuBox' style='display:none'>
<form id='menuForm'>
  <table class='wst-form wst-box-top'>
     <tr>
        <th width='100'>数据分类名称<font color='red'>*</font>：</th>
        <td><input type='text' id='catName' class='ipt2' data-rule="数据分类名称: required;"/></td>
     </tr>
     <tr>
        <th width='100'>数据分类代码<font color='red'>*</font>：</th>
        <td><input type='text' id='catCode' class='ipt2' data-rule="数据分类代码: required;"  /></td>
     </tr>
  </table>
</form>
</div>
<div id='dataBox' style='display:none'>
  <form id='dataForm' autocomplete='off'>
  <table class='wst-form wst-box-top'>
     <tr>
        <th width='100'>数据名称<font color='red'>*</font>：</th>
        <td><input type='text' id='dataName' class='ipt' data-rule="数据名称: required;"/></td>
     </tr>
     <tr>
        <th>数据值<font color='red'>*</font>：</th>
        <td><input type='text' id='dataVal' class='ipt' data-rule="数据值: required;"/></td>
     </tr>
     <tr>
        <th>排序号<font color='red'> </font>：</th>
        <td><input onkeypress='return WST.isNumberKey(event);' onkeyup="javascript:WST.isChinese(this,1)" type='text' id='dataSort' class='ipt' /></td>
     </tr>
  </table>
  </form>
</div>
<div id="rMenu">
  <ul>
    <?php if(WSTGrant('SJFL_01')): ?><li id="m_add" >新增分类</li><?php endif; if(WSTGrant('SJFL_02')): ?><li id="m_edit">编辑分类</li><?php endif; if(WSTGrant('SJFL_03')): ?><li id="m_del" style='border-bottom:0px;'>删除分类</li><?php endif; ?>
  </ul>
</div>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/js/ztree/jquery.ztree.all-3.5.js?v=<?php echo $v; ?>"></script>
<script src="__ADMIN__/js/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/datas/datas.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>