<?php /*a:5:{s:62:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/shops/edit.html";i:1536627214;s:56:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/base.html";i:1536627213;s:63:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/shops/edit0.html";i:1536627214;s:63:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/shops/edit1.html";i:1536627214;s:63:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/shops/edit2.html";i:1536627214;}*/ ?>
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
<link href="/static/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">

<link href="__ADMIN__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
<script>
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',app('request')->root(true)); ?>","ROOT":"","APP":"","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'<?php echo implode(",",session("WST_STAFF.privileges")); ?>',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","ROUTES":'<?php echo WSTRoute(); ?>',"MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>","__HTTP__":"<?php echo WSTProtocol(); ?>"}
</script>
<script language="javascript" type="text/javascript" src="/static/js/common.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="j-loader"><img src="__ADMIN__/img/ajax-loader.gif"/></div>

<style>
.goodsCat{display:inline-block;width:150px}
.accreds{display:inline-block;width:150px}
</style>
<div class="l-loading" style="display: block" id="wst-loading"></div>
<form id='editFrom' autocomplete='off'>
<input type='hidden' id='shopId' class='ipt' value="<?php echo $object['shopId']; ?>"/>
<div class="layui-tab layui-tab-brief" lay-filter="msgTab">
	<ul class="layui-tab-title">
	  <li class="layui-this">店铺信息</li>
	  <li>公司信息</li>
	  <li>税务及银行信息</li>
	</ul>
	<div class="layui-tab-content" style="padding: 10px 0;">
	 	<div class="layui-tab-item layui-show">
        <fieldset class="layui-elem-field layui-field-title">
