var mmg;
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'名称', name:'name', width: 100},
            {title:'描述', name:'hookRemarks', width: 100},
            {title:'对应插件', name:'addons' ,width:70, align:'center'}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-85,indexCol: true, cols: cols,method:'POST',
        url: WST.U('admin/hooks/pageQuery'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });  
}


//查询
function hooksQuery(){
	var query = WST.getParams('.query');
    query.page = 1;
	mmg.load(query);
}

