jQuery.noConflict();
//切换
function pageSwitch(obj,type){
	$(obj).addClass('active').siblings('.ui-tab-nav li.switch').removeClass('active');
	$('#goods'+type).show().siblings('section.ui-container').hide();
}
//商品评价列表
function evaluateList(){
    loading = true;
    var param = {};
    param.goodsId = $('#goodsId').val();
	param.pagesize = 10;
	param.page = Number( $('#currPage').val() ) + 1;
    $.post(WST.U('wechat/goodsappraises/getById'), param,function(data){
        var json = WST.toJson(data);
        $('#currPage').val(json.data.current_page);
        $('#totalPage').val(json.data.last_page);
        var gettpl = document.getElementById('list').innerHTML;
        laytpl(gettpl).render(json.data.data, function(html){
            $('#evaluate-list').append(html);
        });
        loading = false;
        echo.init();//图片懒加载
    });
}
var currPage = totalPage = 0;
var loading = false;
var maxCheckNo = 15;
$(document).ready(function(){
	$("embed").removeAttr('height').css('width','100%');
	time();
	goodsList();
	//商品图片
    var slider = new fz.Scroll('.ui-slider', {
        role: 'slider',
        indicator: true,
        autoplay: true,
        interval: 3000
    });
	var w = WST.pageWidth();
    evaluateList();
	//WST.imgAdapt('j-imgAdapt');
    $(window).scroll(function(){  
        if (loading) return;
        if ((5 + $(window).scrollTop()) >= ($(document).height() - $(window).height())) {
            currPage = Number( $('#currPage').val() );
            totalPage = Number( $('#totalPage').val() );
            if( totalPage > 0 && currPage < totalPage ){
            	evaluateList();
            }
        }
    });
    //弹框的高度
    var dataHeight = $("#frame").css('height');
    var cartHeight = parseInt($("#frame-cart").css('height'))+52+'px';
    if(parseInt(dataHeight)>230){
        $('#content').css('overflow-y','scroll').css('height','200');
    }
    if(parseInt(cartHeight)>420){
        $('#standard').css('overflow-y','scroll').css('height','260');
    }
    var dataHeight = $("#frame").css('height');
    var cartHeight = parseInt($("#frame-cart").css('height'))+52+'px';
    $("#frame").css('bottom','-'+dataHeight);
    $("#frame-cart").css('bottom','-'+cartHeight);
    setInterval(function(){
    	var tuanId = $("#tuanId").val();
    	var currPuId = $("#currPuId").val();
    	var maxPuId = $("#maxPuId").val();
    	if(maxCheckNo>0){
			lastTuan(tuanId,currPuId,maxPuId,5000);
		}
	},10000);
});
//弹框
function dataShow(){
	jQuery('#cover').attr("onclick","javascript:dataHide();").show();
	jQuery('#frame').animate({"bottom": 0}, 500);
}
function dataHide(){
	var dataHeight = $("#frame").css('height');
	jQuery('#frame').animate({'bottom': '-'+dataHeight}, 500);
	jQuery('#cover').hide();
}
//弹框
var type;
function cartShow(t,v,price){
	$("#j-shop-price").html("¥"+price);
	type = t;
	jQuery("#tuanType").val(v);
	jQuery('#cover').attr("onclick","javascript:cartHide();").show();
	jQuery('#frame-cart').animate({"bottom": 0}, 500);
}
function cartHide(){
	var cartHeight = parseInt($("#frame-cart").css('height'))+52+'px';
	jQuery('#frame-cart').animate({'bottom': '-'+cartHeight}, 500);
	jQuery('#cover').hide();
}
//加入购物车
function addCart(goodsType){
	if(WST.conf.IS_LOGIN==0){
		WST.inLogin();
		return;
	}
	var buyNum = $("#buyNum").val()?$("#buyNum").val():1;
	var tuanType = $("#tuanType").val();
	if(tuanType==1){
		$.post(WST.AU('pintuan://carts/addCart'),{id:goodsInfo.tuanId,tuanType:tuanType,buyNum:buyNum,rnd:Math.random()},function(data,textStatus){
		     var json = WST.toJson(data);
		     if(json.status==1){
		    	 cartHide();
	    		 setTimeout(function(){
	    			 location.href=WST.AU('pintuan://carts/wxSettlement','goodsType='+goodsType);
	    		 },1000);
		     }else{
		    	 WST.msg(json.msg,'info');
		     }
		});
	}else{
		$.post(WST.U('wechat/carts/addCart'),{goodsId:goodsInfo.id,goodsSpecId:goodsInfo.goodsSpecId,buyNum:buyNum,rnd:Math.random()},function(data,textStatus){
		     var json = WST.toJson(data);
		     if(json.status==1){
		    	 WST.msg(json.msg,'success');
		    	 cartHide();
		    	 if(type==1){
		    		 setTimeout(function(){
		    			 if(goodsType==1){
		    				 location.href=WST.U('wechat/carts/'+json.data.forward);
		    			 }else{
		    				 location.href=WST.U('wechat/carts/index');
		    			 }
		    		 },1000);
		    	 }else{
		    		 if(json.cartNum>0)$("#cartNum").html('<span>'+json.cartNum+'</span>');
		    	 }
		     }else{
		    	 WST.msg(json.msg,'info');
		     }
		});
	}
	
}

function lastTuan(tuanId,currPuId,maxPuId,laytime){
	$.post(WST.AU('pintuan://goods/getLastTuan'),{tuanId:tuanId,currPuId:currPuId,maxPuId:maxPuId,rnd:Math.random()},function(data,textStatus){
	     var json = WST.toJson(data);
	     maxCheckNo = maxCheckNo-1;
	     if(json.status==1){
	     	var tflag = json.data.tflag;
	     	if(tflag==1){
	     		$("#maxPuId").val(json.data.maxPuId);
	     	}else{
	     		$("#currPuId").val(json.data.currPuId);
	     	}
	     	var userPhoto = WST.userPhoto(json.data.puser.userPhoto);
	     	$("#tuanImg").css({"background-image":'url('+userPhoto+')'});
	     	$("#tuanMsg").html(json.data.tmsg);
	     	
	    	jQuery("#tuantip").fadeIn(1000);
    		setTimeout(function(){
    			jQuery("#tuantip").fadeOut(1000);
    		},laytime);
	     }else{
	     	$("#currPuId").val(maxPuId);
	     }
	});
}

//去拼团
function toPintuan(tuanNo){
    location.href=WST.AU('pintuan://goods/wxtuandetail','tuanNo='+tuanNo);
}


//获取商品列表
function goodsList(){
	$('#Load').show();
    loading = true;
    var param = {};
	param.pagesize = 10;
	param.catId = $("#catId").val();
	param.page = Number( $('#currPage').val() ) + 1;
    $.post(WST.AU('pintuan://goods/wxGrouplists'), param,function(data){
        var json = WST.toJson(data);
        $('#currPage').val(json.current_page);
        $('#totalPage').val(json.last_page);
        var gettpl = document.getElementById('glist').innerHTML;
        laytpl(gettpl).render(json.data, function(html){
            $('#goods-list').append(html);
        });
        WST.imgAdapt('j-imgAdapt');
        loading = false;
        $('#Load').hide();
        echo.init();//图片懒加载
    });
}

function goGoods(id){
    location.href=WST.AU('pintuan://goods/wxdetail','id='+id);
}
