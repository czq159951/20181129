//排序条件
function orderCondition(obj,orderBy){
    var classContent = $(obj).attr('class');
    var status = $(obj).attr('status');
    var theSiblings = $(obj).siblings('.sorts');
    theSiblings.removeClass('active').attr('status','down');
    $(obj).addClass('active');
    if(classContent.indexOf('active')==-1){
        $(obj).children('i').addClass('down2').removeClass('down');
        theSiblings.children('i').addClass('down').removeClass('down2');
    }
    if(status.indexOf('down')>-1){
        if(classContent.indexOf('active')==-1){
        	$(obj).children('i').addClass('down2').removeClass('up2');
            $('#order').val('0');
        }else{
        	$(obj).children('i').addClass('up2').removeClass('down2');
            $(obj).attr('status','up');
            $('#order').val('1');
        }
    }else{
    	$(obj).children('i').addClass('down2').removeClass('up2');
        $(obj).attr('status','down');
        $('#order').val('0');
    }
    $('#orderBy').val(orderBy);//排序条件
    $('#currPage').val('0');//当前页归零
    $('#goods-list').html('');
    goodsList();
}
function searchGoods(){
	var data = $('#wst-search').val();
	location.href = WST.AU('distribut://distribut/wechatdistributgoods','keyword='+data);
}
//获取商品列表
function goodsList(){
	$('#Load').show();
    loading = true;
    var param = {};
    param.orderBy = $('#orderBy').val();
    param.order = $('#order').val();
    param.keyword = $('#keyword').val();
	param.pagesize = 10;
	param.page = Number( $('#currPage').val() ) + 1;
    $.post(WST.U('addon/distribut-goods-mwgoodslist'), param,function(data){
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
var currPage = totalPage = 0;
var loading = false;
$(document).ready(function(){
	WST.initFooter('home');
    goodsList();
	//中间小广告
	var w = WST.pageWidth();
    new Swiper('.swiper-container', {
        slidesPerView: 4,
        freeMode : true,
        spaceBetween: 3,
        autoplay : 2000,
        speed:1200,
        loop : false,
        autoplayDisableOnInteraction : false,
        onSlideChangeEnd: function(swiper){
            echo.init();//图片懒加载
        }
    });
    WST.imgAdapt('j-imgRec');
    $('.wst-gol-adsb').css('height',$('.j-imgRec').width()+20);
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
});