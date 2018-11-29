jQuery.noConflict();

var currPage = totalPage = 0;
var loading = false;
var maxCheckNo = 15;
$(document).ready(function(){
	time();
    goodsList();
    $(window).scroll(function(){
        if (loading) return;
        if ((5 + $(window).scrollTop()) >= ($(document).height() - $(window).height())) {
            currPage = Number( $('#currPage').val() );
            totalPage = Number( $('#totalPage').val() );
            if( totalPage > 0 && currPage < totalPage ){
            	goodsList();
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
    	lastTuan(tuanId,currPuId,maxPuId,5000);
	},10000);
});

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
        var gettpl = document.getElementById('list').innerHTML;
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

function goTuanHome(){
    location.href=WST.AU('pintuan://goods/wxlists');
}

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
function cartShow(t,v){
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
	var tuanNo = $("#tuanNo").val();
	if(tuanType==1){
		tuanNo = 0;
	}
	$.post(WST.AU('pintuan://carts/addCart'),{id:goodsInfo.tuanId,tuanType:1,buyNum:buyNum,tuanNo:tuanNo,rnd:Math.random()},function(data,textStatus){
	     var json = WST.toJson(data);
	     if(json.status==1){
	    	 WST.msg(json.msg,'success');
	    	 cartHide();
    		 setTimeout(function(){
    			 location.href=WST.AU('pintuan://carts/wxSettlement','goodsType='+goodsType);
    		 },1000);
	     }else{
	    	 WST.msg(json.msg,'info');
	     }
	});
}

function continueShare(){
	WST.dialogHide('shareresult');
	$("#wst-di-share").dialog("show");
}

function lastTuan(tuanId,currPuId,maxPuId,laytime){
	$.post(WST.AU('pintuan://goods/getLastTuan'),{tuanId:tuanId,currPuId:currPuId,maxPuId:maxPuId,rnd:Math.random()},function(data,textStatus){
	     var json = WST.toJson(data);
	     maxCheckNo = maxCheckNo-1;
	     if(maxCheckNo==0){
	     	maxCheckNo = 15;
	     	$("#currPuId").val(maxPuId);
	     }
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


//去支付
function choicePay(orderNo){
    location.href=WST.AU('pintuan://pintuan/payTypes',{'orderNo':orderNo});
}