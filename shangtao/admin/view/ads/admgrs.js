var mmg;
function initGrid(){
  var positionType = $("#positionType").val();
  var adPositionId = $("#adPositionId").val();
  var h = WST.pageHeight();
  var cols = [
            {title:'图标', name:'' ,width:50, align:'center', renderer: function(val,item,rowIndex){
               var adFile = item['adFile'].split(',');
               return'<img src="'+WST.conf.ROOT+'/'+adFile[0]+'" height="28px" width="100"/>';
            }},
            {title:'标题', name:'adName', width: 100},
            {title:'广告位置', name:'positionName' ,width:80},
            {title:'广告网址', name:'adURL' ,width:130},
            {title:'广告开始日期', name:'adStartDate' ,width:30},
            {title:'广告结束日期', name:'adEndDate' ,width:30},
            
            {title:'点击数', name:'adClickNum' ,width:15},
            {title:'排序号', name:'adSort' ,width:15, renderer: function(val,item,rowIndex){
               return '<span style="color:blue;cursor:pointer;" ondblclick="changeSort(this,'+item["adId"]+');">'+val+'</span>';
            }},
            {title:'操作', name:'' ,width:80, align:'center', renderer: function(val,item,rowIndex){
                var h = "";
                if(adPositionId>0){
                	if(WST.GRANT.GGGL_02)h += "<a  class='btn btn-blue' href='javascript:toEdit2("+item['adId']+")'><i class='fa fa-pencil'></i>修改</a> ";
                }else{
                	if(WST.GRANT.GGGL_02)h += "<a  class='btn btn-blue' href='javascript:toEdit("+item['adId']+")'><i class='fa fa-pencil'></i>修改</a> ";
                }
                if(WST.GRANT.GGGL_03)h += "<a  class='btn btn-red' href='javascript:toDel(" + item['adId'] + ")'><i class='fa fa-trash-o'></i>删除</a> ";
                return h;
            }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-155,indexCol: true, cols: cols,method:'POST',
        url: WST.U('admin/ads/pageQuery','positionType='+positionType+'&adPositionId='+adPositionId), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });  
    $('#headTip').WSTTips({width:90,height:35,callback:function(v){
         if(v){
             mmg.resize({height:h-155});
         }else{
             mmg.resize({height:h-128});
         }
    }});
}
function toEdit(id){
    location.href = WST.U('admin/ads/toedit','id='+id);
}
function toEdit2(id){
	var adPositionId = $("#adPositionId").val();
    location.href = WST.U('admin/ads/toedit2','id='+id+'&adPositionId='+adPositionId);
}
function toDel(id){
	var box = WST.confirm({content:"您确定要删除该记录吗?",yes:function(){
	           var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	           	$.post(WST.U('admin/Ads/del'),{id:id},function(data,textStatus){
	           			  layer.close(loading);
	           			  var json = WST.toAdminJson(data);
	           			  if(json.status=='1'){
	           			    	WST.msg("操作成功",{icon:1});
	           			    	layer.close(box);
	           		        loadQuery();
	           			  }else{
	           			    	WST.msg(json.msg,{icon:2});
	           			  }
	           		});
	            }});
}
function loadQuery(){
    var query = WST.getParams('.query');
    query.page = 1;
    mmg.load(query);
}
var oldSort;
function changeSort(t,id){
   $(t).attr('ondblclick'," ");
var html = "<input type='text' id='sort-"+id+"' style='width:30px;padding:2px;' onblur='doneChange(this,"+id+")' value='"+$(t).html()+"' />";
 $(t).html(html);
 $('#sort-'+id).focus();
 $('#sort-'+id).select();
 oldSort = $(t).html();
}
function doneChange(t,id){
  var sort = ($(t).val()=='')?0:$(t).val();
  if(sort==oldSort){
    $(t).parent().attr('ondblclick','changeSort(this,'+id+')');
    $(t).parent().html(parseInt(sort));
    return;
  }
  $.post(WST.U('admin/ads/changeSort'),{id:id,adSort:sort},function(data){
    var json = WST.toAdminJson(data);
    if(json.status==1){
        $(t).parent().attr('ondblclick','changeSort(this,'+id+')');
        $(t).parent().html(parseInt(sort));
    }
  });
}


		
//查询
function adsQuery(){
		var query = WST.getParams('.query');
	    grid.set('url',WST.U('admin/ads/pageQuery',query));
}

