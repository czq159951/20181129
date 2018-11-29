<?php /*a:9:{s:51:"/home/mart/shangtao/admin/view/sysconfigs/edit.html";i:1534832886;s:40:"/home/mart/shangtao/admin/view/base.html";i:1534832972;s:54:"/home/mart/shangtao/admin/view/sysconfigs/config0.html";i:1534832886;s:54:"/home/mart/shangtao/admin/view/sysconfigs/config1.html";i:1534832886;s:54:"/home/mart/shangtao/admin/view/sysconfigs/config2.html";i:1534832886;s:54:"/home/mart/shangtao/admin/view/sysconfigs/config3.html";i:1534832886;s:54:"/home/mart/shangtao/admin/view/sysconfigs/config4.html";i:1534832886;s:54:"/home/mart/shangtao/admin/view/sysconfigs/config6.html";i:1534832886;s:54:"/home/mart/shangtao/admin/view/sysconfigs/config5.html";i:1534832886;}*/ ?>
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

<link rel="stylesheet" type="text/css" href="/static/plugins/webuploader/webuploader.css?v=<?php echo $v; ?>" />
<style>
.layui-form-label{width:140px;}
.layui-input-block{  margin-left: 170px;}
#wst-tab-5 input[type="text"]{width:50%}
td{height:40px; }
</style>

<link href="__ADMIN__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
<script>
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',app('request')->root(true)); ?>","ROOT":"","APP":"/index.php","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'<?php echo implode(",",session("WST_STAFF.privileges")); ?>',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","ROUTES":'<?php echo WSTRoute(); ?>',"MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>","__HTTP__":"<?php echo WSTProtocol(); ?>"}
</script>
<script language="javascript" type="text/javascript" src="/static/js/common.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="j-loader"><img src="__ADMIN__/img/ajax-loader.gif"/></div>

<form autocomplete='off'> 
<div class="layui-tab layui-tab-brief" lay-filter="msgTab">
	   <ul class="layui-tab-title">
	      <li class="layui-this">基础设置</li>
	      <li>服务器设置</li>
	      <li>运营设置</li>
	      <li>密匙设置</li>
	      <li>图片设置</li>
	      <li>通知设置</li>
	      <li>SEO设置</li>
	   </ul>
	   <div class="layui-tab-content" style="padding: 10px 0;">
   <?php $grant = WSTGrant('SCPZ_02');  ?>
<div class="layui-tab-item layui-show layui-form">
     <table class='wst-form wst-box-top'>
	  <tr>
	     <th width='150'>商城名称：</th>
	     <td><input type="text" id='mallName' class='ipt' value="<?php echo $object['mallName']; ?>" maxLength='100' placeholder='对外的简称'/></td>
	  </tr>
	  <tr>
	     <th width='150'>商城特色介绍：</th>
	     <td><input type="text" id='mallSlogan' class='ipt' style='width:70%' value="<?php echo $object['mallSlogan']; ?>" maxLength='50' placeholder='商城特色短语介绍'/></td>
	  </tr>
	  <tr>
	     <th>商城开关：</th>
	     <td>
	     <input type="checkbox" <?php if($object['seoMallSwitch']==1): ?>checked<?php endif; ?> value='1' class="ipt" id="seoMallSwitch" name="seoMallSwitch" lay-skin="switch" lay-filter="seoMallSwitch" lay-text="开|关">
	     </td>
	  </tr>
	  <tr id="close" <?php if($object['seoMallSwitch']==1): ?>style="display:none;"<?php endif; ?>>
	     <th width='150'>商城关闭原因：</th>
	     <td><input type="text" id='seoMallSwitchDesc' class='ipt' style='width:70%'  value="<?php echo $object['seoMallSwitchDesc']; ?>" maxLength='50' placeholder='原因'/></td>
	  </tr>
	  <tr>
	     <th>商品是否需要审核：</th>
	     <td>
	     <input type="checkbox" <?php if($object['isGoodsVerify']==1): ?>checked<?php endif; ?> class="ipt" value='1' id="isGoodsVerify" name="isGoodsVerify" lay-skin="switch" lay-filter="isGoodsVerify" lay-text="是|否">
	     </td>
	  </tr>
	  <tr>
	     <th>底部设置：</th>
	     <td>
	       <textarea id='mallFooter' class='ipt' placeholder='显示在网站最底部的内容'><?php echo $object['mallFooter']; ?></textarea>
	     </td>
	  </tr>
	  <tr>
	     <th>访问统计：</th>
	     <td><textarea id='visitStatistics' class='ipt' placeholder='用于统计网站访问信息的代码'><?php echo $object['visitStatistics']; ?></textarea></td>
	  </tr>
	  <tr>
	     <th>客服QQ设置：</th>
	     <td><input type="text" id='serviceQQ' class='ipt' value="<?php echo $object['serviceQQ']; ?>" maxLength='200' placeholder='显示在网站的客服QQ好，多个QQ以，号分割'/></td>
	  </tr>
	  <tr>
	     <th>联系电话：</th>
	     <td><input type="text" id='serviceTel' class='ipt' value="<?php echo $object['serviceTel']; ?>" maxLength='200' placeholder="显示在网站的联系电话"/></td>
	  </tr>
	  <tr>
	     <th>联系邮箱：</th>
	     <td><input type="text" id='serviceEmail' class='ipt' value="<?php echo $object['serviceEmail']; ?>" maxLength='200' placeholder="显示在网站的联系邮箱"/></td>
	  </tr>
	  <tr>
	     <th>版权所有：</th>
	     <td><input type="text" id='copyRight' class='ipt' value="<?php echo $object['copyRight']; ?>" maxLength='200' placeholder="显示在网站的版权所有者"/></td>
	  </tr>
	  <tr>
	     <th>热搜关键词：</th>
	     <td><input type="text" id='hotWordsSearch' class='ipt' value="<?php echo $object['hotWordsSearch']; ?>" maxLength='100' placeholder='商城搜索栏下的引导搜索词' style='width:70%'/></td>
	  </tr>
	  <tr>
	     <th>热搜广告词（商品）：</th>
	     <td><input type="text" id='adsGoodsWordsSearch' class='ipt' value="<?php echo $object['adsGoodsWordsSearch']; ?>" maxLength='100' placeholder='商城搜索栏里的搜索词' style='width:70%'/></td>
	  </tr>
	  <tr>
	     <th>热搜广告词（店铺）：</th>
	     <td><input type="text" id='adsShopWordsSearch' class='ipt' value="<?php echo $object['adsShopWordsSearch']; ?>" maxLength='100' placeholder='商城搜索栏里的搜索词' style='width:70%'/></td>
	  </tr>
	  <tr>
	     <th>账号禁用关键字：</th>
	     <td><textarea id='registerLimitWords' class='ipt' placeholder='禁止用户注册时的账号内容'><?php echo $object['registerLimitWords']; ?></textarea></td>
	  </tr>
	  <tr>
	     <th>禁用关键字：</th>
	     <td><textarea id='limitWords' class='ipt' placeholder='禁止用户发布的商品、评价内容'><?php echo $object['limitWords']; ?></textarea></td>
	  </tr>
	  <tr>
	     <th width='150'>腾讯地图密匙</th>
	     <td><input type="text" id='mapKey' class='ipt' style='width:70%' value="<?php echo $object['mapKey']; ?>" maxLength='100' placeholder='腾讯地图密匙'/></td>
	  </tr>
	  <tr>
	     <th width='150'>开启调试模式</th>
	     <td><input type="checkbox" <?php if($object['isDebug']==1): ?>checked<?php endif; ?> class="ipt" value='1' id="isDebug" name="isDebug" lay-skin="switch" lay-filter="isDebug" lay-text="开|关"></td>
	  </tr>
	  <?php if(($grant)): ?>
	  <tr>
	     <td colspan='2' align='center'>
	     	<button type="button" onclick='javascript:edit()' style='margin-right:15px;' class='btn btn-primary btn-mright'><i class="fa fa-check"></i>保存</button>
            <button type="reset"  class='btn'><i class="fa fa-refresh"></i>重置</button>
	     </td>
	  </tr>
	  <?php endif; ?>
	 </table>
