// 获取浏览记录
function getHistory(){
  $('#Load').show();
    loading = true;
    var param = {};
    param.pagesize = 10;
    param.page = Number( $('#currPage').val() ) + 1;
    $.post(WST.U('wechat/goods/historyQuery'), param, function(data){
        var json = WST.toJson(data);
        var html = '';
        if(json && json.data && json.data.length>0){
           var gettpl = document.getElementById('list').innerHTML;
          laytpl(gettpl).render(json.data, function(html){
            $('#listBox').append(html);
          });
          $('#currPage').val(data.current_page);
          $('#totalPage').val(data.last_page);
        }else{
          html += '<div class="wst-prompt-icon"><img src="'+ window.conf.WECHAT +'/img/nothing-history.png"></div>';
          html += '<div class="wst-prompt-info">';
          html += '<p>暂无浏览记录</p>';
          html += '<button class="ui-btn-s" onclick="javascript:WST.intoIndex();">去逛逛</button>';
          html += '</div>';
          $('#listBox').html(html);
        }
        WST.imgAdapt('j-imgAdapt');
        loading = false;
        $('#Load').hide();
        echo.init();//图片懒加载
    });
}
var currPage = totalPage = 0;
var loading = false;
$(document).ready(function(){
    WST.initFooter();
    getHistory();
    $(window).scroll(function(){  
        if (loading) return;
        if ((5 + $(window).scrollTop()) >= ($(document).height() - screen.height)) {
            currPage = Number( $('#currPage').val() );
            totalPage = Number( $('#totalPage').val() );
            if( totalPage > 0 && currPage < totalPage ){
            	getHistory();
            }
        }
    });
});

