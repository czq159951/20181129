<?php /*a:2:{s:42:"addons/distribut/view/admin/shop_list.html";i:1536627275;s:60:"addons/distribut/view/../../../shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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
   <div id="query" style="float:left;">
	   	<input type="text" name="shopSn"  placeholder='店铺编号' id="shopSn" class="query" />
	   	<input type="text" name="shopName" placeholder='店铺名称' id="shopName" class="query" />
	   	<input type="text" name="shopkeeper" placeholder='店主姓名' id="shopkeeper" class="query" />
	   	<button type="button"  class='btn btn-primary btn-mright' onclick="javascript:userQuery()"><i class="fa fa-search"></i>查询</button>
	</div>
   <div style="clear:both"></div>
</div>
<div class='wst-grid'>
 <div id="mmg" class="mmg"></div>
 <div id="pg" style="text-align: right;"></div>
</div>
<script>
$(function(){initGrid();})
  var mmg;
  function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'店铺编号', name:'shopSn', width: 50},
            {title:'店铺名称', name:'shopName' ,width:100},
            {title:'店主姓名', name:'shopkeeper' ,width:30},
            {title:'店主联系电话', name:'telephone' ,width:30},
            {title:'店主店铺地址', name:'shopAddress' ,width:30},
            {title:'所属公司', name:'shopCompany' ,width:30},
            {title:'分销模式', name:'saleNum' ,width:30, renderer: function(val,item,rowIndex){
                return (item['distributType']==1?"按商品设置提取佣金":"按订单比例提取佣金");
            }},
            {title:'购买者分成', name:'buyerRate' ,width:30},
            {title:'第二级分成', name:'secondRate' ,width:30},
            {title:'第三级分成', name:'thirdRate' ,width:30},
            {title:'营业状态', name:'commission' ,width:80, align:'center', renderer: function(val,item,rowIndex){
                return (item['shopAtive']==1)?"<span class='statu-yes'><i class='fa fa-check-circle'></i> 营业中</span>":"<span class='statu-wait'><i class='fa fa-clock-o'></i> 休息中</span>";
            }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-85,indexCol: true,indexColWidth:50,  cols: cols,method:'POST',
        url: "<?php echo addon_url('distribut://distribut/queryadmindistributshops'); ?>", fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });  
}
function userQuery(){
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