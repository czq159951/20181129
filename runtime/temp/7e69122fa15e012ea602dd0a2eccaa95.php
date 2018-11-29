<?php /*a:2:{s:42:"addons/distribut/view/admin/user_list.html";i:1536627275;s:60:"addons/distribut/view/../../../shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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

<div class="l-loading" style="display: block" id="wst-loading"></div>
<div class="wst-toolbar">
	 <input type="text" name="loginName"  placeholder='会员账号' id="loginName" class="query" />
	 <input type="text" name="loginPhone" placeholder='手机号码' id="loginPhone" class="query" />
	 <input type="text" name="loginEmail" placeholder='电子邮箱' id="loginEmail" class="query" />
	 <button type="button"  class='btn btn-primary' onclick="javascript:userQuery()"><i class="fa fa-search"></i>查询</button>
</div>
<div class='wst-grid'>
 <div id="mmg" class="mmg"></div>
 <div id="pg" style="text-align: right;"></div>
</div>
<script>
$(function(){initGrid()});
var grid;
function initGrid(){
   var h = WST.pageHeight();
   var cols = [
            {title:'账号;', name:'loginName', width: 50},
            {title:'用户名', name:'userName' ,width:100},
            {title:'手机号码', name:'userPhone' ,width:30},
            {title:'电子邮箱', name:'userEmail' ,width:30},
            {title:'积分', name:'userScore' ,width:30},
            {title:'注册时间', name:'createTime' ,width:30},
            {title:'状态', name:'userStatus' ,width:30, renderer: function(val,item,rowIndex){
                return (val==1)?"<span class='statu-yes'><i class='fa fa-check-circle'></i> 启用</span>":"<span class='statu-no'><i class='fa fa-clock-o'></i> 停用</span>";
            }},
            {title:'总佣金', name:'distributMoney' ,width:80, align:'center'},
            {title:'推广用户数', name:'userCnt' ,width:30},
            {title:'操作', name:'userCnt' ,width:30,renderer: function(val,item,rowIndex){
                return "<a class='btn btn-blue' href='"+WST.U('addon/distribut-distribut-admindistributchildusers',{'userId':item['parentId']})+"'><i class='fa fa-search'></i>查看</a> ";
            }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-85,indexCol: true, indexColWidth:50, cols: cols,method:'POST',
        url: "<?php echo addon_url('distribut://distribut/queryAdminDistributUsers'); ?>", fullWidthRows: true, autoLoad: true,
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