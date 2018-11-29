var mmg1,mmg2,isInit1 = false,isInit2 = false;
$(function(){
    var element = layui.element;
    element.on('tab(msgTab)', function(data){
       if(data.index==1){
           initGrid2();
       }else{
           initGrid1();
       }
    });
    initGrid1();
})
function initGrid1(){
	if(isInit1){
        loadGrid1();
        return;
    }
    isInit1 = true;
    var h = WST.pageHeight();
    var cols = [
            {title:'&nbsp;', name:'goodsImg', width: 50, renderer: function(val,item,rowIndex){
            	var thumb = item['goodsImg'];
	        	thumb = thumb.replace('_thumb.','.');
                return "<span class='weixin'><img id='img' onmouseout='toolTip()' onmouseover='toolTip()' style='height:60px;width:60px;' src='"+WST.conf.ROOT+"/"+item['goodsImg']
            	+"'><span class='imged' ><img  style='height:180px;width:180px;' src='"+WST.conf.ROOT+"/"+thumb+"'></span></span>";
            }},
            {title:'商品名称', name:'goodsName', width: 130},
            {title:'所属店铺', name:'shopName', width: 100},
            {title:'商品原价', name:'startPrice', width: 20,renderer:function(val,item,rowIndex){return '￥'+val;}},
            {title:'商品底价', name:'floorPrice', width: 20,renderer:function(val,item,rowIndex){return '￥'+val;}},
            {title:'参与人数', name:'grouponNum', width: 20,renderer:function(val,item,rowIndex){
            	return "<a style='color:blue' href='javascript:logs(" + item['bargainId'] + ")'>"+item['joinNum']+"</a>";
            }},
            {title:'拍卖时间', name:'startTime', width: 100, align:'center',renderer: function(val,item,rowIndex){
            	return item['startTime']+"<br/>至<br/>"+item['endTime'];
            }},
            {title:'状态', name:'saleNum', width: 30, renderer: function(val,rowdata,rowIndex){
            	if(rowdata['status']==1){
                    return "<span class='statu-yes'><i class='fa fa-check-circle'></i> 进行中</span>";
	        	}else if(rowdata['status']==0){
                    return "<span class='statu-wait'><i class='fa fa-clock-o'></i> 未开始</span>";
	        	}else{
                    return "<span class='statu-no'><i class='fa fa-ban'></i> 已结束</span>";
	        	}
            }},
            {title:'操作', name:'' ,width:120, align:'center', renderer: function(val,rowdata,rowIndex){
                var h = "";
	            if(WST.GRANT.BARGAIN_QMKJ_04)h += "<a class='btn btn-red' href='javascript:illegal(" + rowdata['bargainId'] + ",1)'><i class='fa fa-ban'></i>活动下架</a> ";
	            if(WST.GRANT.BARGAIN_QMKJ_03){
                    h += "<a class='btn btn-red' href='javascript:del(" + rowdata['bargainId'] + ",0)'><i class='fa fa-trash'></i>删除</a></div> "; 
	            }
	            return h;
	        }}
            ];
 
    mmg1 = $('.mmg1').mmGrid({height: h-120,indexCol: true, indexColWidth:50, cols: cols,method:'POST',
        url: WST.AU('bargain://admin/pageQuery'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg1').mmPaginator({})
        ]
    }); 
}
function loadGrid1(){
	var params = {};
	params.shopName = $('#shopName1').val();
	params.goodsName = $('#goodsName1').val();
	params.areaIdPath = WST.ITGetAllAreaVals('areaId1','j-areas').join('_');
	params.goodsCatIdPath = WST.ITGetAllGoodsCatVals('cat1_0','pgoodsCats').join('_');
	mmg1.load(params);
}
function loadGrid2(){
	var params = {};
	params.shopName = $('#shopName2').val();
	params.goodsName = $('#goodsName2').val();
	params.areaIdPath = WST.ITGetAllAreaVals('areaId2','j-areas').join('_');
	params.goodsCatIdPath = WST.ITGetAllGoodsCatVals('cat2_0','pgoodsCats').join('_');
	mmg2.load(params);
}

function del(id,type){
	var box = WST.confirm({content:"您确定要删除该全民砍价活动吗?",yes:function(){
	           var loading = WST.msg('正在提交请求，请稍后...', {icon: 16,time:60000});
	           $.post(WST.AU('bargain://admin/del'),{id:id},function(data,textStatus){
	           			layer.close(loading);
	           			var json = WST.toAdminJson(data);
	           			if(json.status=='1'){
	           			    WST.msg(json.msg,{icon:1});
	           			    layer.close(box);
	           			    if(type==0){
	           		            loadGrid1();
	           			    }else{
	           			    	loadGrid2();
	           			    }
	           			}else{
	           			    WST.msg(json.msg,{icon:2});
	           			}
	           		});
	            }});
}
function illegal(id,type){
	var w = WST.open({type: 1,title:((type==1)?"下架原因":"不通过原因"),shade: [0.6, '#000'],border: [0],
	    content: '<textarea id="illegalRemarks" rows="7" style="width:100%" maxLength="200"></textarea>',
	    area: ['500px', '260px'],btn: ['确定', '关闭窗口'],
        yes: function(index, layero){
        	var illegalRemarks = $.trim($('#illegalRemarks').val());
        	if(illegalRemarks==''){
        		WST.msg('请输入原因 !', {icon: 5});
        		return;
        	}
        	var ll = WST.msg('数据处理中，请稍候...',{time:6000000});
		    $.post(WST.AU('bargain://admin/illegal'),{id:id,illegalRemarks:illegalRemarks},function(data){
		    	layer.close(w);
		    	layer.close(ll);
		    	var json = WST.toAdminJson(data);
				if(json.status>0){
					WST.msg(json.msg, {icon: 1});
					if(type==1){
                        loadGrid1();
					}else{
                        loadGrid2();
					}
				}else{
					WST.msg(json.msg, {icon: 2});
				}
		   });
        }
	});
}

