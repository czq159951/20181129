jQuery.noConflict();
//获取店铺列表
function goodsList(){
	$('#Load').show();
    loading = true;
    var param = {};
    param.catId = $('#goodsCatId').val();
    param.goodsName = $('#keyword').val();
	param.pagesize = 10;
	param.page = Number( $('#currPage').val() ) + 1;
    $.post(WST.AU('integral://goods/glists'), param,function(data){
        var json = WST.toJson(data);
        if(window.conf.IS_LOGIN != 0){
            $('#userMoney').html(json.User.userMoney);
            $('#userScore').html(json.User.userScore);
        }
        $('#currPage').val(json.current_page);
        $('#totalPage').val(json.last_page);
        var gettpl = document.getElementById('list').innerHTML;
        laytpl(gettpl).render(json, function(html){
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
    var dataHeight = $("#frame").css('height');
    $('.goodscate1').css('overflow-y','scroll').css('height',WST.pageHeight()-50);
    $("#frame").css('top',0);
     var dataWidth = $("#frame").css('width');
    $("#frame").css('right','-'+dataWidth);

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
function goGoods(id){
    location.href=WST.AU('integral://goods/wxdetail','id='+id);
}
function searchGoods(){
	var data = $('#wst-search').val();
	location.href = WST.AU('integral://goods/wxlists','keyword='+data);
}
//弹框
function dataShow(){
    jQuery('#cover').attr("onclick","javascript:dataHide();").show();
    jQuery('#frame').animate({"right": 0}, 500);
}
function dataHide(){
    var dataHeight = $("#frame").css('height');
    var dataWidth = $("#frame").css('width');
    jQuery('#frame').animate({'right': '-'+dataWidth}, 500);
    jQuery('#cover').hide();
}

function showRight(obj, index){
    $(obj).addClass('wst-goodscate_selected').siblings('#goodscate').removeClass('wst-goodscate_selected');
    $('.goodscate1').eq(index).show().siblings('.goodscate1').hide();
}
/*分类*/
function goodsCat(goodsCatId){
    $('#goodsCatId').val(goodsCatId);
    $('#currPage').val('');
    $('#goods-list').html('');
    goodsList();
    dataHide();
}
