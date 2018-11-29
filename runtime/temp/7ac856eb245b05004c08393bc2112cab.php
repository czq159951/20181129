<?php /*a:1:{s:57:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/login.html";i:1536627213;}*/ ?>
<!DOCTYpE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Ua-Compatible" content="IE=edge">
<meta name="Keywords" content=""/>
<meta name="Description" content=""/> 
<link rel="stylesheet" href="/static/plugins/layui/css/layui.css" type="text/css" />
<link href="__ADMIN__/css/login.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
<title>后台管理中心登录 - <?php echo WSTConf('CONF.mallName'); ?></title>
</head>
<body id="loginFrame">

<div class="wst-lo-center ">
  <div class="wst-lo">
    <div class="login-header"> 
        <div class="login_title">
          <div class='title_cn'>中商优享后台管理系统</div>
          <div class='title_en'>Background Management System</div>
        </div>
        <div class="wst-clear"></div>
    </div>
    <div class="login-wrapper">
      <div class="boxbg2"></div>
      <div class="box">
          <div class="content-wrap">
            <div class="login-box">
              <div class="login-head"><img src="__ADMIN__/img/login_head.png"></div>
              <div class="login-icon1"></div>
              <div class="login-icon2"></div>
              <div class="login-icon3"></div>
              <input id='loginName' type="text" class="layui-input ipt ">
              <input id='loginPwd' type="password" class="layui-input ipt">
              <div class="frame">
                <input type='text' id='verifyCode' class='layui-input  ipt text2'>
                <img id='verifyImg' src="<?php echo url('admin/index/getVerify'); ?>" onclick='javascript:getVerify(this)'>
              </div>
            </div>
            <button id="loginbtn" type="button" onclick='javascript:login()' class="layui-btn layui-btn-big layui-btn-normal" style="width: 100%;">登&nbsp;&nbsp;&nbsp;&nbsp;录</button>
          </div>
        </div>
    </div>
</div>

<input type='hidden' id='token' value='<?php echo WSTConf("CONF.pwdModulusKey"); ?>'/>
<script type='text/javascript' src='/static/js/jquery.min.js'></script>
<script>
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',app('request')->root(true)); ?>","ROOT":"","APP":"","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>"}
</script>
<script type='text/javascript' src='/static/js/common.js'></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script type="text/javascript" src="/static/js/rsa.js"></script>
<script type='text/javascript' src='__ADMIN__/js/common.js'></script>
<script src="__ADMIN__/js/login.js?v=<?php echo $v; ?>" type="text/javascript"></script>
</body>
</html>