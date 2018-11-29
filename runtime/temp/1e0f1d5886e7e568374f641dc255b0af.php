<?php /*a:2:{s:65:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/messages/list.html";i:1536627214;s:56:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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
<style>
body{overflow:hidden;}
.layui-tab-content{padding:0px;}
</style>

<link href="__ADMIN__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
<script>
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',app('request')->root(true)); ?>","ROOT":"","APP":"","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'<?php echo implode(",",session("WST_STAFF.privileges")); ?>',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","ROUTES":'<?php echo WSTRoute(); ?>',"MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>","__HTTP__":"<?php echo WSTProtocol(); ?>"}
</script>
<script language="javascript" type="text/javascript" src="/static/js/common.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="j-loader"><img src="__ADMIN__/img/ajax-loader.gif"/></div>

<div class="layui-tab layui-tab-brief" lay-filter="msgTab">
  <ul class="layui-tab-title">
    <li class="layui-this">消息列表</li>
    <li >发送消息</li>
  </ul>
  <div class="layui-tab-content" >
    <div class="layui-tab-item layui-show">
    <table class='wst-form wst-box-top'>
      <tr>
          <th width='150'>发送类型<font color='red'>*</font>：</th>
          <td style="text-align:left;" colspan='3' class='layui-form'>
            <label><input type="radio" lay-filter="sendType" name="sendType" id="sendType" value="users" class='ipt'  checked title='会员'></label>
            <label><input type="radio" lay-filter="sendType" name="sendType" id="sendType" value="shop" class='ipt' title='店铺'></label>
            <label><input type="radio" lay-filter="sendType" name="sendType" id="theUser" value="theUser" class='ipt' title='指定账号'></label>
          </td>
       </tr>
       <tr id="user_query" style="display:none;">
          <th></th>
          <td>
            <input type='text' id='loginName' name="loginName" value=''  style="width:200px;" maxLength='20' placeholder="请输入要发送消息的账号"/>
            
          </td>
          <td><button type="button"  class='btn btn-primary btn-mright' onclick="userQuery()"><i class="fa fa-search"></i>查询</button></td>
       </tr>
       <tr id="send_to" style="display:none;">
          <th>指定接收账号<font color='red'>*</font>：</th>
          <td width="200">
            <select ondblclick="WST.multSelect({left:'ltarget',right:'rtarget',vtarget:'rtarget',val:'htarget'})" size="12" id="ltarget" multiple="" style="width:200px;height:160px;">
             </select>
          </td>
         <td width="10">
         <input type='hidden' id='htarget' value='' class='ipt'/>
         <button onclick="javascript:WST.multSelect({left:'ltarget',right:'rtarget',vtarget:'rtarget',val:'htarget'})" class="btn btn-primary" type="button">&gt;&gt;</button>
         <br>
         <br>
         <button onclick="javascript:WST.multSelect({left:'rtarget',right:'ltarget',vtarget:'rtarget',val:'htarget'})" class="btn btn-primary" type="button">&lt;&lt;</button>
         </td>
         <td>
         <select ondblclick="WST.multSelect({left:'rtarget',right:'ltarget',vtarget:'rtarget',val:'htarget'})" size="12" id="rtarget" multiple="" style="width:200px;height:160px;">
        </select>
          </td>
       </tr>

       <tr>
          <th>消息内容<font color='red'>  </font>：</th>
          <td colspan="10">
            <textarea class='ipt' name="msgContent" id="msgContent" style="width:700px;height:150px;"></textarea>
          </td>
       </tr>
<?php if(WSTGrant('SCXX_01')): ?> 
  <tr>
     <td colspan='4' align='center'>
       <button type="button" onclick="sendMsg()" class='btn btn-primary btn-mright'><i class="fa fa-share"></i>发送</button>
       <button type="button" onclick='javascript:history.go(-1)' class='btn'><i class="fa fa-angle-double-left"></i>返回</button>
     </td>
  </tr>
<?php endif; ?>
 </table>

</div>

<div class="layui-tab-item">
    <div autocomplete='off' class="wst-toolbar"> 
        <select style="float:left;" name="msgType" id="msgType" class="query">
          <option value="-1">消息类型</option>
          <option value="0">手工</option>
          <option value="1">系统</option>
        </select>
        <input type="text" name="msgContent"  placeholder='系统内容' id="msgContent" class="query" />
        <button type="button"  class='btn btn-primary btn-mright' onclick="javascript:msgQuery()"><i class="fa fa-search"></i>查询</button>
        <?php if(WSTGrant('SCXX_03')): ?>
		<button class="btn btn-danger f-right" onclick='javascript:toBatchDelete()' style='margin-left:10px;'><i class='fa fa-trash-o'></i>批量删除</button>
		<?php endif; ?>
    </div>
    <div style="clear:both"></div>
    <table id="mmg" class="mmg">
       <tr>
          <th rowspan="" colspan=""></th>
       </tr>
    </table>
    <div id="pg" style="text-align: right;"></div>
</div>


</div>


<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/js/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/messages/message.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="/static/plugins/kindeditor/kindeditor.js?v=<?php echo $v; ?>" type="text/javascript" ></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>