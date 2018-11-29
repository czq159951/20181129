jQuery.noConflict();
// 获取订单列表
function getOrderList(){
  $('#Load').show();
    loading = true;
    var param = {};
    param.type = $('#type').val();
    param.pagesize = 10;
    param.page = Number( $('#currPage').val() ) + 1;
    param.deliverType = -1;
    param.payType = -1;
    $.post(WST.U('wechat/orders/getSellerOrderList'), param, function(data){
        var json = WST.toJson(data);
        if(json.status>0){
          var html = '';
          json = json.data;
          if(json && json.data && json.data.length>0){
            var gettpl = document.getElementById('shopList').innerHTML;
            laytpl(gettpl).render(json.data, function(html){
              $('#order-box').append(html);
            });

            $('#currPage').val(json.current_page);
            $('#totalPage').val(json.last_page);
          }else{
              html += '<div class="wst-prompt-icon"><img src="'+ window.conf.WECHAT +'/img/nothing-order.png"></div>';
              html += '<div class="wst-prompt-info">';
              html += '<p>暂无相关订单</p>';
              html += '</div>';
              $('#order-box').html(html);
          }
          WST.imgAdapt('j-imgAdapt');
          loading = false;
          $('#Load').hide();
          echo.init();//图片懒加载
        }else{
          WST.msg(json.msg,'info');
        }
    });
}
var currPage = totalPage = 0;
var loading = false;
$(document).ready(function(){
  $('#type').val(jQuery("#shopBox li:first").attr("type"));
  jQuery("#shopBox li:first").addClass("tab-curr");
  getOrderList();
  WST.initFooter('user');
  // Tab切换卡
  $('.tab-item').click(function(){
      $(this).addClass('tab-curr').siblings().removeClass('tab-curr');
      var type = $(this).attr('type');
      $('#type').val(type);
      reFlashList();
  });
  // 弹出层
   $("#frame").css('top',0);

    $(window).scroll(function(){  
        if (loading) return;
        if ((5 + $(window).scrollTop()) >= ($(document).height() - screen.height)) {
            currPage = Number( $('#currPage').val() );
            totalPage = Number( $('#totalPage').val() );
            if( totalPage > 0 && currPage < totalPage ){
            	getOrderList();
            }
        }
    });
});

// 刷新列表页
function reFlashList(){
  $('#currPage').val('0');
  $('#order-box').html(' ');
  getOrderList();
}


// 拒收
function showEditMoneyBox(event){
    $("#wst-event3").attr("onclick","javascript:"+event);
    $("#editMoneyBox").dialog("show");
}

function editOrderMoney(oid){
  var newOrderMoney = $('#newOrderMoney').val();
  $.post(WST.U('wechat/orders/editOrderMoney'),{id:oid,orderMoney:newOrderMoney},function(data){
              hideDialog('#editMoneyBox');
              var json = WST.toJson(data);
              if(json.status>0){
                $('#newOrderMoney').val(' ');
            	WST.msg(json.msg,'success');
                setTimeout(function(){
                	reFlashList();
                },2000);
              }else{
                WST.msg(json.msg,'info');
              }
            });
}

// 退款
function showRefundBox(id){
    $.post(WST.U('wechat/orders/toShopRefund'),{id:id},function(data){
           var json = WST.toJson(data);
           $('#refundOid').html(json.orderNo);
           $('#realTotalMoney').html('¥ '+json.realTotalMoney);
           $('#refundMoney').html('¥ '+json.backMoney);
           $('#useScore').html(json.useScore);
           $('#scoreMoney').html('¥ '+json.scoreMoney);
           // 弹出层滚动条
           var clientH = WST.pageHeight();// 屏幕高度
           var boxheadH = $('#refund-boxTitle').height();// 弹出层标题高度
           var contentH = $('#refund-content').height(); // 弹出层内容高度
           $('#refund-content').css('height',clientH-boxheadH+'px');
           $("#wst-event8").attr("onclick","javascript:refund("+id+")");
           reFundDataShow();
    })
}
//弹框
function reFundDataHide(){
    $('#shopBox').show();
    var dataHeight = $("#refundFrame").css('height');
    var dataWidth = $("#refundFrame").css('width');
    jQuery('#refundFrame').animate({'right': '-'+dataWidth}, 500);
    jQuery('#cover').hide();
}
function reFundDataShow(){
    jQuery('#cover').attr("onclick","javascript:reFundDataHide();").show();
    jQuery('#refundFrame').animate({"right": 0}, 500);
    setTimeout(function(){$('#shopBox').hide();},600)
}
// 退款
function refund(id){

  var params = {};
      params.refundStatus = $('#refundStatus')[0].checked?1:-1;
      params.content = $.trim($('#shopRejectReason').val());
      params.id = id;
      if(params.refundStatus==-1 && params.content==''){
        WST.msg('请输入原因','info');
        return;
      }
      $.post(WST.U('wechat/orderrefunds/shoprefund'),params,function(data){
        var json = WST.toJson(data);
        if(json.status==1){
            WST.msg('操作成功','success');
            setTimeout(function(){
            	reFundDataHide();
            	reFlashList();
            },2000);
          }else{
            WST.msg(json.msg,'info');
          }
     });

}

