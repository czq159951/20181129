var mmg;
$(function(){initGrid()})
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'参与人', name:'loginName', width: 50},
            {title:'原价', name:'startPrice', width: 30,renderer:function(val,item,rowIndex){return '￥'+val;}},
            {title:'当前价', name:'currPrice', width: 100,renderer:function(val,item,rowIndex){return '￥'+val;}},
            {title:'亲友团', name:'', width: 20,renderer:function(val,rowdata,rowIndex){
            	return "<a style='color:blue' href='"+WST.AU('bargain://admin/showHelps','bargainId='+rowdata['bargainId']+'&bargainJoinId='+rowdata['id'])+"'>"+rowdata['helpNum']+"</a>";
            }},
            {title:'参与时间', name:'createTime', width: 20,renderer:function(val,item,rowIndex){return '￥'+val;}},
            {title:'订单号', name:'orderNo', width: 20}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-90,indexCol: true, indexColWidth:50, cols: cols,method:'POST',
        url: WST.AU('bargain://admin/pageyByJoins','bargainId='+$('#bargainId').val()), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    }); 
}
function loadGrid(){
	var params = {};
	params.bargainId = $('#bargainId').val();
	params.key = $('#key').val();
	mmg.load(params);
}