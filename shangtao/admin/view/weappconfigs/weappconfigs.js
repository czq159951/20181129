function edit(){
	if(!WST.GRANT.WX_GZHSZ_04)return;
	var params = WST.getParams('.ipt');
	var loading = WST.msg('正在保存数据，请稍后...', {icon: 16,time:60000});
    $.post(WST.U('admin/weappconfigs/edit'),params,function(data,textStatus){
          layer.close(loading);
          var json = WST.toAdminJson(data);
          if(json.status==1){
        	  WST.msg(json.msg,{icon:1});
          }
   });
}