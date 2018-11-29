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
$(document).ready(function(){
	$("embed").removeAttr('height').css('width','100%');
	//商品图片
    var slider = new fz.Scroll('.ui-slider', {
        role: 'slider',
        indicator: true,
        autoplay: true,
        interval: 3000
    });
	var w = WST.pageWidth();
    evaluateList();
	WST.imgAdapt('j-imgAdapt');
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
function cartShow(t){
	type = t;
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
	$.post(WST.AU('integral://carts/addCart'),{id:goodsInfo.integralId,buyNum:buyNum,rnd:Math.random()},function(data,textStatus){
	     var json = WST.toJson(data);
	     if(json.status==1){
	    	 WST.msg(json.msg,'success');
	    	 cartHide();
    		 setTimeout(function(){
    			 location.href=WST.AU('integral://carts/wxSettlement','goodsType='+goodsType);
    		 },1000);
	     }else{
	    	 WST.msg(json.msg,'info');
	     }
	});
}