<legend>基础信息</legend>
<table class='wst-form wst-box-top'>
    <tr>
       <th width='150'>店铺编号<font color='red'>*</font>：</th>
       <td><input type="text" id='shopSn' name='shopSn' class='ipt' value="<?php echo $object['shopSn']; ?>" maxLength='20' data-rule="店铺编号:<?php if($object['shopId']>0): ?>required;length[1~20];<?php else: ?>ignoreBlank;<?php endif; ?>remote(post:<?php echo url('admin/shops/checkShopSn',array('shopId'=>$object['shopId'])); ?>)" data-target="#msg_shopSn"/><span class='msg-box' id='msg_shopSn'><?php if($object['shopId']==0): ?>(为空则自动生成'S000000001'类型号码)<?php endif; ?></span></td>
    </tr>
    <tr>
       <th width='150'>店铺名称<font color='red'>*</font>：</th>
       <td><input type="text" id='shopName' class='ipt' value="<?php echo $object['shopName']; ?>" maxLength='20' data-rule="店铺名称: required;"/></td>
    </tr>
    <tr>
       <th width='150'>公司名称<font color='red'>*</font>：</th>
       <td><input type="text" id='shopCompany' class='ipt' maxLength='20' value="<?php echo $object['shopCompany']; ?>" data-rule="店铺名称: required;"/></td>
    </tr>
   
    <tr>
      <th>公司所在地<font color='red'>*</font>：</th>
      <td>
        <select id="area_0" class='j-areas' level="0" onchange="WST.ITAreas({id:'area_0',val:this.value,isRequire:true,className:'j-areas'});">
            <option value="">-请选择-</option>
            <?php foreach($areaList as $v): ?>
              <option value="<?php echo $v['areaId']; ?>"><?php echo $v['areaName']; ?></option>
            <?php endforeach; ?>
          </select>
          <?php if((WSTConf('CONF.mapKey'))): ?><button type='button' class='btn btn-primary' onclick='javascript:mapCity()'><i class='fa fa-map-marker'></i>地图定位</button><?php endif; ?>
      </td>
  </tr>
  <?php if((WSTConf('CONF.mapKey'))): ?>
  <tr>
    <th>&nbsp;</th>
    <td>
       <div id="container" style='width:700px;height:400px'></div> 
    </td>
  </tr>
  <?php endif; ?>
  <tr>
      <th>公司详细地址<font color='red'>*</font>：</th>
      <td>
          <input type='hidden' id='mapLevel' class='ipt'  value="<?php echo $object['mapLevel']; ?>"/>
          <input type='hidden' id='longitude' class='ipt'  value="<?php echo $object['longitude']; ?>"/>
          <input type='hidden' id='latitude' class='ipt'  value="<?php echo $object['latitude']; ?>"/>
          <input type='text' id='shopAddress' class='ipt' style='width:550px' data-rule='公司详细地址:required;' value="<?php echo $object['shopAddress']; ?>"/>
          </td>
  </tr>
  <tr>
      <th>公司电话<font color='red'>*</font>：</th>
          <td>
          <input type='text' id='shopTel' class='ipt' data-rule='公司电话:required;' value="<?php echo $object['shopTel']; ?>"/>
      </td>
  </tr>
  <tr>
      <th>公司紧急联系人<font color='red'>*</font>：</th>
      <td>
          <input type='text' id='shopkeeper' class='ipt' data-rule='公司紧急联系人:required;' value="<?php echo $object['shopkeeper']; ?>"/>
      </td>
  </tr>
  <tr>
      <th>公司紧急联系人手机<font color='red'>*</font>：</th>
      <td>
          <input type='text' id='telephone' class='ipt' data-rule='公司紧急联系人手机:required;mobile' value="<?php echo $object['telephone']; ?>"/>
      </td>
  </tr>
    <tr>
       <th>经营类目<font color='red'>*</font>：</th>
       <td>
         <?php if(is_array($goodsCatList) || $goodsCatList instanceof \think\Collection || $goodsCatList instanceof \think\Paginator): $i = 0; $__LIST__ = $goodsCatList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
         <label class='goodsCat'>
            <input type='checkbox' class='ipt' name='goodsCatIds' value='<?php echo $vo["catId"]; ?>' <?php if($i == 1): ?>data-rule="经营类目:checked"<?php endif; if(array_key_exists($vo['catId'],$object['catshops'])): ?>checked<?php endif; ?>/><?php echo $vo["catName"]; ?>
         </label>
         <?php endforeach; endif; else: echo "" ;endif; ?>
       </td>
    </tr>
    <tr>
       <th>认证类型：</th>
       <td>
         <?php if(is_array($accredList) || $accredList instanceof \think\Collection || $accredList instanceof \think\Paginator): $i = 0; $__LIST__ = $accredList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
         <label class='accreds'>
            <input type='checkbox' class='ipt' name='accredIds' value='<?php echo $vo["accredId"]; ?>' <?php if(array_key_exists($vo['accredId'],$object['accreds'])): ?>checked<?php endif; ?>/><?php echo $vo["accredName"]; ?>
         </label>
         <?php endforeach; endif; else: echo "" ;endif; ?>
       </td>
    </tr>
    <tr>
       <th>店铺图标<font color='red'>*</font>：</th>
       <td>
       <div id='shopImgPicker'>上传店铺图标</div><span id='uploadMsg'></span><span class='msg-box' id='msg_shopImg'></span>
       <?php if($object["shopImg"]!=''): ?>
       <img id='preview' width='150' height='150' src='/<?php echo $object["shopImg"]; ?>'/>
       <?php else: ?>
       <img id='preview' width='150' height='150' src="/<?php echo WSTConf('CONF.shopLogo'); ?>"/>
       <?php endif; ?>
       <input type="hidden" id='shopImg' class='ipt' value="<?php echo $object['shopImg']; ?>" data-rule="店铺图标: required;" data-target='#msg_shopImg'/>
       </td>
    </tr>
    <tr>
       <th>客服QQ：</th>
       <td><input type="text" id='shopQQ' class='ipt' value="<?php echo $object['shopQQ']; ?>" maxLength='200'/><span style='color:gray;'>做为客服接收临时消息的QQ,需开通<a target="_blank" href="http://shang.qq.com/v3/index.html">QQ推广功能</a> -> '首页'-> '推广工具'-> '立即免费开通'</span></td>

    </tr>
    <tr>
       <th>阿里旺旺：</th>
       <td><input type="text" id='shopWangWang' class='ipt' value="<?php echo $object['shopWangWang']; ?>" maxLength='200'/></td>
    </tr>
    
    <tr>
       <th>是否提供开发票<font color='red'>*</font>：</th>
       <td class='layui-form'>
          <label>
             <input type='radio' class='ipt' name='isInvoice' id='isInvoice1' value='1' <?php if($object['isInvoice']==1): ?>checked<?php endif; ?> onclick='javascript:WST.showHide(1,"#trInvoice")' title='是'>
          </label>
          <label>
             <input type='radio' class='ipt' name='isInvoice' value='0' <?php if($object['isInvoice']==0): ?>checked<?php endif; ?> onclick='javascript:WST.showHide(0,"#trInvoice")' title='否'>
          </label>
       </td>
    </tr>
    <tr id='trInvoice' <?php if($object['isInvoice']==0): ?>style='display:none'<?php endif; ?>>
       <th>发票说明<font color='red'>*</font>：</th>
       <td><input type="text" id='invoiceRemarks' class='ipt' value="<?php echo $object['invoiceRemarks']; ?>" style='width:500px;' maxLength='100' data-rule="发票说明:required(#isInvoice1:checked)"/></td>
    </tr>
    <tr>
       <th>营业状态<font color='red'>*</font>：</th>
       <td class='layui-form'>
          <label>
             <input type='radio' class='ipt' name='shopAtive' value='1' <?php if($object['shopAtive']==1): ?>checked<?php endif; ?> title='营业中'>
          </label>
          <label>
             <input type='radio' class='ipt' name='shopAtive' value='0' <?php if($object['shopAtive']==0): ?>checked<?php endif; ?> title='休息中'>
          </label>
       </td>
    </tr>
    <tr>
       <th>默认运费：</th>
       <td><input type="text" id='freight' class='ipt' value="<?php echo $object['freight']; ?>" maxLength='8' data-rule="默认运费: required;" onkeypress='return WST.isNumberdoteKey(event);' onkeyup="javascript:WST.isChinese(this,1)"/></td>
    </tr>
    <tr>
       <th>服务时间<font color='red'>*</font>：</th>
       <td>
          <select class='ipt' id='serviceStartTime'></select>
          至
          <select class='ipt' id='serviceEndTime'></select>
       </td>
    </tr>
  </table>
