var mmg,isInitUpload = false;
function initGrid(staffId){
    var h = WST.pageHeight();
    var cols = [
            {title:'图标', name:'btnImg', width: 50,renderer: function(val,item,rowIndex){
                return '<img src="'+WST.conf.ROOT+'/'+item['btnImg']+'" height="60px" style="margin-top:5px;" />';
            }},
            {title:'按钮名称', name:'btnName' ,width:60},
            {title:'按钮Url', name:'btnUrl' ,width:350},
            {title:'按钮类别', name:'btnSrc' ,width:20,renderer: function(val,item,rowIndex){
                return val==0?'手机版':val==1?'微信版':'小程序';
            }},
            {title:'所属插件', name:'addonsName' ,width:20},
            {title:'排序号', name:'btnSort' ,width:10},
            {title:'操作', name:'' ,width:100, align:'center', renderer: function(val,item,rowIndex){
                var h = "";
    			      if(WST.GRANT.ANGL_02)h += "<a  class='btn btn-blue' onclick='javascript:getForEdit(" + item['id'] + ")'><i class='fa fa-pencil'></i>修改</a> ";
    			      if(WST.GRANT.ANGL_03)h += "<a  class='btn btn-red' onclick='javascript:toDel(" + item['id'] + ")'><i class='fa fa-trash-o'></i>删除</a> ";
                return h;
            }}
            ];
 
    mmg = $('.mmg').mmGrid({height: (h-155),indexCol: true, cols: cols,method:'POST',
        url: WST.U('admin/mobilebtns/pageQuery'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });  
    $('#headTip').WSTTips({width:90,height:35,callback:function(v){
       var diff = v?155:128;
       mmg.resize({height:h-diff})
    }});   
        
}
function loadGrid(){
	var query = WST.getParams('.query');
  query.page = 1;
	mmg.load(query);
}
function getForEdit(id){
	 var loading = WST.msg('正在获取数据，请稍后...', {icon: 16,time:60000});
     $.post(WST.U('admin/mobileBtns/get'),{id:id},function(data,textStatus){
           layer.close(loading);
           var json = WST.toAdminJson(data);
           if(json.id){
           		WST.setValues(json);
           		//显示原来的图片
           		$('#preview').html('<img src="'+WST.conf.ROOT+'/'+json.btnImg+'" height="70px;"/>');
           		$('#isImg').val('ok');
           		toEdit(json.id);
           }else{
           		WST.msg(json.msg,{icon:2});
           }
    });
}

function toEdit(id){
  if(!isInitUpload){
    initUpload();
    isInitUpload = true;
  }
	var title =(id==0)?"新增":"编辑";
	var box = WST.open({title:title,type:1,content:$('#mbtnBox'),area: ['450px', '400px'],btn: ['确定','取消'],yes:function(){
			$('#mbtnForm').submit();
	},cancel:function(){
		//重置表单
		$('#mbtnForm')[0].reset();
		//清空预览图
		$('#preview').html('');
		$('#btnImg').val('');

	},end:function(){
		//重置表单
		$('#mbtnForm')[0].reset();
		//清空预览图
		$('#preview').html('');
		$('#btnImg').val('');
    $('#mbtnBox').hide();

	}});
	$('#mbtnForm').validator({
        fields: {
            btnName: {
            	rule:"required;",
            	msg:{required:"请输入按钮名称"},
            	tip:"请输入按钮名称",
            	ok:"",
            },
            btnUrl: {
            	rule:"required;",
            	msg:{required:"请输入按Url"},
            	tip:"请输入按Url",
            	ok:"",
            },
            btnImg:  {
            	rule:"required;",
            	msg:{required:"请上传图标"},
            	tip:"请上传图标",
            	ok:"",
            },
            
        },
       valid: function(form){
		        var params = WST.getParams('.ipt');
		        	params.id = id;
		        var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
		   		$.post(WST.U('admin/mobileBtns/'+((id==0)?"add":"edit")),params,function(data,textStatus){
		   			  layer.close(loading);
		   			  var json = WST.toAdminJson(data);
		   			  if(json.status=='1'){
		   			    	WST.msg("操作成功",{icon:1});
		   			    	$('#mbtnForm')[0].reset();
		   			    	//清空预览图
		   			    	$('#preview').html('');
		   			    	//清空图片隐藏域
		   			    	$('#btnImg').val('');
		   			    	layer.close(box);
		   		            loadGrid();
		   			  }else{
		   			        WST.msg(json.msg,{icon:2});
		   			  }
		   		});

    	}

  });
}
function initUpload(){
  WST.upload({
    pick:'#adFilePicker',
    formData: {dir:'sysconfigs'},
    accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
    callback:function(f){
      var json = WST.toAdminJson(f);
      if(json.status==1){
        $('#uploadMsg').empty().hide();
        //将上传的图片路径赋给全局变量
      $('#btnImg').val(json.savePath+json.thumb);
      $('#preview').html('<img src="'+WST.conf.ROOT+'/'+json.savePath+json.thumb+'" height="75" />');
      }else{
        WST.msg(json.msg,{icon:2});
      }
  },
  progress:function(rate){
      $('#uploadMsg').show().html('已上传'+rate+"%");
  }
});
}
function toDel(id){
	var box = WST.confirm({content:"您确定要删除该记录吗?",yes:function(){
	           var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	           	$.post(WST.U('admin/mobileBtns/del'),{id:id},function(data,textStatus){
	           			  layer.close(loading);
	           			  var json = WST.toAdminJson(data);
	           			  if(json.status=='1'){
	           			    	WST.msg("操作成功",{icon:1});
	           			    	layer.close(box);
	           		            loadGrid();
	           			  }else{
	           			    	WST.msg(json.msg,{icon:2});
	           			  }
	           		});
	            }});
}





		