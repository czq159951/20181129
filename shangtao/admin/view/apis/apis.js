var mmg;
function initGrid(){
	var h = WST.pageHeight();
    var cols = [
            {title:'接口类型', name:'apiName', width: 50,isSort: false,renderer:function(val,item,rowIndex){
                return (item["apiType"]==0)?"APP":"小程序";
            }},
            {title:'接口名', name:'apiName', width: 50,isSort: false},
            {title:'接口说明', name:'apiDesc',width:300,isSort: false},
            {title:'接口颜色', name:'apiColor' ,width:20,align:'center',isSort: false},
            {title:'排序号', name:'apiSort',width:20,align:'center',isSort: false,renderer:function(val,item,rowIndex){
              return '<span style="cursor:pointer;" ondblclick="changeSort(this,'+item["id"]+');">'+val+'</span>';
          }},
            
            {title:'操作', name:'op',width:60, align:'center',renderer: function (val,item,rowIndex){
	        	var h = "";
	            if(WST.GRANT.API_APILB_03)h += "<a  class='btn btn-blue' href='"+WST.U('admin/Apis/toEdit','id='+item['id'])+"'><i class='fa fa-pencil'></i>修改</a> ";
	            if(WST.GRANT.API_APILB_03)h += "<a  class='btn btn-red' href='javascript:toDel(" + item['id'] + ")'><i class='fa fa-trash-o'></i>删除</a> ";
	            return h;
	        }}
            ];
 
    mmg = $('.mmg').mmGrid({height: (h-80),indexCol: true, cols: cols,method:'POST',
        url: WST.U('admin/Apis/pageQuery'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    }); 
}
function save(){
  $('#editForm').isValid(function(v){
     if(v){
         var params = WST.getParams('.ipt');
         var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
         $.post(WST.U('admin/apis/'+((params.id>0)?"edit":"add")),params,function(data,textStatus){
              layer.close(loading);
              var json = WST.toAdminJson(data);
              if(json.status=='1'){
                  WST.msg(json.msg,{icon:1},function(){
                      location.href = WST.U('admin/Apis/index');
                  });
              }else{
                  WST.msg(json.msg,{icon:2});
              }
         });
     }
  });
}
function toDel(id){
	var box = WST.confirm({content:"您确定要删除该记录吗?",yes:function(){
	           var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	           	$.post(WST.U('admin/Apis/del'),{id:id},function(data,textStatus){
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

var oldSort;
function changeSort(t,id){
    $(t).attr('ondblclick'," ");
    var html = "<input type='text' id='sort-"+id+"' style='width:30px;' onblur='doneChange(this,"+id+")' value='"+$(t).html()+"' />";
    $(t).html(html);
    $('#sort-'+id).focus();
    $('#sort-'+id).select();
    oldSort = $(t).html();
}
function doneChange(t,id){
    var sort = ($(t).val()=='')?0:$(t).val();
    if(sort==oldSort){
       $(t).parent().attr('ondblclick','changeSort(this,'+id+')');
       $(t).parent().html(parseInt(sort));
       return;
    }
    $.post(WST.U('admin/Apis/changeSort'),{id:id,adSort:sort},function(data){
       var json = WST.toAdminJson(data);
       if(json.status==1){
          $(t).parent().attr('ondblclick','changeSort(this,'+id+')');
          $(t).parent().html(parseInt(sort));
       }
    });
}
		
//查询
function apiQuery(){
	  mmg.load({page:1,key:$('#key').val(),apiType:$('#apiType').val()});
}