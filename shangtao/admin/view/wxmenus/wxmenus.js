var grid;
function initGrid(){
	grid = $('#maingrid').WSTGridTree({
		url:WST.U('admin/wxmenus/pageQuery'),
		pageSize:10000,
		pageSizeOptions:[10000],
		height:'99%',
        width:'100%',
        minColToggle:6,
        delayLoad :true,
        rownumbers:true,
        columns: [
	        { display: '分类名称',width:120,name: 'menuName', id:'menuId', align: 'left',isSort: false},
	        { display: '页面地址',name: 'menuUrl',isSort: false,
        	render: function (rowdata){
	            var m = "<div class='urled' style='word-wrap: break-word;padding:6px;'>"+rowdata.menuUrl+"</div>";
	            return m;
        	}},
	        { display: '类型', name: 'type',width: 100,isSort: false,
	        	render: function (rowdata){
	        		if(rowdata['menuType']==0)t = "";
			        if(rowdata['menuType']==1)t = "点击推送";
			        if(rowdata['menuType']==2)t = "跳转地址";
			        if(rowdata['menuType']==3)t = "扫码推送";
			        if(rowdata['menuType']==4)t = "扫码推送且弹出“消息接收中”提示框";
			        if(rowdata['menuType']==5)t = "系统拍照发图";
			        if(rowdata['menuType']==6)t = "拍照或者相册发图";
			        if(rowdata['menuType']==7)t = "微信相册发图";
			        if(rowdata['menuType']==8)t = "地理位置选择";
			        if(rowdata['menuType']==9)t = "下发消息（除文本消息）";
			        if(rowdata['menuType']==10)t = "图文消息地址";
		            return t;
	        	}},
	        { display: '序号', name: 'menuSort',width: 80,isSort: false},
	        { display: '操作', name: 'op',width: 150,isSort: false,
	        	render: function (rowdata){
		            var h = "";
			        if(WST.GRANT.WX_ZDYCD_01)if(rowdata['parentId']==0)h += "<a class='btn btn-green' href='javascript:toEdit("+rowdata["menuId"]+",0)'><i class='fa fa-plus'></i>新增子菜单</a> ";
		            if(WST.GRANT.WX_ZDYCD_02)h += "<a class='btn btn-blue' href='javascript:toEdit("+rowdata["parentId"]+","+rowdata["menuId"]+")'><i class='fa fa-pencil'></i>修改</a> ";
		            if(WST.GRANT.WX_ZDYCD_03)h += "<a class='btn btn-red' href='javascript:toDel("+rowdata["parentId"]+","+rowdata["menuId"]+")'><i class='fa fa-trash-o'></i>删除</a> ";
		            return h;
	        	}}
        ]
    });
}
//与微信菜单同步
function wxSynchro(){
	var box = WST.confirm({content:"您确定与微信菜单同步吗?",yes:function(){
        var loading = WST.msg('正在同步数据，请稍后...', {icon: 16,time:60000});
        $.post(WST.U('admin/wxmenus/synchroWx'),'',function(data,textStatus){
        			  layer.close(loading);
        			  var json = WST.toAdminJson(data);
        			  if(json.status=='1'){
      		            	inView();
        			    	WST.msg(json.msg,{icon:1});
        			    	layer.close(box);
        		            grid.reload();
        			  }else{
        			    	WST.msg(json.msg,{icon:2});
        			  }
        		});
         }});
}
//同步到微信菜单
function adSynchro(){
	var box = WST.confirm({content:"您确定同步到微信菜单吗?",yes:function(){
        var loading = WST.msg('正在同步数据，请稍后...', {icon: 16,time:60000});
        $.post(WST.U('admin/wxmenus/synchroAd'),'',function(data,textStatus){
        			  layer.close(loading);
        			  var json = WST.toAdminJson(data);
        			  if(json.status=='1'){
        			    	WST.msg(json.msg,{icon:1});
        			    	layer.close(box);
        		            grid.reload();
        			  }else{
        			    	WST.msg(json.msg,{icon:2});
        			  }
        		});
         }});
}
function toEdit(parentId,menuId){
	location.href=WST.U('admin/wxmenus/toEdit','menuId='+menuId+'&parentId='+parentId);
}
function wayChange(type){
	if(type==1){
		WST.showHide(1,'#urltext');
		WST.showHide('','.newstext');
	}else{
		WST.showHide('','#urltext');
		WST.showHide(1,'.newstext');
	}
}
function matChange(n){
	$("#view"+n).show().siblings('.j-view').hide();
}
//素材选择
function addMaterial(n){
	var title = '选择文本素材';
	if(n==2)title= '选择图文素材';
	if(n==3)title= '选择图文素材';
	if(n==4)title= '选择语音素材';
	if(n==5)title= '选择视频素材';
	var box = WST.open({title:title,type:1,content:$('#wxmenusBox'),area: ['800px', '500px'],btn:['确定','取消'],yes:function(){
	          }});
}
function toEdits(id){
    var params = WST.getParams('.ipt');
    var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	$.post(WST.U('admin/wxmenus/'+((id>0)?"edit":"add")),params,function(data,textStatus){
		  layer.close(loading);
		  var json = WST.toAdminJson(data);
		  if(json.status=='1'){
	            WST.msg(json.msg,{icon:1},function(){
	            	location.href=WST.U('admin/wxmenus/index');
	            });
		  }else{
		        WST.msg(json.msg,{icon:2});
		  }
	});
}
function toDel(pid,id){
	var box = WST.confirm({content:"您确定要删除该菜单吗?",yes:function(){
	           var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	           	$.post(WST.U('admin/wxmenus/del'),{id:id},function(data,textStatus){
	           			  layer.close(loading);
	           			  var json = WST.toAdminJson(data);
	           			  if(json.status=='1'){
	           		           inView();
	           			       WST.msg(json.msg,{icon:1});
	           			       layer.close(box);
	           		           grid.reload(pid);
	           			  }else{
	           			       WST.msg(json.msg,{icon:2});
	           			  }
	           		});
	            }});
}
function inView(){
    $.post(WST.U('admin/wxmenus/listQuery'),'',function(data,textStatus){
		  var json = WST.toAdminJson(data);
		  $("#list").html('');
		  if(json && json.length>0){
			var html = [];
		    for(var i=0;i<json.length;i++){
		       	 var me = json[i];
		       	 html.push('<div class="li" onclick="javascript:liSelected(this);">'+WST.cutStr(me.menuName,8));
		       	 html.push('<div class="lis" style="display:none;">');
		       	 if(me.listSon.length>0){
		       		for(var s=0;s<me.listSon.length;s++){
				         html.push('<span class="list">'+WST.cutStr(me.listSon[s].menuName,8)+'</span>');
		       		}
		       	 }
		         html.push("</div>");
		         html.push("</div>");
		    }
		    $("#list").html(html.join(""));
		  }
	});
}
function liSelected(obj){
	$(obj).addClass('selected').children('.lis').show();
	$(obj).siblings().removeClass('selected').children('.lis').hide();
}
$(function(){
	var windowH = $(window).height();  
	var windowW = $(window).width();  
	$('.urled').css('width',windowW/4);
	  $('.wst-views').css('height',windowH-$('#alertTips').height()-$('.wst-toolbar').height()-30);
	$('.wst-maingr').css('width',windowW-335);
})
$(window).resize(function(){
	var windowH = $(window).height();  
	var windowW = $(window).width();  
	$('.urled').css('width',windowW/4);
	  $('.wst-views').css('height',windowH-$('#alertTips').height()-$('.wst-toolbar').height()-30);
	$('.wst-maingr').css('width',windowW-335);
})