var grid;
$(function(){initGrid()})
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'竞拍人', name:'loginName', width: 100},
            {title:'竞拍价格', name:'shopName', width: 100, renderer: function(val,item,rowIndex){
            	return "￥"+item['payPrice']+((item['isTop']==1)?("&nbsp;&nbsp;<span class='label label-success'>最高价</span>"):"");
            }},
            {title:'竞拍时间', name:'createTime', width: 100},
            {title:'订单号', name:'orderNo', width: 100}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-85,indexCol: true, cols: cols,method:'GET',
        url: WST.AU('auction://goods/pageAuctionLogQueryByAdmin','id='+$('#id').val()), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });
}
function loadGrid(){
	var params = {};
	params.id = $('#id').val();
	params.key = $('#key').val();
	mmg.load(params);
}