</fieldset>
<fieldset class="layui-elem-field layui-field-title">
<legend>入驻商联系人信息</legend>
<table class='wst-form wst-box-top'>
    <tr>
       <th width='150'>联系人姓名：</th>
       <td>
         <input type='text' id='applyLinkMan' class='ipt' value="<?php echo $object['applyLinkMan']; ?>"/>
       </td>
    </tr>
    <tr>
       <th>联系人手机：</th>
       <td>
         <input type='text' class='ipt' id='applyLinkTel' data-rule="mobile" value="<?php echo $object['applyLinkTel']; ?>"/>
       </td>
    </tr>
    <tr>
       <th>联系人电子邮箱：</th>
       <td>
         <input type='text' name='applyLinkEmail' class='ipt' data-rule="email" id='applyLinkEmail' value="<?php echo $object['applyLinkEmail']; ?>"/>
       </td>
    </tr>
    <tr>
       <th>对接商城招商人员：</th>
       <td class='layui-form'>
         <label>
            <input type='radio' name='isInvestment' class='ipt' id='isInvestment1' value='1' onclick='javascript:WST.showHide(1,"#investmentStaffTr")' <?php if($object['isInvestment']==1): ?>checked<?php endif; ?> title='有'/>
         </label>
         <label>
            <input type='radio' name='isInvestment' class='ipt' id='isInvestment0' value='0' onclick='javascript:WST.showHide(0,"#investmentStaffTr")' <?php if($object['isInvestment']==0): ?>checked<?php endif; ?> title='无'/>
         </label>
       </td>
    </tr>
    <tr id='investmentStaffTr' <?php if($object['isInvestment']==0): ?>style='display:none'<?php endif; ?>>
       <th>姓名<font color='red'>*</font>：</th>
       <td>
          <input type='text' name='investmentStaff' id='investmentStaff' class='ipt' data-rule="商城招商人员姓名:required(#isInvestment1:checked)" value="<?php echo $object['investmentStaff']; ?>"/>
       </td>
    </tr>
  </table>
