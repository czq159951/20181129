jQuery.noConflict();
//切换
function pageSwitch(obj,type){
	$(obj).addClass('active').siblings('.ui-tab-nav li.switch').removeClass('active');
	$('#goods'+type).show().siblings('section.ui-container').hide();
	if(type==1){
		var offsetTop = $("#goods1").offset().top;
        var scrollTop = $(window).scrollTop()-100; 
        if (scrollTop > offsetTop){
            $("#goods-header").show();
        }else{  
            $("#goods-header").hide();
        }
	}
	if(type==3){
		$("#goods-header").show();
	}
}
//商品评价列表
function evaluateList(){
    loading = true;
    var param = {};
    param.goodsId = $('#goodsId').val();
    param.type = $('#evaluateType').val();
	param.pagesize = 10;
	param.page = Number( $('#currPage').val() ) + 1;
    $.post(WST.U('mobile/goodsappraises/getById'), param,function(data){
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
function evaluateSwitch(obj,type){
	$('#evaluateType').val(type);
	$(obj).addClass('active').siblings('.wst-ev-term .ui-col').removeClass('active');
    $('#currPage').val('0');
    $('#totalPage').val('0');
	$('#evaluate-list').html('');
	evaluateList();
}
var currPage = totalPage = 0;
var loading = false;
$(document).ready(function(){
	$("embed").removeAttr('height').css('width','100%');
	//商品图片
    new Swiper('.swiper-container', {
        slidesPerView: 1,
        spaceBetween: 0,
        grabCursor : true,
        autoplayDisableOnInteraction : true,
        pagination : '.swiper-pagination',
        paginationClickable :true
    });
    evaluateList();
    fixedHeader();
	WST.imgAdapt('j-imgAdapt');
    $(window).scroll(function(){  
        if (loading) return;
        if ((5 + $(window).scrollTop()) >= ($(document).height() - screen.height)) {
            currPage = Number( $('#currPage').val() );
            totalPage = Number( $('#totalPage').val() );
            if( totalPage > 0 && currPage < totalPage ){
            	evaluateList();
            }
        }
    });
	if(goodsInfo.sku){
		var specs,dv;
		for(var key in goodsInfo.sku){
			if(goodsInfo.sku[key].isDefault==1){
				specs = key.split(':');
				$('.j-option').each(function(){
					dv = $(this).attr('data-val')
					if($.inArray(dv,specs)>-1){
						$(this).addClass('active');
					}
				})
				$('#buyNum').attr('data-max',goodsInfo.sku[key].specStock);
			}
		}
	}else{
		$('#buyNum').attr('data-max',goodsInfo.goodsStock);
	}
	checkGoodsStock();
	//选择规格
	$('.spec .j-option').click(function(){
		$(this).addClass('active').siblings().removeClass('active');
		if($(this).attr('data-image')){
		    $("#specImage").attr('src',$(this).attr('data-image'));
	    }
		checkGoodsStock();
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
function checkGoodsStock(){
	var specIds = [],stock = 0,goodsPrice=0,marketPrice=0;
	if(goodsInfo.isSpec==1){
		$('.spec .active').each(function(){
			specIds.push(parseInt($(this).attr('data-val'),10));
		});
		specIds.sort(function(a,b){return a-b;});
		if(goodsInfo.sku[specIds.join(':')]){
			stock = goodsInfo.sku[specIds.join(':')].specStock;
			marketPrice = goodsInfo.sku[specIds.join(':')].marketPrice;
			goodsPrice = goodsInfo.sku[specIds.join(':')].specPrice;
		}
	}else{
		stock = goodsInfo.goodsStock;
		marketPrice = goodsInfo.marketPrice;
		goodsPrice = goodsInfo.goodsPrice;
	}
	$('#goods-stock').html(stock);
	$('#buyNum').attr('data-max',stock);
	$('#j-market-price').html('¥'+marketPrice);
	$('#j-shop-price').html('¥'+goodsPrice);
	if(stock<=0){
		$('#addBtn').addClass('disabled');
		$('#buyBtn').addClass('disabled');
	}else{
		$('#addBtn').removeClass('disabled');
		$('#buyBtn').removeClass('disabled');
	}
}
//导航
function fixedHeader(){
    var offsetTop = $("#goods1").offset().top;
    $(window).scroll(function() {
        if($("#goods1").css("display")!='none'){
	        var scrollTop = $(window).scrollTop()-100; 
	        if (scrollTop > offsetTop){
	            $("#goods-header").show();
	        }else{  
	            $("#goods-header").hide();
	        }  
        }else{
        	$("#goods-header").show();
        }
    });
}
function inMore(){
	if($("#arrow").css("display")=='none'){
		jQuery('#arrow').show(200);
		$("#layer").show();
	}else{
		jQuery('#arrow').hide(100);
		$("#layer").hide();
	}
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
function addCart(){
	var goodsSpecId = 0;
	if(goodsInfo.isSpec==1){
		var specIds = [];
		$('.spec .active').each(function(){
			specIds.push($(this).attr('data-val'));
		});
		if(specIds.length==0){
			WST.msg('请选择你要购买的商品信息','info');
		}
		specIds.sort(function(a,b){return a-b;});
		if(goodsInfo.sku[specIds.join(':')]){
			goodsSpecId = goodsInfo.sku[specIds.join(':')].id;
		}
	}
	var goodsType = $("#goodsType").val();
	var buyNum = $("#buyNum").val()?$("#buyNum").val():1;
	$.post(WST.U('mobile/carts/addCart'),{goodsId:goodsInfo.id,goodsSpecId:goodsSpecId,buyNum:buyNum,type:type,rnd:Math.random()},function(data,textStatus){
	     var json = WST.toJson(data);
	     if(json.status==1){
	    	 WST.msg(json.msg,'success');
	    	 cartHide();
	    	 if(type==1){
	    		 setTimeout(function(){
	    			 if(goodsType==1){
	    				 location.href=WST.U('mobile/carts/'+json.data.forward);
	    			 }else{
	    				 location.href=WST.U('mobile/carts/settlement');
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
document.addEventListener('touchmove', function(event) {
    //阻止背景页面滚动,
    if(!jQuery("#cover").is(":hidden")){
        event.preventDefault();
    }
    if(!jQuery("#layer").is(":hidden")){
        event.preventDefault();
    }
})