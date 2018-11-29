$(document).ready(function() {
    var decoration = {};
    
    //当前block
    decoration.current_block_id = null;
    //当前窗口
    decoration.current_dialog = null;
    
    decoration.current_layerId = 0;
    
    //当前编辑按钮
    decoration.current_block_edit_button = null;
    //编辑器
    decoration.editor = null;
    //幻灯图片数限制
    decoration.slide_image_limit = 5;
    //导航菜单默认样式
    decoration.default_nav_style = '.wst-shop-nav { background-color: #fc6047; }';
    decoration.default_nav_style += '.wst-nav-box{width: 1200px; margin: 0 auto; line-height: 36px; color: #ffffff;}';
    decoration.default_nav_style += '.wst-nav-box li:hover, .wst-nav-box .wst-nav-boxa {  background: #cb2004;  background-size: cover;}';
    decoration.default_nav_style += '.wst-nav-box li:hover, .wst-nav-box .wst-nav-boxa {  padding: 0px 26px; text-align: center;  color: #ffffff; font-size: 15px; font-family: "microsoft yahei";}';
    //图片热点图片对象
    decoration.$hot_area_image = null;
    //图片热点序号
    decoration.hot_area_index = 1;

    //封装post提交
    decoration.ajax_post = function(url, post, done, always) {
        $.ajax({
            type: "POST",
            url: url,
            data: post, 
            dataType: "json"
        })
        .done(function(data) {
            if(data.status==1) {
                done(data);
            } else {
                WST.msg(data.msg, {icon: 5});
            }
        })
        .fail(function() {
        	WST.msg("'操作失败'", {icon: 5});
        })
        .always(always);
    }

    //显示模块
    decoration.show_dialog_module = function(module_type, content, full_width) {
        if(typeof full_width == 'undefined') {
            full_width = false;
        }
        var $dialog = $('#dialog_module_' + module_type);
        if($dialog.length > 0) {
            decoration.current_dialog = $dialog;
            layer.close(decoration.current_layerId);
            var function_name = 'show_dialog_module_' + module_type;
            decoration[function_name]($dialog, content, full_width);
        } else {
            WST.msg("模块不存在", {icon: 5});
        }
    }

    //显示自定义模块窗口
    decoration.show_dialog_module_html = function($dialog, content) {
    	if(decoration.editor){
    		decoration.editor.remove('textarea[name="goodsDesc"]');
    	}
    	var winWidth = ($(window).width() - 50);
    	var winHeight = ($(window).height() - 50);
    	$("#module_html_editor").width(winWidth-36).height(winHeight-200);
    	decoration.current_layerId = layer.open({
    		  type: 1,
    		  title:'自定义模块',
    		  area: [winWidth +'px', winHeight +'px'], //宽高
    		  content: $("#dialog_module_html"),
    		  btnAlign:"c",
    		  btn: ['保存设置', '取消'],
    		  yes: function(index){
    			  decoration.editor.sync();
    			  var html = $('#module_html_editor').val();
    			  decoration.save_decoration_block(html, 'html');
    			  //layer.close(index);
    		  }
    	});
    	
	    decoration.editor = KindEditor.create('#module_html_editor', {
	        		 
	    	uploadJson : WST.U('/home/goods/editorUpload'),
	    	allowFileManager : false,
	      	allowImageUpload : true,
	     	items:[
	       			          'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
	       			          'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
	       			          'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
	       			          'superscript', 'clearhtml', 'quickformat', 'selectall', '/',
	       			          'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
	       			          'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|','image','multiimage','table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
	       			          'anchor', 'link', 'unlink', '|', 'about'
	     	],
	      	afterBlur: function(){ this.sync(); }
	  	});
    	
        decoration.editor.html(content);
    };

    //显示幻灯模块窗口
    decoration.show_dialog_module_slide = function($dialog, content, full_width) {
        decoration.current_layerId = layer.open({
	  		  type: 1,
	  		  title:'图片和幻灯',
	  		  area: [($(window).width() - 50) +'px', ($(window).height() - 50) +'px'], //宽高
	  		  content: $("#dialog_module_slide"),
    		  btnAlign:"c",
    		  btn: ['保存设置', '取消'],
    		  yes: function(index){
    			  var data = {};
    			  var i = 0;
    			  data.height = parseInt($('#txt_slide_height').val(), 10);
    			  //验证高度
    			  if(isNaN(data.height)) {
    				  WST.msg('请输入正确的显示高度', {icon: 5});
    				  return;
    			  }
    			  data.images = [];
    			  $('#module_slide_html li').each(function() { 
    				  var image = {};
    				  image.image_name = $(this).attr('data-image-name');
    				  image.image_link = $(this).attr('data-image-link');
    				  data.images[i] = image;
    				  i++;
    			  });
    			  if(data.images.length){
    				  decoration.save_decoration_block(data, 'slide', $('#txt_slide_full_width').attr('checked'));
        			  layer.close(index);
    			  }else{
    				  WST.msg('请添加图片', {icon: 5});
    				  return;
    			  }
    		  }
	  	});
        
        if($("#btn_module_slide_upload input").length==0){
        	WST.upload({
	       	  	  pick:'#btn_module_slide_upload',
	       	  	  formData: {dir:'goods',isWatermark:1,isThumb:1},
	       	  	  accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
	       	  	  callback:function(f){
	       	  		  var json = WST.toJson(f);
	       	  		  if(json.status==1){
	      	 	          $('#div_module_slide_image').html('<img src="' +  WST.conf.ROOT+"/"+json.savePath+json.name + '" data-image-name="' +  WST.conf.ROOT+"/"+json.savePath+json.name + '">');
	       	  		  }
	       		  },
	       		  progress:function(rate){
	       			  $('#div_module_slide_image').html('<img class="loading" src="' + LOADING_IMAGE + '">');
	       		  }
	       	});
    	}
        
        var html = '';
        $(content).find('li').each(function() { 
            var data = {};
            data.image_url = $(this).attr('data-image-url');
            data.image_name = $(this).attr('data-image-name');
            data.image_link = $(this).attr('data-image-link');
            html += template.render('template_module_slide_image_list', data);
        });
        var slideHeight = $(content).find(".s-wst-slide-numbox").attr("data-slide-height");
        $('#txt_slide_height').val(slideHeight);
        $('#txt_slide_full_width').attr('checked', full_width);
        $('#module_slide_html ul').html(html);
        $('#btn_add_slide_image').show();
    }

    //显示图片热点模块窗口
    decoration.show_dialog_module_hot_area = function($dialog, content) {
        decoration.hot_area_index = 1;

        //图片
        $('#div_module_hot_area_image').html($(content).find('img'));
        decoration.$hot_area_image = $('#div_module_hot_area_image').find('img');
        decoration.$hot_area_image.imgAreaSelect({ 
            handles: true,
            zIndex: 1200000000,
            fadeSpeed: 200 
        });

        $('#module_hot_area_url').val('');

        var html = '';
        $('#module_hot_area_select_list').html('');
        $(content).find('area').each(function() { 
            var position = $(this).attr('coords');
            var link = $(this).attr('href');
            decoration.add_hot_area(position, link);
        });

        decoration.current_layerId = layer.open({
	  		  type: 1,
	  		  title:'图片热点模块',
	  		  area: [($(window).width() - 50) +'px', ($(window).height() - 50) +'px'], //宽高
	  		  content: $("#dialog_module_hot_area"),
    		  btnAlign:"c",
    		  btn: ['保存设置', '取消'],
    		  yes: function(index){
    			  var data = {};
    			  var i = 0;
    			  data.image = decoration.$hot_area_image.attr('data-image-name');
    			  if(data.image == '') {
    				  WST.msg('请首先上传图片并添加热点', {icon: 5});
    				  return;
    			  }
    			  data.areas = [];
    			  $('#module_hot_area_select_list li').each(function() { 
    		       		var area = {};
    		            var position = $(this).attr('data-hot-area-position').split(',');
    		            area.x1 = position[0];
    		            area.y1 = position[1];
    		            area.x2 = position[2];
    		            area.y2 = position[3];
    		            area.link= $(this).attr('data-hot-area-link');
    		            data.areas[i] = area;
    		            i++;
    			  });
    			  decoration.hot_area_cancel_selection();
    			  decoration.save_decoration_block(data, 'hot_area');
    			  layer.close(index);
    		  }
	  		  
	  	});
        
        if($("#btn_module_hot_area_upload input").length==0){
        	WST.upload({
	       	  	  pick:'#btn_module_hot_area_upload',
	       	  	  formData: {dir:'goods',isWatermark:1,isThumb:1},
	       	  	  accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
	       	  	  callback:function(f){
	       	  		  var json = WST.toJson(f);
	       	  		  if(json.status==1){
	      	 	          $('#div_module_hot_area_image').html('<img src="' + WST.conf.ROOT+"/"+json.savePath+json.name + '" data-image-name="' + WST.conf.ROOT+"/"+json.savePath+json.name + '">');
	      	              decoration.$hot_area_image = $('#div_module_hot_area_image').find('img');
	      	
	      	              decoration.$hot_area_image.imgAreaSelect({ 
	      	                  handles: true,
	      	                  zIndex: 1200000000,
	      	                  fadeSpeed: 200 
	      	              });
	       	  		  }
	       		  },
	       		  progress:function(rate){
	       			$('#div_module_hot_area_image').html('<img class="loading" src="' + LOADING_IMAGE + '">');
	       		  }
	       	});
    	}
    }

    //显示店铺商品模块窗口
    decoration.show_dialog_module_goods = function($dialog, content) {
        var html = '';
        $(content).find('[nctype="goods_item"]').each(function() {
        	$(this).find('.wst-shop-recta').remove();
            $(this).append('<a class="wst-btn-mini" nctype="btn_module_goods_operate" href="javascript:;"><i class="icon-ban-circle"></i>取消选择</a>');
            html += $('<div />').append($(this)).html();
            
        });
        $('#div_module_goods_list').html(html);
     
        decoration.current_layerId = layer.open({
	  		  type: 1,
	  		  title:'店铺商品模块',
	  		  area: [($(window).width() - 50) +'px', ($(window).height() - 50) +'px'], //宽高
	  		  content: $("#dialog_module_goods"),
    		  btnAlign:"c",
    		  btn: ['保存设置', '取消'],
    		  yes: function(index){
    			  var data = [];
    			  var i = 0;
    			  $('#div_module_goods_list').find('[nctype="goods_item"]').each(function() { 
    		            var goods = {};
    		            goods.goodsId = $(this).attr('data-goods-id');
    		            goods.goodsName = $(this).attr('data-goods-name');
    		            goods.shopPrice = $(this).attr('data-goods-price');
    		            goods.goodsImg = $(this).attr('data-goods-image');
    		            data[i] = goods;
    		            i++;
    			  });
    			  decoration.save_decoration_block(data, 'goods');
    			  layer.close(index);
    		  }
	  	});
    };

    //块排序
    decoration.sort_decoration_block = function() {
        var sort_string = '';
        $block_list = $('#shop_decoration_area').children();
        $block_list.each(function(index, block) {
            sort_string += $(block).attr('data-block-id') + ',';
        });
        $.post(WST.AU("decoration://decoration/blocksort"), {sort_string: sort_string}, function(data) {
            if(typeof data.status==-1) {
                WST.msg('模块排序失败', {icon: 5});
            }
        }, 'json');
    };

    //保存块内容
    decoration.save_decoration_block = function(html, module_type, full_width) {
        //是否100%宽度设置
        if(typeof full_width == 'undefined') {
            full_width = 0;
        } else {
            full_width = 1;
        }

        var post = { 
            block_id: decoration.current_block_id,
            module_type: module_type,
            full_width: full_width,
            content: html
        };

        decoration.ajax_post(
            WST.AU("decoration://decoration/blocksave"),
            post,
            function(data) {
            	WST.msg(data.msg, {icon: (data.status?1:-5)});
                decoration.current_block_edit_button.attr('data-module-type', module_type);
                var $block = $('#block_' + decoration.current_block_id);
                if(full_width) {
                    $block.addClass('store-decoration-block-full-width');
                } else {
                    $block.removeClass('store-decoration-block-full-width');
                }
                if(module_type == 'html') {
                    data.data.html = data.data.html.replace(/\\"/g, '"');
                }
                $block.find('[nctype="shop_decoration_block_module"]').html(data.data.html);
              
                layer.close(decoration.current_layerId);
            }
        );
    };

    decoration.apply_nav_style = function(nav_style) {
    	
    	$("#style_nav").remove();
        if(nav_style == '') {
            nav_style = decoration.default_nav_style;
            $('#decoration_nav_style').val(decoration.default_nav_style);
        }
        $('head').append('<style id="style_nav">' + nav_style + '</style>');
    };

    decoration.apply_banner = function(banner_display, banner_image_url) {
        var $decoration_banner = $('#decoration_banner');
        if(banner_display == 'true' && banner_image_url != '') {
            $decoration_banner.show();
        } else {
            $decoration_banner.hide();
        }
        $decoration_banner.html('<img src="' + banner_image_url + '" alt="">');
    };

    //添加热点块
    decoration.add_hot_area = function(position, link) {
        var data = {};
        data.link = link;
        data.position = position; 
        data.index = decoration.hot_area_index;
        var html = template.render('template_module_hot_area_list', data);
        $('#module_hot_area_select_list').append(html);

        var position_array = position.split(',');
        var display = {};
        display.width = position_array[2] - position_array[0];
        display.height = position_array[3] - position_array[1];
        display.left = position_array[0];
        display.top = position_array[1];
        display.index = decoration.hot_area_index;
        var display_html = template.render('template_module_hot_area_display', display);
        $('#div_module_hot_area_image').append(display_html);

        decoration.hot_area_index = decoration.hot_area_index + 1;
    };

    //取消热点块选区
    decoration.hot_area_cancel_selection = function() {
        var ias = decoration.$hot_area_image.imgAreaSelect({ instance: true });
        if(typeof ias != 'undefined') {
            ias.cancelSelection();
        }
    };

    //初始化banner
    decoration.apply_banner(
        $("input[name='decoration_banner_display']:checked").val(),
        $('#img_banner_image').attr('src')
    );

    //初始化导航样式
    //decoration.apply_nav_style( $('#decoration_nav_style').val() );
    
    //编辑背景
    $('#btn_edit_background').on('click', function() {
    	 decoration.current_layerId = layer.open({
	  		  type: 1,
	  		  title:'编辑背景',
	  		  area: ['860px',  '500px'], //宽高
	  		  content: $("#dialog_edit_background"),
    		  btnAlign:"c",
    		  btn: ['保存设置', '取消'],
    		  yes: function(index){
    			  var post = { 
    					  id: DECORATION_ID,
    					  background_color: $('#txt_background_color').val(),
    					  background_image_url: $('#txt_background_image').val(),
    					  background_image_repeat: $("input[name='background_repeat']:checked").val(),
    					  background_position_x: $('#txt_background_position_x').val(),
    					  background_position_y: $('#txt_background_position_y').val(),
    					  background_attachment: $('#txt_background_attachment').val()
    			  };
    			  decoration.ajax_post(
    					  WST.AU("decoration://decoration/bgsettingsave"),
    					  post,
    					  function(data) {
    						  WST.msg(data.msg, {icon: (data.status?1:-5)});
    						  $('#shop_decoration_content').attr('style', data.data.decoration_background_style);
    					  }
    			  );
    			  layer.close(index);
    		  }
	  	});
    	if($("#file_background_image input").length==0){
	    	 WST.upload({
		   	  	  pick:'#file_background_image',
		   	  	  formData: {dir:'goods'},
		   	  	  accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
		   	  	  callback:function(f){
		   	  		  var json = WST.toJson(f);
		   	  		  if(json.status==1){
		   	              $('#img_background_image').attr('src', WST.conf.ROOT+"/"+json.savePath+json.name);
		   	              $('#txt_background_image').val(WST.conf.ROOT+"/"+json.savePath+json.name);
		   	          	  $('#div_background_image').show();
		   	  		  }
		   		  },
		   		  progress:function(rate){
		   			  $('#img_background_image').attr('src', LOADING_IMAGE);
		   		  }
		   	});
    	}
    });


    //删除背景图
    $('#btn_del_background_image').on('click', function() {
        $('#img_background_image').attr('src', '');
        $('#txt_background_image').val('');
        $('#div_background_image').hide();
    });

    //编辑头部
    $('#btn_edit_head').on('click', function() {
    	
        if($('#decoration_nav_style').val() == '') {
        	$("#style_nav").remove();
            nav_style = decoration.default_nav_style;
            $('#decoration_nav_style').val(decoration.default_nav_style);
            $('head').append('<style id="style_nav">' + nav_style + '</style>');
        }
       
    	decoration.current_layerId = layer.open({
	  		  type: 1,
	  		  title:'编辑头部',
	  		  area: ['680px', '500px'], //宽高
	  		  content: $("#dialog_edit_head"),
    		  btnAlign:"c",
    		  btn: ['保存设置', '取消'],
    		  yes: function(index){
    			  var nav_style = $('#decoration_nav_style').val();
    			  var post = {
    					id: DECORATION_ID,
    		    		content: nav_style
    			  };
    			  decoration.ajax_post(
    		            WST.AU("decoration://decoration/navsave"),
    		            post,
    		            function(data) {
    		            	WST.msg(data.msg, {icon: (data.status?1:-5)});
    		                decoration.apply_nav_style(nav_style);
    		                $('#dialog_edit_head').hide();
    		            }
    			  );
    			  layer.close(index);
    		  }
	  	});
        
    });

    //编辑头部弹出窗口tabs
    $('#dialog_edit_head_tabs').tabs();

    //恢复默认导航样式
    $('#btn_default_nav_style').on('click', function() {
        $('#decoration_nav_style').val(decoration.default_nav_style);
    });


    //删除banner图
    $('#btn_del_banner_image').on('click', function() {
        $('#txt_banner_image').val('');
        $('#div_banner_image').hide();
    });

    //保存装修banner设置
    $('#btn_save_decoration_banner').on('click', function() {
        var banner_display = $("input[name='decoration_banner_display']:checked").val();
        var banner_image = $('#txt_banner_image').val();

        var post = {
            id: DECORATION_ID,
            banner_display: banner_display,
            content: banner_image
        };
       
        decoration.ajax_post(
            WST.AU('decoration://decoration/bannersave'),
            post,
            function(data) {
            	WST.msg(data.msg, {icon: (data.status?1:-5)});
                decoration.apply_banner(banner_display, data.image_url);
                $('#dialog_edit_head').hide();
            }
        );
    });

    //添加块
    $('#btn_add_block').on('click', function() {
        var post = { 
            id: DECORATION_ID,
            block_layout: 'block_1'
        };

        decoration.ajax_post(
            WST.AU("decoration://decoration/blockadd"),
            post,
            function(data) {
            	WST.msg(data.msg, {icon: (data.status?1:-5)});
                $('#shop_decoration_area').append(data.data.html);
                //滚动到底部
                $("html, body").animate({ scrollTop: $(document).height()-($(window).height()+300) }, 1000);
                //块排序
                decoration.sort_decoration_block();
            }
        );
    });

    //删除块
    $('#shop_decoration_area').on('click', '[nctype="btn_del_block"]', function() {
        $this = $(this);
        
        layer.confirm('您确认删除该模块吗？', {
        	  btn: ['确定','取消'] //按钮
        }, function(index){
        	var post = {
        			block_id: $this.attr('data-block-id')
        	};
        	decoration.ajax_post(
        			WST.AU("decoration://decoration/blockdel"),
        			post,
        			function(data) {
        				WST.msg(data.msg, {icon: (data.status?1:-5)});
        				$this.parents('[nctype="shop_decoration_block"]').hide();
        			}
        	);
        	layer.close(index);
     	});
    });

    //装修块拖拽排序
    $( "#shop_decoration_area" ).sortable({
        update: function(event, ui) {
            decoration.sort_decoration_block();
        }
    });

    //添加模块
    $('#shop_decoration_area').on('click', '[nctype="btn_edit_module"]', function() {
        var module_type = $(this).attr('data-module-type');
        decoration.current_block_id = $(this).attr('data-block-id');
        decoration.current_block_edit_button = $(this);
        if(module_type == '') {
            //新模块弹出模块选择窗口
            decoration.current_layerId = layer.open({
	      		  type: 1,
	      		  title:'选择模块',
	      		  area: ['610px', '320px'], //宽高
	      		  content: $("#dialog_select_module")
	      	});
        } else {
            //已有模块直接编辑
            var $block = $('#block_' + decoration.current_block_id);
            var content = $block.find('[nctype="shop_decoration_block_module"]').html();
            var full_width = $block.hasClass('store-decoration-block-full-width');
            decoration.show_dialog_module(module_type, content, full_width);
        }
    });

    //模块选择窗口选择模块类型后打开对应的模块编辑窗口
    $('[nctype="btn_show_module_dialog"]').on('click', function() {
        var module_type = $(this).attr('data-module-type');
        decoration.show_dialog_module(module_type);
    });


    //保存添加的幻灯图片
    $('#btn_save_add_slide_image').on('click', function() {
    	var image_count = $('#module_slide_html ul').children().length;
        if(image_count >= decoration.slide_image_limit) {
            WST.msg('每个幻灯片最多只能上传' + decoration.slide_image_limit + '张图片');
            return;
        }
        var data = {};
        $image = $('#div_module_slide_image img');
        if($image.length > 0) {
            data.image_url = $image.attr('src');
            data.image_name = $image.attr('data-image-name');
            data.image_link = $('#module_slide_url').val();

            var html = template.render('template_module_slide_image_list', data);
            $('#module_slide_html ul').append(html);
            $('#btn_add_slide_image').show();
            
            $('#div_module_slide_image').html('');
            $('#module_slide_url').val('');
        } else {
            WST.msg('请上传图片', {icon: 5});
        }
    });

    //幻灯片模块图片删除
    $('#module_slide_html').on('click', '[nctype="btn_del_slide_image"]', function() {
        $(this).parents('li').remove();
    });

    //取消添加幻灯图片
    $('#btn_cancel_add_slide_image').on('click', function() {
        $('#div_module_slide_upload').hide();
        $('#btn_add_slide_image').show();
    });

    //添加热点区域
    $('#btn_module_hot_area_add').on('click', function() {
        var ias = decoration.$hot_area_image.imgAreaSelect({ instance: true });
        var selection = ias.getSelection();
        if (!selection.width || !selection.height) {
            WST.msg('请选择热点区域', {icon: 5});
            return;
        }

        //添加热点块
        var position = selection.x1 + ',' + selection.y1 + ',' + selection.x2 + ',' + selection.y2; 
        var link = $('#module_hot_area_url').val();
        decoration.add_hot_area(position, link);

        decoration.hot_area_cancel_selection();
    });

    //选择图片热点块
    $('#dialog_module_hot_area').on('click', '[nctype="btn_module_hot_area_select"]', function() {
        var position = $(this).attr('data-hot-area-position').split(',');
        var ias = decoration.$hot_area_image.imgAreaSelect({ instance: true });
        ias.setSelection(position[0], position[1], position[2], position[3], true);
        ias.setOptions({ show: true });
        ias.update();
    });

    //删除图片热点块
    $('#dialog_module_hot_area').on('click', '[nctype="btn_module_hot_area_del"]', function() {
        var display_id = $(this).attr('data-index');
        $('#hot_area_display_' + display_id).remove();
        $(this).parents('li').remove();
    });


    //商品模块搜索
    $('#btn_module_goods_search').on('click', function() {
    	var param = {};
    	param.shopCatId1 = $("#shopCatId1").val();
    	param.shopCatId2 = $("#shopCatId2").val();
    	param.goodsName = $("#goodsName").val();
    	var load = layer.load(0, {shade: false})
        $('#div_module_goods_search_list').load(WST.AU("decoration://decoration/goodssearch") ,param,function(){
        	layer.close(load);
        });
    });

    //商品模块搜索结果翻页
    $('#div_module_goods_search_list').on('click', 'a.demo', function() {
        $('#div_module_goods_search_list').load($(this).attr('href'));
        return false;
    });

    //商品添加
    $('#div_module_goods_search_list').on('click', '[nctype="btn_module_goods_operate"]', function() {
        var $goods = $(this).parents('[nctype="goods_item"]').clone();
        $goods.find('[nctype="btn_module_goods_operate"]').html('<i class="icon-ban-circle"></i>取消选择');
        $('#div_module_goods_list').append($goods);
    });

    //商品删除
    $('#div_module_goods_list').on('click', '[nctype="btn_module_goods_operate"]', function() {
        $(this).parents('[nctype="goods_item"]').remove();
    });


    //关闭窗口
    $('#btn_close').on('click', function() {
        window.close();
    });
    
    $(".wst-color").on('change', function() {
    	if($(this).attr("data")=="hd"){
    		$("#showColor").html($(this).val());
    	}else{
    		$("#txt_background_color").val($(this).val());
    	}
    });
   
});
