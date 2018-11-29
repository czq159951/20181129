jQuery.noConflict();
var currPage = totalPage = 0;
var loading = false;
$(document).ready(function(){
  getcouponList();
  // Tab切换卡
  $('.tab-item').click(function(){
      $(this).addClass('tab-curr').siblings().removeClass('tab-curr');
      var catId = $(this).attr('catId');
      $('#catId').val(catId);
      reFlashList();
  });
  // 分类筛选点击
  $('.cat_item').click(function(){
      $(this).addClass('cat_item_curr').siblings().removeClass('cat_item_curr');
      var catId = $(this).attr('id');
      $('#catId').val(catId);
      $('.tab-item').each(function(k,v){
          $(v).removeClass('tab-curr');
          if($(v).attr('catId')==catId){
            $(v).addClass('tab-curr');
          }
      });
      reFlashList();
      dataHide();

  });
  // 弹出层
  var w = WST.pageWidth();
  $("#frame").css('top',0);
  $("#frame").css('right',-w);

    $(window).scroll(function(){  
        if (loading) return;
        if ((5 + $(window).scrollTop()) >= ($(document).height() - $(window).height())) {
            currPage = Number( $('#currPage').val() );
            totalPage = Number( $('#totalPage').val() );
            if( totalPage > 0 && currPage < totalPage ){
                getcouponList();
            }
        }
    });
});

//弹框
function dataShow(){
    jQuery('#cover').attr("onclick","javascript:dataHide();").show();
    // 获取当前选中的分类
    var catId = $('#catId').val();
    $('.cat_item').each(function(){
      $(this).removeClass('cat_item_curr');
      if($(this).attr('id')==catId){
        $(this).addClass('cat_item_curr');
      }
    })
    jQuery('#frame').animate({"right": 0}, 500);
    setTimeout(function(){$('#shopBox').hide();},600)
    
}
function dataHide(){
    $('#shopBox').show();
    var dataHeight = $("#frame").css('height');
    var dataWidth = $("#frame").css('width');
    jQuery('#frame').animate({'right': '-'+dataWidth}, 500);
    jQuery('#cover').hide();
}


// 获取优惠券列表
function getcouponList(){
  $('#Load').show();
    loading = true;
    var param = {};
    param.catId = $('#catId').val();
    param.pagesize = 10;
    param.page = Number( $('#currPage').val() ) + 1;
    $.post(WST.AU('coupon://coupons/pageCouponQuery'), param, function(data){
        var json = WST.toJson(data);
        var html = '';
        if(json && json.data && json.data.length>0){
          var gettpl = document.getElementById('shopList').innerHTML;
          laytpl(gettpl).render(json.data, function(html){
            $('#order-box').append(html);
          });

          $('#currPage').val(json.current_page);
          $('#totalPage').val(json.last_page);
        }else{
           var mhtml = '<div class="wst-prompt-icon">';
           mhtml += '<img src="'+ window.conf.ROOT +'/addons/coupon/view/mobile/index/img/nothing-coupon.png"></div>';
           mhtml += '<div class="wst-prompt-info">'
           mhtml += '<p>暂无相关优惠券</p>';
           mhtml += '</div>';
          $('#order-box').html(mhtml);
        }
        WST.imgAdapt('j-imgAdapt');
        loading = false;
        $('#Load').hide();
        echo.init();//图片懒加载
    });
}

// 刷新列表页
function reFlashList(){
  $('#currPage').val('0');
  $('#order-box').html(' ');
  getcouponList();
}
// 领取优惠券
function getCoupon(couponId){
    $.post(WST.AU('coupon://coupons/receive'), {couponId:couponId}, function(data){
        var json = WST.toJson(data);
            if(json.status==1){
              WST.msg(json.msg,'success');
              //reFlashList();
            }else{
               WST.msg(json.msg,'info');
            }
    });
}
