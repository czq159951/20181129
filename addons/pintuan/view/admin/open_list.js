var grid;
$(function(){initGrid()})
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'团号', name:'tuanNo', width: 120},
            {title:'', name:'userPhoto', width: 80, renderer: function(val,item,rowIndex){
                return "<img  style='height:80px;width:80px;' src='"+val+"'>";
            }},
            {title:'用户', name:'userName', width: 180, renderer: function(val,item,rowIndex){
                if(item['isHead']==1){
                    return val+"&nbsp;<span style='background-color:#f0ad4e;color:#fff;padding:4px;border-radius:10px;'>拼主</span>";
                }else{
                    return val;
                }
            }},
            {title:'参团时间', name:'createTime', width: 180},
            {title:'拼团状态', name:'tuanStatus', width: 100, renderer: function(val,item,rowIndex){
                if(val==1){
                    return "待成团";
                }else if(val==2){
                    return "已成团";
                }else if(val==-1){
                    return "已退款";
                }
            }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-85,indexCol: true, cols: cols,method:'GET',
        url: WST.AU('pintuan://goods/tuanByAdminPageQuery','tuanId='+$('#tuanId').val()+'&tuanStatus='+$('#tuanStatus').val()), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });
}
function loadGrid(){
	var params = {};
	params.tuanId = $('#tuanId').val();
    params.tuanStatus = $('#tuanStatus').val();
	params.key = $('#key').val();
	mmg.load(params);
}