<?php 
header("Content-type:text/html;charset=utf-8" );
error_reporting(E_ERROR | E_WARNING | E_PARSE);
@set_time_limit(1000);
if (version_compare(PHP_VERSION, '5.6', '<=')) {
    die("系统需运行在PHP5.6及以上版本!!!");
}
define('IN_SHANGTAO', TRUE);
define('INSTALL_ROOT', dirname(dirname(__FILE__)));
define('INSTALL_PATH', dirname(__FILE__));
require INSTALL_PATH.'/include/install_var.php';
require INSTALL_PATH.'/include/install_function.php';
$step = (int)$_GET['step'];
if($step<3){
	if(file_exists(INSTALL_PATH.'/install.ok'))header("Location:../index.php");;
}
timezone_set();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>商淘电商安装</title>
<link href="./css/general.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/install.js"></script>
<script type="text/javascript">
</script>
</head>
<body>
<div style="width:960px;margin:0 auto;">
    <div style="margin:0 auto;margin-top:20px;background:url('./images/logo.png') no-repeat left top;width:250px;height:85px;"></div>
    <form id='form1'action='index.php'>
    <input type='hidden' name="step" id='step' value='0'/>
    <input type='hidden' name="rnd" id='rnd' value='0'/>
    <?php if($step==0){?>
    
    <div id="system_agreement" class="main">
        <div class='content'>
            <p class='bold center'>安装协议</p>
			
			<p style='margin-top:10px;'>用户须知：</p>
			<p style='margin-top:10px;text-indent: 2em'>无</p>
			
        </div>
        <div class='bottom'>
        <input type='button' class='btn' value='我同意' onclick='showStep(1)'/>
        </div>
    </div>
    <?php 
    }else if($step==1){
    	$env_items = env_check($env_items);
        $dir_items = dir_check($dir_items);
        $func_items = check_func($func_items);
    ?>
    <div id="system_env" class="main">
        <div class="content">
            <span class='bold' style='font-size:15px;'>系统环境检查</span>
            <table class="check-env" style='margin-bottom:20px;'>
                <?php 
                    echo '<tr><td class="left">操作系统</td><td><span class="check'.$env_items['os']['status'].'"></span>'.$env_items['os']['current'].'</td></tr>';
                    echo '<tr><td class="left">PHP 版本</td><td><span class="check'.$env_items['php']['status'].'"></span>'.$env_items['php']['current'].'</td></tr>';
                    echo '<tr><td class="left">附件上传</td><td><span class="check'.$env_items['attachmentupload']['status'].'"></span>'.$env_items['attachmentupload']['current'].'</td></tr>';
                    echo '<tr><td class="left">GD 库</td><td><span class="check'.$env_items['gdversion']['status'].'"></span>'.$env_items['gdversion']['current'].'</td></tr>';
                    echo '<tr><td class="left">磁盘空间 </td><td><span class="check'.$env_items['diskspace']['status'].'"></span>'.$env_items['diskspace']['current'].'</td></tr>';
                ?>
            </table>
            <span class='bold' style='font-size:15px;'>目录权限检查</span>
            <table class="check-env" style='margin-bottom:20px;'>
                <?php 
                    $str = '';
                    if(count($dir_items)>0){
                        $check = true;
                        foreach($dir_items as $v){
                            $str .= '<tr><td class="left">'.$v['path'].'</td><td>';
                            if($v['status'] == 1) {
                                $str .= '<span class="check1"></span>可写';
                            }else if($v['status'] == -1) {
                                $str .= '<span class="check-1"></span>目录不可写，请检查后再试';
                                $check = false;
                            }else {
                                $str .= '<span class="check-1"></span>不可写，请检查后再试';
                                $check = false;
                            }
                            $str .= '</td></tr>';
                        }
                    }
                    echo $str;
                ?>
            </table>
            <span style="display:none;color:red;margin-top:30px;text-align:center;" id="envInfo">目录不存在或不可写，请检查后再试</span>
            <span class='bold' style='font-size:15px;'>依赖函数检查</span>
            <table class="check-env">
                <?php 
                    echo '<tr><td class="left">scandir()</td><td><span class="check'.$func_items['scandir']['status'].'"></span>'.$func_items['scandir']['current'].'</td></tr>';
                    echo '<tr><td class="left">mysqli_connect()</td><td><span class="check'.$func_items['mysqli_connect']['status'].'"></span>'.$func_items['mysqli_connect']['current'].'</td></tr>';
                    echo '<tr><td class="left">file_get_contents()</td><td><span class="check'.$func_items['file_get_contents']['status'].'"></span>'.$func_items['file_get_contents']['current'].'</td></tr>';
                    echo '<tr><td class="left">curl_init()</td><td><span class="check'.$func_items['curl_init']['status'].'"></span>'.$func_items['curl_init']['current'].'</td></tr>';
                    echo '<tr><td class="left">mb_strlen()</td><td><span class="check'.$func_items['mb_strlen']['status'].'"></span>'.$func_items['mb_strlen']['current'].'</td></tr>';
                    echo '<tr><td class="left">php_fileinfo</td><td><span class="check'.$func_items['finfo_open']['status'].'"></span>'.$func_items['finfo_open']['current'].'</td></tr>';
                    echo '<tr><td class="left">bcmath</td><td><span class="check'.$func_items['bcmath']['status'].'"></span>'.$func_items['bcmath']['current'].'</td></tr>';
                ?>
            </table>
        </div>
        <div class='bottom'>
        <input type='button' class='btn' value='重新检测' onclick='javascript:showStep(1,1)'/>
        <input type='button' class='btn nextBtn' value='下一步' onclick='showStep(2)'/>
        </div>
    </div>
    <?php }else if($step==2){?>
    <div id="system_data" class='main'>
       <div class='content'>
          <div id='data_config'> 
             <span class='bold' style='font-size:15px;'>数据库帐号</span>
             <table class='check-env'>
                <tbody>
                <tr>
                  <td width="130" align="right" class="item">数据库主机<span class='red'>*</span>：</td>
                  <td align="left">
                      <input type="text" class="ipt" name="db_host" id="db_host" value="127.0.0.1" onblur='checkVal(this.id)'>
                      <span class='db_host tips'>数据库主机不能为空</span>
                  </td>
                </tr>
                <tr>
                  <td width="130" align="right" class="item">数据库端口<span class='red'>*</span>：</td>
                  <td align="left">
                      <input type="text" class="ipt" name="db_port" id="db_port" value="3306" onblur='checkVal(this.id)'>
                      <span class='db_port tips'>数据库端口不能为空</span>
                  </td>
                </tr>
                <tr>
                  <td align="right">访问账号<span class='red'>*</span>：</td>
                  <td align="left">
                      <input type="text" class="ipt" name="db_user" id="db_user" value="root" onblur='checkVal(this.id)'>
                      <span class='db_user tips'>数据库访问账号不能为空</span>
                  </td>
                </tr>
                <tr>
                  <td align="right">访问密码：</td>
                  <td align="left">
                      <input type="password" class="ipt" name="db_pass" id="db_pass" value="">
                  </td>
                </tr>
                <tr>
                   <td align="right">数据库名<span class='red'>*</span>：</td>
                   <td align="left">
                      <input type="text" class="ipt" name="db_name" id="db_name" value="zsmall" onblur='checkVal(this.id)'>
                      <span class='db_name tips'>数据库名不能为空</span>
                      <span class="tips" style='display:inline-block;color: red;'>&nbsp; (若数据库存在则会覆盖原数据库，不存在则会创建一个新数据库)</span>
                   </td>
                </tr>
                <tr>
                   <td align="right">表前缀：</td>
                   <td align="left">
                      <input type="text" class="ipt" name="db_prefix" id="db_prefix" value="wst_" onblur='checkVal(this.id)'>
                      <span class="tips" style='display:inline-block;color: red;'>&nbsp; (建议修改表前缀)</span>
                   </td>
                </tr>
                <tr>
                   <td align="right">&nbsp;</td>
                   <td align="left">
                      <label>
                      <input type="checkbox" name="db_demo" id="db_demo" checked><span class="tips" style='display:inline;color: #121212;'>&nbsp;安装演示数据</span>
                      <span class="tips">&nbsp; </span>
                      </label>
                   </td>
                </tr>
           </tbody>
          </table>
          <span class='bold' style='font-size:15px;'>管理员帐号</span>
          <table class='check-env'>
          <tbody>
              <tr>
                <td width="130" align="right">管理员账号<span class='red'>*</span>：</td>
                <td align="left">
                   <input type="text" class="ipt" name="admin_name" id="admin_name" value="admin" onblur='checkVal(this.id)'>
                   <span class='admin_name tips'>管理员账号不能为空</span>
                </td>
              </tr>
              <tr>
                 <td align="right">登录密码<span class='red'>*</span>：</td>
                 <td align="left">
                   <input type="password" class="ipt" name="admin_password" id="admin_password" value="" onblur='checkVal(this.id)'>
                   <span class='admin_password tips'>管理员密码不能为空</span>
                 </td>
               </tr>
               <tr>
                  <td align="right">密码确认<span class='red'>*</span>：</td>
                  <td align="left">
                    <input type="password" class="ipt" name="admin_password2" id="admin_password2" value="" onblur='checkVal(this.id)'>
                    <span class="admin_password2 tips">两次输入的密码不一致</span>
                  </td>
               </tr>
            </tbody>
           </table>
           </div>
           <div id='data_init' style='display:none'></div>
        </div>
        
        <div class='bottom'>
           <span id='init_msg' style='display:none'><img width='16' src='images/loading-2.gif'>正在初始化数据库...</span>
           <input type='button' class='btn' value='上一步' onclick='showStep(1)'/>
		   <input type='button' class='btn nextBtn' value='下一步' onclick='showStep(3)'/>
	    </div>
    </div>
    <?php }else if($step==3){?>
    <div class="main" id="system_success">
        <div class="content" style='text-align:center;'>
        <div style="margin-top: 120px;">
        <div style="margin:0 auto;background:url('./images/icon_success.png') no-repeat left top;width:62px;height:62px;"></div>
        <span style="display:inline-block;margin-top:10px;">
           <span style="font-size:18px;color: red;"> 恭喜，系统已安装成功&nbsp;!</span><br /><br />
            安装成功后，建议删除install目录
        </span><br /><br />
        <div style="margin:0 auto;width:350px;">
        <a class="btn2" href="../index.php" target="_blank" title="跳到商城首页"><span>跳到商城首页 </span></a>&nbsp;&nbsp;
        <a class="btn2" href="../index.php/Admin/index" target="_blank" title="跳到管理员后台"><span>跳到管理员后台 </span></a>
        </div>
        </div>
        </div>
    </div>
    <?php }?>
    </form>
    </div>
</body>
</html>