var mmg;
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'名称', name:'title', width: 50,sortable:true},
            {title:'标识', name:'name', width: 10,sortable:true},
            {title:'描述', name:'description', width: 220,sortable:true},
            {title:'状态', name:'status', width: 10,sortable:true, renderer: function(val,item,rowIndex){
              	if(item['status']==0){
                    return "<span class='statu-wait'><i class='fa fa-ban'></i> "+item.statusName+"</span>";
            	}else if(item['status']==2){
            		return "<span class='statu-no'><i class='fa fa-ban'></i> "+item.statusName+"</span>";
            	}else{
                    return "<span class='statu-yes'><i class='fa fa-check-circle'></i> "+item.statusName+"</span>";
            	}
            }},
            {title:'作者', name:'author', width: 10,sortable:true},
            {title:'版本', name:'version', width: 5,sortable:true},
            {title:'操作', name:'' ,width:100, align:'center', renderer: function(val,item,rowIndex){
                var h = "";
	            if(WST.GRANT.CJGL_01 && item['status']>0 && item['isConfig']==1)h += "<a class='btn btn-blue' href='"+WST.U('admin/Addons/toEdit','id='+item['addonId'])+"'><i class='fa fa-gear'></i>设置</a> ";
	            if(WST.GRANT.CJGL_02 && item['status']==0)h += "<a class='btn btn-blue' href='javascript:install(" + item['addonId'] + ")'><i class='fa fa-gear'></i>安装</a> "; 
	            if(WST.GRANT.CJGL_03 && item['status']>0)h += "<a class='btn btn-red' href='javascript:uninstall(" + item['addonId'] + ")'><i class='fa fa-trash-o'></i>卸载</a> "; 
	            if(WST.GRANT.CJGL_04 && item['status']==2)h += "<a class='btn btn-blue' href='javascript:enable(" + item['addonId'] + ")'><i class='fa fa-check'></i>启用</a> "; 
	            if(WST.GRANT.CJGL_05 && item['status']==1)h += "<a class='btn btn-red' href='javascript:disable(" + item['addonId'] + ")'><i class='fa fa-ban'></i>禁用</a> ";
	            return h;
            }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-85,indexCol: true, cols: cols,method:'POST',
        url: WST.U('admin/addons/pageQuery'), fullWidthRows: true, autoLoad: true,remoteSort: true,sortName:'status',sortStatus:'desc',
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });  
}

//安装
function install(id){
	var loading = WST.msg('正在安装，请稍后...', {icon: 16,time:60000});
	$.post(WST.U('admin/addons/install'),{id:id},function(data,textStatus){
		layer.close(loading);
		var json = WST.toAdminJson(data);
		if(json.status=='1'){
	       	WST.msg("安装成功,请刷页面",{icon:1});
	        layer.close(loading);
	         addonsQuery();
		}else{
			WST.msg(json.msg,{icon:2});
	     }
	});
}

//卸载
function uninstall(id){
	var box = WST.confirm({content:"您确定要卸载吗?",yes:function(){
		var loading = WST.msg('正在卸载，请稍后...', {icon: 16,time:60000});
		$.post(WST.U('admin/addons/uninstall'),{id:id},function(data,textStatus){
			layer.close(loading);
			var json = WST.toAdminJson(data);
			if(json.status=='1'){
	        	WST.msg("卸载成功,请刷页面",{icon:1});
	         	layer.close(box);
	         	addonsQuery();
			}else{
				WST.msg(json.msg,{icon:2});
	     	}
		});
	}});
}

//禁用
function enable(id){
	var loading = WST.msg('正在启用，请稍后...', {icon: 16,time:60000});
	$.post(WST.U('admin/addons/enable'),{id:id},function(data,textStatus){
		layer.close(loading);
		var json = WST.toAdminJson(data);
		if(json.status=='1'){
	       	WST.msg("启用成功",{icon:1});
	        layer.close(loading);
	        addonsQuery();
		}else{
			WST.msg(json.msg,{icon:2});
	     }
	});
}

//启用
function disable(id){
	var loading = WST.msg('正在禁用，请稍后...', {icon: 16,time:60000});
	$.post(WST.U('admin/addons/disable'),{id:id},function(data,textStatus){
		layer.close(loading);
		var json = WST.toAdminJson(data);
		if(json.status=='1'){
	       	WST.msg("禁用成功",{icon:1});
	        layer.close(loading);
	        addonsQuery();
		}else{
			WST.msg(json.msg,{icon:2});
	     }
	});
}

//查询
function addonsQuery(){
	var query = WST.getParams('.query');
	query.page = 1;
	mmg.load(query);
}

