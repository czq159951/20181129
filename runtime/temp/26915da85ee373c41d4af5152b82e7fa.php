<?php /*a:2:{s:66:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/homemenus/list.html";i:1536647730;s:56:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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

<style>.mmGrid{border-bottom:0px;}</style>
<div id='alertTips' class='alert alert-success alert-tips fade in'>
  <div id='headTip' class='head'><i class='fa fa-lightbulb-o'></i>操作说明（点击隐藏）</div>
  <ul class='body'>
    <li>本功能为开发者功能，主要用于设置电脑版买家和卖家菜单，普通使用者请勿随意修改，以免影响系统使用。</li>
  </ul>
</div>
<div class="wst-toolbar">
   <select id='s_menuType' onchange='loadGrid()'>
      <option value='-1'>菜单类型</option>
      <option value='0'>用户菜单</option>
      <option value='1'>商家菜单</option>
   </select>
   <?php if(WSTGrant('QTCD_01')): ?>
   <button class="btn btn-primary f-right" onclick="javascript:toEdit(0,0)"><i class='fa fa-plus'></i>新增</button>
   <?php endif; ?>
   <div style="clear:both"></div>
</div>
<div class='wst-grid'>
<div class='mmGrid layui-form' id="maingrid"></div>
</div>
<div id='menuBox' style='display:none'>
    <form id='menuForm'>
    <table class='wst-form wst-box-top'>
       <tr>
          <th>菜单名称<font color='red'>*</font>：</th>
          <td>
              <input type="text" id="menuName" name="menuName" class="ipt" maxLength='20' />
          </td>
       </tr>
       <tr>
          <th>菜单Url<font color='red'>*</font>：</th>
          <td>
              <input type="text" id="menuUrl" name="menuUrl" class="ipt" maxLength='200' style='width:300px'/>
          </td>
       </tr>
       <tr>
          <th>附加资源：</th>
          <td>
              <textarea id="menuOtherUrl" name="menuOtherUrl" class="ipt" style='width:80%'></textarea>
          </td>
       </tr>
       
       <tr id="menuTypes">
          <th>菜单类型<font color='red'>*</font>：</th>
            <td>
        <select id="menuType" class="ipt">
          <option value="0">用户菜单</option>
          <option value="1">商家菜单</option>
        </select>
            </td>
        </tr>

       <tr>
          <th>菜单排序<font color='red'>*</font>：</th>
          <td>
              <input type="text" id="menuSort" name="menuSort" class="ipt" maxLength='20' />
          </td>
       </tr>
       <tr>
          <th>是否显示<font color='red'>  </font>：</th>
          <td class="layui-form">
            <input type="checkbox" id="isShow" name="isShow" value="1" class="ipt" lay-skin="switch" lay-filter="isShow1" lay-text="显示|隐藏">
          </td>
       </tr>
    </table>
    </form>
</div>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/js/wstgridtree.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/homemenus/homemenus.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>