</fieldset>
<fieldset class="layui-elem-field layui-field-title">
<legend>店铺状态</legend>
<table class='wst-form wst-box-top'>
    <tr>
       <th width='150'>店铺状态<font color='red'>*</font>：</th>
       <td class='layui-form'>
          <label>
             <input type='radio' class='ipt' name='shopStatus' id='shopStatus-1' value='-1' <?php if($object['shopStatus']==-1): ?>checked<?php endif; ?> onclick='javascript:WST.showHide(1,"#trStatusDesc")' title='停止'>
          </label>
          <label>
             <input type='radio' class='ipt' name='shopStatus' value='1' <?php if($object['shopStatus']==1): ?>checked<?php endif; ?> onclick='javascript:WST.showHide(0,"#trStatusDesc")' title='正常'>
          </label>
       </td>
    </tr>
    <tr id='trStatusDesc' <?php if($object['shopStatus']==1): ?>style='display:none'<?php endif; ?>>
       <th>停止原因<font color='red'>*</font>：</th>
       <td><textarea id='statusDesc' class='ipt' style='width:500px;height:100px;' maxLength='100' data-rule="停止原因:required(#shopStatus-1:checked);"><?php echo $object['statusDesc']; ?></textarea></td>
    </tr>
    <tr>
       <td colspan='2' align='center'>
       	<button type="button"  class='btn btn-primary btn-mright' onclick='javascript:save()'><i class="fa fa-check"></i>保存</button>
       	<button type="button"  class='btn' onclick='javascript:history.go(-1)'><i class="fa fa-angle-double-left"></i>返回</button>
       </td>
    </tr>
</table>
</fieldset>
        </div>
        <div class="layui-tab-item">
	     <fieldset class="layui-elem-field layui-field-title">
