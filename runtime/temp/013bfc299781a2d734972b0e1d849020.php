<?php /*a:2:{s:69:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/shop_login.html";i:1536734393;s:66:"/www/beidou/mart/zsbd_mart/shangtao/home/view/default/base_js.html";i:1536627231;}*/ ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>商家登录 - <?php echo WSTConf('CONF.mallName'); ?><?php echo WSTConf('CONF.mallTitle'); ?></title>

<link href="__STYLE__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="/static/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">

<link href="/static/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="__STYLE__/css/login.css?v=<?php echo $v; ?>" rel="stylesheet">

<script type="text/javascript" src="/static/js/jquery.min.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="/static/plugins/layer/layer.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>
<script type='text/javascript' src='/static/js/common.js?v=<?php echo $v; ?>'></script>

<script type='text/javascript' src='__STYLE__/js/common.js?v=<?php echo $v; ?>'></script>
<script>
window.conf = {"ROOT":"","APP":"","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","SMS_VERFY":"<?php echo WSTConf('CONF.smsVerfy'); ?>","SMS_OPEN":"<?php echo WSTConf('CONF.smsOpen'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>","IS_LOGIN":"<?php if((int)session('WST_USER.userId')>0): ?>1<?php else: ?>0<?php endif; ?>","TIME_TASK":"1","ROUTES":'<?php echo WSTRoute(); ?>',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>"}
</script>
</head>
<body>

	<input type="hidden" id="token" value='<?php echo WSTConf("CONF.pwdModulusKey"); ?>'/>
	<div class="wst-header wst-color">
    <div class="wst-nav">
		<ul class="headlf">
			<li class="drop-info">
			  <div>欢迎来到<?php echo WSTMSubstr(WSTConf('CONF.mallName'),0,13); ?><a href="<?php echo Url('home/users/login'); ?>" onclick="WST.currentUrl();">&nbsp;&nbsp;请&nbsp;登录</a></div>
			</li>
			<li class="spacer">|</li>
			<li class="drop-info">
			  <div><a href="<?php echo Url('home/users/regist'); ?>" onclick="WST.currentUrl();">免费注册</a></div>
			</li>
		</ul>
		<ul class="wst-icon">
		 <li class="wst-img-icon"></li><li class="wst-remind">欢迎登陆!</li>
	    </ul>
		<div class="wst-clear"></div>
	  </div>
	</div>
    <div class="wst-login-banner">
      <div class="wst-icon-banner">
      	<a href='<?php echo app('request')->root(true); ?>' title="<?php echo WSTConf('CONF.mallName'); ?>" >
    	<div class="img-banner" >
    		<img src="/<?php echo WSTConf('CONF.mallLogo'); ?>">    		
    	</div>
        </a>
    	<div class="wst-stript"></div>
    	<div class="wst-login-action">
    		<div class="wst-left">商家登录</div>
    		
    	</div>
       </div>
    </div>
	<div class="wst-login-middle-shop">
	<div class="wst-container">
	<div class="wst-login_l_shop">
	<div class="wst-login_r">
		<form method="post" autocomplete="off">
		<span class="wst-login-u">商家登录</span>
		<input type='hidden' id='typ' value='2' class='ipt'/>
		<div class="wst-item wst-item-box" style="margin-top: 20px;">
				<div for="loginname" class="login-img"></div>
				<input id="loginName" name="loginName" class="ipt wst-login-input-1"  tabindex="1" value="<?php echo $loginName; ?>" autocomplete="off" type="text" data-rule="用户名: required;" data-msg-required="请填写用户名" data-tip="请输入用户名" placeholder="邮箱/用户名/手机号"/>
			</div>
			<div class="wst-item wst-item-box">
				<div for="loginname" class="password-img"></div>
				<input id="loginPwd" name="loginPwd" class="ipt wst-login-input-1" tabindex="2" autocomplete="off" type="password" data-rule="密码: required;" data-msg-required="请填写密码" data-tip="请输入密码" placeholder="密码"/> 
			</div>
			<div class="wst-item wst-item-box">
				<div for="loginname" class="yanzheng-img"></div>
				<div class="wst-login-code-1">
					<input id="verifyCode" style="ime-mode:disabled" name="verifyCode"  class="ipt wst-login-codein-1" tabindex="6" autocomplete="off" maxlength="6" type="text" data-rule="验证码: required;" data-msg-required="请输入验证码" data-tip="请输入验证码" data-target="#verify"placeholder="验证码"/>
					<img id='verifyImg' class="wst-login-codeim-1" src="<?php echo url('home/users/getVerify'); ?>" onclick="javascript:WST.getVerify('#verifyImg')" style="width:125px;height:36px;"><span id="verify"></span>    	
				</div>
			</div>
			<table class="wst-table">
			<tr class="wst-login-tr">
				<td colspan="2" style="padding-left:0px;">
					<input id="rememberPwd" name="rememberPwd" class="ipt wst-login-ch" checked="checked" type="checkbox"/>
			     	<label>记住密码</label>                                      
					<label><a style="color:#b2b1b1;padding-left: 140px;float:right;" href="<?php echo Url('home/Users/forgetPass'); ?>">忘记密码? </a></label>
				</td>
			</tr>
			</table>
			<div class="wst-item wst-item-box" style="border: 0;" >
				<div style="width: 100%;height:32px;line-height:32px;float:left;"><a class="wst-login-but" href="javascript:void(0);" onclick='javascript:login(2)'>登录</a></div>
			</div>
		</form>
		 <span class="wst-login-three" style='display:none'>您还可以使用以下方式登录：</span>
		 <a href="#" style='display:none'><img style='margin-right:10px' src="__STYLE__/img/btn_QQ.png"/></a>
		 <a href="#" style='display:none'><img src="__STYLE__/img/btn_wechat.png"/></a>
	</div>
	<div class="wst-clear"></div>
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
	  

    <script type="text/javascript" src="/static/js/rsa.js"></script>
	<script type="text/javascript" src="/static/plugins/validator/jquery.validator.min.js?v=<?php echo $v; ?>"></script>
	<script type='text/javascript' src='__STYLE__/js/login.js?v=<?php echo $v; ?>'></script>
	<script>
    $(document).keypress(function(e) { 
		if(e.which == 13) {  
			login(2);  
		}
	}); 
	</script>

</body>