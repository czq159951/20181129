var WST = WST?WST:{};
WST.wxv = '1.0_0825';
WST.toJson = function(str,notLimit){
	var json = {};
	if(str){
	try{
		if(typeof(str )=="object"){
			json = str;
		}else{
			json = eval("("+str+")");
		}
		if(!notLimit){
			if(json.status && json.status=='-999'){
				WST.inLogin();
			}
		}
	}catch(e){
		alert("系统发生错误:"+e.getMessage);
		json = {};
	}
	return json;
	}else{
		return;
	}
}
//登录
WST.inLogin = function(){
	var urla = window.location.href;
	$.post(WST.U('wechat/index/sessionAddress'),{url:urla},function(data,textStatus){});
	var urls = escape(document.location.protocol+'//'+window.location.host+WST.U('wechat/users/login','type=1'));
	var url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='+WST.conf.wxAppId+'&redirect_uri='+urls+'&response_type=code&scope=snsapi_userinfo&state='+window.conf.wxAppCode+'#wechat_redirect';
	window.location.href = url;
}
//底部的tab
WST.initFooter = function(tab){
    var homeImage = (tab=='home') ? 'home-active' : 'home';
    var categoryImage = (tab=='category') ? 'category-active' : 'category';
    var cartImage = (tab=='cart') ? 'cart-active' : 'cart';
    var followImage = (tab=='brand') ? 'follow-active' : 'follow';
    var usersImage = (tab=='user') ? 'user-active' : 'user';
    $('#home').append('<span class="icon '+homeImage+'"></span><span class="'+homeImage+'-word">首页</span>');
    $('#category').append('<span class="icon '+categoryImage+'"></span><span class="'+categoryImage+'-word">分类</span>');
    $('#cart').prepend('<span class="icon '+cartImage+'"></span><span class="'+cartImage+'-word">购物车</span>');
    $('#follow').append('<span class="icon '+followImage+'"></span><span class="'+followImage+'-word">关注</span>');
    $('#user').append('<span class="icon '+usersImage+'"></span><span class="'+usersImage+'-word">我的</span>');
}
//变换选中框的状态
WST.changeIconStatus = function (obj, toggle, status){
    if(toggle==1){
        if( obj.attr('class').indexOf('ui-icon-unchecked-s') > -1 ){
            obj.removeClass('ui-icon-unchecked-s').addClass('ui-icon-success-block wst-active');
        }else{
            obj.removeClass('ui-icon-success-block wst-active').addClass('ui-icon-unchecked-s');
        }
    }else if(toggle==2){
        if(status == 'wst-active'){
            obj.removeClass('ui-icon-unchecked-s').addClass('ui-icon-success-block wst-active');
        }else{
            obj.removeClass('ui-icon-success-block wst-active').addClass('ui-icon-unchecked-s');
        }
    }
}
WST.changeIptNum = function(diffNum,iptId,id,func){
	var suffix = (id)?"_"+id:"";
	var iptElem = $(iptId+suffix);
	var minVal = parseInt(iptElem.attr('data-min'),10);
	var maxVal = parseInt(iptElem.attr('data-max'),10);
	var num = parseInt(iptElem.val(),10);
	num = num?num:1;
	num = num + diffNum;
	if(maxVal<=num)num=maxVal;
	if(num<=minVal)num=minVal;
	if(num==0)num=1;
	iptElem.val(num);
	if(suffix!='')WST.changeCartGoods(id,num,-1);
	if(func){
		var fn = window[func];
		fn();
	}
}
WST.changeCartGoods = function(id,buyNum,isCheck){
	$.post(WST.U('wechat/carts/changeCartGoods'),{id:id,isCheck:isCheck,buyNum:buyNum,rnd:Math.random()},function(data,textStatus){
	     var json = WST.toJson(data);
	     if(json.status!=1){
	    	 WST.msg(json.msg,'info');
	     }
	});
}
// 批量修改购物车状态
WST.batchChangeCartGoods = function(ids,isCheck){
	$.post(WST.U('wechat/carts/batchChangeCartGoods'),{ids:ids,isCheck:isCheck},function(data,textStatus){
	     var json = WST.toJson(data);
	     if(json.status!=1){
	    	 WST.msg(json.msg,'info');
	     }
	});
}
//商品主页
WST.intoGoods = function(id){
	location.href = WST.U('wechat/goods/detail','goodsId='+id);
};
//店铺主页
WST.intoShops = function(id){
	location.href = WST.U('wechat/shops/home','shopId='+id);
};
//首页
WST.intoIndex = function(){
	location.href = WST.U('wechat/index/index');
};
//搜索
WST.searchPage = function(type,state){
	if(state==1){
		$("#wst-"+type+"-search").show();
	}else{
		$("#wst-"+type+"-search").hide();
	}
};
WST.search = function(type){
	var data = $('#wst-search').val();
	if(type==1){
		location.href = WST.U('wechat/shops/shopStreet','keyword='+data);//店铺
	}else if(type==0){
		location.href = WST.U('wechat/goods/lists','keyword='+data);//商品
	}else if(type==2){
		var shopId = $('#shopId').val();
		location.href = WST.U('wechat/shops/shopGoodsList','goodsName='+data+'&shopId='+shopId);//店铺商品
	}
};
//关注
WST.favorites = function(sId,type){
    $.post(WST.U('wechat/favorites/add'),{id:sId,type:type},function(data){
        var json = WST.toJson(data);
        if(json.status==1){
            WST.msg(json.msg,'success');
            if(type==1){
                $('#fStatus').html('已关注');
                $('#fBtn').attr('onclick','WST.cancelFavorite('+json.data.fId+',1)');
                $('.j-shopfollow').addClass('follow');
                $('#followNum').html(parseInt($('#followNum').html())+1);
            }else{
            	$('.imgfollow').removeClass('nofollow').addClass('follow');
            	$('.imgfollow').attr('onclick','WST.cancelFavorite('+json.data.fId+',0)');
            }
        }else{
            WST.msg(json.msg,'info');
        }
    })
}
// 取消关注
WST.cancelFavorite = function(fId,type){
    $.post(WST.U('wechat/favorites/cancel'),{id:fId,type:type},function(data){
    var json = WST.toJson(data);
    if(json.status==1){
      WST.msg(json.msg,'success');
      if(type==1){
          $('#fStatus').html('关注店铺');
          $('#fBtn').attr('onclick','WST.favorites('+$('#shopId').val()+',1)');
          $('.j-shopfollow').removeClass('follow');
          $('#followNum').html(parseInt($('#followNum').html())-1);
      }else{
    	  $('.imgfollow').removeClass('follow').addClass('nofollow');
    	  $('.imgfollow').attr('onclick','WST.favorites('+$('#goodsId').val()+',0)');
      }
    }else{
      WST.msg(json.msg,'info');
    }
  });
}
WST.userPhoto = function(userPhoto){
	if(userPhoto.substring(0,4)!='http' && userPhoto!=""){
		userPhoto = window.conf.ROOT+"/"+userPhoto;
	}else if(!userPhoto){
		userPhoto = window.conf.ROOT+"/"+window.conf.USER_LOGO;
	}
	return userPhoto;
}
//刷新验证码
WST.getVerify = function(id){
    $(id).attr('src',WST.U('wechat/index/getVerify','rnd='+Math.random()));
}
//返回当前页面高度
WST.pageHeight = function(){
	if(WST.checkBrowser().msie){ 
		return document.compatMode == "CSS1Compat"? document.documentElement.clientHeight : 
		document.body.clientHeight; 
	}else{ 
		return self.innerHeight; 
	} 
};
//返回当前页面宽度 
WST.pageWidth = function(){ 
	if(WST.checkBrowser().msie){ 
		return document.compatMode == "CSS1Compat"? document.documentElement.clientWidth : 
		document.body.clientWidth; 
	}else{ 
		return self.innerWidth; 
	} 
};
WST.checkBrowser = function(){
	return {
		mozilla : /firefox/.test(navigator.userAgent.toLowerCase()),
		webkit : /webkit/.test(navigator.userAgent.toLowerCase()), 
	    opera : /opera/.test(navigator.userAgent.toLowerCase()), 
	    msie : /msie/.test(navigator.userAgent.toLowerCase())
	}
}
//只能輸入數字
WST.isNumberKey = function(evt){
	var charCode = (evt.which) ? evt.which : event.keyCode;
	if (charCode > 31 && (charCode < 48 || charCode > 57)){
		return false;
	}else{		
		return true;
	}
}
WST.isChinese = function(obj,isReplace){
 	var pattern = /[\u4E00-\u9FA5]|[\uFE30-\uFFA0]/i
 	if(pattern.test(obj.value)){
 		if(isReplace)obj.value=obj.value.replace(/[\u4E00-\u9FA5]|[\uFE30-\uFFA0]/ig,"");
 		return true;
 	}
 	return false;
}
//适应图片大小正方形
WST.imgAdapt = function(name){
	var w = $('.'+name).width();
	$('.'+name).css({"width": w+"px","height": w+"px"});
	$('.'+name+' a').css({"width": w+"px","height": w+"px"});
	$('.'+name+' a img').css({"max-width": w+"px","max-height": w+"px"});
}
//显示隐藏
WST.showHide = function(t,str){
	var s = str.split(',');
	if(t){
		for(var i=0;i<s.length;i++){
		   $(s[i]).show();
		}
	}else{
		for(var i=0;i<s.length;i++){
		   $(s[i]).hide();
		}
	}
	s = null;
}
/**
 * 提示信息
 * @param content   	内容
 * @param type          info/普通,success/成功,warn/错误
 * @param stayTime      显示时间
 */
