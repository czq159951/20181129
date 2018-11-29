var mmg;
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'发送时机', name:'tplCode', width: 100},
            {title:'模板ID', name:'tplExternaId', width: 100},
            {title:'发送内容', name:'tplContent', width: 500},
            {title:'是否开启', name:'status',align:'center',width:50,renderer: function(val,item,rowIndex){
            	return '<input type="checkbox" '+((item['status']==1)?"checked":"")+' name="isShow4" lay-skin="switch" lay-filter="isShow4" data="'+item['id']+'" lay-text="开启|关闭">';
            	
            }},
            {title:'操作', name:'' ,width:30, align:'center', renderer: function(val,item,rowIndex){
                var h="";
	            if(WST.GRANT.XXMB_02)h += "<a class='btn btn-blue' href='javascript:toEdit(" + item['id'] + ")'><i class='fa fa-pencil'></i>编辑</a> "; 
	            return h;
            }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-165,indexCol: true, cols: cols,method:'POST',nowrap:true,
        url: WST.U('admin/wxtemplatemsgs/pageQuery'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });
    mmg.on('loadSuccess',function(){
    	layui.form.render();
        layui.form.on('switch(isShow4)', function(data){
            var id = $(this).attr("data");
            if(this.checked){
  				toggleIsShow(0,id);
  			}else{
  				toggleIsShow(1,id);
  			}
        });
     })
    $('#headTip').WSTTips({width:90,height:35,callback:function(v){
         if(v){
             mmg.resize({height:h-165});
         }else{
             mmg.resize({height:h-87});
         }
    }});  
}
function toggleIsShow(t,v){
	if(!WST.GRANT.DQGL_02)return;
    var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
    	$.post(WST.U('admin/TemplateMsgs/editiIsShow'),{id:v,status:t},function(data,textStatus){
			  layer.close(loading);
			  var json = WST.toAdminJson(data);
			  if(json.status=='1'){
			    	WST.msg(json.msg,{icon:1});
		            grid.reload();
			  }else{
			    	WST.msg(json.msg,{icon:2});
			  }
		});
}
function initParamGrid(){
	var loading = WST.msg('正在加载数据，请稍后...', {icon: 16,time:60000});
    var params = {parentId:$('#id').val()};
	$.post(WST.U('admin/wxtemplatemsgs/listQuery'),params,function(data,textStatus){
	    layer.close(loading);
	    var json = WST.toAdminJson(data);
	    if(json.status=='1'){
	    	childrenNum = json.data?json.data.length:0;
	        var gettpl = document.getElementById('paramjs').innerHTML;
	       	layui.laytpl(gettpl).render(json.data, function(html){
	       		$('#paramlist').html(html);
	       	});
	    }
	});
}
var childrenNum = 0;
function addNewRow(){
	var html = ['<tr id="tr_'+childrenNum+'">',
		'<td><input type="text" style="width:92%" id="fiedlCode_'+childrenNum+'"/></td>',
		'<td><input type="text" style="width:98%" id="fiedlVal_'+childrenNum+'"/></td>',
		'<td><input type="button" value="删除" class="btn btn-danger" onclick="javascript:deleteRow('+childrenNum+')"></td>',
		'</tr>'
    ];
    $('#paramlist').append(html.join(''));
    childrenNum++;
}
function deleteRow(n){
    $('#tr_'+n).remove();
}
function toEdit(id){
    location.href = WST.U('admin/wxtemplatemsgs/toEdit','id='+id);
}
function save(type){
	var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
    var params = WST.getParams('.ipt');
    params.num = childrenNum;
    for(var i=0;i<=params.num;i++){
    	if($.trim($('#fiedlCode_'+i).val())!=''){
	        params['code_'+i] = $('#fiedlCode_'+i).val();
	        params['val_'+i] = $('#fiedlVal_'+i).val();
    	}
    }
	$.post(WST.U('admin/wxtemplatemsgs/edit'),params,function(data,textStatus){
	    layer.close(loading);
	    var json = WST.toAdminJson(data);
	    if(json.status=='1'){
	        WST.msg("操作成功",{icon:1});
	        location.href = WST.U('admin/wxtemplatemsgs/index','src='+type);
	    }else{
	        WST.msg(json.msg,{icon:2});
	    }
	});
}



		