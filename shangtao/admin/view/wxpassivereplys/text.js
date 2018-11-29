var mmg;
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'关键字', name:'keyword', width: 100},
            {title:'回复内容', name:'content', width: 100},
            {title:'操作', name:'' ,width:70, align:'center', renderer: function(val,item,rowIndex){
                var h = "";
                if(WST.GRANT.WX_WBXX_02)h += "<a class='btn btn-blue' href='"+WST.U('admin/wxpassivereplys/textEdit','id='+item['id'])+"'><i class='fa fa-pencil'></i>修改</a> ";
                if(WST.GRANT.WX_WBXX_03)h += "<a class='btn btn-red' href='javascript:toDel(" + item['id'] + ")'><i class='fa fa-trash-o'></i>删除</a> "; 
                return h;
            }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-80,indexCol: true, cols: cols,method:'POST',
        url: WST.U('admin/wxpassivereplys/textPageQuery'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    }); 
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
	           		            addonsQuery();
	           			  }else{
	           			    	WST.msg(json.msg,{icon:2});
	           			  }
	           		});
	            }});
}

function textEditInit(){
 /* 表单验证 */
    $('#replyForm').validator({
            fields: {
                keyword: {
                  rule:"required",
                  msg:{required:"请输入关键字"},
                  tip:"请输入关键字",
                  ok:"",
                },
                content: {
                  rule:"required",
                  msg:{required:"请输入回复内容"},
                  tip:"请输入回复内容",
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
                  location.href=WST.U('Admin/wxpassivereplys/text');
              }else{
                    WST.msg(json.msg,{icon:2});
              }
            });

      }

    });




};
  
//查询
function addonsQuery(){
	var query = WST.getParams('.query');
	query.page = 1;
	mmg.load(query);
};



		