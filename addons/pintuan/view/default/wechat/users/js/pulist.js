jQuery.noConflict();
//获取店铺列表
function goodsList(){
	$('#Load').show();
    loading = true;
    var param = {};
	param.pagesize = 10;
    param.ftype = $('#ftype').val();
	param.page = Number( $('#currPage').val() ) + 1;
    $.post(WST.AU('pintuan://pintuan/pageQuery'), param,function(data){
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

    // Tab切换卡
    $('.tab-item').click(function(){
        $(this).addClass('tab-curr').siblings().removeClass('tab-curr');
        var type = $(this).attr('type');
        $('#ftype').val(type);
        $('#currPage').val('0');
        $('#goods-list').html('');
        goodsList();
    });

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

function showCancelBox(event){
    $("#wst-event0").attr("onclick","javascript:"+event);
    $("#cancelBox").dialog("show");
}
//取消拼单
function toCancel(id){
    $('#cancelBox').dialog("hide");
    $.post(WST.AU('pintuan://pintuan/toCancel'),{id:id},function(data){
        var json = WST.toJson(data);
        if(json.status==1){
            $('#currPage').val(0);
            $('#goods-list').empty();
            goodsList();
        }else{
            WST.msg(json.msg,'info');
        }
    });
}
//去支付
function choicePay(orderNo){
    location.href=WST.AU('pintuan://pintuan/payTypes',{'orderNo':orderNo});
}
//查看拼团
function toDetail(tuanNo){
    location.href=WST.AU('pintuan://goods/wxtuandetail','tuanNo='+tuanNo);
}

