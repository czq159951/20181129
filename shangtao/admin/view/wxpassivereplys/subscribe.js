var mmg;
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'图片', name:'picUrl', width: 60, renderer: function(val,item,rowIndex){
                return (WST.blank(item['picUrl'],'')!='')?"<img width='60' src='"+WST.conf.ROOT+"/"+item['picUrl']+"'/>":"";
            }},
            {title:'内容', name:'content', width: 500},
            {title:'类型', name:'msgType', width: 50, renderer: function(val,item,rowIndex){
                return (item['msgType']=='news')?"图文":"文字";
            }},
            {title:'序号', name:'subscribeSort', width: 50, renderer: function(val,item,rowIndex){
                return '<input size="5" type="text" id="subscribeSort_'+item['id']+'" value="'+item['subscribeSort']+'" onblur="javascript:editSort('+item['id']+',this.value)"/>';
            }},
            {title:'操作', name:'' ,width:70, align:'center', renderer: function(val,item,rowIndex){
                var h = "";
                if(WST.GRANT.WX_GZHF_02 && item['dataSrc']==1)h += "<a class='btn btn-blue' href='javascript:toEdit("+item['id']+")'><i class='fa fa-pencil'></i>修改</a> ";
                if(WST.GRANT.WX_GZHF_03)h += "<a class='btn btn-red' href='javascript:toDel(" + item['id'] + ")'><i class='fa fa-trash-o'></i>删除</a> "; 
                return h;
            }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-80,indexCol: true, cols: cols,method:'POST',
        url: WST.U('admin/wxpassivereplys/pagSubscribeQuery'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    }); 
}
function toDel(id){
	var box = WST.confirm({content:"您确定要删除该关注回复记录吗?",yes:function(){
	           var loading = WST.msg('正在提交请求，请稍后...', {icon: 16,time:60000});
	           	$.post(WST.U('admin/wxpassivereplys/delSubscribe'),{id:id},function(data,textStatus){
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
function toEdit(id){
    var loading = WST.msg('正在请求数据，请稍后...', {icon: 16,time:60000});
    $.post(WST.U('admin/wxpassivereplys/getById'),{id:id},function(data,textStatus){
        layer.close(loading);
        var json = WST.toAdminJson(data);
        if(json.status==1){
            editBox(json.data);
        }else{
            WST.msg(json.msg,{icon:2});
        }
        
    });
}
function editSort(id,no){
   var loading = WST.msg('正在请求数据，请稍后...', {icon: 16,time:60000});
    $.post(WST.U('admin/wxpassivereplys/editSubscribeSort'),{id:id,subscribeSort:no},function(data,textStatus){
        layer.close(loading);
        var json = WST.toAdminJson(data);
        if(json.status==1){
            WST.msg("操作成功",{icon:1});
            addonsQuery();
        }else{
            WST.msg(json.msg,{icon:2});
        }
        
    });
}
var rb;
function openResource(){
   rb = WST.open({type: 1,title:"选择内容",shade: [0.6, '#000'],offset:'50px',border: [0],content:'<div class="wst-grid"><div id="boxmmg" class="mmg"></div><div id="boxpg" style="text-align: right;"></div></div>',area: ['800px', '500px'],success:function(){
       initBoxGrid();
   }});
}  
function editBox(obj){
   var w = WST.open({type: 1,title:"回复内容",shade: [0.6, '#000'],offset:'50px',border: [0],content:'<textarea id="boxContent" style="margin-left:3px;width:99%;height:95%">'+obj.content+'</textarea>',area: ['550px', '280px'],btn:['确定','取消'],
           yes:function(index, layero){
            var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
            $.post(WST.U('admin/wxpassivereplys/'+((obj.id==0)?"addSubscribe":"editSubscribe")),{id:obj.id,content:$('#boxContent').val(),msgType:'text',dataSrc:1,isSubscribe:1},function(data,textStatus){
                layer.close(loading);
                var json = WST.toAdminJson(data);
                if(json.status=='1'){
                    WST.msg("操作成功",{icon:1});
                    layer.close(w);
                    addonsQuery();
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            });
    }});
}
//查询
function addonsQuery(){
	var query = WST.getParams('.query');
	query.page = 1;
	mmg.load(query);
};

function initBoxGrid(){
    var cols = [
            {title:'关键字', name:'keyword', width: 60},
            {title:'标题/内容', name:'content', width: 200, renderer: function(val,item,rowIndex){
                return (item['msgType']=='news')?item['title']:item['content'];
            }},
            {title:'类型', name:'msgType', width: 50, renderer: function(val,item,rowIndex){
                return (item['msgType']=='news')?"图文":"文字";
            }},
            {title:'操作', name:'' ,width:70, align:'center', renderer: function(val,item,rowIndex){
                return "<a class='btn btn-blue' href='javascript:selectBox("+item['id']+")'><i class='fa fa-check'></i>选择</a> ";
            }}
            ];
 
    $('#boxmmg').mmGrid({height: 410,indexCol: true, cols: cols,method:'POST',
        url: WST.U('admin/wxpassivereplys/pagNoSubscribeQuery'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#boxpg').mmPaginator({})
        ]
    }); 
}
function selectBox(id){
  var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
  $.post(WST.U('admin/wxpassivereplys/selectSubscribe'),{id:id},function(data,textStatus){
      layer.close(loading);
      var json = WST.toAdminJson(data);
      if(json.status=='1'){
          WST.msg("操作成功",{icon:1});
          layer.close(rb);
          addonsQuery();
      }else{
          WST.msg(json.msg,{icon:2});
      }
  });
}



		