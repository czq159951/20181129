<?php /*a:2:{s:68:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/recommends/goods.html";i:1536647730;s:56:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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

<link href="__ADMIN__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
<script>
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',app('request')->root(true)); ?>","ROOT":"","APP":"","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'<?php echo implode(",",session("WST_STAFF.privileges")); ?>',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","ROUTES":'<?php echo WSTRoute(); ?>',"MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>","__HTTP__":"<?php echo WSTProtocol(); ?>"}
</script>
<script language="javascript" type="text/javascript" src="/static/js/common.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="j-loader"><img src="__ADMIN__/img/ajax-loader.gif"/></div>

<style>
input[type=text]{padding: 6px 5px;}
</style>
<form autocomplete='off'>
<div id='alertTips' class='alert alert-success alert-tips fade in'>
  <div id='headTip' class='head'><i class='fa fa-lightbulb-o'></i>操作说明（点击隐藏）</div>
  <ul class='body'>
      <li>本功能主要用于前台商品展示的推荐设置，例如首页各楼层，猜你喜欢，最新上架，热销商品，推荐商城等等。</li>
      <li>若未进行过商品的推荐操作，则系统默认按照商品销量、上架时间排序；若有设置过则以设置的商品及排序为主。</li>
      <li>本功能为扩展功能，开发者可通过组合不同的商品分类和推荐类型在前台进行商品信息的展示</li>
  </ul>
</div>
<table class='wst-form wst-box-top'>
	  <tr>
	     <th width='120'>商品分类<font color='red'>*</font>：</th>
	     <td colspan='2'>
	        <select id="cat12_0" class='ipt pgoodsCats1_2' level="0" onchange="WST.ITGoodsCats({id:'cat12_0',val:this.value,isRequire:false,className:'pgoodsCats1_2'});">
	          <option value=''>请选择</option>
	          <?php $_result=WSTGoodsCats(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	          <option value="<?php echo $vo['catId']; ?>"><?php echo $vo['catName']; ?></option>
	          <?php endforeach; endif; else: echo "" ;endif; ?>
	        </select>
	     </td>
	     <td>
	        商品分类<font color='red'>*</font>：
	        <select id="cat22_0" class='ipt pgoodsCats2_2' level="0" onchange="WST.ITGoodsCats({id:'cat22_0',val:this.value,isRequire:false,className:'pgoodsCats2_2',afterFunc:'listQueryByGoods'});">
	          <option value=''>所有分类</option>
	          <?php $_result=WSTGoodsCats(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	          <option value="<?php echo $vo['catId']; ?>"><?php echo $vo['catName']; ?></option>
	          <?php endforeach; endif; else: echo "" ;endif; ?>
	        </select>
	     </td>
	  <tr>
	     <th width='120'>搜索：</th>
	     <td colspan='2'>
	        <input type='text' id='key_2' style='width:250px' class='ipt_2' placeholder='店铺名、商品名称、商品编号、商品货号'/>
	        <button type="button" class="btn btn-primary" onclick='javascript:loadGoods("_2")'><i class="fa fa-search"></i>搜索</button>
	     </td>
	     <td style='padding-left:30px;'>
	       类型<font color='red'>*</font>：
	       <select id='dataType_2' onchange='listQueryByGoods("_2")'>
	          <option value='0'>推荐</option>
	          <option value='1'>热销</option>
	          <option value='2'>精品</option>
	          <option value='3'>新品</option>
	        </select>
	     </td>
	  </tr>
	  <tr>
	     <th>请选择<font color='red'>*</font>：</th>
	     <td width='320'>
	       <div class="recom-lbox">
	            <div class="trow head">
	              <div class="tck"><input onclick="WST.checkChks(this,'.lchk_2')" type="checkbox"></div>
	              <div class="ttxt">商品</div>
	            </div>
	            <div id="llist_2" style="width:350px;"></div>
	       </div>
	     </td>
	     <td align='center'>
	       <input type='button' value='》》' class='btn btn-primary' onclick='javascript:moveRight("_2")'/>
	       <br/><br/>
	       <input type='button' value='《《' class='btn btn-primary' onclick='javascript:moveLeft("_2")'/>
	       <input type='hidden' id='ids_2'/>
	     </td>
	     <td>
	       <div class="recom-rbox">
	            <div class="trow head">
		            <div class="tck"><input onclick="WST.checkChks(this,'.rchk_2')" type="checkbox"></div>
		            <div class="ttxt">商品</div>
		            <div class="top">排序</div>
		        </div>
	            <div id="rlist_2"></div>
	       </div>
	     </td>
	  </tr>
	  <?php if(WSTGrant('SPTJ_04')): ?>
	  <tr>
	     <td colspan='4' align='center' style='padding-top:10px;'>
	     	<button type="button" class="btn btn-primary" onclick='javascript:editGoods("_2")'><i class="fa fa-check"></i>保存</button>
	     </td>
	  </tr>
	  <?php endif; ?>
</table>
</form>
<script>
$(function(){
	listQueryByGoods('_2');
	$('#headTip').WSTTips({width:90,height:35,callback:function(v){}});
});
</script>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/recommends/recommends.js?v=1<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>