</div><div class="layui-tab-item layui-form">
	<?php echo hook('adminDocumentSysConfig',['object'=>$object]); ?>
    <fieldset class="layui-elem-field layui-field-title">
	  <legend>邮件服务器设置</legend>
     <table class='wst-form wst-box-top'>
      <tr>
	     <th width='150'>开启邮件发送：</th>
	     <td>
	     <input type="checkbox" <?php if($object['mailOpen']==1): ?>checked<?php endif; ?> class="ipt" id="mailOpen" value='1' name="mailOpen" value='1' lay-skin="switch" lay-filter="mailOpen" lay-text="开|关">
	     </td>
	  </tr>
	  <tr class='mailOpenTr' <?php if($object['mailOpen']!=1): ?>style='display:none'<?php endif; ?>>
	     <th>SMTP服务器：</th>
	     <td><input type="text" id='mailSmtp' class='ipt' maxLength='100' value='<?php echo $object["mailSmtp"]; ?>'/></td>
	  </tr>
	  <tr class='mailOpenTr' <?php if($object['mailOpen']!=1): ?>style='display:none'<?php endif; ?>>
	     <th>SMTP端口：</th>
	     <td><input type="text" id='mailPort' class='ipt' maxLength='10' value='<?php echo $object["mailPort"]; ?>'/></td>
	  </tr>
	  <tr class='mailOpenTr' <?php if($object['mailOpen']!=1): ?>style='display:none'<?php endif; ?>>
	     <th>是否开启SSL：</th>
	     <td>
	     <input type="checkbox" <?php if($object['mailOpenSSL']==1): ?>checked<?php endif; ?> class="ipt" id="mailOpenSSL" name="mailOpenSSL" value='1' lay-skin="switch" lay-filter="mailOpenSSL" lay-text="是|否">&nbsp;<span style='color:gray;'>例如SMTP端口为465时需开启；</span>
	     </td>
	  </tr>
	  <tr class='mailOpenTr' <?php if($object['mailOpen']!=1): ?>style='display:none'<?php endif; ?>>
	     <th>是否验证SMTP：</th>
	     <td>
	     <input type="checkbox" <?php if($object['mailAuth']==1): ?>checked<?php endif; ?> class="ipt" id="mailAuth" name="mailAuth" value='1' lay-skin="switch" lay-filter="mailAuth" lay-text="是|否">
	     </td>
	  </tr>
	  <tr class='mailOpenTr' <?php if($object['mailOpen']!=1): ?>style='display:none'<?php endif; ?>>
	     <th>SMTP发件人邮箱：</th>
	     <td><input type="text" id='mailAddress' class='ipt' value='<?php echo $object["mailAddress"]; ?>' maxLength='100'/></td>
	  </tr>
	  <tr class='mailOpenTr' <?php if($object['mailOpen']!=1): ?>style='display:none'<?php endif; ?>>
	     <th>SMTP登录账号：</th>
	     <td><input type="text" id='mailUserName' class='ipt' value='<?php echo $object["mailUserName"]; ?>' maxLength='100'/></td>
	  </tr>
	  <tr class='mailOpenTr' <?php if($object['mailOpen']!=1): ?>style='display:none'<?php endif; ?>>
	     <th>SMTP登录密码：</th>
	     <td><input type="text" id='mailPassword' class='ipt' value='<?php echo $object["mailPassword"]; ?>' maxLength='100'/></td>
	  </tr>
	  <tr class='mailOpenTr' <?php if($object['mailOpen']!=1): ?>style='display:none'<?php endif; ?>>
	     <th>发件人名称：</th>
	     <td><input type="text" id='mailSendTitle' class='ipt' value='<?php echo $object["mailSendTitle"]; ?>' maxLength='100'/></td>
	  </tr>
	</table>
    </fieldset>
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
	  <legend>短信服务器设置</legend>
	  <table class='wst-form wst-box-top'>
	  <tr>
	  	<th colspan='2' style='text-align:left;padding-left:40px'><span style='color:gray;'>(请确保在“拓展管理-插件管理"中有安装相应的短信插件”)</span></th>
	  </tr>
	  <tr style='display:none'>
	     <th >开启手机验证：</th>
	     <td>
	     <input type="checkbox" checked class="ipt" id="smsOpen" value='1' name="smsOpen" value='1' lay-skin="switch" lay-filter="smsOpen" lay-text="开|关">
	     </td>
	  </tr>
	  <tr>
	     <th width='150'>每个号码每日发送数：</th>
	     <td><input type="text" id='smsLimit' class='ipt' value="<?php echo $object['smsLimit']; ?>" maxLength='100'/></td>
	  </tr>
	  <tr>
	     <th>开启短信发送验证码：</th>
	     <td>
	     <input type="checkbox" <?php if($object['smsVerfy']==1): ?>checked<?php endif; ?> class="ipt" id="smsVerfy" value='1' name="smsVerfy" value='1' lay-skin="switch" lay-filter="smsVerfy" lay-text="开|关">
	     </td>
	  </tr>
	  <?php if(($grant)): ?>
	  <tr>
	     <td colspan='2' align='center'>
	     	<button type="button" onclick='javascript:edit()'  class='btn btn-primary btn-mright'><i class="fa fa-check"></i>保存</button>
            <button type="reset"  class='btn'><i class="fa fa-refresh"></i>重置</button>
	     </td>
	  </tr>
	   <?php endif; ?>
	 </table>
	</fieldset>
</div><div class="layui-tab-item layui-form">
	<fieldset class="layui-elem-field layui-field-title">
	  <legend>订单设置</legend>
	  <table class='wst-form wst-box-top'>
	  <tr>
	     <th width='150'>开启积分支付：</th>
	     <td>
	      <input type="checkbox" <?php if($object['isOpenScorePay']==1): ?>checked<?php endif; ?> class="ipt" id="isOpenScorePay" name="isOpenScorePay" value='1' lay-skin="switch" lay-filter="isOpenScorePay" lay-text="开|关">
	     </td>
	  </tr>
	  <tr id='scoreToMoneyTr' <?php if($object['isOpenScorePay']==0): ?>style='display:none'<?php endif; ?>>
	     <th>积分兑换金额：</th>
	     <td>
	     积分支付时<input type="text" id='scoreToMoney' class='ipt' value="<?php echo $object['scoreToMoney']; ?>" maxLength='5' style='width:60px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberKey(event)"/>个积分抵1个金额
	     </td>
	  </tr>
	  <tr>
	     <th>开启下单获积分：</th>
	     <td>
	      <input type="checkbox" <?php if($object['isOrderScore']==1): ?>checked<?php endif; ?> class="ipt" id="isOrderScore" name="isOrderScore" value='1' lay-skin="switch" lay-filter="isOrderScore" lay-text="开|关">
	     </td>
	  </tr>
	  <tr id='moneyToScoreTr' <?php if($object['isOrderScore']==0): ?>style='display:none'<?php endif; ?>>
	     <th>金额兑换积分：</th>
	     <td>
	     下单后订单金额1元可获得<input type="text" id='moneyToScore' class='ipt' value="<?php echo $object['moneyToScore']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/>个积分
	     <span style='color:gray;'>
	     </span>
	     </td>
	  </tr>
	  <tr>
	     <th>开启评价获积分：</th>
	     <td>
	     <input type="checkbox" <?php if($object['isAppraisesScore']==1): ?>checked<?php endif; ?> class="ipt" id="isAppraisesScore" name="isAppraisesScore" value='1' lay-skin="switch" lay-filter="isAppraisesScore" lay-text="开|关">
	     </td>
	  </tr>
	  <tr id='appraisesScoreTr' <?php if($object['isAppraisesScore']==0): ?>style='display:none'<?php endif; ?>>
	     <th>评价获得积分：</th>
	     <td>
	     <input type="text" id='appraisesScore' class='ipt' value="<?php echo $object['appraisesScore']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/>个积分
	     <span style='color:gray;'>
	     </span>
	     </td>
	  </tr>
	  <tr>
	  <tr>
	     <th>结算方式：</th>
	     <td>
	     <label>
	         <input type='radio' id='statementType' name='statementType' class='ipt' value='0' <?php if($object['statementType']==0): ?>checked<?php endif; ?> title='即时结算'>
	     </label>
	     <label>
	         <input type='radio' id='statementType' name='statementType' class='ipt' value='1' <?php if($object['statementType']==1): ?>checked<?php endif; ?> title='统一结算'>
	     </label>
	     <span style='color:gray;'>(即时结算指用户确认收货就把钱打到商家钱包，统一结算指系统定时结算或者商家管理员手工结算)
	     </span>
	     </td>
	  </tr>
	  <tr style='display:none'>
	     <th>积分与金钱兑换比例：</th>
	     <td><input type="text" id='scoreCashRatio' class='ipt' value="<?php echo $object['scoreCashRatio']; ?>" maxLength='20'/></td>
	  </tr>
	  <tr style='display:none'>
	     <th>结算金额设置：</th>
	     <td><input type="text" id='settlementStartMoney' class='ipt' value="<?php echo $object['settlementStartMoney']; ?>" maxLength='10'/></td>
	  </tr>
	  </table>
	</fieldset>


	<fieldset class="layui-elem-field layui-field-title">
	  <legend>信息设置</legend>
	  <table class='wst-form wst-box-top'>
	  <tr>
	     <th width='150'>商城咨询默认是否显示：</th>
	     <td>
	      <input type="checkbox" <?php if($object['isConsult']==1): ?>checked<?php endif; ?> class="ipt" id="isConsult" name="isConsult" value='1' lay-skin="switch" lay-filter="isConsult" lay-text="是|否">
	     </td>
	  </tr>
	  <tr>
	     <th width='150'>订单评价默认是否显示：</th>
	     <td>
	      <input type="checkbox" <?php if($object['isAppraise']==1): ?>checked<?php endif; ?> class="ipt" id="isAppraise" name="isAppraise" value='1' lay-skin="switch" lay-filter="isAppraise" lay-text="是|否">
	     </td>
	  </tr>
	  </table>
	</fieldset>

	<fieldset class="layui-elem-field layui-field-title">
	  <legend>积分设置</legend>
	  <table class='wst-form wst-box-top'>
	   <tr>
	     <th width='150'>开启积分签到：</th>
	     <td>
	     <input type="checkbox" <?php if($object['signScoreSwitch']==1): ?>checked<?php endif; ?> class="ipt" id="signScoreSwitch" name="signScoreSwitch" value='1' lay-skin="switch" lay-filter="signScoreSwitch" lay-text="开|关">
	     </td>
	  </tr>
	  <tr id="signScore" <?php if($object['signScoreSwitch']==0): ?>style="display:none;"<?php endif; ?>>
	     <th>累计签到获得的积分：</th>
	     <td>&nbsp;</td>
	  </tr>
  	 <tr id="signScores" <?php if($object['signScoreSwitch']==0): ?>style="display:none;"<?php endif; ?>>
     <th></th>
     	<td>
	     	<table><tbody>
	     	<tr>
	     	<th>第1天：</th><td><input type="text" id='signScore0' class='ipt' value="<?php echo $object['signScore0']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第2天：</th><td><input type="text" id='signScore1' class='ipt' value="<?php echo $object['signScore1']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第3天：</th><td><input type="text" id='signScore2' class='ipt' value="<?php echo $object['signScore2']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第4天：</th><td><input type="text" id='signScore3' class='ipt' value="<?php echo $object['signScore3']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第5天：</th><td><input type="text" id='signScore4' class='ipt' value="<?php echo $object['signScore4']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	</tr>
	     	<tr>
	     	<th>第6天：</th><td><input type="text" id='signScore5' class='ipt' value="<?php echo $object['signScore5']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第7天：</th><td><input type="text" id='signScore6' class='ipt' value="<?php echo $object['signScore6']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第8天：</th><td><input type="text" id='signScore7' class='ipt' value="<?php echo $object['signScore7']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第9天：</th><td><input type="text" id='signScore8' class='ipt' value="<?php echo $object['signScore8']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第10天：</th><td><input type="text" id='signScore9' class='ipt' value="<?php echo $object['signScore9']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	</tr>
	     	<tr>
	     	<th>第11天：</th><td><input type="text" id='signScore10' class='ipt' value="<?php echo $object['signScore10']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第12天：</th><td><input type="text" id='signScore11' class='ipt' value="<?php echo $object['signScore11']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第13天：</th><td><input type="text" id='signScore12' class='ipt' value="<?php echo $object['signScore12']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第14天：</th><td><input type="text" id='signScore13' class='ipt' value="<?php echo $object['signScore13']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第15天：</th><td><input type="text" id='signScore14' class='ipt' value="<?php echo $object['signScore14']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	</tr>
	     	<tr>
	     	<th>第16天：</th><td><input type="text" id='signScore15' class='ipt' value="<?php echo $object['signScore15']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第17天：</th><td><input type="text" id='signScore16' class='ipt' value="<?php echo $object['signScore16']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第18天：</th><td><input type="text" id='signScore17' class='ipt' value="<?php echo $object['signScore17']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第19天：</th><td><input type="text" id='signScore18' class='ipt' value="<?php echo $object['signScore18']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第20天：</th><td><input type="text" id='signScore19' class='ipt' value="<?php echo $object['signScore19']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	</tr>
	     	<tr>
	     	<th>第21天：</th><td><input type="text" id='signScore20' class='ipt' value="<?php echo $object['signScore20']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第22天：</th><td><input type="text" id='signScore21' class='ipt' value="<?php echo $object['signScore21']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第23天：</th><td><input type="text" id='signScore22' class='ipt' value="<?php echo $object['signScore22']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第24天：</th><td><input type="text" id='signScore23' class='ipt' value="<?php echo $object['signScore23']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第25天：</th><td><input type="text" id='signScore24' class='ipt' value="<?php echo $object['signScore24']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	</tr>
	     	<tr>
	     	<th>第26天：</th><td><input type="text" id='signScore25' class='ipt' value="<?php echo $object['signScore25']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第27天：</th><td><input type="text" id='signScore26' class='ipt' value="<?php echo $object['signScore26']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第28天：</th><td><input type="text" id='signScore27' class='ipt' value="<?php echo $object['signScore27']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第29天：</th><td><input type="text" id='signScore28' class='ipt' value="<?php echo $object['signScore28']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第30天：</th><td><input type="text" id='signScore29' class='ipt' value="<?php echo $object['signScore29']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	</tr>
	     	<tr><span style='color:gray;'>(单位（个），必须第1天大于0才能签到，填写为空则为0;填写第1天为0则保存所有为0;填写中间位或最后位为零则取前一天积分，类推取得不为0的积分)</tr>
	     	</tbody></table>
	     </td>
	  </tr>
	  </table>
	</fieldset>
	  
	<fieldset class="layui-elem-field layui-field-title">
	  <legend>提现设置</legend>
	  <table class='wst-form wst-box-top'>
	  <tr>
	     <th width='150'>用户提现设置：</th>
	     <td>至少<input type="text" id='drawCashUserLimit' class='ipt' value="<?php echo $object['drawCashUserLimit']; ?>" maxLength='10' style='width:40px' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberKey(event)"/>元以上方能申请提现。</td>
	  </tr>
	  <tr>
	     <th>商家提现设置：</th>
	     <td>至少<input type="text" id='drawCashShopLimit' class='ipt' value="<?php echo $object['drawCashShopLimit']; ?>" maxLength='10' style='width:40px' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberKey(event)"/>元以上方能申请提现。</td>
	  </tr>
	  </table>
	</fieldset>

	<fieldset class="layui-elem-field layui-field-title">
	  <legend>定时设置</legend>
	  <table class='wst-form wst-box-top'>
	  <tr>
	     <th width='150'>未支付订单失效时间：</th>
	     <td>下单后<input type="text" id='autoCancelNoPayDays' class='ipt' value="<?php echo $object['autoCancelNoPayDays']; ?>" maxLength='3' style='width:40px' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberKey(event)"/>小时</td>
	  </tr>
	  <tr>
	     <th>自动收货期限：</th>
	     <td>发货后<input type="text" id='autoReceiveDays' class='ipt' value="<?php echo $object['autoReceiveDays']; ?>" maxLength='3' style='width:40px' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberKey(event)"/>天自动收货</td>
	  </tr>
	  <tr>
	     <th>自动评价期限：</th>
	     <td>确认收货后<input type="text" id='autoAppraiseDays' class='ipt' value="<?php echo $object['autoAppraiseDays']; ?>" maxLength='3' style='width:40px' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberKey(event)"/>天自动好评</td>
	  </tr>
	  <?php if(($grant)): ?>
	  <tr>
	     <td colspan='2' align='center'>
	       <button type="button" onclick='javascript:edit()' class='btn btn-primary btn-mright'><i class="fa fa-check"></i>保存</button>
            <button type="reset"  class='btn'><i class="fa fa-refresh"></i>重置</button>
	     </td>
	  </tr>
	  <?php endif; ?>
	 </table>
</div><div class="layui-tab-item layui-form">
	<table class='wst-form wst-box-top'>
	<tr>
	     <th width='150'>密码加密传输：</th>
	     <td><input type="checkbox" <?php if($object['isCryptPwd']==1): ?>checked<?php endif; ?> value='1' class="ipt" id="isCryptPwd" name="isCryptPwd" lay-skin="switch" lay-filter="isCryptPwd" lay-text="开|关"><span style='color:gray;margin-left:5px;'>开启则用户登录、支付密码加密后再进行提交。<font color='red'>注意：该功能需开启openssl扩展支持!</font></span>
	     </td>
	</tr>
	<tr class='pwdCryptKeyTr' <?php if($object['isCryptPwd']==0): ?>style='display:none'<?php endif; ?>>
	    <th width='150'>商城密匙：</th>
	    <td>
	     	<textarea id='pwdPrivateKey' style='height:250px' class="ipt" placeholder='请输入用于登录、支付密码加密传输的密匙，请勿留空'><?php echo $object['pwdPrivateKey']; ?></textarea>
	    </td>
	</tr>
	<tr class='pwdCryptKeyTr' <?php if($object['isCryptPwd']==0): ?>style='display:none'<?php endif; ?>>
	    <th width='150'>Modulus：</th>
	    <td>
	     	<textarea id='pwdModulusKey' class="ipt" placeholder='请输入用于登录、支付密码加密传输的16进制公钥，请勿留空'><?php echo $object['pwdModulusKey']; ?></textarea>
	    </td>
	</tr>
	<?php if(($grant)): ?>
	<tr>
	    <td colspan='2' align="center">
	     	<button  type="button" class="btn btn-primary btn-mright" onclick='javascript:edit()'><i class="fa fa-check"></i>保存</button> 
        	<button class="btn" onclick='javascript:resetForm()'><i class="fa fa-refresh"></i>重置</button>
	    </td>
	 </tr>
	<?php endif; ?>
	</table>
</div><link rel="stylesheet" type="text/css" href="/static/plugins/colpick/css/colpick.css" />
<script src="/static/plugins/colpick/js/colpick.js"></script>
<div class="layui-tab-item layui-form">
    <table class='wst-form wst-box-top'>
	<tr>
	 <th>水印位置：</th>
	 <td>
	 	<label><input type="radio" id='watermarkPosition' name='watermarkPosition' class='ipt' value="0" <?php if(($object['watermarkPosition']==0)): ?>checked<?php endif; ?> title='无'/></label>
	 	<label><input type="radio" id='watermarkPosition' name='watermarkPosition' class='ipt' value="1" <?php if(($object['watermarkPosition']==1)): ?>checked<?php endif; ?> title='左上'/></label>
	 	<label><input type="radio" id='watermarkPosition' name='watermarkPosition' class='ipt' value="3" <?php if(($object['watermarkPosition']==3)): ?>checked<?php endif; ?> title='右上'/></label>
	 	<label><input type="radio" id='watermarkPosition' name='watermarkPosition' class='ipt' value="5" <?php if(($object['watermarkPosition']==5)): ?>checked<?php endif; ?> title='居中'/></label>
	 	<label><input type="radio" id='watermarkPosition' name='watermarkPosition' class='ipt' value="7" <?php if(($object['watermarkPosition']==7)): ?>checked<?php endif; ?> title='左下'/></label>
	 	<label><input type="radio" id='watermarkPosition' name='watermarkPosition' class='ipt' value="9" <?php if(($object['watermarkPosition']==9)): ?>checked<?php endif; ?> title='右下'/></label>
	 	<span style="color:gray;">设置为"无"则视为关闭水印</span>
	 </td>
	</tr>
	  <tr>
	     <th>水印文字偏移量：</th>
	     <td>
	     	<input type="text" placeholder="-10,-10" id='watermarkOffset' class='ipt' value="<?php echo $object['watermarkOffset']; ?>"/>
	     	<span style="color:gray;"> x，y 定义了第一个字符的左上角。x<0,向左偏移；x>0,向右偏移。y<0,向上偏移；y>0,向下偏移。</span>
	     </td>
	  </tr>
	  <tr>
	     <th width='150'>水印文字：</th>
	     <td>
	     	<input type="text" id='watermarkWord' class='ipt' value="<?php echo $object['watermarkWord']; ?>" maxLength='50' />
	     	<span style="color:gray;">当文字和图片同时存在时以文字为主</span>
	     </td>
	  </tr>
	  <tr>
	     <th>水印文字大小：</th>
	     <td>
	     	<input type="text" id='watermarkSize' class='ipt' value="<?php echo $object['watermarkSize']; ?>" maxLength='2'/>
	     	<span style="color:gray;">建议大小为20</span>
	     </td>
	  </tr>
	  <tr>
	     <th>水印文字颜色：</th>
	     <td>
	     	<input type="text" id='watermarkColor' class='ipt' value="<?php echo $object['watermarkColor']; ?>" />
	     	<span style="color:gray;">仅支持16进制的颜色：如#00FF00</span>
	     </td>
	  </tr>
	  <tr>
	     <th>水印文字字体路径：</th>
	     <td>
	     	<input type="text" id='watermarkTtf' class='ipt' value="<?php echo $object['watermarkTtf']; ?>" placeholder="extend/verify/verify/ttfs/test.ttf" />
	     	<span style="color:gray;">后缀为.ttf,若留空则使用默认字体(使用中文水印时，若字体不支持中文，则会出现方框)</span>
	     </td>
	  </tr>
	  <tr>
	     <th>水印文件：</th>
	     <td>
	     	<div id='watermarkFilePicker'>上传图标</div><span id='watermarkFileMsg'></span>
	     	<input type="hidden" id='watermarkFile' class='ipt' value="<?php echo $object['watermarkFile']; ?>" />
	     </td>
	  </tr>
	  	<tr>
          <th width='100'>水印图预览：</th>
          <td>
          	<div style="min-height:70px;" id="preview">
          	<?php if((isset($object['watermarkFile']))): ?>
          	 <img id='watermarkFilePrevw' src="/<?php echo $object['watermarkFile']; ?>" style="max-height:75px;" /> 
          	<?php endif; ?>
          	</div>
          	<span style="color:gray;">水印图最终大小由上传的图片大小决定</span>
          </td>
       </tr>
	  
	  <tr>
	     <th>水印透明度：</th>
	     <td>
	     	<input type="text" id='watermarkOpacity' class='ipt' value="<?php echo $object['watermarkOpacity']; ?>" />
	     	<br>
	     	<span style="color:gray;">水印的透明度,可选值为0-100。当设置为100时则为不透明</span>
	     </td>
	  </tr>
      <tr>
	     <th>商城Logo：</th>
	     <td>
	     <div id='mallLogoPicker'>请上传商城Logo</div><span id='mallLogoMsg'></span>
	     <img src='/<?php echo $object["mallLogo"]; ?>' width='120' hiegth='120' id='mallLogoPrevw'/>
	     <input type="hidden" id='mallLogo' class='ipt' value='<?php echo $object["mallLogo"]; ?>'/>
	     </td>
	  </tr>
	  <tr>
	     <th>默认店铺头像：</th>
	     <td>
	     <div id='shopLogoPicker'>请上传默认店铺头像</div><span id='shopLogoMsg'></span>
	     <img src='/<?php echo $object["shopLogo"]; ?>' width='120' hiegth='120' id='shopLogoPrevw'/>
	     <input type="hidden" id='shopLogo' class='ipt' value='<?php echo $object["shopLogo"]; ?>'/>
	     </td>
	  </tr>
	  <tr>
	     <th>移动端店铺默认顶部广告：</th>
	     <td>
	     <div id='shopAdtopPicker'>请上传移动端店铺默认顶部广告</div><span id='shopAdtopMsg'></span>
	     <img src='/<?php echo $object["shopAdtop"]; ?>' width='120' hiegth='120' id='shopAdtopPrevw'/>
	     <input type="hidden" id='shopAdtop' class='ipt' value='<?php echo $object["shopAdtop"]; ?>'/>
	     </td>
	  </tr>
	  <tr>
	     <th>默认会员头像：</th>
	     <td>
	     <div id='userLogoPicker'>请上传默认会员头像</div><span id='userLogoMsg'></span>
	     <img src='/<?php echo $object["userLogo"]; ?>' width='120' hiegth='120' id='userLogoPrevw'/>
	     <input type="hidden" id='userLogo' class='ipt' value='<?php echo $object["userLogo"]; ?>'/>
	     </td>
	  </tr>
	  <tr>
	     <th>默认商品图片：</th>
	     <td>
	     <div id='goodsLogoPicker'>请上传默认商品图片</div><span id='goodsLogoMsg'></span>
	     <img src='/<?php echo $object["goodsLogo"]; ?>' width='120' hiegth='120' id='goodsLogoPrevw'/>
	     <input type="hidden" id='goodsLogo' class='ipt' value='<?php echo $object["goodsLogo"]; ?>'/>
	     </td>
	  </tr>
	  <?php if(($grant)): ?>
	    <tr>
	      <td colspan='2' align="center">
	     	<button type="button" class="btn btn-primary btn-mright" onclick='javascript:edit()'><i class="fa fa-check"></i>保存</button> 
        	<button class="btn" onclick='javascript:resetForm()'><i class="fa fa-refresh"></i>重置</button>
	     </td>
	    </tr>
	 <?php endif; ?>
	 </table>
</div><style>
.staffName label{width:120px;display:inline-block;}
.staffName label input[type='checkbox']{margin-right:5px;}
</style>
<div class="layui-tab-item staffName">
     <table class='wst-form wst-box-top'>
      <tr>
      	<td colspan='2'>
      		   <div id='alertTips' class='alert alert-success alert-tips fade in' style='margin:0px 0px'>
				  <div id='headTip' class='head'><i class='fa fa-lightbulb-o'></i>操作说明</div>
				  <ul class='body'>
				    <li>因微信通和短信通知会在事件发生的时候触发，请勿同时发送给太多人，以免造成提交延时影响用户体验。</li>
				  </ul>
				</div>
      	</td>
      </tr>
      <tr>
	     <td colspan='2' class='head-ititle'>用户下单：</td>
	  </tr>
	  <tr>
	     <th width='150'>提醒方式：</th>
	     <td>
	     	<label><input type='checkbox' id='wxSubmitOrderTip' class='ipt' <?php if($object["wxSubmitOrderTip"]==1): ?>checked<?php endif; ?> onclick='javascript:checkTip("wxSubmitOrderTip,smsSubmitOrderTip","submitOrderTipUsers")'/>微信提醒</label>
	     	<label><input type='checkbox' id='smsSubmitOrderTip' class='ipt' <?php if($object["smsSubmitOrderTip"]==1): ?>checked<?php endif; ?> onclick='javascript:checkTip("wxSubmitOrderTip,smsSubmitOrderTip","submitOrderTipUsers")'/>短信提醒</label>
	     </td>
	  </tr>
	  <tr>
	     <th valign="top">提醒人：</th>
	     <td>
	        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	     	<label class='staffName'><input type='checkbox' class='ipt submitOrderTipUsers' value='<?php echo $vo['staffId']; ?>' <?php if(in_array($vo['staffId'],$object["submitOrderTipUsers"])): ?>checked<?php endif; ?>/><?php echo $vo['staffName']; ?></label>
	     	<?php endforeach; endif; else: echo "" ;endif; ?>
	     </td>
	  </tr>
	  <tr>
	     <td colspan='2' class='head-ititle'>支付订单：</td>
	  </tr>
	  <tr>
	     <th>提醒方式：</th>
	     <td>
	     	<label><input type='checkbox' id='wxPayOrderTip' class='ipt' <?php if($object["wxPayOrderTip"]==1): ?>checked<?php endif; ?> onclick='javascript:checkTip("wxPayOrderTip,smsPayOrderTip","payOrderTipUsers")'/>微信提醒</label>
	     	<label><input type='checkbox' id='smsPayOrderTip' class='ipt' <?php if($object["smsPayOrderTip"]==1): ?>checked<?php endif; ?> onclick='javascript:checkTip("wxPayOrderTip,smsPayOrderTip","payOrderTipUsers")'/>短信提醒</label>
	     </td>
	  </tr>
	  <tr>
	     <th valign="top">提醒人：</th>
	     <td>
	     	<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	     	<label class='staffName'><input type='checkbox' class='ipt payOrderTipUsers' value='<?php echo $vo['staffId']; ?>' <?php if(in_array($vo['staffId'],$object["payOrderTipUsers"])): ?>checked<?php endif; ?>/><?php echo $vo['staffName']; ?></label>
	     	<?php endforeach; endif; else: echo "" ;endif; ?>
	     </td>
	  </tr>
	  <tr>
	     <td colspan='2' class='head-ititle'>取消订单：</td>
	  </tr>
	  <tr>
	     <th>提醒方式：</th>
	     <td>
	     	<label><input type='checkbox' id='wxCancelOrderTip' class='ipt' <?php if($object["wxCancelOrderTip"]==1): ?>checked<?php endif; ?> onclick='javascript:checkTip("wxCancelOrderTip,smsCancelOrderTip","cancelOrderTipUsers")'/>微信提醒</label>
	     	<label><input type='checkbox' id='smsCancelOrderTip' class='ipt' <?php if($object["smsCancelOrderTip"]==1): ?>checked<?php endif; ?> onclick='javascript:checkTip("wxCancelOrderTip,smsCancelOrderTip","cancelOrderTipUsers")'/>短信提醒</label>
	     </td>
	  </tr>
	  <tr>
	     <th valign="top">提醒人：</th>
	     <td>
	     	<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	     	<label class='staffName'><input type='checkbox' class='ipt cancelOrderTipUsers' value='<?php echo $vo['staffId']; ?>' <?php if(in_array($vo['staffId'],$object["cancelOrderTipUsers"])): ?>checked<?php endif; ?>/><?php echo $vo['staffName']; ?></label>
	     	<?php endforeach; endif; else: echo "" ;endif; ?>
	     </td>
	  </tr>
	  <tr>
	     <td colspan='2' class='head-ititle'>拒收订单：</td>
	  </tr>
	  <tr>
	     <th>提醒方式：</th>
	     <td>
	     	<label><input type='checkbox' id='wxRejectOrderTip' class='ipt' <?php if($object["wxRejectOrderTip"]==1): ?>checked<?php endif; ?> onclick='javascript:checkTip("wxRejectOrderTip,smsRejectOrderTip","rejectOrderTipUsers")'/>微信提醒</label>
	     	<label><input type='checkbox' id='smsRejectOrderTip' class='ipt' <?php if($object["smsRejectOrderTip"]==1): ?>checked<?php endif; ?> onclick='javascript:checkTip("wxRejectOrderTip,smsRejectOrderTip","rejectOrderTipUsers")'/>短信提醒</label>
	     </td>
	  </tr>
	  <tr>
	     <th valign="top">提醒人：</th>
	     <td>
	     	<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	     	<label class='staffName'><input type='checkbox' class='ipt rejectOrderTipUsers' value='<?php echo $vo['staffId']; ?>' <?php if(in_array($vo['staffId'],$object["rejectOrderTipUsers"])): ?>checked<?php endif; ?>/><?php echo $vo['staffName']; ?></label>
	     	<?php endforeach; endif; else: echo "" ;endif; ?>
	     </td>
	  </tr>
	  <tr>
	     <td colspan='2' class='head-ititle'>申请退款：</td>
	  </tr>
	  <tr>
	     <th>提醒方式：</th>
	     <td>
	     	<label><input type='checkbox' id='wxRefundOrderTip' class='ipt' <?php if($object["wxRefundOrderTip"]==1): ?>checked<?php endif; ?> onclick='javascript:checkTip("wxRefundOrderTip,smsRefundOrderTip","refundOrderTipUsers")'/>微信提醒</label>
	     	<label><input type='checkbox' id='smsRefundOrderTip' class='ipt' <?php if($object["smsRefundOrderTip"]==1): ?>checked<?php endif; ?> onclick='javascript:checkTip("wxRefundOrderTip,smsRefundOrderTip","refundOrderTipUsers")'/>短信提醒</label>
	     </td>
	  </tr>
	  <tr>
	     <th valign="top">提醒人：</th>
	     <td>
	     	<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	     	<label class='staffName'><input type='checkbox' class='ipt refundOrderTipUsers' value='<?php echo $vo['staffId']; ?>' <?php if(in_array($vo['staffId'],$object["refundOrderTipUsers"])): ?>checked<?php endif; ?>/><?php echo $vo['staffName']; ?></label>
	     	<?php endforeach; endif; else: echo "" ;endif; ?>
	     </td>
	  </tr>
	  <tr>
	     <td colspan='2' class='head-ititle'>订单投诉：</td>
	  </tr>
	  <tr>
	     <th>提示方式：</th>
	     <td>
	     	<label><input type='checkbox' id='wxComplaintOrderTip' class='ipt' <?php if($object["wxComplaintOrderTip"]==1): ?>checked<?php endif; ?> onclick='javascript:checkTip("wxComplaintOrderTip,smsComplaintOrderTip","complaintOrderTipUsers")'/>微信提醒</label>
	     	<label><input type='checkbox' id='smsComplaintOrderTip' class='ipt' <?php if($object["smsComplaintOrderTip"]==1): ?>checked<?php endif; ?> onclick='javascript:checkTip("wxComplaintOrderTip,smsComplaintOrderTip","complaintOrderTipUsers")'/>短信提醒</label>
	     </td>
	  </tr>
	  <tr>
	     <th valign="top">提醒人：</th>
	     <td>
	     	<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	     	<label class='staffName'><input type='checkbox' class='ipt complaintOrderTipUsers' value='<?php echo $vo['staffId']; ?>' <?php if(in_array($vo['staffId'],$object["complaintOrderTipUsers"])): ?>checked<?php endif; ?>/><?php echo $vo['staffName']; ?></label>
	     	<?php endforeach; endif; else: echo "" ;endif; ?>
	     </td>
	  </tr>
	  <tr>
	     <td colspan='2' class='head-ititle'>申请提现：</td>
	  </tr>
	  <tr>
	     <th>提醒方式：</th>
	     <td>
	     	<label><input type='checkbox' id='wxCashDrawsTip' class='ipt' <?php if($object["wxCashDrawsTip"]==1): ?>checked<?php endif; ?> onclick='javascript:checkTip("wxCashDrawsTip,smsCashDrawsTip","cashDrawsTipUsers")'/>微信提醒</label>
	     	<label><input type='checkbox' id='smsCashDrawsTip' class='ipt' <?php if($object["smsCashDrawsTip"]==1): ?>checked<?php endif; ?> onclick='javascript:checkTip("wxCashDrawsTip,smsCashDrawsTip","cashDrawsTipUsers")'/>短信提醒</label>
	     </td>
	  </tr>
	  <tr>
	     <th valign="top">提醒人：</th>
	     <td>
	     	<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	     	<label class='staffName'><input type='checkbox' class='ipt cashDrawsTipUsers' value='<?php echo $vo['staffId']; ?>' <?php if(in_array($vo['staffId'],$object["cashDrawsTipUsers"])): ?>checked<?php endif; ?>/><?php echo $vo['staffName']; ?></label>
	     	<?php endforeach; endif; else: echo "" ;endif; ?>
	     </td>
	  </tr>
	  <tr>
	  	<?php if(($grant)): ?>
	     <td colspan='2' align='center'>
	     	<button type="button" class="btn btn-primary btn-mright" onclick='javascript:edit()'><i class="fa fa-check"></i>保存</button> 
        	<button type="reset" class="btn" ><i class="fa fa-refresh"></i>重置</button>
	     </td>
	     <?php endif; ?>
	  </tr>
	 </table>
</div><div class="layui-tab-item layui-form">
     <table class='wst-form wst-box-top'>
	  <tr>
	     <th width='150'>商城标题：</th>
	     <td><input type="text" id='seoMallTitle' class='ipt' value="<?php echo $object['seoMallTitle']; ?>" maxLength='100'/></td>
	  </tr>
	  <tr>
	     <th>商城关键字：</th>
	     <td><input type="text" id='seoMallKeywords' class='ipt' style='width:70%' value="<?php echo $object['seoMallKeywords']; ?>" maxLength='100'/></td>
	  </tr>
	  <tr>
	     <th>商城描述：</th>
	     <td><input type="text" id='seoMallDesc' class='ipt' style='width:70%' value="<?php echo $object['seoMallDesc']; ?>" maxLength='100'/></td>
	  </tr>
	  <tr>
	  	<?php if(($grant)): ?>
	     <td colspan='2' align='center'>
	     	<button type="button" class="btn btn-primary btn-mright" onclick='javascript:edit()'><i class="fa fa-check"></i>保存</button> 
        	<button type="reset" class="btn" onclick='javascript:resetForm()'><i class="fa fa-refresh"></i>重置</button>
	     </td>
	     <?php endif; ?>
	  </tr>
	 </table>
</div>
   </div>
</div>
</form>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script type='text/javascript' src='/static/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>' type="text/javascript"></script>
<script src="__ADMIN__/sysconfigs/sysconfigs.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>