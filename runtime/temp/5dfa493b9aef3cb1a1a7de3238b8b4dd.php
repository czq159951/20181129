<?php /*a:2:{s:61:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/navs/edit.html";i:1536627203;s:56:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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

<link href="__ADMIN__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
<script>
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',app('request')->root(true)); ?>","ROOT":"","APP":"/index.php","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'<?php echo implode(",",session("WST_STAFF.privileges")); ?>',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","ROUTES":'<?php echo WSTRoute(); ?>',"MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>","__HTTP__":"<?php echo WSTProtocol(); ?>"}
</script>
<script language="javascript" type="text/javascript" src="/static/js/common.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="j-loader"><img src="__ADMIN__/img/ajax-loader.gif"/></div>

<form id="navForm">
<table class='wst-form wst-box-top'>
       <tr>
          <th width='120'>导航位置<font color='red'>  </font>：</th>
          <td>
            <select id="navType" class='ipt' maxLength='20'>
              <option value="0">顶部</option>
              <option value="1">底部</option>
            </select>
          </td>
       </tr>
       <tr>
          <th>导航名称<font color='red'>*</font>：</th>
          <td>
              <input type="text" id="navTitle" name="navTitle" class="ipt" maxLength='50' style='width:300px;'/>
          </td>
       </tr>
       <tr>
          <th>导航链接<font color='red'>*</font>：</th>
          <td>
            <input type='text' id='navUrl' name="navUrl"  class='ipt' style='width:500px;'/>
          </td>
       </tr>
       <tr>
          <th>是否显示<font color='red'>  </font>：</th>
          <td class='layui-form'>
           <input type="checkbox" <?php if($data['isShow']==1): ?>checked<?php endif; ?> class="ipt" id="isShow" name="isShow" lay-skin="switch" lay-filter="isShow" value='1' lay-text="显示|隐藏">
          </td>
       </tr>
       <tr>
          <th>打开方式<font color='red'>*</font>：</th>
          <td class='layui-form'>

            <lable>
              <input type="radio" name="isOpen" value="1" id="isOpen" class="ipt" <?=($data['isOpen']!==0)?'checked="checked"':'';?> title='新窗口打开'/>
            </lable>
            <lable>
              <input type="radio" name="isOpen" value="0" id="isOpen" class="ipt" <?=($data['isOpen']===0)?'checked="checked"':'';?> title='页面跳转'/>
            </lable>
          </td>
       </tr>
       <tr>
          <th>导航排序号<font color='red'>*</font>：</th>
          <td>
            <input type="text" id="navSort" class="ipt" maxLength="20"  />
          </td>
       </tr>
       
    <tr>
     <td colspan='2' align='center' class='wst-bottombar'>
       <input type="hidden" id="id" value="<?php echo $data['id']+0; ?>" />
       <button type="submit" class="btn btn-primary btn-mright"><i class="fa fa-check"></i>提交</button> 
        <button type="button" class="btn" onclick="javascript:history.go(-1)"><i class="fa fa-angle-double-left"></i>返回</button>
     </td>
  </tr>
</table>
</form>


<script>
$(function(){
    WST.setValues(<?=json_encode($data)?>);
});
</script>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/navs/navs.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>