<?php /*a:2:{s:68:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/settlements/list.html";i:1536627203;s:56:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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
    <li>本功能主要用于处理来自于商家订单的结算申请，结算时不管在线支付和货到付款都会先收取订单佣金，然后再返还在线支付的结算款。</li>
    <li>订单结算款会返还到商家在本系统中的商家钱包中</li>
  </ul>
</div>
<div class="wst-toolbar">
<input type="text" id="startDate" style='width:120px' name="startDate" class="laydate-icon j-ipt" maxLength="20" value='' placeholder='申请开始日期'/>
至
<input type="text" id="endDate" name="endDate" style='width:120px' class="laydate-icon j-ipt" maxLength="20" value='' placeholder='申请结束日期'/>
<input type="text" name="settlementNo"  placeholder='结算单号' id="settlementNo" class='j-ipt'/>
<input type="text" name="shopName"  placeholder='店铺名称/店铺编号' id="shopName" class='j-ipt'/>
<select id='settlementStatus' class='j-ipt'>
    <option value='-1'>全部</option>
    <option value='0'>未结算</option>
    <option value='1'>已结算</option>
</select>
<button class="btn btn-primary" onclick='javascript:loadGrid(0)'><i class='fa fa-search'></i>查询</button>
<button class="btn btn-primary f-right btn-fixtop" onclick='javascript:toExport(0)'><i class="fa fa-sign-in"></i>导出</button>
<div style='clear:both'></div>
</div>
<div class='wst-grid'>
<div id="mmg" class="mmg"></div>
<div id="pg" style="text-align: right;"></div>
</div>
<script>
$(function(){
	initGrid();
    var laydate = layui.laydate;
    laydate.render({
        elem: '#startDate'
    });
    laydate.render({
        elem: '#endDate'
    });
})
</script>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/js/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/settlements/shopsettlements.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>