WST.msg = function(content,type,stayTime){
	if(!stayTime){
		stayTime = '1200';
	}
	var el = $.tips({content:content,type:type,stayTime:stayTime});
    return  el;
}
//提示对话框
WST.dialog = function(content,event){
	$("#wst-dialog").html(content);
	$("#wst-event2").attr("onclick","javascript:"+event);
	$("#wst-di-prompt").dialog("show");
}
//提示分享对话框
WST.share = function(){
	$("#wst-di-share").dialog("show");
}
/**
 * 隐藏对话框
 * @param event   	prompt/提示对话框
 * @param event   	share/提示对话框
 */
WST.dialogHide = function(event){
	$("#wst-di-"+event).dialog("hide");
}
//加载中
WST.load = function(content){
	$('#Loadl').css('display','-webkit-box');
	$('#j-Loadl').html(content);
}
WST.noload = function(){
	$('#Loadl').css('display','none');
}
//滚动到顶部
WSTrunToTop = function (){  
	currentPosition=document.documentElement.scrollTop || document.body.scrollTop; 
	currentPosition-=20;
	if(currentPosition>0){
		window.scrollTo(0,currentPosition);  
	}  
	else{  
		window.scrollTo(0,0);  
		clearInterval(timer); 
	}  
}

WST.blank = function(str,defaultVal){
	if(str=='0000-00-00')str = '';
	if(str=='0000-00-00 00:00:00')str = '';
	if(!str)str = '';
	if(typeof(str)=='null')str = '';
	if(typeof(str)=='undefined')str = '';
	if(str=='' && defaultVal)str = defaultVal;
	return str;
}

