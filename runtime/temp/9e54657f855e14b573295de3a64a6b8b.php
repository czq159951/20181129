<?php /*a:2:{s:49:"/home/mart/shangtao/home/view/default/regist.html";i:1535861870;s:50:"/home/mart/shangtao/home/view/default/base_js.html";i:1535861796;}*/ ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>用户注册 - <?php echo WSTConf('CONF.mallName'); ?><?php echo WSTConf('CONF.mallTitle'); ?></title>

<link href="__STYLE__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="/static/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">

<link href="__STYLE__/css/login.css?v=<?php echo $v; ?>" rel="stylesheet">
<script type="text/javascript" src="/static/js/rsa.js"></script>

<script type="text/javascript" src="/static/js/jquery.min.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="/static/plugins/layer/layer.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>
<script type='text/javascript' src='/static/js/common.js?v=<?php echo $v; ?>'></script>

<script type='text/javascript' src='__STYLE__/js/common.js?v=<?php echo $v; ?>'></script>
<script>
window.conf = {"ROOT":"","APP":"/index.php","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","SMS_VERFY":"<?php echo WSTConf('CONF.smsVerfy'); ?>","SMS_OPEN":"<?php echo WSTConf('CONF.smsOpen'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>","IS_LOGIN":"<?php if((int)session('WST_USER.userId')>0): ?>1<?php else: ?>0<?php endif; ?>","TIME_TASK":"1","ROUTES":'<?php echo WSTRoute(); ?>',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>"}
</script>
</head>
<body>

    <div class="wst-login-banner-regist">
      <div class="wst-icon-banner">
      	<a href='<?php echo app('request')->root(true); ?>' title="<?php echo WSTConf('CONF.mallName'); ?>" >
    	<div class="img-banner" >
    		<img src="/<?php echo WSTConf('CONF.mallLogo'); ?>">
    	</div>
        </a>
    	<div class="wst-login-action">
    	  <ul class="wst-icon" style="width: 300px;margin-top: 20px;">
	     	 <li class="wst-img-icon"></li><li class="wst-remind" style="color:#333;">您好!欢迎来到<?php echo WSTMSubstr(WSTConf('CONF.mallName'),0,13); ?>！<a href="<?php echo Url('home/users/login'); ?>" onclick="WST.currentUrl();" style="color:red;">&nbsp;&nbsp;请登录</a></li>
	      </ul>
    	</div>
       </div>
    </div>
<input type="hidden" id="token" value='<?php echo WSTConf("CONF.pwdModulusKey"); ?>'/>
<div class="wst-regist-b">
	<div class="wst-container">
	<div class="wst-regist">
	<div class="wst-regist-c">
	<input type="hidden" id="nameType" class="wst_ipt nameType" value='2' autocomplete="off"/>
	<form id="reg_form"  method="post" autocomplete="off">
	<div class="wst-regist-head">Hi～欢迎来注册</div>
	<table class="wst-table">
		<tr class="wst-login-tr">
			<td class="wst-regist-td"><font color='red'>*</font>用户名</td>
			<td><input id="loginName" name="loginName" class="wst_ipt wst-regist-input" tabindex="1" maxlength="30" autocomplete="off" onpaste="return false;" style="ime-mode:disabled;" placeholder="手机号"  type="text" onkeyup="javascript:WST.isChinese(this,1)" /></td>
		</tr>
		<tr class="wst-login-tr">
			<td class="wst-regist-td"><font color='red'>*</font>密码</td>
			<td><input id="loginPwd" name="loginPwd" class="wst_ipt wst-regist-input" tabindex="2" style="ime-mode:disabled;" autocomplete="off" type="password" placeholder="6-16位字符"/></td>
		</tr>
		<tr class="wst-login-tr">
			<td class="wst-regist-td"><font color='red'>*</font>确认密码</td>
			<td><input id="reUserPwd" name="reUserPwd" class="wst_ipt wst-regist-input" tabindex="3" autocomplete="off" type="password" placeholder="6-16位字符"/></td>
		</tr>
		<tr id="mobileCodeDiv" class="wst-login-tr">
			<td class="wst-regist-td"><font color='red'>*</font>短信验证码</td>
			<td>
				<input maxlength="6" autocomplete="off" tabindex="6" class="wst_ipt wst-regist-codemo" name="mobileCode" style="ime-mode:disabled" id="mobileCode" type="text" data-target="#mobileCodeTips" placeholder="短信验证码"/>
				<button type="button" id="timeTips" onclick="getVerifyCode();" class="wst-regist-obtain">获取短信验证码</button>
				<span id="mobileCodeTips"></span>
			</td>
		</tr>
		<tr class="wst-login-tr">
			<td colspan="2" style="padding-left:180px;">
				<label>
					<input id="protocol" name="protocol" type="checkbox" class="wst_ipt" value="1" data-rule="checked" data-msg="请同意后才能注册"/>我已阅读并同意
	           		<a href="javascript:;" style="color:#69b7b5;" id="protocolInfo" onclick="showProtocol();">《用户注册协议》</a>
	           	</label>                                  
			</td>
		</tr>
		<tr style="height:80px;">
			<td colspan="2" style="padding-left:260px;">
				<input id="reg_butt" class="wst-regist-but" type="submit" value="注册" style="width: 180px;height:30px;"/>
			</td>
		</tr>
	</table>
	</form>
	</div>
	</div>
	</div>
</div>
<div class="wst-footer">
		<div class="wst-container">
		<div class="wst-footer-hp-ck3">
	        <div class="links">
	           <?php $navs = WSTNavigations(1); if(is_array($navs) || $navs instanceof \think\Collection || $navs instanceof \think\Paginator): $i = 0; $__LIST__ = $navs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	           <a href="<?php echo $vo['navUrl']; ?>" <?php if($vo['isOpen']==1): ?>target="_blank"<?php endif; ?>><?php echo $vo['navTitle']; ?></a>
	           <?php if($i< count($navs)): ?>&nbsp;&nbsp;|&nbsp;&nbsp;<?php endif; endforeach; endif; else: echo "" ;endif; ?>
	        </div>
	        <div class="copyright">
	        <?php 
	        	if(WSTConf('CONF.mallFooter')!=''){
	         		echo htmlspecialchars_decode(WSTConf('CONF.mallFooter'));
	        	}
	         
				if(WSTConf('CONF.visitStatistics')!=''){
					echo htmlspecialchars_decode(WSTConf('CONF.visitStatistics'))."<br/>";
			    }
			 ?>
	       
	        </div>
	    </div>
	    </div>
	  </div>


	<script type="text/javascript" src="/static/plugins/validator/jquery.validator.min.js?v=<?php echo $v; ?>"></script>
	<script type='text/javascript' src='__STYLE__/js/login.js?v=<?php echo $v; ?>'></script>
	<Script>$(function(){initRegist();})</Script>

</body>