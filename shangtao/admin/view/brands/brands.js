var mmg;
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'品牌图标', name:'img', width: 100, renderer: function(val,item,rowIndex){
                return "<span class='weixin'><img id='img' onmouseout='toolTip()' onmouseover='toolTip()' style='height:40px;width:40px;' src='"+WST.conf.ROOT+"/"+item['brandImg']
            	+"'><span class='imged' style='left:45px;' ><img  style='height:150px;width:150px;' src='"+WST.conf.ROOT+"/"+item['brandImg']+"'></span></span>";
            }},
            {title:'品牌名称', name:'brandName', width: 100},
            {title:'品牌介绍', name:'brandDesc', width: 100,renderer: function(val,item,rowIndex){
                return "<span  ><p class='wst-nowrap'>"+item['brandDesc']+"</p></span>";
            }},
            { title: '排序号', name: 'sortNo',isSort: false,renderer: function(val,item,rowIndex){
                return '<span style="color:blue;cursor:pointer;" ondblclick="changeSort(this,'+item["brandId"]+');">'+val+'</span>';
            }},
            {title:'操作', name:'' ,width:70, align:'center', renderer: function(val,item,rowIndex){
                var h = "";
		        if(WST.GRANT.PPGL_02)h += "<a class='btn btn-blue' href='javascript:toEdit("+item["brandId"]+")'><i class='fa fa-pencil'></i>修改</a> ";
		        if(WST.GRANT.PPGL_03)h += "<a class='btn btn-red' href='javascript:toDel("+item["brandId"]+")'><i class='fa fa-trash-o'></i>删除</a> "; 
		        return h;
            }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-85,indexCol: true,indexColWidth:50, cols: cols,method:'POST',
        url: WST.U('admin/brands/pageQuery'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    }); 
}

function loadGrid(){
	mmg.load({page:1,key:$('#key').val(),id:$('#catId').val()});
}

function toEdit(id){
	location.href=WST.U('admin/brands/toEdit','id='+id);
}

function toEdits(id){
    var params = WST.getParams('.ipt');
    params.id = id;
    var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	$.post(WST.U('admin/brands/'+((id>0)?"edit":"add")),params,function(data,textStatus){
		  layer.close(loading);
		  var json = WST.toAdminJson(data);
		  if(json.status=='1'){
		    	WST.msg(json.msg,{icon:1});
		        setTimeout(function(){ 
			    	location.href=WST.U('admin/brands/index');
		        },1000);
		  }else{
		        WST.msg(json.msg,{icon:2});
		  }
	});
}

function toDel(id){
	var box = WST.confirm({content:"您确定要删除该品牌吗?",yes:function(){
	           var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	           	$.post(WST.U('admin/brands/del'),{id:id},function(data,textStatus){
	           			  layer.close(loading);
	           			  var json = WST.toAdminJson(data);
	           			  if(json.status=='1'){
	           			    	WST.msg(json.msg,{icon:1});
	           			    	layer.close(box);
	           		            loadGrid();
	           			  }else{
	           			    	WST.msg(json.msg,{icon:2});
	           			  }
	           		});
	            }});
}
function toolTip(){
    $('body').mousemove(function(e){
    	var windowH = $(window).height();  
        if(e.pageY >= windowH*0.8){
        	var top = windowH*0.233;
        	$('.imged').css('margin-top',-top);
        }else{
        	var top = windowH*0.06;
        	$('.imged').css('margin-top',-top);
        }
    });
}


var oldSort;
function changeSort(t,id){
    $(t).attr('ondblclick'," ");
    var html = "<input type='text' id='sort-"+id+"' style='width:30px;padding:2px;' onblur='doneChange(this,"+id+")' value='"+$(t).html()+"' />";
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
    $.post(WST.U('admin/brands/changeSort'),{id:id,sortNo:sort},function(data){
        var json = WST.toAdminJson(data);
        if(json.status==1){
            $(t).parent().attr('ondblclick','changeSort(this,'+id+')');
            $(t).parent().html(parseInt(sort));
        }
    });
}