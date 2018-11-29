//列表
function indexList(){
	$('#Load').show();
	loading = true;
	var param = {};
	param.currPage = Number( $('#currPage').val() ) + 1;
    $.post(WST.U('mobile/index/pageQuery'), param,function(data){
        var json = WST.toJson(data);
        if(json && json.catId){
	        $('#currPage').val(json.currPage);
            var gettpl = document.getElementById('list').innerHTML;
            laytpl(gettpl).render(json, function(html){
                $('#goods-list').append(html);
            });
            echo.init();
	        WST.imgAdapt('j-imgAdapt');
        }
	    loading = false;
	    $('#Load').hide();
    });
}
//商品列表页
function getGoodsList(goodsCatId){
	location.href = WST.U('mobile/goods/lists','catId='+goodsCatId);
}
var currPage = 0;
var loading = false;
$(document).ready(function(){
	WST.initFooter('home');
	//搜索
    $(window).scroll(function(){
        if( $(window).scrollTop() > 42 ){
            $('#j-header').addClass('active');
            $('#j-searchs').addClass('active');
        }else{
        	$('#j-header').removeClass('active');
        	$('#j-searchs').removeClass('active');
        }
    });
	$('.wst-se-search').on('submit', '.input-form', function(event){
	    event.preventDefault();
	})
    if($('.ui-slider li').hasClass("advert1")){
    	//广告
        var slider = new fz.Scroll('.ui-slider', {
            role: 'slider',
            indicator: true,
            autoplay: true,
            interval: 3000
        });
    }else{
    	$('.ui-slider').hide();
    }
	
	//文章
	if($('.wst-in-news a').hasClass("words")){
	    new Swiper('.swiper-container1', {
	        slidesPerView: 1,
	        freeMode : true,
	        spaceBetween: 0,
	        autoplay : 3800,
	        speed:500,
	        direction : 'vertical',
	        loop : true,
	        autoplayDisableOnInteraction : false,
	        onlyExternal : true
	    });
	}
    
	var w = WST.pageWidth();
	//咨询上广告
	if($('.wst-in-activity a').hasClass("advert4")){
	}else{
		$('.wst-in-activity .advert4').hide();
	}
	//中间大广告
	if($('.wst-in-adst a').hasClass("advert2")){
	}else{
		$('.wst-in-adst ').hide();
	}
	
	//中间小广告	
	if($('.wst-in-adsb a').hasClass("advert3")){
	    new Swiper('.swiper-container2', {
	        slidesPerView: 3,
	        freeMode : true,
	        spaceBetween: 0,
	        autoplay : 2000,
	        speed:1200,
	        loop : true,
	        autoplayDisableOnInteraction : false,
	        onSlideChangeEnd: function(swiper){
	            echo.init();//图片懒加载
	        }
	    });
	}else{
		$('.wst-in-adsb').hide();
	}


	//刷新
	indexList();
    $(window).scroll(function(){
        if (loading) return;
        if ((5 + $(window).scrollTop()) >= ($(document).height() - screen.height)) {
            currPage = Number( $('#currPage').val() );
            if(currPage < 10 ){
                indexList();
            }
        }
    });
});