function initGrid2(){
	if(isInit2){
        loadGrid2();
        return;
    }
    isInit2 = true;
    var h = WST.pageHeight();
    var cols = [
            {title:'&nbsp;', name:'goodsImg', width: 50, renderer: function(val,item,rowIndex){
               var thumb = item['goodsImg'];
	        	thumb = thumb.replace('_thumb.','.');
                return "<span class='weixin'><img id='img' onmouseout='toolTip()' onmouseover='toolTip()' style='height:60px;width:60px;' src='"+WST.conf.ROOT+"/"+item['goodsImg']
            	+"'><span class='imged' ><img  style='height:180px;width:180px;' src='"+WST.conf.ROOT+"/"+thumb+"'></span></span></span>";
            }},
            {title:'商品名称', name:'goodsName', width: 130},
            {title:'所属店铺', name:'shopName', width: 100},
            {title:'商品原价', name:'startPrice', width: 20,renderer:function(val,item,rowIndex){return '￥'+val;}},
            {title:'商品底价', name:'floorPrice', width: 20,renderer:function(val,item,rowIndex){return '￥'+val;}},
            {title:'参与人数', name:'grouponNum', width: 20,renderer:function(val,item,rowIndex){
            	return "<a style='color:blue' href='javascript:logs(" + item['bargainId'] + ")'>"+item['joinNum']+"</a>";
            }},
            {title:'活动时间', name:'startTime', width: 100, align:'center',renderer: function(val,item,rowIndex){
            	return item['startTime']+"<br/>至<br/>"+item['endTime'];
            }},
            {title:'状态', name:'saleNum', width: 30, renderer: function(val,rowdata,rowIndex){
            	if(rowdata['bargainStatus']==1){
                    return "<span class='statu-yes'><i class='fa fa-check-circle'></i> 通过</span>";
	        	}else if(rowdata['bargainStatus']==0){
                    return "<span class='statu-wait'><i class='fa fa-clock-o'></i> 待审核</span>";
	        	}else{
                    return "<span class='statu-no'><i class='fa fa-ban'></i> 不通过</span>";
	        	}
            }},
            {title:'操作', name:'' ,width:120, align:'center', renderer: function(val,rowdata,rowIndex){
                var h = "";
	            if(WST.GRANT.BARGAIN_QMKJ_04)h += "<a class='btn btn-blue' href='javascript:allow(" + rowdata['bargainId'] + ")'><i class='fa fa-check'></i>通过</a> ";
	            if(WST.GRANT.BARGAIN_QMKJ_04)h += "<a class='btn btn-red' href='javascript:illegal(" + rowdata['bargainId'] + ",0)'><i class='fa fa-ban'></i>不通过</a> ";
	            if(WST.GRANT.BARGAIN_QMKJ_03)h += "<a class='btn btn-red' href='javascript:del(" + rowdata['bargainId'] + ",1)'><i class='fa fa-trash'></i>删除</a>"; 
	            return h;
	        }}
            ];
 
    mmg2 = $('.mmg2').mmGrid({height: h-120,indexCol: true, indexColWidth:50, cols: cols,method:'POST',
        url: WST.AU('bargain://admin/pageAuditQuery'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg2').mmPaginator({})
        ]
    }); 
}

function allow(id,type){
	var box = WST.confirm({content:"您确定审核通过该全民砍价活动吗?",yes:function(){
        var loading = WST.msg('正在提交请求，请稍后...', {icon: 16,time:60000});
        $.post(WST.AU('bargain://admin/allow'),{id:id},function(data,textStatus){
        			layer.close(loading);
        			var json = WST.toAdminJson(data);
        			if(json.status=='1'){
        			    WST.msg(json.msg,{icon:1});
        			    layer.close(box);
        		        loadGrid1();
        		        loadGrid2();
        		    }else{
        			    WST.msg(json.msg,{icon:2});
        			}
        		});
         }});
}

function logs(id){
	parent.showBox({type:2,title:'参与记录',area: ['800px', '450px'],content:WST.AU('bargain://admin/joins','bargainId='+id+"&rd="+Math.random())});
}
function toolTip(){
    WST.toolTip();
}
