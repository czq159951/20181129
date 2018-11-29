$(function(){
	getcouponList();
})
function couponListShow(){
	$("body").css("overflow", "hidden");
	jQuery('#cover').attr("onclick","javascript:couponListHide();").show();
	jQuery('#gcoupon_listbox').animate({"bottom": 0}, 500);
}
function couponListHide(){
	$("body").css("overflow", "auto");
	var coulinLIstHeight = parseInt($("#gcoupon_listbox").css('height'))+52+'px';
	jQuery('#gcoupon_listbox').animate({'bottom': '-'+coulinLIstHeight}, 500);
	jQuery('#cover').hide();
}
// 获取优惠券列表
function getcouponList(){
    var param = {};
    param.goodsId = $('#goodsId').val();
    $.post(WST.AU('coupon://coupons/getCouponsByGoods'), param, function(data){
        var json = WST.toJson(data);
        var html = '';
        if(json && json.status==1 && json.data!=undefined){
          // 显示优惠券选项
          $('#j-coupon').show();
          // 显示排序靠前的两个优惠券信息
          var _obj = json.data[0];
          var txt1 = _obj.useCondition==0?_obj.couponValue+'元无门槛券':'消费满'+_obj.useMoney+'立减'+_obj.couponValue;
          $('#coupon_txt1').html(txt1);

          if(json.data.length>1){
          	  _obj = json.data[1];
          	  $('#gc_item2').show();
          	  var txt2 = _obj.useCondition==0?_obj.couponValue+'元无门槛券':'消费满'+_obj.useMoney+'立减'+_obj.couponValue;
			        $('#coupon_txt2').html(txt2);
          }

          var gettpl = document.getElementById('gcoupon_script').innerHTML;
          laytpl(gettpl).render(json.data, function(html){
            $('.gcl_content').html(html);
          });
          $('#currPage').val(json.current_page);
          $('#totalPage').val(json.last_page);
        }else{
           var mhtml = '<ul class="ui-row-flex wst-flexslp">';
           mhtml += '<li class="ui-col ui-flex ui-flex-pack-center">';
           mhtml += '<p>暂无相关优惠券</p>';
           mhtml += '</li>';
           mhtml += '</ul>';
          $('.gcl_content').html(mhtml);
        }
    });
}
// 领取优惠券
function getCoupon(couponId){
    $.post(WST.AU('coupon://coupons/receive'), {couponId:couponId}, function(data){
        var json = WST.toJson(data);
            if(json.status==1){
              WST.msg(json.msg,'success');
            }else{
               WST.msg(json.msg,'info');
            }
    });
}