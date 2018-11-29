var mmg;
$(function(){initGrid()})
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'亲友名称', name:'userName', width: 50},
            {title:'帮砍金额', name:'minusMoney', width: 30,renderer:function(val,item,rowIndex){return '￥'+val;}},
            {title:'砍价时间', name:'createTime', width: 20}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-90,indexCol: true,indexColWidth:50, cols: cols,method:'POST',
        url: WST.AU('bargain://admin/pageByHelps','bargainJoinId='+$('#bargainJoinId').val()), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    }); 
}
function loadGrid(){
	var params = {};
	params.bargainJoinId = $('#bargainJoinId').val();
	params.key = $('#key').val();
	mmg.load(params);
}