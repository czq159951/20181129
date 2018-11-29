<?php /*a:2:{s:68:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/adpositions/edit.html";i:1536627215;s:56:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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

<link rel="stylesheet" type="text/css" href="/static/plugins/webuploader/webuploader.css?v=<?php echo $v; ?>" />

<link href="__ADMIN__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
<script>
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',app('request')->root(true)); ?>","ROOT":"","APP":"/index.php","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'<?php echo implode(",",session("WST_STAFF.privileges")); ?>',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","ROUTES":'<?php echo WSTRoute(); ?>',"MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>","__HTTP__":"<?php echo WSTProtocol(); ?>"}
</script>
<script language="javascript" type="text/javascript" src="/static/js/common.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="j-loader"><img src="__ADMIN__/img/ajax-loader.gif"/></div>

<form id="adPositionsForm" autocomplete="off">
<table class='wst-form wst-box-top'>
  <tr>
      <th width='150'>位置类型<font color='red'>*</font>：</th>
          <td>
            <select id="positionType" name="positionType" class='ipt' maxLength='20'>
              <option value="">-请选择-</option>
              <?php $_result=WSTDatas('ADS_TYPE');if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
              <option <?=($data['positionType']==$vo['dataVal'])?'selected':'';?> value="<?php echo $vo['dataVal']; ?>"><?php echo $vo['dataName']; ?></option>
              <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
          </td>
       </tr>
       <tr>
          <th>位置名称<font color='red'>*</font>：</th>
          <td>
            <input type="text" id="positionName" name="positionName" value='<?php echo $data['positionName']; ?>' class="ipt" />
          </td>
       </tr>
       <tr>
          <th>位置代码<font color='red'>*</font>：</th>
          <td>
            <input type="text" id="positionCode" name="positionCode" value='<?php echo $data['positionCode']; ?>' class="ipt" />
          </td>
       </tr>
       <tr>
          <th>建议宽度<font color='red'>*</font>：</th>
          <td>
            <input type="text" id="positionWidth" name="positionWidth" value='<?php echo $data['positionWidth']; ?>' class="ipt" maxLength='4' />
          </td>
       </tr>
       <tr>
          <th>建议高度<font color='red'>*</font>：</th>
          <td>
            <input type='text' id='positionHeight' name="positionHeight" value='<?php echo $data['positionHeight']; ?>' class='ipt' maxLength='4'/>
          </td>
       </tr>
       <tr>
          <th>排序号<font color='red'> </font>：</th>
          <td>
            <input type='text' id='apSort' name="apSort" value='<?php echo $data['apSort']; ?>' class='ipt' maxLength='10'/>
          </td>
       </tr>
  
  <tr>
     <td colspan='2' align='center' class='wst-bottombar'>
       <input type="hidden" name="id" id="positionId" class="ipt" value="<?php echo $data['positionId']+0; ?>" />
       <button type="submit" class='btn btn-primary btn-mright'><i class="fa fa-check"></i>提交</button>
       <button type="button" onclick="javascript:history.go(-1)" class='btn'><i class="fa fa-angle-double-left"></i>返回</button>
     </td>
  </tr>
</table>
</form>
<script>
$(function(){editInit()});
</script>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script type='text/javascript' src='/static/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>'></script>
<script src="__ADMIN__/adpositions/adpositions.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>