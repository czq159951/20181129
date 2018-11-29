// 获取关注的店铺
function getFavorites(){
  $('#Load').show();
    loading = true;
    var param = {};
    param.pagesize = 10;
    param.page = Number( $('#currPage').val() ) + 1;
    $.post(WST.U('wechat/favorites/listShopQuery'), param, function(data){
        var json = WST.toJson(data.data);
        var html = '';
        if(json && json.data && json.data.length>0){
          var gettpl = document.getElementById('shopList').innerHTML;
          laytpl(gettpl).render(json.data, function(html){
            $('#shopBox').html(html);
          });

          $('#currPage').val(json.current_page);
          $('#totalPage').val(json.last_page);
        }else{
            html += '<div class="wst-prompt-icon"><img src="'+ window.conf.WECHAT +'/img/nothing-follow-shps.png"></div>';
        	html += '<div class="wst-prompt-info">';
        	html += '<p>您还没有关注店铺。</p>';
        	html += '</div>';
            $('#shopBox').html(html);
        }
        imgShop('j-imgAdapt');
        imgShop('goodsImg');
        loading = false;
        $('#Load').hide();
        echo.init();//图片懒加载
    });


}
function goToShop(sid){
  location.href=WST.U('wechat/shops/home','shopId='+sid);
}
// 全选
function checkAll(obj){
  var chk = $(obj).prop('checked');
  $('.s-active').each(function(k,v){
    $(this).prop('checked',chk);
  });
}
// 取消关注
function cancelFavorite(){
  WST.dialogHide('prompt');
  var fids = new Array();
  $('.s-active').each(function(k,v){
    if($(this).attr('checked')){
      fids.push($(this).attr('fid'));
    }
  });
  fids = fids.join(',');
  if(fids==''){
    WST.msg('请先选择店铺','info');
    return;
  }
  $.post(WST.U('wechat/favorites/cancel'),{id:fids,type:1},function(data){
    var json = WST.toJson(data);
    if(json.status==1){
      $('#currPage').val('0')
      getFavorites();
    }else{
      WST.msg(json.msg,'info');
    }
  });

}

var currPage = totalPage = 0;
var loading = false;
$(document).ready(function(){
  getFavorites();
    $(window).scroll(function(){  
        if (loading) return;
        if ((5 + $(window).scrollTop()) >= ($(document).height() - screen.height)) {
            currPage = Number( $('#currPage').val() );
            totalPage = Number( $('#totalPage').val() );
            if( totalPage > 0 && currPage < totalPage ){
            	getFavorites();
            }
        }
    });
});

//适应图片大小正方形
function imgShop(name){
    var w = $('.'+name).width();
    if(name == 'j-imgAdapt'){
       $('.'+name).css({"width": w+"px","height": w+"px"});
    }else{
       $('.'+name).css({"width": w+"px","height": w+20+"px"});
    }
    $('.'+name+' a').css({"width": w+"px","height": w+"px"});
    $('.'+name+' a img').css({"width": w+"px","height": w+"px"});
    $('.'+name+' a .goodsPrice').css({"width": w+"px"});
}