<?php /*a:2:{s:41:"/home/mart/shangtao/admin/view/index.html";i:1536512711;s:40:"/home/mart/shangtao/admin/view/base.html";i:1534832972;}*/ ?>
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

<link rel="stylesheet" href="__ADMIN__/css/skins/skin-blue.min.css"type="text/css"/>
<link rel="stylesheet" href="__ADMIN__/css/index.css" type="text/css"/>

<link href="__ADMIN__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
<script>
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',app('request')->root(true)); ?>","ROOT":"","APP":"/index.php","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'<?php echo implode(",",session("WST_STAFF.privileges")); ?>',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","ROUTES":'<?php echo WSTRoute(); ?>',"MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>","__HTTP__":"<?php echo WSTProtocol(); ?>"}
</script>
<script language="javascript" type="text/javascript" src="/static/js/common.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="j-loader"><img src="__ADMIN__/img/ajax-loader.gif"/></div>

<style>body,.wrapper{overflow:hidden;}</style>
<div class="wrapper">
  <header class="main-header">
    <a href="#" class="logo">
      <span class="logo-mini">中商</span>
      <span class="logo-lg">中商优享</span>
    </a>
    <nav class="navbar navbar-static-top">
      <div class="navbar-custom-menu" style='float:left'>
        <ul class='nav navbar-nav'>
          <li><a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a></li>
          <?php if(is_array($sysMenus) || $sysMenus instanceof \think\Collection || $sysMenus instanceof \think\Paginator): $i = 0; $__LIST__ = $sysMenus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?>
          <li><a href='#' class='top-menu' dataid='<?php echo $top['menuId']; ?>'><i class="fa fa-<?php echo $top['menuIcon']; ?>"></i><span><?php echo $top['menuName']; ?></span></a></li>
          <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
      </div>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li id='toMall'><a target='_blank' href='<?php echo Url("home/index/index"); ?>'><i class='fa fa-television'></i></a></li>
          <li id='toSelft'><a target='_blank' href='<?php echo Url('admin/shops/inself'); ?>'><i class='fa fa-podcast'></i></a></li>
          <li id='toClearCache'><a class='j-clear-cache' href='#'><i class='fa fa-spinner'></i></a></li>
          <li id='toLogout'><a class='j-logout' href='#' title='退出系统'><i class='fa fa-power-off'></i></a></li>
        </ul>
      </div>
    </nav>
  </header>
  <aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <img src="__ADMIN__/img/login_head.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo app('session')->get('WST_STAFF.loginName'); ?></p>
          <p><?php echo app('session')->get('WST_STAFF.roleName'); ?></p>
        </div>brand_street_out
        <div class='pull-left button'>
           <a href='javascript:void(0);' class='j-edit-pass edit-pass'><i class='fa fa-key'></i><span>修改密码</span></a>
           <a href='javascript:void(0);' class='j-logout logout'><i class='fa fa-power-off'></i><span>退出系统</span></a>
        </div>
      </div>
      
      <ul class="sidebar-menu" data-widget="tree">
        <?php if(is_array($sysMenus) || $sysMenus instanceof \think\Collection || $sysMenus instanceof \think\Paginator): $key0 = 0; $__LIST__ = $sysMenus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$left0): $mod = ($key0 % 2 );++$key0;if(!empty($left0['child'])): if(is_array($left0['child']) || $left0['child'] instanceof \think\Collection || $left0['child'] instanceof \think\Paginator): $i = 0; $__LIST__ = $left0['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$left1): $mod = ($i % 2 );++$i;?>
        <li class="treeview j-menulevel0 j-sysmenu<?php echo $left0['menuId']; ?>" <?php if($key0>1): ?>style='display:none'<?php endif; ?>">
          <a href="#">
            <i class="fa fa-<?php echo !empty($left1['menuIcon']) ? $left1['menuIcon'] : 'eercast'; ?>"></i> <span><?php echo $left1['menuName']; ?></span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <?php if(!empty($left1['child'])): ?>
          <ul class="treeview-menu">
            <?php if(is_array($left1['child']) || $left1['child'] instanceof \think\Collection || $left1['child'] instanceof \think\Paginator): $i = 0; $__LIST__ = $left1['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$left2): $mod = ($i % 2 );++$i;?>
            <li><a class='menuItem' href="<?php echo Url($left2['privilegeUrl']); ?>" dataid='<?php echo $left2['menuId']; ?>'><i class="fa fa-<?php echo !empty($left2['menuIcon']) ? $left2['menuIcon'] : 'circle-o'; ?>"></i><?php echo $left2['menuName']; if(!empty($left2['child'])): ?><i class="fa fa-angle-left pull-right"></i><?php endif; ?></a>
              <?php if(!empty($left2['child'])): ?>
              <ul class="treeview-menu">
                <?php if(is_array($left2['child']) || $left2['child'] instanceof \think\Collection || $left2['child'] instanceof \think\Paginator): $i = 0; $__LIST__ = $left2['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$left3): $mod = ($i % 2 );++$i;?>
                <li>
                  <a class="menuItem" href="<?php echo $left3['privilegeUrl']; ?>" dataid='<?php echo $left2['menuId']; ?>'><i class="fa fa-<?php echo !empty($left3['menuIcon']) ? $left3['menuIcon'] : 'circle-o'; ?>"></i><?php echo $left3['menuName']; ?>
                  </a>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
              </ul>
             <?php endif; ?>
            </li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
          </ul>
          <?php endif; ?>
        </li>
        <?php endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; ?>
      </ul>
    </section>
  </aside>
  <div class="content-wrapper">
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href='<?php echo app('request')->root(true); ?>' target='_blank'><i class='fa fa-map-marker'></i>首页</a></li>
      </ol>
      <button id='toFullSreen' class="fullscreen" style="display: none"><i class="fa fa-arrows-alt"></i></button>
    </section>
    <section class="content-iframe" style="margin:0px;padding:0;height:100%">
      <iframe id='iframe' class="iframe" width="100%" height="100%" src="<?php echo Url('admin/index/main'); ?>" frameborder="0"></iframe>
    </section>
  </div>
</div>
<div id='editPassBox' style='display:none;padding-top:5px;'>
  <form id='editPassFrom' autocomplete="off">
   <table class='wst-form'>
      <tr>
         <th style='width:100px'>原密码：</th>
         <td><input type='password' id='srcPass' name='srcPass' class='ipt' data-rule="原密码: required;" maxLength='16'/></td>
      </tr>
      <tr>
         <th>新密码：</th>
         <td><input type='password' id='newPass' name='newPass' class='ipt' data-rule="新密码: required;length[6~]" maxLength='16'/></td>
      </tr>
      <tr>
         <th>确认密码：</th>
         <td><input type='password' id='newPass2' name='newPass2' class='ipt' data-rule="确认密码: required;match(newPass);" maxLength='16'/></td>
      </tr>
   </table>
  </form>
</div>

<script>
var menus = <?php echo json_encode($sysMenus); ?>;
function showImg(opt){
  layer.photos(opt);
}
function showBox(opts){
  return WST.open(opts);
}
$(function(){
   $('#toMall').poshytip({content:'点击打开商城首页',showTimeout:0,hideTimeout:1,
              offsetY: 25,allowTipHover: false,timeOnScreen:1000});
   $('#toSelft').poshytip({content:'点击打开自营店铺',showTimeout:0,hideTimeout:1,
              offsetY: 25,timeOnScreen:1000,allowTipHover: false});
   $('#toTechSupp').poshytip({content:'点击打开技术支持页面',showTimeout:0,hideTimeout:1,
              offsetY: 25,allowTipHover: false,timeOnScreen:1000});
   $('#toClearCache').poshytip({content:'点击清除服务器缓存',showTimeout:0,hideTimeout:1,
              offsetY: 25,allowTipHover: false,timeOnScreen:1000});
   $('#toLogout').poshytip({content:'点击退出系统',showTimeout:0,hideTimeout:1,
              offsetY: 25,allowTipHover: false,timeOnScreen:1000});
   $('#toFullSreen').poshytip({content:'点击全屏展示',showTimeout:0,hideTimeout:1,
              offsetY: 25,allowTipHover: false,timeOnScreen:1000});
})
</script>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/js/index.js"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>