<?php /*a:2:{s:71:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/ordercomplains/list.html";i:1536627198;s:56:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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

<div class="wst-toolbar">
<select id="areaId1" class='ipt j-ipt hide' level="0" onchange="WST.ITAreas({id:'areaId1',val:this.value,className:'j-ipt'});">
  <option value="-1">-商家所在地-</option>
  <?php if(is_array($areaList) || $areaList instanceof \think\Collection || $areaList instanceof \think\Paginator): $i = 0; $__LIST__ = $areaList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
  <option value="<?php echo $vo['areaId']; ?>"><?php echo $vo['areaName']; ?></option>
  <?php endforeach; endif; else: echo "" ;endif; ?>
</select>
<input type="text" name="orderNo"  placeholder='订单编号' id="orderNo" class='j-ipt'/>
<select id='complainStatus' class='j-ipt'>
 <option value='-1'>投诉处理状态</option>
 <option value='0'>新投诉</option>
 <option value='1'>转给应诉人</option>
 <option value='2'>应诉人回应</option>
 <option value='3'>等待仲裁</option>
 <option value='4'>已仲裁</option>
</select>
<input type="text" id="startDate" name="startDate" class="laydate-icon j-ipt" maxLength="20" value='' placeholder='开始日期'/>
至
<input type="text" id="endDate" name="endDate" class="laydate-icon j-ipt" maxLength="20" value='' placeholder='结束日期'/>
   <button class="btn btn-primary" onclick='javascript:loadGrid(0)'><i class="fa fa-search"></i>查询</button>
   <div style='clear:both'></div>
</div>
<div class='wst-grid'>
 <div id="mmg" class="mmg"></div>
 <div id="pg" style="text-align: right;"></div>
</div>
<script>
$(function(){initGrid();})
</script>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/js/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/ordercomplains/ordercomplains.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>