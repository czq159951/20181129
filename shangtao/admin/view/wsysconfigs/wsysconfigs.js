$(function(){
	WST.upload({
		  k:"wxAppLogo",
	  	  pick:"#wxAppLogoPicker",
	  	  formData: {dir:'sysconfigs'},
	  	  accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
	  	  callback:function(f){
	  		  var json = WST.toAdminJson(f);
	  		  if(json.status==1){
	  			 $('#wxAppLogoMsg').empty().hide();
	  			 $('#wxAppLogoPrevw').empty();
	  			 $('#wxAppLogoPrevw').html('<img src="'+WST.conf.ROOT+'/'+json.savePath+json.name+'" width="120" hiegth="120"/>');
	  			 $('#wxAppLogo').val(json.savePath+json.name);
	  		  }
		  },
		  progress:function(rate){
			  $('#'+this.k+'Msg').show().html('已上传'+rate+"%");
		  }
	    });
})
function edit(){
	if(!WST.GRANT.WX_GZHSZ_04)return;
	var params = WST.getParams('.ipt');
	var loading = WST.msg('正在保存数据，请稍后...', {icon: 16,time:60000});
    $.post(WST.U('admin/wsysconfigs/edit'),params,function(data,textStatus){
          layer.close(loading);
          var json = WST.toAdminJson(data);
          if(json.status==1){
        	  WST.msg(json.msg,{icon:1});
          }
   });
}