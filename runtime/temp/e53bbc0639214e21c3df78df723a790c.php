<?php /*a:2:{s:43:"addons/distribut/view/admin/goods_list.html";i:1536627275;s:60:"addons/distribut/view/../../../shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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
<input type="text" name="shopName"  placeholder='店铺名称/店铺编号' id="shopName" class='query'/>
<input type="text" name="goodsName"  placeholder='商品名称/商品编号' id="goodsName" class='query'/>
<button class="btn btn-primary" onclick='javascript:goodsQuery()'><i class='fa fa-search'></i>查询</button>
<div style='clear:both'></div>
</div>
<div class='wst-grid'>
 <div id="mmg" class="mmg"></div>
 <div id="pg" style="text-align: right;"></div>
</div>
<script>
$(function(){initGrid();});
  var mmg;
  function initGrid(){
  	var h = WST.pageHeight();
    var cols = [
            {title:'&nbsp;', name:'goodsImg', width: 50,renderer:function(val,item,rowIndex){
                var thumb = item['goodsImg'];
	        	thumb = thumb.replace('.','_thumb.');
            	return "<span class='weixin'><img id='img' onmouseout='toolTip()' onmouseover='toolTip()' style='height:60px;width:60px;' src='"+WST.conf.ROOT+"/"+thumb
            	+"'><span class='imged' ><img  style='height:180px;width:180px;' src='"+WST.conf.ROOT+"/"+item['goodsImg']+"'></span></span>";
            }},
            {title:'商品名称', name:'goodsName' ,width:100,renderer:function(val,item,rowIndex){
            	 return "<a style='color:blue;' href='"+WST.U('home/goods/detail',{"goodsId":item['goodsId']})+"' target='_blank'>"+item['goodsName']+"</a>";
            }},
            {title:'商品编号', name:'goodsSn' ,width:30},
            {title:'价格', name:'shopPrice' ,width:30,renderer:function(val,item,rowIndex){return '￥'+val;}},
            {title:'所属店铺', name:'shopName' ,width:30},
            {title:'所属分类', name:'goodsCatName' ,width:30},
            {title:'销量', name:'saleNum' ,width:30,align:'center'},
            {title:'佣金', name:'commission' ,width:80, align:'center', renderer: function(val,item,rowIndex){
                return (item['distributType']==1)?item['commission']:"按订单比例分成";
            }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-85,indexCol: true, indexColWidth:50, cols: cols,method:'POST',

        url: "<?php echo addon_url('distribut://distribut/queryadmindistributgoods'); ?>", fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });  
  }
  
  function goodsQuery(){
		var query = WST.getParams('.query');
		mmg.load(query);
  }
  function toolTip(){
    WST.toolTip();
}
</script>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/js/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>