<legend>公司信息</legend>
<table class='wst-form wst-box-top'>
  <tr>
      <th width='150'>执照类型：</th>
      <td>
          <select id='businessLicenceType' class='ipt'>
             <?php $_result=WSTDatas('LICENSE_TYPE');if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
             <option value='<?php echo $vo["dataVal"]; ?>' <?php if($object['businessLicenceType']==$vo["dataVal"]): ?>selected<?php endif; ?>><?php echo $vo["dataName"]; ?></option>
             <?php endforeach; endif; else: echo "" ;endif; ?>
          </select>
      </td>
  </tr>
  <tr>
      <th valign="top">营业执照注册号：</th>
      <td><input type='text' id='businessLicence' class='ipt' value="<?php echo $object['businessLicence']; ?>"/><br/><span style='color:gray;'>请按照营业执照上登记的完整名称填写</span></td>
  </tr>
  <tr>
      <th valign="top">法定代表人姓名：</th>
      <td>
          <input type='text' id='legalPersonName' class='ipt' value="<?php echo $object['legalPersonName']; ?>"/>
          <br/><span style='color:gray;'>请按照营业执照上登记的法人填写</span>
      </td>
  </tr>
  <tr>
      <th>营业执照所在地：</th>
      <td>
            <select id="carea_0" class='j-careas' level="0" onchange="WST.ITAreas({id:'carea_0',val:this.value,isRequire:false,className:'j-careas'});">
            <option value="">-请选择-</option>
            <?php foreach($areaList as $v): ?>
              <option value="<?php echo $v['areaId']; ?>"><?php echo $v['areaName']; ?></option>
            <?php endforeach; ?>
          </select>
      </td>
  </tr>
  <tr>
      <th>营业执照详细地址：</th>
      <td>
          <input type='text' id='licenseAddress' class='ipt' style='width:550px' value="<?php echo $object['licenseAddress']; ?>"/>
      </td>
  </tr>
  <tr>
      <th>成立日期：</th>
      <td>
          <input type='text' id='establishmentDate' readonly class='ipt laydate-icon' value="<?php echo $object['establishmentDate']; ?>"/>
      </td>
  </tr>
  <tr>
      <th>营业期限：</th>
      <td>
          <input type='text' id='businessStartDate' readonly class='ipt laydate-icon' value="<?php echo $object['businessStartDate']; ?>"/> - 
          <input type='text' id='businessEndDate' <?php if($object['isLongbusinessDate']==1): ?>style='display:none'<?php endif; ?> readonly class='ipt laydate-icon' value="<?php echo $object['businessEndDate']; ?>"/>&nbsp;&nbsp;&nbsp;
          <label><input type='checkbox' name='isLongbusinessDate' id='isLongbusinessDate' class='ipt' onclick='WST.showHide(this.checked?0:1,"#businessEndDate")' value='1' <?php if($object['isLongbusinessDate']==1): ?>checked<?php endif; ?>/>长期</label>
      </td>
  </tr>
  <tr>
      <th>注册资本（万元）：</th>
      <td>
          <input type='text' id='registeredCapital' class='ipt' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberKey(event)" value="<?php echo $object['registeredCapital']; ?>"/>
      </td>
  </tr>
  <tr>
      <th>经营范围：</th>
      <td>
             <textarea id='empiricalRange' class='ipt' style='width:550px;height:150px;'><?php echo $object['empiricalRange']; ?></textarea>
      </td>
  </tr>
  <tr>
      <th>法人代表证件类型：</th>
      <td>
          <select id='legalCertificateType' class='ipt'>
             <?php $_result=WSTDatas('LEGAL_LICENSE');if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
             <option value='<?php echo $vo["dataVal"]; ?>' <?php if($object['legalCertificateType']==$vo['dataVal']): endif; ?>><?php echo $vo["dataName"]; ?></option>
             <?php endforeach; endif; else: echo "" ;endif; ?>
          </select>
      </td>
  </tr>
  <tr>
      <th>法定代表人证件号：</th>
      <td>
          <input type='text' id='legalCertificate' class='ipt' value="<?php echo $object['legalCertificate']; ?>"/>
      </td>
  </tr>
  <tr>
      <th>有效期：</th>
      <td>
          <input type='text' id='legalCertificateStartDate' readonly class='ipt laydate-icon' value="<?php echo $object['legalCertificateStartDate']; ?>"/> - 
          <input type='text' id='legalCertificateEndDate' readonly value="<?php echo $object['legalCertificateEndDate']; ?>" <?php if($object['isLonglegalCertificateDate']==1): ?>style='display:none'<?php endif; ?> class='ipt laydate-icon' />&nbsp;&nbsp;&nbsp;
          <label><input type='checkbox' name='isLonglegalCertificateDate' id='isLonglegalCertificateDate' class='ipt' onclick='WST.showHide(this.checked?0:1,"#legalCertificateEndDate")' value='1' <?php if($object['isLonglegalCertificateDate']==1): ?>checked<?php endif; ?>/>长期</label>
          
      </td>
  </tr>
  <tr>
      <th>法人证件电子版：</th>
      <td>
          <input type='hidden' id='legalCertificateImg' class='ipt' value='<?php echo $object['legalCertificateImg']; ?>'/>
          <div id='legalCertificateImgPicker'>请上传法人证件电子版</div>
          <span id='legalCertificateImgMsg'></span>
          <a id='legalCertificateImgPreview_a' href='/<?php echo $object['legalCertificateImg']; ?>' target='_blank'>
          <img id='legalCertificateImgPreview' src='/<?php echo $object['legalCertificateImg']; ?>' <?php if($object['legalCertificateImg'] ==''): ?>style='display:none'<?php endif; ?> width='150'>
          </a>
      </td>
  </tr>
  <tr>
      <th>营业执照电子版：</th>
      <td>
          <input type='hidden' id='businessLicenceImg' class='ipt' value='<?php echo $object['businessLicenceImg']; ?>'/>
          <div id='businessLicenceImgPicker'>请上传营业执照电子版</div>
          <span id='businessLicenceImgMsg'></span>
          <a id='businessLicenceImgPreview_a' href='/<?php echo $object['businessLicenceImg']; ?>' target='_blank'>
          <img id='businessLicenceImgPreview' src='/<?php echo $object['businessLicenceImg']; ?>' <?php if($object['businessLicenceImg'] ==''): ?>style='display:none'<?php endif; ?> width='150'>
          </a>
      </td>
  </tr>
  <tr>
      <th>银行开户许可证电子版：</th>
      <td>
          <input type='hidden' id='bankAccountPermitImg' class='ipt' value='<?php echo $object['bankAccountPermitImg']; ?>'/>
          <div id='bankAccountPermitImgPicker'>请上传银行开户许可证电子版</div>
          <span id='bankAccountPermitImgMsg'></span>
          <a id='bankAccountPermitImgPreview_a' href='/<?php echo $object['bankAccountPermitImg']; ?>' target='_blank'>
          <img id='bankAccountPermitImgPreview' src='/<?php echo $object['bankAccountPermitImg']; ?>' <?php if($object['bankAccountPermitImg'] ==''): ?>style='display:none'<?php endif; ?> width='150'>
          </a>
      </td>
  </tr>
  </table>
