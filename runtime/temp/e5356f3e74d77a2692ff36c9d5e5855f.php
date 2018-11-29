<?php /*a:2:{s:60:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/ads/edit.html";i:1536627199;s:56:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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

<style>
#preview img{max-width: 600px;max-height:150px;}
</style>
<link rel="stylesheet" type="text/css" href="/static/plugins/webuploader/webuploader.css?v=<?php echo $v; ?>" />

<link href="__ADMIN__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
<script>
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',app('request')->root(true)); ?>","ROOT":"","APP":"","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'<?php echo implode(",",session("WST_STAFF.privileges")); ?>',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","ROUTES":'<?php echo WSTRoute(); ?>',"MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>","__HTTP__":"<?php echo WSTProtocol(); ?>"}
</script>
<script language="javascript" type="text/javascript" src="/static/js/common.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="j-loader"><img src="__ADMIN__/img/ajax-loader.gif"/></div>

<form id="adsForm">
<table class='wst-form wst-box-top'>
  <tr>
      <th width='150'>位置类型<font color='red'>*</font>：</th>
          <td>
            <select id="positionType" name="positionType" class='ipt' maxLength='20' onchange='javascript:addPosition(this.value);'>
              <option value=''>请选择</option>
              <?php $_result=WSTDatas('ADS_TYPE');if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
              <option <?php if($data['positionType'] == $vo['dataVal']): ?>selected<?php endif; ?> value="<?php echo $vo['dataVal']; ?>"><?php echo $vo['dataName']; ?></option>
              <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
          </td>
       </tr>
       <tr>
          <th>广告位置<font color='red'>*</font>：</th>
          <td>
            <select id="adPositionId" name="adPositionId" class='ipt' maxLength='20' onchange='javascript:getPhotoSize(this.value);'>
              <option value="">-请选择-</option>
            </select>
          </td>
       </tr>
       <tr>
          <th>广告标题<font color='red'>*</font>：</th>
          <td><input type='text' id='adName' name="adName" value='<?php echo $data['adName']; ?>' class='ipt' maxLength='20'/></td>
       </tr>
        <tr>
          <th>广告副标题：</th>
          <td><input type='text' id='subTitle' name="subTitle" value='<?php echo $data['subTitle']; ?>' class='ipt' maxLength='20'/></td>
       </tr>
       <tr>
          <th>广告图片<font color='red'>*</font>：</th>
          <td><div id='adFilePicker'>上传广告图</div><span id='uploadMsg'></span>
              <div>
                图片大小:<span id="img_size">300x300</span>(px)，格式为 gif, jpg, jpeg, png
              </div>
          </td>

       </tr>
       <tr>
          <th>预览图<font color='red'>  </font>：</th>
          <td>
            <div id="preview" style="min-height:30px;">
              <?php if(($data['adFile']!='')): ?>
              <img src="/<?php echo $data['adFile']; ?>">
              <?php endif; ?>
            </div>
            <input type="hidden" name="adFile" id="adFile" class="ipt" value="<?php echo $data['adFile']; ?>" />
          </td>
       </tr>
       <tr>
          <th>广告网址<font color='red'>  </font>：</th>
          <td>
            <input type="text" id="adURL" class="ipt" maxLength="200" value='<?php echo $data['adURL']; ?>' />
          </td>
       </tr>
       <tr>
          <th >广告开始时间<font color='red'>*</font>：</th>
          <td>
            <input type="text" style="margin:0px;vertical-align:baseline;" id="adStartDate" name="adStartDate" class="laydate-icon ipt" maxLength="20" value='<?php echo $data['adStartDate']; ?>'  />
          </td>
       </tr>
       <tr>
          <th>广告结束时间<font color='red'>*</font>：</th>
          <td>
            <input type="text" style="margin:0px;vertical-align:baseline;" id="adEndDate" name="adEndDate" class="laydate-icon ipt" maxLength="20" value='<?php echo $data['adEndDate']; ?>' />
          </td>
       </tr>
       <tr>
          <th>广告排序号：</th>
          <td>
            <input type="text" id="adSort" class="ipt" maxLength="20"  value='<?php echo $data['adSort']; ?>' />
          </td>
       </tr>
  
  <tr>
     <td colspan='2' align='center' class='wst-bottombar'>
       <input type="hidden" name="id" id="adId" class="ipt" value="<?php echo $data['adId']+0; ?>" />
       <button type="button" class="btn btn-primary btn-mright" onclick="javascript:save(1)"><i class="fa fa-check"></i>提交</button>
       <?php if($data['adId']==0): ?>
       <button type="button" class="btn btn-primary btn-mright" onclick="javascript:continueAdd(1)"><i class="fa fa-check"></i>继续新增</button> 
       <?php endif; ?> 
        <button type="button" class="btn" onclick="javascript:history.go(-1)"><i class="fa fa-angle-double-left"></i>返回</button>
     </td>
  </tr>
</table>
</form>
<script>
$(function(){
editInit();
//初始化位置类型
addPosition("<?php echo $data['positionType']; ?>","<?php echo $data['adPositionId']; ?>");
});

</script>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script type='text/javascript' src='/static/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>'></script>
<script src="__ADMIN__/ads/admgrs.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>