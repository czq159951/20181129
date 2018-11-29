<?php /*a:2:{s:65:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/speccats/list.html";i:1536647730;s:56:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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
	<div class="f-left">
		<div id="pcat_0_box" class="f-left">
		 <select id="cat_0" class='ipt pgoodsCats' level="0" onchange="WST.ITGoodsCats({id:'cat_0',val:this.value,isRequire:false,className:'pgoodsCats'});">
	      	<option value="">-所属商品分类-</option>
	      	<?php $_result=WSTGoodsCats(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	        <option value="<?php echo $vo['catId']; ?>"><?php echo $vo['catName']; ?></option>
	        <?php endforeach; endif; else: echo "" ;endif; ?>
	     </select>
	     </div>
	     <input type="text" id="keyName" placeholder="请输入规格名称"/>
	     <button class="btn btn-primary" onclick="loadGrid(0)"><i class='fa fa-search'></i>查询</button>
     </div>
   <?php if(WSTGrant('SPGG_01')): ?>
   <button class="btn btn-primary f-right" onclick="javascript:toEditCat(0);"><i class='fa fa-plus'></i>新增</button>
   <?php endif; ?>
   <div style="clear:both"></div>
</div>
<div class='wst-grid'>
 <div id="mmg" class="mmg layui-form"></div>
</div>
<div id="pg" style="text-align: right;"></div>
<div id='specCatsBox' style='display:none'>
	<form id="specCatsForm">
	    <input type='hidden' id='catId' class='ipt'/>
		<table class='wst-form wst-box-top'>
		  <tr>
		      <th width='150'>
		    	 所属商品分类<font color='red'>*</font>：</th>
		     	<td id="bcat_0_box">
		            <select id="bcat_0" class='ipt goodsCats' level="0" onchange="WST.ITGoodsCats({id:'bcat_0',val:this.value,isRequire:false,className:'goodsCats'});" data-rule='所属商品分类:required;' data-target="#msg_bcat_0">
		                <option value="">-请选择-</option>
		                <?php $_result=WSTGoodsCats(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
				        <option value="<?php echo $vo['catId']; ?>"><?php echo $vo['catName']; ?></option>
				        <?php endforeach; endif; else: echo "" ;endif; ?>
		           	</select>
		           	<span class='msg-box' id='msg_bcat_0' style='color:red;'>(至少选择一个商品分类)</span>
		          </td>
		       </tr>
		       <tr>
		          <th>规格名称<font color='red'>*</font>：</th>
		          <td>
		              <input type="text" id="catName" name="catName" class="ipt" maxLength='20'/>
		          </td>
		       </tr>
		       <tr>
		          <th>是否允许上传图片<font color='red'>  </font>：</th>
		          <td class='layui-form'>
		            <input type="checkbox" id="isAllowImg" name="isAllowImg" value="1" class="ipt" lay-skin="switch" lay-filter="isAllowImg" lay-text="是|否">
		            <span style='color:red;margin-left:20px;'>(*同一分类下只能设置一个上传图片的规格分类)</span>
		          </td>
		       </tr>
		       <tr>
		          <th>是否显示<font color='red'>  </font>：</th>
		          <td class='layui-form'>
		            <input type="checkbox" id="isShow" name="isShow" value="1" class="ipt" lay-skin="switch" lay-filter="isShow" lay-text="显示|隐藏">
		          </td>
		       </tr>
		</table>
	</form>
</div>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/js/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/speccats/speccats.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>