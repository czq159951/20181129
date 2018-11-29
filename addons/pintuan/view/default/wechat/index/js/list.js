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
var currPage = totalPage = 0;
var loading = false;
var maxCheckNo = 15;
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

    setInterval(function(){
        var currPuId = $("#currPuId").val();
        var maxPuId = $("#maxPuId").val();
        if(maxCheckNo>0){
            lastTuan(0,currPuId,maxPuId,5000);
        }
    },10000);
});
function goGoods(id){
    location.href=WST.AU('pintuan://goods/wxdetail','id='+id);
}
function searchGoods(){
	var data = $('#wst-search').val();
	location.href = WST.AU('pintuan://goods/wxlists','keyword='+data);
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