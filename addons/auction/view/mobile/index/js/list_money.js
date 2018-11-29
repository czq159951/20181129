//获取列表
function goodsList(){
	$('#Load').show();
    loading = true;
    var param = {};
    param.catId = $('#goodsCatId').val();
    param.goodsName = $('#keyword').val();
	param.pagesize = 10;
	param.page = Number( $('#currPage').val() ) + 1;
    $.post(WST.AU('auction://users/pageQueryByMoney'), param,function(data){
        var json = WST.toJson(data);
        $('#currPage').val(json.data.current_page);
        $('#totalPage').val(json.data.last_page);
        var gettpl = document.getElementById('list').innerHTML;
        laytpl(gettpl).render(json.data.data, function(html){
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
	if(WST.conf.IS_LOGIN==0){//是否登录
		WST.inLogin();
		return;
	}
	WST.initFooter('user');
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
});
function goGoods(id){
    location.href=WST.AU('auction://goods/modetail','id='+id);
}