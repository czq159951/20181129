<?php /*a:2:{s:51:"/home/mart/shangtao/admin/view/attributes/list.html";i:1534832882;s:40:"/home/mart/shangtao/admin/view/base.html";i:1534832972;}*/ ?>
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
	      	<option value="">-所属分类-</option>
	      	<?php $_result=WSTGoodsCats(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	        <option value="<?php echo $vo['catId']; ?>"><?php echo $vo['catName']; ?></option>
	        <?php endforeach; endif; else: echo "" ;endif; ?>
	     </select>
	     </div>
	     <input type="text" id="keyName" placeholder="请输入属性名称"/>
	     <button class="btn btn-primary" onclick="loadGrid(0)"><i class='fa fa-search'></i>查询</button>
     </div>
    
   <?php if(WSTGrant('SPSX_01')): ?>
   <button class="btn btn-success f-right" onclick="javascript:toEdit(0);"><i class='fa fa-plus'></i>新增</button>
   <?php endif; ?>
   <div style="clear:both"></div>
</div>
<div class='wst-grid'>
<div id="mmg" class="mmg layui-form"></div>
<div id="pg" style="text-align: right;"></div>
</div>
<div id='attrBox' style='display:none'>
	<form id="attrForm">
		<table class='wst-form wst-box-top'>
		  <tr>
		      <th width='150'>
		      	<input type="hidden" id="attrId" value="" class="ipt" />
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
		          <th>属性名称<font color='red'>*</font>：</th>
		          <td>
		              <input type="text" id="attrName" name="attrName" class="ipt" maxLength='20'/>
		          </td>
		       </tr>
		       <tr>
		          <th>属性类型<font color='red'>*</font>：</th>
		          <td>
		              <select id='attrType' class='ipt' onchange='changeArrType(this.value)'>
		                 <option value='0'>输入框</option>
		                 <option value='1'>多选项</option>
		                 <option value='2'>下拉项</option>
		              </select>
		          </td>
		       </tr>
		       <tr id='attrValTr' style='display:none'>
		          <th>属性选项<font color='red'>*</font>：</th>
		          <td>
		              <input type="text" id="attrVal" name="attrVal" class="ipt" style='width:70%' placeholder="每个属性选项以,号分隔" data-msg='请输入属性选项'/>
		          </td>
		       </tr>
		       <tr>
		          <th>是否显示<font color='red'>  </font>：</th>
		          <td class='layui-form'>
		            <input type="checkbox" id="isShow" name="isShow" value="1" class="ipt" lay-skin="switch" lay-filter="isShow" lay-text="显示|隐藏">
		          </td>
		       </tr>
		       <tr>
		          <th>排序号<font color='red'>*</font>：</th>
		          <td>
		              <input type="text" id="attrSort" name="attrSort" class="ipt" maxLength='20'/>
		          </td>
		       </tr>
		</table>
	</form>
</div>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/js/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/attributes/attributes.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>