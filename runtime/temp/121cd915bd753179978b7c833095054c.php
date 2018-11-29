<?php /*a:2:{s:65:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/addons/config.html";i:1536627199;s:56:"/www/beidou/mart/zsbd_mart/shangtao/admin/view/base.html";i:1536627213;}*/ ?>
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

	<div >
	<form action="<?php echo url('saveConfig'); ?>" method="post" style="line-height: 30px;margin:10px;" autocomplete="off">
			<div class="main-title cf">
				<div class="addoncfg-title">插件配置 [ <?php echo $data['title']; ?> ]</div>
			</div>
			<?php if(is_array($data['config']) || $data['config'] instanceof \think\Collection || $data['config'] instanceof \think\Paginator): if( count($data['config'])==0 ) : echo "" ;else: foreach($data['config'] as $o_key=>$form): ?>
				<div class="form-item cf">
				<?php if(isset($form['title'])): ?>
					<label class="item-label">
						<span style="font-weight: bold;"><?php echo (isset($form['title']) && ($form['title'] !== '')?$form['title']:''); ?></span>
						
					</label>
					<?php endif; switch($form['type']): case "tips": ?>
							<div>
								<?php echo $form['value']; ?>
							</div>
							<?php break; case "text": ?>
							<div>
								<input type="text" name="config[<?php echo $o_key; ?>]" class="text input-large" value="<?php echo $form['value']; ?>"  style="width:400px;"><?php if(isset($form['tips'])){ ?><span><?php echo $form['tips']; ?></span><?php } ?>
							</div>
							<?php break; case "password": ?>
							<div>
								<input type="password" name="config[<?php echo $o_key; ?>]" class="text input-large" value="<?php echo $form['value']; ?>">
							</div>
							<?php break; case "hidden": ?>
								<input type="hidden" name="config[<?php echo $o_key; ?>]" value="<?php echo $form['value']; ?>">
							<?php break; case "radio": ?>
							<div class="layui-form">
								<?php if(is_array($form['options']) || $form['options'] instanceof \think\Collection || $form['options'] instanceof \think\Paginator): if( count($form['options'])==0 ) : echo "" ;else: foreach($form['options'] as $opt_k=>$opt): ?>
									<label class="radio">
										<input type="radio" name="config[<?php echo $o_key; ?>]" value="<?php echo $opt_k; ?>" <?php if($form['value'] == $opt_k): ?> checked<?php endif; ?> title="<?php echo $opt; ?>">
									</label>
								<?php endforeach; endif; else: echo "" ;endif; ?>
							</div>
							<?php break; case "checkbox": ?>
							<div>
								<?php if(is_array($form['options']) || $form['options'] instanceof \think\Collection || $form['options'] instanceof \think\Paginator): if( count($form['options'])==0 ) : echo "" ;else: foreach($form['options'] as $opt_k=>$opt): ?>
									<label class="checkbox">
										<?php 
											is_null($form["value"]) && $form["value"] = array();
										 ?>
										<input type="checkbox" name="config[<?php echo $o_key; ?>][]" value="<?php echo $opt_k; ?>" <?php if(in_array(($opt_k), is_array($form['value'])?$form['value']:explode(',',$form['value']))): ?>checked<?php endif; ?>><?php echo $opt; ?>
									</label>
								<?php endforeach; endif; else: echo "" ;endif; ?>
							</div>
							<?php break; case "select": ?>
							<div>
								<select name="config[<?php echo $o_key; ?>]">
									<?php if(is_array($form['options']) || $form['options'] instanceof \think\Collection || $form['options'] instanceof \think\Paginator): if( count($form['options'])==0 ) : echo "" ;else: foreach($form['options'] as $opt_k=>$opt): ?>
										<option value="<?php echo $opt_k; ?>" <?php if($form['value'] == $opt_k): ?> selected<?php endif; ?>><?php echo $opt; ?></option>
									<?php endforeach; endif; else: echo "" ;endif; ?>
								</select>
							</div>
							<?php break; case "textarea": ?>
							<div>
								<label class="textarea input-large">
									<textarea name="config[<?php echo $o_key; ?>]" style="width:500px;height:200px;"><?php echo $form['value']; ?></textarea>
								</label>
							</div>
							<?php break; case "group": ?>
								<ul class="tab-nav nav">
									<?php if(is_array($form['options']) || $form['options'] instanceof \think\Collection || $form['options'] instanceof \think\Paginator): $i = 0; $__LIST__ = $form['options'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$li): $mod = ($i % 2 );++$i;?>
										<li data-tab="tab<?php echo $i; ?>" <?php if($i == '1'): ?>class="current" <?php endif; ?> ><a href="javascript:void(0);"><?php echo $li['title']; ?></a></li>
									<?php endforeach; endif; else: echo "" ;endif; ?>
									<div style="clear: both;"></div>
								</ul>
								<div class="tab-content">
								<?php if(is_array($form['options']) || $form['options'] instanceof \think\Collection || $form['options'] instanceof \think\Paginator): $i = 0; $__LIST__ = $form['options'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tab): $mod = ($i % 2 );++$i;?>
									<div id="tab<?php echo $i; ?>" class="tab-pane <?php if($i == '1'): ?>in<?php endif; ?> tab<?php echo $i; ?>">
										<?php if(is_array($tab['options']) || $tab['options'] instanceof \think\Collection || $tab['options'] instanceof \think\Paginator): if( count($tab['options'])==0 ) : echo "" ;else: foreach($tab['options'] as $o_tab_key=>$tab_form): ?>
										<label class="item-label">
											<span style="font-weight: bold;"><?php echo (isset($tab_form['title']) && ($tab_form['title'] !== '')?$tab_form['title']:''); ?></span>
											<?php if(isset($tab_form['tip'])): ?>
												<span class="check-tips"><?php echo $tab_form['tip']; ?></span>
											<?php endif; ?>
										</label>
										<div>
											<?php switch($tab_form['type']): case "tips": ?>
												<div>
													<?php echo $form['value']; ?>
												</div>
												<?php break; case "text": ?>
													<input type="text" name="config[<?php echo $o_tab_key; ?>]" class="text input-large" value="<?php echo $tab_form['value']; ?>" style="width:400px;">
												<?php break; case "password": ?>
													<input type="password" name="config[<?php echo $o_tab_key; ?>]" class="text input-large" value="<?php echo $tab_form['value']; ?>">
												<?php break; case "hidden": ?>
													<input type="hidden" name="config[<?php echo $o_tab_key; ?>]" value="<?php echo $tab_form['value']; ?>">
												<?php break; case "radio": if(is_array($tab_form['options']) || $tab_form['options'] instanceof \think\Collection || $tab_form['options'] instanceof \think\Paginator): if( count($tab_form['options'])==0 ) : echo "" ;else: foreach($tab_form['options'] as $opt_k=>$opt): ?>
														<label class="layui-form radio">
															<input type="radio" name="config[<?php echo $o_tab_key; ?>]" value="<?php echo $opt_k; ?>" <?php if($tab_form['value'] == $opt_k): ?> checked<?php endif; ?> title="<?php echo $opt; ?>">
														</label>
													<?php endforeach; endif; else: echo "" ;endif; break; case "checkbox": if(is_array($tab_form['options']) || $tab_form['options'] instanceof \think\Collection || $tab_form['options'] instanceof \think\Paginator): if( count($tab_form['options'])==0 ) : echo "" ;else: foreach($tab_form['options'] as $opt_k=>$opt): ?>
														<label class="checkbox">
															<?php  
															is_null($tab_form["value"]) && $tab_form["value"] = array();
															 ?>
															<input type="checkbox" name="config[<?php echo $o_tab_key; ?>][]" value="<?php echo $opt_k; ?>" <?php if(in_array(($opt_k), is_array($tab_form['value'])?$tab_form['value']:explode(',',$tab_form['value']))): ?> checked<?php endif; ?>><?php echo $opt; ?>
														</label>
													<?php endforeach; endif; else: echo "" ;endif; break; case "select": ?>
													<select name="config[<?php echo $o_tab_key; ?>]">
														<?php if(is_array($tab_form['options']) || $tab_form['options'] instanceof \think\Collection || $tab_form['options'] instanceof \think\Paginator): if( count($tab_form['options'])==0 ) : echo "" ;else: foreach($tab_form['options'] as $opt_k=>$opt): ?>
															<option value="<?php echo $opt_k; ?>" <?php if($tab_form['value'] == $opt_k): ?> selected<?php endif; ?>><?php echo $opt; ?></option>
														<?php endforeach; endif; else: echo "" ;endif; ?>
													</select>
												<?php break; case "textarea": ?>
													<label>
														<textarea name="config[<?php echo $o_tab_key; ?>]"><?php echo $tab_form['value']; ?></textarea>
													</label>
												<?php break; endswitch; ?>
											</div>
										<?php endforeach; endif; else: echo "" ;endif; ?>
									</div>
								<?php endforeach; endif; else: echo "" ;endif; ?>
								</div>
							<?php break; endswitch; ?>
					</div>
			<?php endforeach; endif; else: echo "" ;endif; ?>
		<div class="form-item cf wst-bottombar" style='padding-left:130px;padding-top:10px'>
		<input type="hidden" name="id" value="<?php echo $addonId; ?>" readonly/>
		<button type="submit" class="btn submit-btn ajax-post btn-primary btn-mright" ><i class="fa fa-check"></i>确 定</button>&nbsp;&nbsp;&nbsp;&nbsp;
		<button type="button"  class='btn' onclick="location.href='<?php echo url("admin/addons/index"); ?>'"><i class="fa fa-angle-double-left"></i>返回</button>
		</div>
	</form>
	</div>

<script src="__ADMIN__/js/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script type="text/javascript" charset="utf-8">

$(function(){
	 $(".tab-nav li").click(function(){
	        var self = $(this), target = self.data("tab");
	        self.addClass("current").siblings(".current").removeClass("current");
	        //window.location.hash = "#" + target.substr(3);
	        $(".tab-pane.in").removeClass("in");
	        $("." + target).addClass("in");
	}).filter("[data-tab=tab" + window.location.hash.substr(1) + "]").click();
})
</script>

<?php echo hook('initCronHook'); ?>
</body>
</html>