//  隐藏对话框
function hideDialog(id){
  $(id).dialog("hide");
}

// 确认收货
function receive(oid){
  hideDialog('#wst-di-prompt');
  $.post(WST.U('wechat/orders/receive'),{id:oid},function(data){
      var json = WST.toJson(data);
      if(json.status==1){
	  	WST.msg(json.msg,'success');
	    setTimeout(function(){
	    	reFlashList();
	    },2000);
      }else{
        WST.msg(json.msg,'info');
      }
  });
}



/*********************** 订单详情 ****************************/
//弹框
function dataShow(){
    jQuery('#cover').attr("onclick","javascript:dataHide();").show();
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



function getOrderDetail(oid){
  $.post(WST.U('wechat/orders/getDetail'),{id:oid},function(data){
      var json = WST.toJson(data);
      if(json.status!=-1){
        var gettpl1 = document.getElementById('detailBox').innerHTML;
          laytpl(gettpl1).render(json, function(html){
            $('#content').html(html);
            // 弹出层滚动条
            var clientH = WST.pageHeight();// 屏幕高度
            var boxheadH = $('#boxTitle').height();// 弹出层标题高度
            var contentH = $('#content').height(); // 弹出层内容高度
            if((clientH-boxheadH) < contentH){
              $('#content').css('height',clientH-boxheadH+'px');
            }
            dataShow();
          });
      }else{
        WST.msg(json.msg,'info');
      }
  });
}
// 跳转到评价页
function toAppr(oid){
  location.href=WST.U('wechat/orders/orderappraise',{'oId':oid});
}
// 投诉
function complain(oid){
  location.href=WST.U('wechat/ordercomplains/complain',{'oId':oid});
}

//修改价格
function editPrice(orderNo){
  alert('修改价格');
}

// 发货
var deliverType
function toDeliver(id,type){
	deliverType = type
	if(type==0){
		delivery('orderDelivery('+id+')');
	}else{
		WST.dialog('确定发货吗？','orderDelivery('+id+')');
	}
}
function delivery(event){
    $("#wst-event0").attr("onclick","javascript:"+event);
    $("#deliveryBox").dialog("show");
}
function orderDelivery(oid){
	if(deliverType==0){
		hideDialog('#deliveryBox');
	}else{
		 WST.dialogHide('prompt');
	}
  $.post(WST.U('wechat/orders/deliver'),{id:oid,expressId:$('#expressId').val(),expressNo:$('#expressNo').val()},function(data){
        var json = WST.toJson(data);
        if(json.status>0){
	      	WST.msg(json.msg,'success');
	        setTimeout(function(){
	        	reFlashList();
	        },2000);
        }else{
          WST.msg(json.msg,'info');
        }
      });
}

/*************** 修改价格需要用到的方法 ******************/

 //只能輸入數字和小數點
 WST.isNumberdoteKey = function(evt){
  var e = evt || window.event; 
  var srcElement = e.srcElement || e.target;
  
  var charCode = (evt.which) ? evt.which : event.keyCode;     
  if (charCode > 31 && ((charCode < 48 || charCode > 57) && charCode!=46)){
    return false;
  }else{
    if(charCode==46){
      var s = srcElement.value;     
      if(s.length==0 || s.indexOf(".")!=-1){
        return false;
      }     
    }   
    return true;
  }
 }
 WST.limitDecimal = function(obj,len){
  var s = obj.value;
  if(s.indexOf(".")>-1){
    if((s.length - s.indexOf(".")-1)>len){
      obj.value = s.substring(0,s.indexOf(".")+len+1);
    }
  }
  s = null;
}