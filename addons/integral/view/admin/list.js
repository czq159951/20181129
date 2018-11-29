var mmg;
$(function(){
    initGrid();
})
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'&nbsp;', name:'goodsImg', width: 50, renderer: function(val,item,rowIndex){
                var thumb = item['goodsImg'];
	        	thumb = thumb.replace('_thumb.','.');
                return "<span class='weixin'><img id='img' onmouseout='toolTip()' onmouseover='toolTip()' style='height:60px;width:60px;' src='"+WST.conf.ROOT+"/"+item['goodsImg']
            	+"'><span class='imged' ><img  style='height:180px;width:180px;' src='"+WST.conf.ROOT+"/"+thumb+"'></span></span>";
            }},
            {title:'商品名称', name:'goodsName', width: 150},
            {title:'商品编号', name:'goodsSn', width: 100},
            {title:'商品价格', name:'goodsPrice', width: 30,renderer: function(val,item,rowIndex){return '￥'+val;}},
            {title:'所需积分', name:'integralNum', width: 30},
            {title:'所属店铺', name:'shopName', width: 100},
            {title:'数量', name:'totalNum', width: 15},
            {title:'销量', name:'orderNum', width: 15},
            {title:'开始时间', name:'orderNum', width: 100, align:'center',renderer: function(val,item,rowIndex){
            	return item['startTime']+"<br/>至<br/>"+item['endTime'];
            }},
            {title:'状态', name:'orderNum', width: 30, renderer: function(val,item,rowIndex){
            	if(item['integralStatus']==1){
		        	if(item['status']==1){
		        		
	                    return "<span class='statu-yes'><i class='fa fa-check-circle'></i> 进行中</span>";
		        	}else if(item['status']==0){
	                    return "<span class='statu-wait'><i class='fa fa-ban'></i> 未开始</span>";
		        	}else{
	                    return "<span class='statu-no'><i class='fa fa-ban'></i> 已结束</span>";
		        	}
		        }else{
		        	 return "<span class='statu-no'><i class='fa fa-ban'></i> 已下架</span>";
		        }
            }},
            {title:'操作', name:'' ,width:100, align:'center', renderer: function(val,rowdata,rowIndex){
                var h = "";
	            h += "<a class='btn btn-blue' target='_blank' href='"+WST.AU("integral://goods/detail","id="+rowdata['id']+"&key="+rowdata['verfiycode'])+"'><i class='fa fa-search'></i>查看</a> ";
	           	if(WST.GRANT.INTEGRAL_TGHD_04)h += "<a class='btn btn-blue' href='javascript:toEdit(" + rowdata['id'] + ")'><i class='fa fa-pencil'></i>修改</a> ";
	            if(WST.GRANT.INTEGRAL_TGHD_04){
	            	if(rowdata['integralStatus']>0){
	            		h += "<a class='btn btn-red' href='javascript:changeSale(" + rowdata['id'] + ",0)'><i class='fa fa-ban'></i>下架</a> ";
	            	}else{
	            		h += "<a class='btn btn-blue' class='btn btn-primary' href='javascript:changeSale(" + rowdata['id'] + ",1)'><i class='fa fa-check'></i>上架</a> ";
	            	}
	            }
	            if(WST.GRANT.INTEGRAL_TGHD_03)h += "<a class='btn btn-red' href='javascript:del(" + rowdata['id'] + ",0)'><i class='fa fa-trash'></i>删除</a>"; 
	            return h;
            }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-85,indexCol: true, indexColWidth:50, cols: cols,method:'POST',
        url: WST.AU('integral://goods/pageQueryByAdmin'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    }); 
}
function loadGrid(){
	var params = {};
	params.shopName = $('#shopName1').val();
	params.goodsName = $('#goodsName1').val();
	params.areaIdPath = WST.ITGetAllAreaVals('areaId1','j-areas').join('_');
	params.goodsCatIdPath = WST.ITGetAllGoodsCatVals('cat1_0','pgoodsCats').join('_');
	mmg.load(params);
}

function del(id,type){
	var box = WST.confirm({content:"您确定要删除该积分商城商品吗?",yes:function(){
	var loading = WST.msg('正在提交请求，请稍后...', {icon: 16,time:60000});
		$.post(WST.AU('integral://goods/delByAdmin'),{id:id},function(data,textStatus){
			layer.close(loading);
			var json = WST.toAdminJson(data);
			if(json.status=='1'){
			    WST.msg(json.msg,{icon:1});
			    layer.close(box);
			    loadGrid();
			}else{
			    WST.msg(json.msg,{icon:2});
			}
		});
	}});
}


function changeSale(id,type){
	$.post(WST.AU('integral://goods/changeSale'),{id:id,type:type},function(data){
    	var json = WST.toAdminJson(data);
		if(json.status>0){
			WST.msg(json.msg, {icon: 1});
			loadGrid();
		}else{
			WST.msg(json.msg, {icon: 2});
		}
   });
}


function toEdit(id){
	location.href = WST.AU('integral://goods/toEdit',{id:id});
}
function toolTip(){
    WST.toolTip();
}
