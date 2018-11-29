var mmg;
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'会员等级图标', name:'userrankImg', width: 30,renderer:function(val,item,rowIndex){
            return '<img src="'+WST.conf.ROOT+'/'+item['userrankImg']+'" height="28px" />';
          }},
            {title:'会员等级名称', name:'rankName' ,width:100},
            {title:'积分下限', name:'startScore' ,width:100},
            {title:'积分上限', name:'endScore' ,width:60},
            {title:'操作', name:'' ,width:180, align:'center', renderer: function(val,item,rowIndex){
                var h = "";
                if(WST.GRANT.HYDJ_02)h += "<a  class='btn btn-blue' onclick='javascript:toEdit("+item['rankId']+")'><i class='fa fa-pencil'></i>修改</a> ";
                if(WST.GRANT.HYDJ_03)h += "<a  class='btn btn-red' onclick='javascript:toDel(" + item['rankId'] + ")'><i class='fa fa-trash-o'></i>删除</a> ";
                return h;
            }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-80,indexCol: true, cols: cols,method:'POST',
        url: WST.U('admin/userranks/pageQuery'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });   
}
function toEdit(id){
    location.href = WST.U('admin/userranks/toEdit','id='+id);
}
function toDel(id){
	var box = WST.confirm({content:"您确定要删除该记录吗?",yes:function(){
	           var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	           	$.post(WST.U('admin/userranks/del'),{id:id},function(data,textStatus){
	           			  layer.close(loading);
	           			  var json = WST.toAdminJson(data);
	           			  if(json.status=='1'){
	           			    	WST.msg("操作成功",{icon:1});
	           			    	layer.close(box);
	           		        mmg.load();
	           			  }else{
	           			    	WST.msg(json.msg,{icon:2});
	           			  }
	           		});
	            }});
}

function editInit(){
 /* 表单验证 */
    $('#userRankForm').validator({
            fields: {
                rankName: {
                  rule:"required",
                  msg:{required:"请输入会员等级名称"},
                  tip:"请输入会员等级名称",
                  ok:"",
                },
                userrankImg: {
                  rule:"required",
                  msg:{required:"请输上传会员图标"},
                  tip:"请输上传会员图标",
                  ok:"",
                }
                
            },

          valid: function(form){
            var params = WST.getParams('.ipt');
            var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
            $.post(WST.U('admin/userranks/'+((params.rankId==0)?"add":"edit")),params,function(data,textStatus){
              layer.close(loading);
              var json = WST.toAdminJson(data);
              if(json.status=='1'){
                  WST.msg("操作成功",{icon:1});
                  location.href=WST.U('Admin/userranks/index');
              }else{
                    WST.msg(json.msg,{icon:2});
              }
            });

      }

    });

//文件上传
WST.upload({
    pick:'#userranksPicker',
    formData: {dir:'userranks'},
    accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
    callback:function(f){
      var json = WST.toAdminJson(f);
      if(json.status==1){
      $('#uploadMsg').empty().hide();
      //保存上传的图片路径
      $('#userrankImg').val(json.savePath+json.thumb);
      $('#preview').html('<img src="'+WST.conf.ROOT+'/'+json.savePath+json.thumb+'" height="25" />');
      }else{
        WST.msg(json.msg,{icon:2});
      }
  },
  progress:function(rate){
      $('#uploadMsg').show().html('已上传'+rate+"%");
  }
});


};
  




		