</fieldset>
<fieldset class="layui-elem-field layui-field-title">
<legend>组织机构代码证</legend>
<table class='wst-form wst-box-top'>
  <tr>
      <th width='150'>组织机构代码：</th>
      <td>
          <input type='text' id='organizationCode' class='ipt' value="<?php echo $object['organizationCode']; ?>"/>
      </td>
  </tr>
  <tr>
      <th>组织机构代码证有效期：</th>
      <td>
          <input type='text' id='organizationCodeStartDate' readonly class='ipt laydate-icon' value="<?php echo $object['organizationCodeStartDate']; ?>"/> - 
          <input type='text' id='organizationCodeEndDate' readonly value="<?php echo $object['organizationCodeEndDate']; ?>" <?php if($object['isLongOrganizationCodeDate']==1): ?>style='display:none'<?php endif; ?> class='ipt laydate-icon'/>&nbsp;&nbsp;&nbsp;
          <label><input type='checkbox' name='isLongOrganizationCodeDate' id='isLongOrganizationCodeDate' class='ipt' onclick='WST.showHide(this.checked?0:1,"#organizationCodeEndDate")' value='1' <?php if($object['isLongOrganizationCodeDate']==1): ?>checked<?php endif; ?>/>长期</label>
      </td>
  </tr>
  <tr>
      <th valign="top">组织机构代码证电子版：</th>
      <td>
          <span style='color:gray;'>复印件需加盖公司红章扫描上传，三证合一的此处请上传营业执照电子版</span><br/>
          <input type='hidden' id='organizationCodeImg' class='ipt' value='<?php echo $object['organizationCodeImg']; ?>'/>
          <div id='organizationCodeImgPicker'>请上传组织机构代码证电子版</div>
          <span id='organizationCodeImgMsg'></span>
          <a id='organizationCodeImgPreview_a' href='/<?php echo $object['organizationCodeImg']; ?>' target='_blank'>
          <img id='organizationCodeImgPreview' src='/<?php echo $object['organizationCodeImg']; ?>' <?php if($object['organizationCodeImg'] ==''): ?>style='display:none'<?php endif; ?> width='150'>
          </a>
      </td>
  </tr>
  <tr>
      <td colspan='2' align='center'>
      	 <button type="button"  class='btn btn-primary btn-mright' onclick='javascript:save()'><i class="fa fa-check"></i>保存</button>
      	 <button type="button"  class='btn' onclick='javascript:history.go(-1)'><i class="fa fa-angle-double-left"></i>返回</button>
      </td>
  </tr>
