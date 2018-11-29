<?php /*a:2:{s:63:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/orders/list.html";i:1536627215;s:56:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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

<input type="hidden" id="userId" class="j-ipt" value='<?php echo $userId; ?>' autocomplete="off"/>
<div class="wst-toolbar">
<div id='moreItem' class='hide'>
  <select id="areaId1" class='ipt j-ipt' level="0" onchange="WST.ITAreas({id:'areaId1',val:this.value,className:'j-ipt'});">
  <option value="-1">-商家所在地-</option>
  <?php if(is_array($areaList) || $areaList instanceof \think\Collection || $areaList instanceof \think\Paginator): $i = 0; $__LIST__ = $areaList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
  <option value="<?php echo $vo['areaId']; ?>"><?php echo $vo['areaName']; ?></option>
  <?php endforeach; endif; else: echo "" ;endif; ?>
</select>
<select id='orderCode' class='j-ipt'>
 <option value=''>订单来源</option>
 <?php $_result=WSTOrderModule();if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$om): $mod = ($i % 2 );++$i;?>
 <option value="<?php echo $om['name']; ?>"><?php echo $om['name']; ?></option>
 <?php endforeach; endif; else: echo "" ;endif; ?>
</select>
<select id='isRefund' class='j-ipt'>
 <option value='-1'>是否退款</option>
 <option value='1'>是</option>
 <option value='0'>否</option>
</select>
<select id='isInvoice' class='j-ipt'>
 <option value='-1'>是否要发票</option>
 <option value='1'>是</option>
 <option value='0'>否</option>
</select>
<input type="text" id="startDate" style='width:120px' name="startDate" class="laydate-icon j-ipt" maxLength="20" value='' placeholder='下单开始日期'/>
至
<input type="text" id="endDate" name="endDate" style='width:120px' class="laydate-icon j-ipt" maxLength="20" value='' placeholder='下单结束日期'/>
</div>
<input type="text" name="orderNo"  placeholder='订单编号' id="orderNo" class='j-ipt'/>
<input type="text" name="shopName"  placeholder='店铺名称/店铺编号' id="shopName" class='j-ipt'/>
<select id='orderStatus' class='j-ipt' style='margin-top:2px'>
  <option value='10000'>订单状态</option>
  <option value='0'>待发货</option>
  <option value='-2'>待支付</option>
  <option value='-1'>已取消</option>
  <option value='1'>配送中</option>
  <option value='2'>已收货</option>
  <option value='-3'>用户拒收</option>
</select>
<select id='payType' class='j-ipt' style='margin-top:2px'>
   <option value='-1'>支付方式</option>
   <option value='0'>货到付款</option>
   <option value='1'>在线支付</option>
</select>
<select id='deliverType' class='j-ipt' style='margin-top:2px'>
 <option value='-1'>配送方式</option>
 <option value='1'>自提</option>
 <option value='0'>送货上门</option>
</select>

<select id='payFrom' class='j-ipt'>
 <option value=''>支付来源</option>
 <?php $_result=WSTLangPayFrom('',1);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$paySrc): $mod = ($i % 2 );++$i;?>
 <option value='<?php echo $paySrc['payCode']; ?>'><?php echo $paySrc['payName']; ?></option>
 <?php endforeach; endif; else: echo "" ;endif; ?>
</select>  
   <button class="btn btn-primary" onclick='javascript:loadGrid(0)'><i class="fa fa-search"></i>查询</button>
   <button class="btn btn-primary" onclick='javascript:loadMore()'><i class="fa fa-search"></i>更多查询项</button>
   <?php if(($userId>0)): ?><button class="btn f-right btn-fixtop" onclick="javascript:history.go(-1)" style="margin-left: 10px;"><i class="fa fa-angle-double-left"></i>返回</button><?php endif; ?>
   <button class="btn btn-primary f-right btn-fixtop" onclick='javascript:toExport(0)'><i class="fa fa-sign-in"></i>导出</button>
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
<script src="__ADMIN__/orders/orders.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>