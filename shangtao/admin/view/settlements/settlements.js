var mmg;
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'店铺编号', name:'shopSn', width: 130},
            {title:'店铺名称', name:'shopName' ,width:100},
            {title:'店主姓名', name:'shopkeeper', width: 130},
            {title:'店主联系电话', name:'telephone' ,width:100},
            {title:'待结算订单数', name:'noSettledOrderNum' ,width:60},
            {title:'待结算佣金', name:'noSettledOrderFee' ,width:40, renderer:function(val,item,rowIndex){
                return '￥'+val;
            }},
            {title:'操作', name:'' ,width:120, align:'center', renderer: function(val,item,rowIndex){
                var h = "<span id='s_"+item['shopId']+"' dataval='"+item['shopName']+"'></span><a class='btn btn-blue' href='javascript:toView(" + item['shopId'] + ")'><i class='fa fa-search'></i>订单列表</a>&nbsp;&nbsp;";
	            return h;
            }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-173,indexCol: true,indexColWidth:50,  indexColWidth:50,cols: cols,method:'POST',checkCol:true,multiSelect:true,
        url: WST.U('admin/settlements/pageShopQuery'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });
    $('#headTip').WSTTips({width:90,height:35,callback:function(v){
         var diff = v?173:128;
         mmg.resize({height:h-diff})
    }});
}
function toView(id){
   location.href=WST.U('admin/settlements/toOrders','id='+id);
}
function initOrderGrid(id){
    var h = WST.pageHeight();
    var cols = [
            {title:'订单号', name:'orderNo', width: 130},
            {title:'支付方式', name:'payTypeName' ,width:100},
            {title:'商品金额', name:'goodsMoney', width: 130},
            {title:'运费', name:'deliverMoney' ,width:100, renderer:function(val,item,rowIndex){
                return '￥'+val;
            }},
            {title:'订单总金额', name:'totalMoney' ,width:60, renderer:function(val,item,rowIndex){
                return '￥'+val;
            }},
            {title:'实付金额', name:'realTotalMoney' ,width:40, renderer:function(val,item,rowIndex){
                return '￥'+val;
            }},
            {title:'佣金', name:'commissionFee' ,width:40, renderer:function(val,item,rowIndex){
                return '￥'+val;
            }},
            {title:'下单时间', name:'createTime' ,width:120, align:'center'}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-95,indexCol: true, indexColWidth:50, cols: cols,method:'POST',
        url: WST.U('admin/settlements/pageShopOrderQuery','id='+id), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });
}
function loadShopGrid(){
	var areaIdPath = WST.ITGetAllAreaVals('areaId1','j-areas').join('_');
	mmg.load({page:1,shopName:$('#shopName').val(),areaIdPath:areaIdPath});
}
function loadOrderGrid(){
	var id = $('#id').val();
	mmg.load({page:1,orderNo:$('#orderNo').val(),payType:$('#payType').val(),id:id});
}
var generateNo = 0;
var shops = [];
function generateSettle(){
	var shopId = shops[generateNo];
	var shopName = $('#s_'+shopId).attr('dataval');
	var load = WST.msg('正在生成【'+shopName+'】结算单，请稍后...', {icon: 16,time:60000});
    $.post(WST.U('admin/settlements/generateSettleByShop'),{id:shopId},function(data,textStatus){
		layer.close(load);
		var json = WST.toAdminJson(data);
		if(json.status==1){
				if(generateNo<(shops.length-1)){
					generateNo++;
		            generateSettle();
				}else{
                    WST.msg(json.msg,{icon:1});
                    loadShopGrid();
				}
		}else{
			WST.msg(json.msg,{icon:2});
			loadShopGrid();
		}
	});
}
function generateSettleByShop(){
    var rows = mmg.selectedRows();
    if(rows.length==0){
        WST.msg('请选择要结算的商家!',{icon:2});
        return;
    }
    var ids = [];
    for(var i=0;i<rows.length;i++){
       ids.push(rows[i]['shopId']); 
    }
	shops = ids;
	WST.confirm({content:'您确定生成选中商家的结算单吗？',yes:function(){
        generateNo = 0;
	    generateSettle();
	}});
}