var isContinueAdd = false;
function save(){
   isContinueAdd = false;
   $('#adsForm').submit();
}
function continueAdd(){
   isContinueAdd = true;
   $('#adsForm').submit();
}
function editInit(){
  var laydate = layui.laydate;
    form = layui.form; 
    laydate.render({
        elem: '#adStartDate'
    });
    laydate.render({
        elem: '#adEndDate'
    });
  //文件上传
	WST.upload({
  	  pick:'#adFilePicker',
  	  formData: {dir:'adspic'},
      compress:false,//默认不对图片进行压缩
  	  accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
  	  callback:function(f){
  		  var json = WST.toAdminJson(f);
  		  if(json.status==1){
  			$('#uploadMsg').empty().hide();
        var html = '<img src="'+WST.conf.ROOT+'/'+json.savePath+json.thumb+'" />';
        $('#preview').html(html);
        // 图片路径
        $('#adFile').val(json.savePath+json.thumb);
  		  }
	  },
	  progress:function(rate){
	      $('#uploadMsg').show().html('已上传'+rate+"%");
	  }
    });
  
 /* 表单验证 */
    $('#adsForm').validator({
    		timely:2,
            fields: {
                adPositionId: {
                  rule:"required",
                  msg:{required:"请选择广告位置"},
                  tip:"请选择广告位置",
                  ok:"验证通过",
                },
                adName: {
                  rule:"required;",
                  msg:{required:"广告标题不能为空"},
                  tip:"请输入广告标题",
                  ok:"验证通过",
                },
                adFile: {
                  rule:"required;",
                  msg:{required:"请上传广告图片"},
                  tip:"请上传广告图片",
                  ok:"",
                },
                adStartDate: {
                  rule:"required;match(lt, adEndDate, date)",
                  msg:{required:"请选择广告开始时间",match:"必须小于广告结束时间"},
                  ok:"验证通过",
                },
                adEndDate: {
                  rule:"required;match(gt, adStartDate, date)",
                  msg:{required:"请选择广告结束时间",match:"必须大于广告开始时间"},
                  ok:"验证通过",
                }
            },
          valid: function(form){
            var params = WST.getParams('.ipt');
            var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
            $.post(WST.U('admin/Ads/'+((params.adId==0)?"add":"edit")),params,function(data,textStatus){
              layer.close(loading);
              var json = WST.toAdminJson(data);
              if(json.status=='1'){
                  WST.msg("操作成功",{icon:1});
                  if(isContinueAdd){
                     $('#adsForm').get(0).reset();
                     $('#preview').empty();
                     $('#adFile').val('');
                  }else{
                	  var positionId = $("#positionId").val();
                	  if(positionId>0){
                		  location.href = WST.U('admin/ads/index2','id='+positionId);
                	  }else{
                		  location.href = WST.U('Admin/Ads/index');
                	  }
                  }
              }else{
                    WST.msg(json.msg,{icon:2});
              }
            });
      }
    });
}

var positionInfo;
/*获取地址*/
function addPosition(pType, val, getSize)
{
    $.post(WST.U('admin/Adpositions/getPositon'),{'positionType':pType},function(data,textStatus){
        positionInfo = data;
        var html='<option value="">请选择</option>';
        $(data).each(function(k,v){
			var selected;
            if(v.positionId==val){
              selected = 'selected="selected"';
              getPhotoSize(v.positionId);
            }
            html +='<option '+selected+' value="'+v.positionId+'">'+v.positionName+'</option>';
        });
        $('#adPositionId').html(html);
        layui.form.render('select');
    })
}
/*获取图片尺寸 以及设置图片显示方式*/
function getPhotoSize(pType)
{
  $(positionInfo).each(function(k,v){
      if(v.positionId==pType){
        $('#img_size').html(v.positionWidth+'x'+v.positionHeight);
        if(v.positionWidth>v.positionHeight){
             $('.ads-h-list').removeClass('ads-h-list').addClass('ads-w-list');
         }
      }
  });

}