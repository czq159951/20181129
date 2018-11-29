<?php /*a:2:{s:43:"addons/distribut/view/admin/money_list.html";i:1536627275;s:60:"addons/distribut/view/../../../shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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

<div class="wst-toolbar">
	<input type="text" name="orderNo" placeholder='订单号' id="orderNo" class="query" />
	<input type="text" name="userName" placeholder='用户名称' id="userName" class="query" />
<button class="btn btn-primary" onclick='javascript:moneyQuery()'><i class='fa fa-search'></i>查询</button>
<div style='clear:both'></div>
</div>
<div class='wst-grid'>
 <div id="mmg" class="mmg"></div>
 <div id="pg" style="text-align: right;"></div>
</div>
<script>
$(function(){
	  initGrid();
});
 var mmg;
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'订单编号', name:'orderNo', width: 50},
            {title:'获佣用户', name:'userName' ,width:100},
            {title:'佣金描述', name:'remark' ,width:30},
            {title:'佣金金额', name:'distributMoney' ,width:30},
            {title:'记录时间', name:'createTime' ,width:30}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-85,indexCol: true,indexColWidth:50,  cols: cols,method:'POST',
        url: "<?php echo addon_url('distribut://distribut/queryadmindistributmoneys'); ?>", fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });
}
function moneyQuery(){
	var query = WST.getParams('.query');
	mmg.load(query);
}
</script>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/js/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>