/**
* 上传图片
*/
WST.upload = function(opts){
  var _opts = {};
  _opts = $.extend(_opts,{auto: true,swf: WST.conf.STATIC +'/plugins/webuploader/Uploader.swf',server:WST.U('wechat/orders/uploadPic')},opts);
  var uploader = WebUploader.create(_opts);
  uploader.on('uploadSuccess', function( file,response ) {
      var json = WST.toJson(response._raw);
      if(_opts.callback)_opts.callback(json,file);
  });
  uploader.on('uploadError', function( file ) {
    if(_opts.uploadError)_opts.uploadError();
  });
  uploader.on( 'uploadProgress', function( file, percentage ) {
    percentage = percentage.toFixed(2)*100;
    if(_opts.progress)_opts.progress(percentage);
  });
    return uploader;
}

//返回键
function backPrevPage(url){
	window.location.hash = "ready";
	window.location.hash = "ok";
    setTimeout(function(){
		$(window).on('hashchange', function(e) {
			var hashName = window.location.hash.replace('#', '');
			hashName = hashName.split('&');
			if( hashName[0] == 'ready' ){
			    location.href = url;
			}
		});
    },50);
}

//图片切换
WST.replaceImg = function(v,str){
	var vs = v.split('.');
    return v.replace("."+vs[1],str+"."+vs[1]);
}

