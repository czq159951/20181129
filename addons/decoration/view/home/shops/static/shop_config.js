function decorationBuild(decorationId){
	var loading = WST.msg('正在生成页面，请稍后...', {icon: 16,time:60000});
	$.post(WST.AU('decoration://decoration/build'),{"id":decorationId,"rootPath":WST.conf.ROOT},function(data,textStatus){
    	layer.close(loading);
    	var json = WST.toJson(data);
    	if(json.status=='1'){
    		WST.msg("操作成功",{icon:1});
    	}else{
    		WST.msg(json.msg,{icon:2});
    	}
    });
}
function saveConf(decorationId){
	var loading = WST.msg('正在保存数据，请稍后...', {icon: 16,time:60000});
	var userDecoration = $("input[name='userDecoration']:checked").val();
	$.post(WST.AU('decoration://decoration/settingsave'),{"userDecoration":userDecoration},function(data,textStatus){
    	layer.close(loading);
    	var json = WST.toJson(data);
    	if(json.status=='1'){
    		WST.msg("操作成功",{icon:1});
    	}else{
    		WST.msg(json.msg,{icon:2});
    	}
    });
}