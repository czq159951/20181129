jQuery.noConflict();
//商品评价
function clickStar(obj){
    var index = $(obj).index(); // 当前选中的分数
    $(obj).parent().find('span').each(function(k,v){
        if(k<=index){
            $(this).removeClass('start-not').addClass('start-on');
        }else{
            $(this).removeClass('start-on').addClass('start-not');
        }
    })
    $(obj).parent().siblings().html(index+1+'分');
    $(obj).parent().siblings().attr('score',index+1);
}

function appraise(gId,sId,ogId,obj){

	$('.appraise').removeClass('score');
	$(obj).addClass('score');
	var gName = $(obj).parent().parent().find('.g-gName').html();

	var param = {};
	param.gId = gId;
	param.sId = sId;
	param.oId = $('#oId').val();
	param.orderGoodsId = ogId;
	$.post(WST.U('wechat/goodsappraises/getAppr'),param,function(data){
		var json = WST.toJson(data);
		if(json.status==1){
			var gettpl = document.getElementById('appraises-box').innerHTML;
			json.data.goodsName = gName;
			json.data.goodsId = gId;
			json.data.goodsSpecId = sId;
			json.data.orderGoodsId = ogId;
	          laytpl(gettpl).render(json.data, function(html){
	          	$('div[id^="appBox_"]').html(' ');
	            $('#appBox_'+ogId).html(html);
	          });
	        if(json.data.serviceScore=='')userAppraiseInit();
		}else{
			WST.msg('请求出错','info');
		}
	})
}
function saveAppr(gId,sId,ogId){
	var content = $.trim($('#content').val());
	if(content==''){
		WST.msg('评价内容不能为空','info');
		return
	}
	var param = {};
	param.content = content;
	param.goodsId = gId;
	param.goodsSpecId = sId;
	param.orderId = $('#oId').val();
	param.timeScore = $('#timeScore').attr('score');
	param.goodsScore = $('#goodsScore').attr('score');
	param.serviceScore = $('#serviceScore').attr('score');
	param.orderGoodsId = ogId;

	var imgs = [];
	//  是否有上传附件
	$('.imgSrc').each(function(k,v){
		imgs.push($(this).attr('v'));
	})
	imgs = imgs.join(',');
	if(imgs!='')
	param.images = imgs;

	$.post(WST.U('wechat/goodsappraises/add'),param,function(data){
		var json = WST.toJson(data);
		if(json.status==1){
			WST.msg(json.msg,'success');
			setTimeout(function(){location.reload();},1000);
		}else{
			WST.msg(json.msg);
		}
	})

}
$(function(){
	WST.initFooter('user');
	WST.imgAdapt('j-imgAdapt');
})


/*************** 上传图片 *****************/
function userAppraiseInit(){
   var uploader =WST.upload({
        pick:'#filePicker',
        formData: {dir:'appraises',isThumb:1},
        fileNumLimit:5,
        accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
        callback:function(f,file){
          var json = WST.toJson(f);
          if(json.status==1){
          var tdiv = $("<li>"+
                       "<img class='imgSrc' src='"+WST.conf.ROOT+"/"+json.savePath+json.thumb+"' v='"+json.savePath+json.name+"'></li>");
          var btn = $('<div class="del-btn"><span class="ui-icon-delete"></span></div>');
          tdiv.append(btn);
          $('#edit_chart').append(tdiv);
          btn.on('click','span',function(){
            uploader.removeFile(file);
            $(this).parent().parent().remove();
            uploader.refresh();
          });
          }else{
            WST.msg(json.msg,{icon:2});
          }
      },
      progress:function(rate){
          $('#uploadMsg').show().html('已上传'+rate+"%");
      }
    });
}
