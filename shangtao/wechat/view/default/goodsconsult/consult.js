jQuery.noConflict();
// 获取商品咨询
function getgoodsConsultList(){
  $('#Load').show();
    loading = true;
    var param = {};
    param.goodsId = $('#goodsId').val();
    param.pagesize = 10;
    param.page = Number( $('#currPage').val() ) + 1;
    $.post(WST.U('wechat/goodsconsult/listQuery'), param, function(data){
        var json = WST.toJson(data);
        json = json.data;
        var html = '';
        if(json && json.data && json.data.length>0){
           var gettpl = document.getElementById('gcList').innerHTML;
          laytpl(gettpl).render(json.data, function(html){
            $('#_gcList').append(html);
          });
          $('#currPage').val(data.data.current_page);
          $('#totalPage').val(data.data.last_page);
        }else{
            html += '<div class="wst-prompt-icon"><img src="'+ window.conf.WECHAT +'/img/nothing-message.png"></div>';
            html += '<div class="wst-prompt-info">';
            html += '<p>暂无商品咨询~</p>';
            html += '<button class="ui-btn-s" onclick="javascript:consult();">我要咨询</button>';
            html += '</div>';
          $('#_gcList').html(html);
        }
        loading = false;
        $('#Load').hide();
        echo.init();//图片懒加载
    });
}
function consultListInit(){
  var currPage = totalPage = 0;
  var loading = false;
  $(document).ready(function(){
      getgoodsConsultList();
      var dataHeight = $("#frame").css('height');
      $("#frame").css('top',0);
       var dataWidth = $("#frame").css('width');
      $("#frame").css('right','-'+dataWidth);
      $(window).scroll(function(){  
          if (loading) return;
          if ((5 + $(window).scrollTop()) >= ($(document).height() - $(window).height())) {
              currPage = Number( $('#currPage').val() );
              totalPage = Number( $('#totalPage').val() );
              if( totalPage > 0 && currPage < totalPage ){
                getgoodsConsultList();
              }
          }
      });
  });
};
$(function(){WST.initFooter()});
/* 发布咨询 */
function consult(){
  var goodsId = $('#goodsId').val();
  location.href=WST.U('wechat/goodsconsult/consult',{goodsId:goodsId});
}
// 提交商品咨询
function consultCommit(){
  var params={};
  params.goodsId = $('#goodsId').val();
  params.consultType = $('#consultType').val();
  if(params.consultType<=0){
    WST.msg('请选择咨询类别','info');
    return;
  }
  params.consultContent = $('#consultContent').val();
  if(params.consultContent == ''){
    WST.msg('请输入咨询内容','info');
    return;
  }
  if(params.consultContent.length<3 || params.consultContent.length>200){
    WST.msg('咨询内容应为3-200个字','info');
    return;
  }
  WST.load('正在提交，请稍后...');
  $.post(WST.U('wechat/goodsconsult/add'),params,function(responData){
    WST.noload();
    var json = WST.toJson(responData);
    if(json.status==1){
       // 发布成功
       WST.msg(json.msg,'success');
       setTimeout(function(){
    	   history.back();
        },1000);
    }else{
      WST.msg(json.msg,'warn');
    }
  })
}