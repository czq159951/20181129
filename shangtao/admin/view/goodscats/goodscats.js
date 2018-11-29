var grid,oldData = {},oldorderData = {};
function initGrid(){	
	grid = $('#maingrid').WSTGridTree({
		url:WST.U('admin/goodscats/pageQuery'),
		pageSize:10000,
		pageSizeOptions:[10000],
		height:'99%',
        width:'100%',
        minColToggle:6,
        delayLoad :true,
        rownumbers:true,
        columns: [
	        { display: '分类名称', width: 230,name: 'catName', id:'catId', align: 'left',isSort: false,render: function (item)
                {
                	oldData[item.catId] = item.catName;
                    return '<input type="text" size="40" value="'+item.catName+'" onblur="javascript:editName('+item.catId+',this)" style="width:200px"/>';
            }},
	        { display: '分类名缩写', width: 150,name: 'simpleName', id:'catId', align: 'left',isSort: false,render: function (item)
                {
                	oldData[item.catId] = item.simpleName;
                    return '<input type="text" size="40" maxLength="4" value="'+item.simpleName+'" onblur="javascript:editsimpleName('+item.catId+',this)" style="width:120px"/>';
            }},
            { display: '推荐楼层', width: 70, name: 'isFloor',isSort: false,
                render: function (itemf)
                {
                    return '<input type="checkbox" '+((itemf.isFloor==1)?"checked":"" )+'  class="ipt" lay-skin="switch" lay-filter="isFloor" data="'+itemf.catId+'" lay-text="是|否">';
                }
            },
            { display: '是否显示', width: 70, name: 'isShow',isSort: false,
                render: function (item)
                {
                    return '<input type="checkbox" '+((item.isShow==1)?"checked":"")+' class="ipt" lay-skin="switch" lay-filter="isShow" data="'+item.catId+'" lay-text="显示|隐藏">';
                }
            },
            { display: '排序号', name: 'catSort',width: 50,isSort: false,render: function (item)
                {
                	oldorderData[item.catId] = item.catSort;
                    return '<input type="text" style="width:50px" value="'+item.catSort+'" onblur="javascript:editOrder('+item.catId+',this)"/>';
            }},
            { display: '佣金', width: 50, name: 'commissionRate',isSort: false,
                render: function (item)
                {
                    return item["commissionRate"]+'%';
                }
            },
	        { display: '操作', name: 'op',width: 170,isSort: false,
	        	render: function (rowdata){
		            var h = "";
			        if(WST.GRANT.SPFL_01)h += "<a class='btn btn-blue' href='javascript:toEdit("+rowdata["catId"]+",0)'><i class='fa fa-plus'></i>新增子分类</a> ";
		            if(WST.GRANT.SPFL_02)h += "<a class='btn btn-blue' href='javascript:toEdit("+rowdata["parentId"]+","+rowdata["catId"]+")'><i class='fa fa-pencil'></i>修改</a> ";
		            if(WST.GRANT.SPFL_03)h += "<a class='btn btn-red' href='javascript:toDel("+rowdata["parentId"]+","+rowdata["catId"]+")'><i class='fa fa-trash-o'></i>删除</a> "; 
		            return h;
	        	}}
        ],
        callback:function(){
		    layui.form.render();
	    }
    });
    layui.form.on('switch(isShow)', function(data){
        var id = $(this).attr("data");
        if(this.checked){
            toggleIsShow(id, 1);
        }else{
            toggleIsShow(id, 0);
        }
   });
   layui.form.on('switch(isFloor)', function(data){
        var id = $(this).attr("data");
        if(this.checked){
            toggleIsFloor(id, 1);
        }else{
            toggleIsFloor(id, 0);
        }
   });
}

function toggleIsFloor(id,isFloor){
	if(!WST.GRANT.SPFL_02)return;
    var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	$.post(WST.U('admin/goodscats/editiIsFloor'),{id:id,isFloor:isFloor},function(data,textStatus){
		  layer.close(loading);
		  var json = WST.toAdminJson(data);
		  if(json.status=='1'){
		    	WST.msg(json.msg,{icon:1});
				grid.reload(id);
		  }else{
		    	WST.msg(json.msg,{icon:2});
		  }
	});
}

function toggleIsShow(id,isShow){
	if(!WST.GRANT.SPFL_02)return;
	if(isShow==0){
		var box = WST.confirm({content:"您确定要隐藏该商品分类并下架该分类下的所有商品吗?",yes:function(){
			  layer.close(box);
              var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
			  $.post(WST.U('admin/goodscats/editiIsShow'),{id:id,isShow:isShow},function(data,textStatus){
					layer.close(loading);
					var json = WST.toAdminJson(data);
					if(json.status=='1'){
						 WST.msg(json.msg,{icon:1});
						 grid.reload(id);
					}else{
						 WST.msg(json.msg,{icon:2});
					}
			  });
		}});	
	}else{
		var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	    $.post(WST.U('admin/goodscats/editiIsShow'),{id:id,isShow:isShow},function(data,textStatus){
			layer.close(loading);
			var json = WST.toAdminJson(data);
			if(json.status=='1'){
				 WST.msg(json.msg,{icon:1});
				 grid.reload(id);
			}else{
				 WST.msg(json.msg,{icon:2});
			}
		});
	}
}

