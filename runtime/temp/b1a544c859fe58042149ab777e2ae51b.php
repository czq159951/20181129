<?php /*a:2:{s:73:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/settlements/list_shop.html";i:1536647730;s:56:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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

<div id='alertTips' class='alert alert-success alert-tips fade in'>
  <div id='headTip' class='head'><i class='fa fa-lightbulb-o'></i>操作说明（点击隐藏）</div>
  <ul class='body'>
    <li>本功能主要用于按商家进行批量结算。结算时不需要商家申请，管理员点击“生成结算单”则自动完成订单的佣金结算和在线支付订单返款。</li>
    <li>订单结算款会返还到商家在本系统中的商家钱包中</li>
  </ul>
</div>
<div class="wst-toolbar">
<select id="areaId1" class='j-ipt j-areas' level="0" onchange="WST.ITAreas({id:'areaId1',val:this.value,className:'j-areas'});">
  <option value="">-商家所在地-</option>
  <?php if(is_array($areaList) || $areaList instanceof \think\Collection || $areaList instanceof \think\Paginator): $i = 0; $__LIST__ = $areaList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
  <option value="<?php echo $vo['areaId']; ?>"><?php echo $vo['areaName']; ?></option>
  <?php endforeach; endif; else: echo "" ;endif; ?>
</select>
<input type="text" name="shopName"  placeholder='店铺名称/店铺编号' id="shopName" class='j-ipt'/>
<button class="btn btn-primary" type='button' onclick='javascript:loadShopGrid()'><i class='fa fa-search'></i>查询</button>
<?php if(WSTGrant('SJJS_04')): ?>
<button class="btn btn-primary" type='button' onclick='javascript:generateSettleByShop()'><i class="fa fa-check-circle"></i>生成结算单</button>
<?php endif; ?>
<div style='clear:both'></div>
</div>
<div class='wst-grid'>
<div id="mmg" class="mmg"></div>
<div id="pg" style="text-align: right;"></div>
</div>
<script>
$(function(){
	initGrid();
})
</script>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/js/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/settlements/settlements.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>