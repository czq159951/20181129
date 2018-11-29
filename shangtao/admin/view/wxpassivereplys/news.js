
function listQuery(){
  var loading = WST.msg('正在获取数据，请稍后...', {icon: 16,time:60000});
  $.post(WST.U('admin/wxpassivereplys/newsPageQuery'),{},function(data,textStatus){
    layer.close(loading);
    var json = WST.toAdminJson(data);
    if(json.status=='1'){
      var gettpl = document.getElementById('tblist').innerHTML;
          layui.laytpl(gettpl).render(json.data, function(html){
            $('#maingrid').html(html);
          });
    }
  });
}


function toEdit(id){
  location.href=WST.U('admin/wxpassivereplys/newsEdit',{'id':id});
}
function toDel(id){
	var box = WST.confirm({content:"您确定要删除该记录吗?",yes:function(){
	           var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	           	$.post(WST.U('admin/wxpassivereplys/del'),{id:id},function(data,textStatus){
	           			  layer.close(loading);
	           			  var json = WST.toAdminJson(data);
	           			  if(json.status=='1'){
	           			    	WST.msg("操作成功",{icon:1});
	           			    	layer.close(box);
	           		            listQuery();
	           			  }else{
	           			    	WST.msg(json.msg,{icon:2});
	           			  }
	           		});
	            }});
}

function newsEditInit(){
 /* 表单验证 */
    $('#replyForm').validator({
            fields: {
                keyword: {
                  rule:"required",
                  msg:{required:"请输入关键字"},
                  tip:"请输入关键字",
                  ok:"",
                },
                title: {
                  rule:"required",
                  msg:{required:"请输入标题"},
                  tip:"请输入标题",
                  ok:"",
                },
                description: {
                  rule:"required",
                  msg:{required:"请输入描述"},
                  tip:"请输入描述",
                  ok:"",
                },
                picUrl: {
                  rule:"required",
                  msg:{required:"封面图片不能为空"},
                  tip:"封面图片不能为空",
                  ok:"",
                },
                content: {
                  rule:"required",
                  msg:{required:"请输入回复内容"},
                  tip:"请输入回复内容",
                  ok:"",
                },
                url: {
                  rule:"required",
                  msg:{required:"请输入图文链接"},
                  tip:"请输入图文链接",
                  ok:"",
                }
                
            },

          valid: function(form){
            var params = WST.getParams('.ipt');
            var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
            $.post(WST.U('admin/wxpassivereplys/'+((params.id==0)?"add":"edit")),params,function(data,textStatus){
              layer.close(loading);
              var json = WST.toAdminJson(data);
              if(json.status=='1'){
                  WST.msg("操作成功",{icon:1});
                  location.href=WST.U('Admin/wxpassivereplys/news');
              }else{
                    WST.msg(json.msg,{icon:2});
              }
            });

      }

    });


//文件上传
WST.upload({
    pick:'#adFilePicker',
    formData: {dir:'wechat'},
    accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
    callback:function(f){
      var json = WST.toAdminJson(f);
      if(json.status==1){
        $('#uploadMsg').empty().hide();
        $('#picUrl').val(WST.conf.ROOT+'/'+json.savePath+json.thumb);
        $('#preview').html('<img src="'+WST.conf.ROOT+'/'+json.savePath+json.thumb+'" height="75" />');
      }else{
          WST.msg(json.msg,{icon:2});
      }
  },
  progress:function(rate){
      $('#uploadMsg').show().html('已上传'+rate+"%");
  }
});



};
  




		