</table>
</fieldset>
	   </div>
	   <div class="layui-tab-item">
	     <fieldset class="layui-elem-field layui-field-title">
<legend>税务信息</legend>
<table class='wst-form wst-box-top'>
  <tr>
      <th width='150'>纳税人类型：</th>
      <td>
          <select id='taxpayerType' class='ipt'>
             <?php $_result=WSTDatas('TAXPAYER_TYPE');if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
             <option value='<?php echo $vo["dataVal"]; ?>' <?php if($object['taxpayerType']==$vo["dataVal"]): ?>selected<?php endif; ?>><?php echo $vo["dataName"]; ?></option>
             <?php endforeach; endif; else: echo "" ;endif; ?>
          </select>
      </td>
  </tr>
  <tr>
      <th valign="top">纳税人识别号：</th>
      <td><input type='text' id='taxpayerNo' class='ipt' value='<?php echo $object["taxpayerNo"]; ?>'/><br/><span style='color:gray;'>三证合一的请填写统一社会信用代码</span></td>
  </tr>
  <tr>
      <th valign="top">税务登记证电子版：</th>
      <td>
            <span style='color:gray;'>请同时上传国税、地税的税务登记证，两者缺一不可，复印件加盖公司红章，如贵司所在地区已推行“三证合一”;<br/>此处请上传营业执照副本电子版。【最多只能上传三张图片】</span><br/>
            <input type='hidden' id='taxRegistrationCertificateImg' class='ipt' value='<?php echo $object["taxRegistrationCertificateImg"]; ?>'/>
            <div id='taxRegistrationCertificateImgPicker'>请上传组织机构代码证电子版</div>
            <span id='taxRegistrationCertificateImgMsg'></span>
            <div id='taxRegistrationCertificateImgBox'></div>
            <span class='msg-box' id='msg_taxRegistrationCertificateImg'>
              <?php if(is_array($object['taxRegistrationCertificateImgVO']) || $object['taxRegistrationCertificateImgVO'] instanceof \think\Collection || $object['taxRegistrationCertificateImgVO'] instanceof \think\Paginator): $i = 0; $__LIST__ = $object['taxRegistrationCertificateImgVO'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
              <div style="width:75px;float:left;margin-right:5px;">
                <a href='/<?php echo $vo; ?>' target='_blank'>
                <img class="step_pic" width="75" height="75" src="/<?php echo $vo; ?>" v="<?php echo $vo; ?>">
                </a>
                <div style="position:relative;top:-80px;left:60px;cursor:pointer;" onclick='javascript:delVO(this)'>
                  <img src="/shangtao/home/View/default/img/seller_icon_error.png">
                </div>
              </div>
              <?php endforeach; endif; else: echo "" ;endif; ?>
            </span>
      </td>
  </tr>
  <tr>
      <th valign="top">一般纳税人资格证电子版：</th>
      <td>
          <span style='color:gray;'>三证合一地区请上传税务局网站上一般纳税人截图，复印件需加盖公司红章。</span><br/>
          <input type='hidden' id='taxpayerQualificationImg' class='ipt' value='<?php echo $object["taxpayerQualificationImg"]; ?>'/>
          <div id='taxpayerQualificationImgPicker'>请上传法人证件电子版</div>
          <span id='taxpayerQualificationImgMsg'></span>
          <a id='taxpayerQualificationImgPreview_a' href='/<?php echo $object["taxpayerQualificationImg"]; ?>' target='_blank'>
          <img id='taxpayerQualificationImgPreview' src='/<?php echo $object["taxpayerQualificationImg"]; ?>' <?php if($object["taxpayerQualificationImg"]==''): ?>style='display:none'<?php endif; ?> width='150'>
          </a>
      </td>
  </tr>
  </table>
</fieldset>
<fieldset class="layui-elem-field layui-field-title">
<legend>结算账号信息</legend>
<table class='wst-form wst-box-top'>
  <tr>
      <th width='150'>银行开户名<font color='red'>*</font>：</th>
      <td>
            <input type='text' id='bankUserName' maxlength='50' class='ipt' data-rule='银行开户名:required;' value='<?php echo $object["bankUserName"]; ?>'/>
      </td>
  </tr>
  <tr>
      <th>对公结算银行账号<font color='red'>*</font>：</th>
      <td>
            <input type='text' id='bankNo' class='ipt' maxlength='20'  data-rule='对公结算银行账号:required;' value='<?php echo $object["bankNo"]; ?>'/>
      </td>
  </tr>
  <tr>
      <th>开户银行名称<font color='red'>*</font>：</th>
      <td>
          <select id='bankId' id='bankId' class='ipt' data-rule='对公结算银行账号:required;'>
              <?php foreach($bankList as $v): ?>
              <option value="<?php echo $v['bankId']; ?>" <?php if($object['bankId']==$v['bankId']): ?>selected<?php endif; ?>><?php echo $v['bankName']; ?></option>
              <?php endforeach; ?>
          </select>
      </td>
  </tr>
  <tr>
      <th>开户银行支行所在地<font color='red'>*</font>：</th>
      <td>
          <select id="barea_0" class='j-bareas' level="0" onchange="WST.ITAreas({id:'barea_0',val:this.value,isRequire:true,className:'j-bareas'});">
            <option value="">-请选择-</option>
            <?php foreach($areaList as $v): ?>
              <option value="<?php echo $v['areaId']; ?>"><?php echo $v['areaName']; ?></option>
            <?php endforeach; ?>
          </select>
      </td>
  </tr>
  <tr>
      <td colspan='2' align='center'>
      	<button type="button"  class='btn btn-primary btn-mright' onclick='javascript:save()'><i class="fa fa-check"></i>保存</button>
      	<button type="button"  class='btn' onclick='javascript:history.go(-1)'><i class="fa fa-angle-double-left"></i>返回</button>
      </td>
  </tr>
</table>
</fieldset>
	   </div>
	</div>
</div>
</form>
<script>
$(function(){initEdit({serviceStartTime:'<?php echo date("H:i",strtotime($object["serviceStartTime"])); ?>',serviceEndTime:'<?php echo date("H:i",strtotime($object["serviceEndTime"])); ?>',areaId:'<?php echo $object["areaId"]; ?>',areaIdPath:'<?php echo $object["areaIdPath"]; ?>',bankAreaId:'<?php echo $object["bankAreaId"]; ?>',bankAreaIdPath:'<?php echo $object["bankAreaIdPath"]; ?>',longitude:'<?php echo $object["longitude"]; ?>',latitude:'<?php echo $object["latitude"]; ?>',mapLevel:<?php echo $object["mapLevel"]; ?>,businessAreaPath:'<?php echo $object["businessAreaPath"]; ?>',isEdit:true});})
</script>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script charset="utf-8" src="<?php echo WSTProtocol(); ?>map.qq.com/api/js?v=2.exp"></script>
<script type='text/javascript' src='/static/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>'></script>
<script src="__ADMIN__/shops/shops.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>