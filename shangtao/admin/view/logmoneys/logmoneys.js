var mmg1,mmg2,mmg3,mmg,h;
function initTab(){
	var element = layui.element;
	var isInit = isInit2 =  false;
	element.on('tab(msgTab)', function(data){
	     if(data.index==1){
	        if(!isInit){
	           isInit = true;
	           shopGridInit();
	        }else{
	           loadShopGrid();
	        }
	     }
	     if(data.index==2){
	        if(!isInit2){
	        	isInit2 = true;
	        	flowGridInit();
	        }else{
	        	loadFlowGrid();
	        }
	     }
	});
	userGridInit();
}
function userGridInit(){
	h = WST.pageHeight();
    var cols = [
            {title:'账号', name:'loginName', width: 50,sortable: true},
            {title:'名称', name:'userName' ,width:80,sortable: true},
            {title:'可用金额', name:'userMoney' ,width:200,sortable: true,renderer: function (rowdata, rowindex, value){
	        	return '￥'+rowindex['userMoney'];
	        }},
            {title:'冻结金额', name:'lockMoney' ,width:70,sortable: true,renderer: function (rowdata, rowindex, value){
	        	return '￥'+rowindex['lockMoney'];
	        }},
            {title:'充值送', name:'rechargeMoney' ,width:70,sortable: true,renderer: function (rowdata, rowindex, value){
                return '￥'+rowindex['rechargeMoney'];
            }},
            {title:'操作', name:'op' ,width:20,renderer: function (val,item,rowIndex){
	        	return '<a class="btn btn-blue" href="javascript:tologmoneys(0,'+item['userId']+')"><i class="fa fa-search"></i>查看</a>';
	        }}
            ];
 
    mmg1 = $('.mmg1').mmGrid({
        height: h-120,
        indexCol: true,
        indexColWidth:50, 
        cols: cols,
        method:'POST',
        url: WST.U('admin/logmoneys/pageQueryByUser'), 
        fullWidthRows:true, 
        autoLoad: true,
        remoteSort:true ,
        sortName: 'userMoney',
        sortStatus: 'desc',
        plugins: [
            $('#pg1').mmPaginator({})
        ]
    });
}
function shopGridInit(){
	h = WST.pageHeight();
    var cols = [
            {title:'账号', name:'loginName', width: 50},
            {title:'商家', name:'shopName' ,width:80},
            {title:'可用金额', name:'shopMoney' ,width:200,renderer: function (rowdata, rowindex, value){
	        	return '￥'+rowindex['shopMoney'];
	        }},
            {title:'冻结金额', name:'lockMoney' ,width:70,renderer: function (rowdata, rowindex, value){
	        	return '￥'+rowindex['lockMoney'];
	        }},
            {title:'充值送', name:'rechargeMoney' ,width:70,sortable: true,renderer: function (rowdata, rowindex, value){
                return '￥'+rowindex['rechargeMoney'];
            }},
            {title:'操作', name:'op' ,width:20,renderer: function (val,item,rowIndex){
	        	return '<a class="btn btn-blue" href="javascript:tologmoneys(1,'+item['shopId']+')"><i class="fa fa-search"></i>查看</a>';
	        }}
            ];
 
    mmg2 = $('.mmg2').mmGrid({height: h-120,indexCol: true,indexColWidth:50, cols: cols,method:'POST',
        url: WST.U('admin/logmoneys/pageQueryByShop'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg2').mmPaginator({})
        ]
    });
}
function flowGridInit(){
    var h = WST.pageHeight();
    var laydate = layui.laydate;
    laydate.render({
        elem: '#startDate'
    });
    laydate.render({
        elem: '#endDate'
    });
    var cols = [
            {title:'来源', name:'dataSrc', width: 30},
            {title:'个人账号/店铺名', name:'loginName', width: 30},
            {title:'金额', name:'money' ,width:20,renderer: function (rowdata, rowindex, value){
	        	if(rowindex['moneyType']==1){
                    return '<font color="red">+￥'+rowindex['money']+'</font>';
	        	}else{
                    return '<font color="green">-￥'+rowindex['money']+'</font>';
	        	}
	        }},
            {title:'备注', name:'remark',width:370},
            {title:'外部流水', name:'tradeNo',width:120},
            {title:'日期', name:'createTime' ,width:60}
            ];
 
    mmg3 = $('.mmg3').mmGrid({height: h-120,indexCol: true,indexColWidth:50, cols: cols,method:'POST',
        url: WST.U('admin/logmoneys/pageQuery'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg3').mmPaginator({})
        ]
    });
}
function loadUserGrid(){
	mmg1.load({page:1,key:$('#key1').val()});
}
function loadShopGrid(){
	mmg2.load({page:1,key:$('#key2').val()});
}
function loadFlowGrid(){
	mmg3.load({page:1,key:$('#key3').val(),type:$('#type').val(),startDate:$('#startDate').val(),endDate:$('#endDate').val()});
}
function tologmoneys(t,id){
	location.href= WST.U('admin/logmoneys/tologmoneys','id='+id+"&type="+t+"&startDate="+$('#startDate').val()+"&endDate="+'&endDate='+$('#endDate').val());
}

function moneyGridInit(type,id){
    var h = WST.pageHeight();
    var laydate = layui.laydate;
    laydate.render({
        elem: '#startDate'
    });
    laydate.render({
        elem: '#endDate'
    });
    var cols = [
            {title:'来源', name:'dataSrc', width: 30},
            {title:'金额', name:'money' ,width:20,renderer: function (rowdata, rowindex, value){
	        	if(rowindex['moneyType']==1){
                    return '<font color="red">+￥'+rowindex['money']+'</font>';
	        	}else{
                    return '<font color="green">-￥'+rowindex['money']+'</font>';
	        	}
	        }},
            {title:'备注', name:'remark',width:370},
            {title:'外部流水', name:'tradeNo',width:120},
            {title:'日期', name:'createTime' ,width:60}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-120,indexCol: true,indexColWidth:50, cols: cols,method:'POST',
        url: WST.U('admin/logmoneys/pageQuery','type='+type+'&id='+id), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });
}

function loadMoneyGrid(t,id){
	mmg.load({page:1,id:id,type:t,startDate:$('#startDate').val(),endDate:$('#endDate').val()});
}