function toEdit(pid,id){
	$('#goodscatsForm')[0].reset();
	if(id>0){
		$.post(WST.U('admin/goodscats/get'),{id:id},function(data,textStatus){
			var json = WST.toAdminJson(data);
			if(json){
				WST.setValues(json);
				layui.form.render();
				if(json.catImg){
					$('#preview').html('<img src="'+WST.conf.ROOT+'/'+json.catImg+'" height="70px" />');
				}else{
					$('#preview').html('');
				}
				editsBox(id);
			}
		});
	}else{
		WST.setValues({parentId:pid,catName:'',simpleName:'',isShow:1,isFloor:0,catSort:0,catImg:''});
		$('#preview').html('');
		layui.form.render();
		editsBox(id);
	}
}
var isInitUpload = false;
function editsBox(id,v){
	if(!isInitUpload)initUpload();
	var title =(id>0)?"修改商品分类":"新增商品分类";
	var box = WST.open({title:title,type:1,content:$('#goodscatsBox'),area: ['465px', '460px'],btn:['确定','取消'],
		 end:function(){$('#goodscatsBox').hide();},yes:function(){
		$('#goodscatsForm').submit();
	          }});
	$('#goodscatsForm').validator({
	    fields: {
	    	catName: {
	    		tip: "请输入商品分类名称",
	    		rule: '商品分类名称:required;length[~20];'
	    	},
	    	simpleName: {
	    		tip: "请输入商品分类名缩写",
	    		rule: '商品分类名缩写:required;length[~20];'
	    	},
	    	commissionRate: {
	    		tip: "请输入分类的佣金",
	    		rule: '分类的佣金:required;'
	    	},
	    	catSort: {
            	tip: "请输入排序号",
            	rule: '排序号:required;length[~8];'
            },
	    },
	    valid: function(form){
	        var params = WST.getParams('.ipt');
	        params.id = id;
	        var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
    		$.post(WST.U('admin/goodscats/'+((id>0)?"edit":"add")),params,function(data,textStatus){
    			  layer.close(loading);
    			  var json = WST.toAdminJson(data);
    			  if(json.status=='1'){
    			    	WST.msg(json.msg,{icon:1});
    			    	$('#goodscatsBox').hide();
    			    	layer.close(box);
    			    	grid.reload(params.parentId);
    			  }else{
    			        WST.msg(json.msg,{icon:2});
    			  }
    		});
	    }
	});
}

function toDel(pid,id){
	var box = WST.confirm({content:"您确定要删除该商品分类并下架该分类下的所有商品吗?",yes:function(){
	           var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	           	$.post(WST.U('admin/goodscats/del'),{id:id},function(data,textStatus){
	           			  layer.close(loading);
	           			  var json = WST.toAdminJson(data);
	           			  if(json.status=='1'){
	           			       WST.msg(json.msg,{icon:1});
	           			       layer.close(box);
	           		           grid.reload(pid);
	           			  }else{
	           			       WST.msg(json.msg,{icon:2});
	           			  }
	           		});
	            }});
}

function initUpload(){
	isInitUpload = true;
	//文件上传
	WST.upload({
	    pick:'#catFilePicker',
	    formData: {dir:'goodscats'},
	    accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
	    callback:function(f){
	      var json = WST.toAdminJson(f);
	      if(json.status==1){
	        $('#uploadMsg').empty().hide();
	        //将上传的图片路径赋给全局变量
		    $('#catImg').val(json.savePath+json.thumb);
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

function editName(id,obj){
	if($.trim(obj.value)=='' || $.trim(obj.value)==oldData[id]){
		obj.value = oldData[id];
		return;
	}
	$.post(WST.U('admin/goodscats/editName'),{id:id,catName:obj.value},function(data,textStatus){
	    var json = WST.toAdminJson(data);
	    if(json.status=='1'){
	    	oldData[id] = $.trim(obj.value);
	        WST.msg(json.msg,{icon:1});
	    }else{
	        WST.msg(json.msg,{icon:2});
	    }
	});
}
function editsimpleName(id,obj){
	if($.trim(obj.value)=='' || $.trim(obj.value)==oldData[id]){
		obj.value = oldData[id];
		return;
	}
	if(obj.value.length>4){
		return WST.msg('商品分类名缩写不能超过4个字',{icon:2});
	}
	$.post(WST.U('admin/goodscats/editsimpleName'),{id:id,simpleName:obj.value},function(data,textStatus){
	    var json = WST.toAdminJson(data);
	    if(json.status=='1'){
	    	oldData[id] = $.trim(obj.value);
	        WST.msg(json.msg,{icon:1});
	    }else{
	        WST.msg(json.msg,{icon:2});
	    }
	});
}
function editOrder(id,obj){
	if($.trim(obj.value)=='' || $.trim(obj.value)==editOrder[id]){
		obj.value = editOrder[id];
		return;
	}
	$.post(WST.U('admin/goodscats/editOrder'),{id:id,catSort:obj.value},function(data,textStatus){
	    var json = WST.toAdminJson(data);
	    if(json.status=='1'){
	    	editOrder[id] = $.trim(obj.value);
	        WST.msg(json.msg,{icon:1});
	    }else{
	        WST.msg(json.msg,{icon:2});
	    }
	});
}