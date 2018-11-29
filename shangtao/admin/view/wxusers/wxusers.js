var mmg;
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'用户头像', name:'userPhoto', width: 25, renderer: function(val,item,rowIndex){
                if(item["userPhoto"]){
                    var i = '<span><img style="height:40px;" src="'+item["userPhoto"]+'" /></span>';
                    return i;
                }
            }},
            {title:'用户名称', name:'userName', width: 100},
            {title:'性别', name:'userSex', width: 20, renderer: function(val,item,rowIndex){
                if(item['userSex']==0)s = "保密";
                if(item['userSex']==1)s = "男";
                if(item['userSex']==2)s = "女";
                return s;
            }},
            {title:'用户所在地', name:'userAreas', width: 60},
            {title:'openId', name:'openId', width: 180},
            {title:'用户关注时间', name:'subscribeTime', width: 80, renderer: function(val,item,rowIndex){
                if(WST.blank(item["subscribeTime"]))return item["subscribeTime"];
            }},
            {title:'用户备注', name:'userRemark', width: 100},
            {title:'操作', name:'' ,width:70, align:'center', renderer: function(val,item,rowIndex){
                var h = "";
                if(WST.GRANT.WX_ZDYCD_02)h += "<a class='btn btn-blue' href='javascript:toEdit("+item["userId"]+")'><i class='fa fa-pencil'></i>修改备注</a> ";
                return h;
            }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-85,indexCol: true, cols: cols,method:'POST',
        url: WST.U('admin/wxusers/pageQuery'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });  
}

function loadGrid(){
	mmg.load({page:1,key:$('#key').val()});
}

//与微信用户管理同步
var userTotal,num=0;
function wxSynchro(){
	var box = WST.confirm({content:"您确定与微信用户管理同步吗?</br>(用户越多同步时间将越久)",yes:function(){
        var loading = WST.msg('正在同步数据，请稍后...', {icon: 16,time:60000});
        $.post(WST.U('admin/wxusers/synchroWx'),'',function(data,textStatus){
        			  layer.close(loading);
        			  var json = WST.toAdminJson(data);
        			  if(json.status=='1'){
        				    userTotal = json.data;
        			    	WST.msg(json.msg,{icon:1});
        			    	layer.close(box);
        		            loadGrid();
        		            wxLoad();
        			  }else{
        			    	WST.msg(json.msg,{icon:2});
        			  }
        		});
         }});
}

function wxLoad(){
		id = userTotal[num]['openId'];
        $.post(WST.U('admin/wxusers/wxLoad'),{id:id},function(data,textStatus){
        			  var json = WST.toAdminJson(data);
        			  if(json.status=='1'){
        				    if(num < userTotal.length-1){
        				    	num++
        				    	WST.msg("当前正在同步第"+num+"个用户,进度"+num+"/"+userTotal.length);
        				    	wxLoad();
        				        return;
        				    }else{
            			    	num=0;
            			    	WST.msg("同步完成",{icon:1});
            		            loadGrid();
        				    }
        			  }else{
        			    	WST.msg(json.msg,{icon:2});
        			  }
        		});
}

function toEdit(id){
	$('#wxusersForm')[0].reset();
		$.post(WST.U('admin/wxusers/getById'),{id:id},function(data,textStatus){
			var json = WST.toAdminJson(data);
			if(json){
				WST.setValues(json);
				var box = WST.open({title:'修改备注',type:1,content:$('#wxusersBox'),area: ['460px', '160px'],btn:['确定','取消'],
                        end:function(){$('#wxusersBox').hide();},yes:function(){
						if(!$('#userRemark').isValid())return;
				        var params = WST.getParams('.ipt');
				        params.id = id;
				        var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
			    		$.post(WST.U('admin/wxusers/edit'),params,function(data,textStatus){
			    			  layer.close(loading);
			    			  var json = WST.toAdminJson(data);
			    			  if(json.status=='1'){
			    			    	WST.msg(json.msg,{icon:1});
                                    $('#wxusersBox').hide();
			    			    	layer.close(box);
			    			    	loadGrid(params.parentId);
			    			  }else{
			    			        WST.msg(json.msg,{icon:2});
			    			  }
			    		});
				          }});
			}
		});
}