var mmg,h;
function initGridMsg(){
    var cols = [
            {title:'发送时机', name:'tplCode', width: 80},
            {title:'发送内容', name:'tplContent' ,width:600},
            {title:'是否开启', name:'status',align:'center',width:50,renderer: function(val,item,rowIndex){
            	return '<input type="checkbox" '+((item['status']==1)?"checked":"")+' name="isShow2" lay-skin="switch" lay-filter="isShow2" data="'+item['id']+'" lay-text="开启|关闭">';
            	
            }},
            {title:'操作', name:'' ,width:20, align:'center', renderer: function(val,item,rowIndex){
                var h = "";
                if(WST.GRANT.XXMB_02)h += "<a  class='btn btn-blue' onclick='javascript:toEditMsg("+item['id']+")'><i class='fa fa-pencil'></i>修改</a> ";
                return h;
            }}
            ];
 
    mmg = $('.mmg1').mmGrid({height: h-80,indexCol: true, cols: cols,method:'POST',
        url: WST.U('admin/templatemsgs/pageMsgQuery'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg1').mmPaginator({})
        ]
    });  
    mmg.on('loadSuccess',function(){
    	layui.form.render();
        layui.form.on('switch(isShow2)', function(data){
            var id = $(this).attr("data");
            if(this.checked){
  				toggleIsShow(0,id);
  			}else{
  				toggleIsShow(1,id);
  			}
        });
    })
}
function initGridEmail(){
    var cols = [
            {title:'发送时机', name:'tplCode', width: 80},
            {title:'发送内容', name:'tplContent' ,width:600},
            {title:'是否开启', name:'status',align:'center',width:50,renderer: function(val,item,rowIndex){
            	return '<input type="checkbox" '+((item['status']==1)?"checked":"")+' name="isShow3" lay-skin="switch" lay-filter="isShow3" data="'+item['id']+'" lay-text="开启|关闭">';
            	
            }},
            {title:'操作', name:'' ,width:20, align:'center', renderer: function(val,item,rowIndex){
                var h = "";
                if(WST.GRANT.XXMB_02)h += "<a  class='btn btn-blue' onclick='javascript:toEditEmail("+item['id']+")'><i class='fa fa-pencil'></i>修改</a> ";
                return h;
            }}
            ];
 
    mmg = $('.mmg2').mmGrid({height: h-80,indexCol: true, cols: cols,method:'POST',
        url: WST.U('admin/templatemsgs/pageEmailQuery'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg2').mmPaginator({})
        ]
    });  
    mmg.on('loadSuccess',function(){
    	layui.form.render();
        layui.form.on('switch(isShow3)', function(data){
            var id = $(this).attr("data");
            if(this.checked){
  				toggleIsShow(0,id);
  			}else{
  				toggleIsShow(1,id);
  			}
        });
     })
}
function initGridSMS(){
    var cols = [
            {title:'发送时机', name:'tplCode', width: 80},
            {title:'发送内容', name:'tplContent' ,width:600},
            {title:'是否开启', name:'status',align:'center',width:50,renderer: function(val,item,rowIndex){
            	return '<input type="checkbox" '+((item['status']==1)?"checked":"")+' name="isShow4" lay-skin="switch" lay-filter="isShow4" data="'+item['id']+'" lay-text="开启|关闭">';
            	
            }},
            {title:'操作', name:'' ,width:20, align:'center', renderer: function(val,item,rowIndex){
                var h = "";
                if(WST.GRANT.XXMB_02)h += "<a  class='btn btn-blue' onclick='javascript:toEditSMS("+item['id']+")'><i class='fa fa-pencil'></i>修改</a> ";
                return h;
            }}
            ];
 
    mmg = $('.mmg3').mmGrid({height: h-182,indexCol: true, cols: cols,method:'POST',
        url: WST.U('admin/templatemsgs/pageSMSQuery'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg3').mmPaginator({})
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
        mmg.resize();
     })
    $('#headTip').WSTTips({width:90,height:35,callback:function(v){
         if(v){
             mmg.resize({height:h-182});
         }else{
             mmg.resize({height:h-124});
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
var editor1;
function initEditor(){
  KindEditor.ready(function(K) {
    editor1 = K.create('textarea[name="tplContent"]', {
      uploadJson : WST.conf.ROOT+'/admin/messages/editorUpload',
      height:'350px',
      allowFileManager : false,
      allowImageUpload : true,
      items:[
              'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
              'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
              'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
              'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
              'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
              'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|','image','table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
              'anchor', 'link', 'unlink', '|', 'about'
      ],
      afterBlur: function(){ this.sync(); }
    });
  });
}

function toEditMsg(id){
    location.href = WST.U('admin/templatemsgs/toEditMsg','id='+id);
}
function toEditEmail(id){
    location.href = WST.U('admin/templatemsgs/toEditEmail','id='+id);
}
function toEditSMS(id){
    location.href = WST.U('admin/templatemsgs/toEditSMS','id='+id);
}
function save(type){
	  var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
    var params = WST.getParams('.ipt');
	  $.post(WST.U('admin/templatemsgs/edit'),params,function(data,textStatus){
	      layer.close(loading);
	      var json = WST.toAdminJson(data);
	      if(json.status=='1'){
	          WST.msg("操作成功",{icon:1});
	          location.href = WST.U('admin/templatemsgs/index','src='+type);
	      }else{
	          WST.msg(json.msg,{icon:2});
	      }
	  });
}
var isInit1 = false;
var isInit2 = false;
var isInit3 = false;
//切换卡
$(function (){ 
    var element = layui.element;
    element.on('tab(msgTab)', function(data){
       if(data.index==0){
          if(!isInit1){
             isInit1 = true;
             initGridMsg();
          }
       }else if(data.index==1){
          if(!isInit2){
             isInit2 = true;
             initGridEmail();
          }
       }else if(data.index==2){
          if(!isInit3){
             isInit3 = true;
             initGridSMS();

          }
       }
    });
});



		