$(function(){
	echo.init();//图片懒加载
    // 滚动到顶部	
    $(window).scroll(function(){
        if( $(window).scrollTop() > 200 ){
            $('#toTop').show();
        }else{
            $('#toTop').hide();
        }
    });
    $('#toTop').on('click', function() {
    	timer=setInterval("WSTrunToTop()",1);
	});
	/**
	 * @type {object}
	 */
	WST.conf = window.conf;
	/* 基础对象检测 */
	WST.conf || $.error("基础配置没有正确加载！");
	/**
	 * 解析URL
	 * @param  {string} url 被解析的URL
	 * @return {object}     解析后的数据
	 */
	WST.parse_url = function(url){
		var parse = url.match(/^(?:([a-z]+):\/\/)?([\w-]+(?:\.[\w-]+)+)?(?::(\d+))?([\w-\/]+)?(?:\?((?:\w+=[^#&=\/]*)?(?:&\w+=[^#&=\/]*)*))?(?:#([\w-]+))?$/i);
		parse || $.error("url格式不正确！");
		return {
			"scheme"   : parse[1],
			"host"     : parse[2],
			"port"     : parse[3],
			"path"     : parse[4],
			"query"    : parse[5],
			"fragment" : parse[6]
		};
	}

	WST.parse_str = function(str){
		var value = str.split("&"), vars = {}, param;
		for(var i=0;i<value.length;i++){
			param = value[i].split("=");
			vars[param[0]] = param[1];
		}
		return vars;
	}
	WST.U = function(url, vars){
		if(!url || url=='')return '';
		var info = this.parse_url(url), path = [], reg;
		/* 验证info */
		info.path || $.error("url格式错误！");
		url = info.path;
		/* 解析URL */
		path = url.split("/");
		path = [path.pop(), path.pop(), path.pop()].reverse();
		path[1] || $.error("WST.U(" + url + ")没有指定控制器");

		/* 解析参数 */
		if(typeof vars === "string"){
			vars = this.parse_str(vars);
		}
		/* 解析URL自带的参数 */
		info.query && $.extend(vars, this.parse_str(info.query));
		if(false !== WST.conf.SUFFIX){
			url += "." + WST.conf.SUFFIX;
		}
		if($.isPlainObject(vars)){
			url += "?" + $.param(vars);
		}
		//url = url.replace(new RegExp("%2F","gm"),"+");
		url = WST.conf.APP + "/"+url;
		return url;
	}
	WST.AU = function(url, vars){
        if(!url || url=='')return '';
        var info = this.parse_url(url);
        url = info.path;
        path = url.split("/");
        url = "addon/";
        path = [path.pop(), path.pop()].reverse();
        path[0] || $.error("WST.AU(" + url + ")没有指定控制器");
        path[1] || $.error("WST.AU(" + url + ")没有指定接口");
        url  = url + info.scheme + "-" + path.join('-');
        /* 解析参数 */
		if(typeof vars === "string"){
			vars = this.parse_str(vars);
		}
		info.query && $.extend(vars, this.parse_str(info.query));
		if(false !== WST.conf.SUFFIX){
			url += "." + WST.conf.SUFFIX;
		}
		if($.isPlainObject(vars)){
			url += "?" + $.param(vars);
		}
		return WST.conf.APP + "/"+url;
	}
});