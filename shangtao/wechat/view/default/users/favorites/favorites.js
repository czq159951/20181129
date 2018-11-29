// 获取关注的商品
function getFavorites(){
  $('#Load').show();
    loading = true;
    var param = {};
    param.id = $('#catId').val();
    param.condition = $('#condition').val();
    param.desc = $('#desc').val();
    param.keyword = $('#searchKey').val();
    param.pagesize = 10;
    param.page = Number( $('#currPage').val() ) + 1;
    $.post(WST.U('wechat/favorites/listGoodsQuery'), param, function(data){
        var json = WST.toJson(data.data);
        var html = '';
        if(json && json.data && json.data.length>0){
           var gettpl = document.getElementById('fGoods').innerHTML;
          laytpl(gettpl).render(json.data, function(html){
            $('#goods-list').html(html);
          });
          $('#currPage').val(data.current_page);
          $('#totalPage').val(data.last_page);
        }else{
          html += '<div class="wst-prompt-icon"><img src="'+ window.conf.WECHAT +'/img/nothing-follow-goods.png"></div>';
	      html += '<div class="wst-prompt-info">';
	      html += '<p>您还没有关注商品。</p>';
	      html += '</div>';
          $('#goods-list').html(html);
        }
        WST.imgAdapt('j-imgAdapt');
        loading = false;
        $('#Load').hide();
        echo.init();//图片懒加载
    });
}
// 全选
function checkAll(obj){
  var chk = $(obj).attr('checked');
  $('.active').each(function(k,v){
    $(this).prop('checked',chk);
  });
}
// 取消关注
function cancelFavorite(){
  WST.dialogHide('prompt');
  var gids = new Array();
  $('.active').each(function(k,v){
    if($(this).attr('checked')){
      gids.push($(this).attr('gid'));
    }
  });
  gids = gids.join(',');
  if(gids==''){
    WST.msg('请先选择商品','info');
    return;
  }
  $.post(WST.U('wechat/favorites/cancel'),{id:gids,type:0},function(data){
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



function addCart(goodsId){
  $.post(WST.U('wechat/carts/addCart'),{goodsId:goodsId,buyNum:1},function(data,textStatus){
       var json = WST.toJson(data);
       if(json.status==1){
         WST.msg(json.msg,'success');
       }else{
         WST.msg(json.msg,'info');
       }
  });
}