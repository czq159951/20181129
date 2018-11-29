//消息列表
function getMessages(){
  $('#Load').show();
  loading = true;
  var param = {};
  param.pagesize = 12;
  param.page = Number( $('#currPage').val() ) + 1;
  $.post(WST.U('wechat/messages/pageQuery'), param, function(data){
      var json = WST.toJson(data);
      var mhtml = '';
      if(json && json.data && json.data.length>0){
          var gettpl = document.getElementById('msgList').innerHTML;
          laytpl(gettpl).render(json.data, function(html){
            $('#info-list').append(html);
          });
          $('#currPage').val(json.current_page);
          $('#totalPage').val(json.last_page);
          
      }else{
    	  mhtml += '<div class="wst-prompt-icon"><img src="'+ window.conf.WECHAT +'/img/nothing-message.png"></div>';
        mhtml += '<div class="wst-prompt-info">';
        mhtml += '<p>对不起，没有相关消息。</p>';
        mhtml += '</div>';
		  $('.info-prompt').append(mhtml);
    }
      loading = false;
      $('#Load').hide();
  });
}
//返回消息列表
function returnInfo(){
	$('#info_details').hide();
	$('#info_list').show();
}

// 全选
function checkAll(obj){
  var chk = $(obj).attr('checked');
  $('.active').each(function(k,v){
    $(this).prop('checked',chk);
  });
}
//消息详情
function getMsgDetails(id){
	$('#info_list').hide();
	$('#info_details').show();
	$('.j-icon_'+id).addClass('wst-info_ico1').removeClass('wst-info_ico');
    $.post(WST.U('wechat/messages/getById'), {msgId:id}, function(data){
        var json = WST.toJson(data);
        if(json){
            $('.wst-info_detime').html(json.createTime);
            $('.wst-info_decontent').html(json.msgContent);
        }
        json = null;
    });
}
var msgIdsToDel=new Array();//要删除的消息的id 数组
//去删除商城消息
function toDelMsg(){
  var msgIds = new Array();
  $('.active').each(function(k,v){
    if($(this).attr('checked')){
      msgIds.push($(this).attr('msgid'));
    }
  });
  msgIdsToDel = msgIds;
  if(msgIds.join(',')==''){
    WST.msg('请选择要删除的消息','info');
    return false;
  }
  WST.dialog('确定要删除选中的消息吗？','delMsg()');
}
var vn ='';
//删除商城消息
function delMsg(){
  WST.dialogHide('prompt');
  $.post(WST.U('wechat/messages/del'), {ids:msgIdsToDel}, function(data){
      var json = WST.toJson(data);
      if(json.status==1){
		  WST.msg(json.msg,'success');
      $('#currPage').val(0)
      $('#info-list').html(' ');
      getMessages();
      }else{
    	  WST.msg(json.msg,'warn');
      }
  });
}
var currPage = totalPage = 0;
var loading = false;
$(document).ready(function(){
	getMessages();
    $(window).scroll(function(){  
        if (loading) return;
        if ((5 + $(window).scrollTop()) >= ($(document).height() - screen.height)) {
            currPage = Number( $('#currPage').val() );
            totalPage = Number( $('#totalPage').val() );
            if( totalPage > 0 && currPage < totalPage ){
            	getMessages();
            }
        }
    });
});