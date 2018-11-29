// 获取商城快讯
function getNewList($catId = ''){
  $('#Load').show();
    loading = true;
    var param = {};
    param.pagesize = 10;
    param.page = Number( $('#currPage').val() ) + 1;
    param.catId = $('#catId').val();
    $.post(WST.U('wechat/news/getNewsList'), param, function(data){
        var json = WST.toJson(data);
        var html = '';
        if(json && json.data && json.data.length>0){
           var gettpl = document.getElementById('newsList').innerHTML;
          laytpl(gettpl).render(json.data, function(html){
            $('#newsListBox').append(html);
          });
          $('#currPage').val(data.current_page);
          $('#totalPage').val(data.last_page);
        }else{
          html += '<ul class="ui-row-flex wst-flexslp">';
          html += '<li class="ui-col ui-flex ui-flex-pack-center"  style="margin-top:150px;">';
          html += '<p>暂无商城快讯</p>';
          html += '</li>';
          html += '</ul>';
          $('#newsListBox').html(html);
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
    getNewList();



    var dataHeight = $("#frame").css('height');
    $("#frame").css('top',0);
     var dataWidth = $("#frame").css('width');
    $("#frame").css('right','-'+dataWidth);

	  
    $(window).scroll(function(){  
        if (loading) return;
        if ((5 + $(window).scrollTop()) >= ($(document).height() - screen.height)) {
            currPage = Number( $('#currPage').val() );
            totalPage = Number( $('#totalPage').val() );
            if( totalPage > 0 && currPage < totalPage ){
            	getNewList();
            }
        }
    });
});
// 刷新列表页
function reFlashList(){
  $('#currPage').val('0');
  $('#newsListBox').html(' ');
  getNewList();
}

//弹框
function dataShow(){
    jQuery('#cover').attr("onclick","javascript:dataHide();").show();
    jQuery('#frame').animate({"right": 0}, 500);
}
function dataHide(){
    var dataHeight = $("#frame").css('height');
    var dataWidth = $("#frame").css('width');
    jQuery('#frame').animate({'right': '-'+dataWidth}, 500);
    jQuery('#cover').hide();
}

function viewNews(id){
  $.post(WST.U('wechat/news/getNews'),{id:id},function(data){
      var json = WST.toJson(data);
      $('#createTime').html(json.createTime);
      $('#articleTitle').html(json.articleTitle);
      $('#articleContent').html(json.articleContent);
      $('#likeNum').html(json.likeNum);
      $('#articleId').val(json.articleId);
      // 计算弹出层是否需要滚动条
      var sHeight = WST.pageHeight();
      var tHeight = $('#articleTitle').height();
      var cHeight = $('#articleContent').height();
      $('#content').css('height',sHeight-tHeight+'px');
      if(json.likeState == 1){
        $('#like1').hide();
        $('#like').show();
        $('#likeNum2').html(json.likeNum);
        $(".icon-like1").removeClass('icon-like1').addClass('icon-like2');
      }else{
        $('#like').hide();
        $('#like1').show();
        $(".icon-like2").removeClass('icon-like2').addClass('icon-like1');
      }
      dataShow();
  })
}
function like(){
    var articleId = $('#articleId').val();
    $.post(WST.U('wechat/News/like'),{id:articleId},function(data){
        var json = WST.toJson(data);
        if(json.status==1){
           $(".icon-like1").removeClass('icon-like1').addClass('icon-like2');
           $num = parseInt($('#likeNum').html());
           $num = $num+1;
           $('#like1').hide();
           $('#like').show();
           $('#likeNum2').html($num);
        }
    })
}