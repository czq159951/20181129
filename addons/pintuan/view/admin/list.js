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
            {title:'商品名称', name:'goodsName', width: 100},
            {title:'商品编号', name:'goodsSn', width: 100},
            {title:'店铺价', name:'shopPrice', width: 20,renderer: function(val,item,rowIndex){return '￥'+val}},
            {title:'拼团价', name:'tuanPrice', width: 20,renderer: function(val,item,rowIndex){return '￥'+val}},
            {title:'参团所需人数', name:'tuanNum', width: 30},
            {title:'所属店铺', name:'shopName', width: 20},
            {title:'开团数', name:'openTuanCnt', width: 20,renderer: function(val,item,rowIndex){
                return "<a style='color:blue;text-decoration:underline' href=\"javascript:opentuans(" + item['tuanId'] + ",0)\">"+val+"</a>";
            }},
            {title:'销量', name:'saleNum', width: 20,renderer: function(val,item,rowIndex){
                return "<a style='color:blue;text-decoration:underline' href=\"javascript:opentuans(" + item['tuanId'] + ",2)\">"+val+"</a>";
            }},
            {title:'有效时间（小时）', name:'tuanTime', width: 60},
            {title:'操作', name:'' ,width:120, align:'center', renderer: function(val,item,rowIndex){
                var h = "";
	            if(WST.GRANT.PINTUAN_PTHD_04)h += "<a class='btn btn-red' href='javascript:illegal(" + item['tuanId'] + ",1)'><i class='fa fa-ban'></i>违规下架</a> ";
	            if(WST.GRANT.PINTUAN_PTHD_03)h += "<a class='btn btn-red' href='javascript:del(" + item['tuanId'] + ",0)'><i class='fa fa-trash'></i>删除</a>"; 
	            return h;
	        }}
            ];
 
    mmg1 = $('.mmg1').mmGrid({height: h-120,indexCol: true, indexColWidth:50, cols: cols,method:'POST',
        url: WST.AU('pintuan://goods/pageQueryByAdmin'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg1').mmPaginator({})
        ]
    }); 
}

function opentuans(tuanId,tuanStatus){
    parent.showBox({type:2,title:'开团记录',area: ['900px', '550px'],content:WST.AU('pintuan://goods/openTuanByAdmin','tuanId='+tuanId+"&tuanStatus="+tuanStatus+"&rd="+Math.random())});
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
	var box = WST.confirm({content:"您确定要删除该拼团商品吗?",yes:function(){
	           var loading = WST.msg('正在提交请求，请稍后...', {icon: 16,time:60000});
	           $.post(WST.AU('pintuan://goods/delByAdmin'),{id:id},function(data,textStatus){
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
		    $.post(WST.AU('pintuan://goods/illegal'),{id:id,illegalRemarks:illegalRemarks},function(data){
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
            	+"'><span class='imged' ><img  style='height:180px;width:180px;' src='"+WST.conf.ROOT+"/"+thumb+"'></span></span>";
            }},
            {title:'商品名称', name:'goodsName', width: 100},
            {title:'商品编号', name:'goodsSn', width: 70},
            {title:'店铺价', name:'shopPrice', width: 20,renderer: function(val,item,rowIndex){return '￥'+val}},
            {title:'拼团价', name:'tuanPrice', width: 20,renderer: function(val,item,rowIndex){return '￥'+val}},
            {title:'所属店铺', name:'shopName', width: 70},
            {title:'数量', name:'tuanNum', width: 20},
            {title:'销量', name:'saleNum', width: 20},
            {title:'有效时间（小时）', name:'tuanTime', width: 60},
            {title:'操作', name:'' ,width:120, align:'center', renderer: function(val,item,rowIndex){
                var h = "";
	            if(WST.GRANT.PINTUAN_PTHD_04){
	            	h += "<a class='btn btn-blue' href='javascript:allow(" + item['tuanId'] + ")'><i class='fa fa-check'></i>通过</a> ";
	            	h += "<a class='btn btn-red' href='javascript:illegal(" + item['tuanId'] + ",0)'><i class='fa fa-ban'></i>不通过</a> ";
	            }
	            if(WST.GRANT.PINTUAN_PTHD_03)h += "<a class='btn btn-red' href='javascript:del(" + item['tuanId'] + ",1)'><i class='fa fa-trash'></i>删除</a> "; 
	            return h;
	        }}
            ];
 
    mmg2 = $('.mmg2').mmGrid({height: h-120,indexCol: true, indexColWidth:50, cols: cols,method:'POST',
        url: WST.AU('pintuan://goods/pageAuditQueryByAdmin'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg2').mmPaginator({})
        ]
    }); 
}

function allow(id,type){
	var box = WST.confirm({content:"您确定审核通过该拼团商品吗?",yes:function(){
        var loading = WST.msg('正在提交请求，请稍后...', {icon: 16,time:60000});
        $.post(WST.AU('pintuan://goods/allow'),{id:id},function(data,textStatus){
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
function toolTip(){
    WST.toolTip();
}
