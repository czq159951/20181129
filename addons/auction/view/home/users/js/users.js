function queryByPage(p){
	$('#list').html('<tr><td colspan="11"><img src="'+WST.conf.ROOT+'/shangtao/home/view/default/img/loading.gif">正在加载数据...</td></tr>');
	var params = {};
	params = {};
	params.goodsName = $.trim($('#goodsName').val());
	params.page = p;
	$.post(WST.AU('auction://users/pageQuery'),params,function(data,textStatus){
	    var json = WST.toJson(data);
	    if(json.status==1){
	    	json = json.data;
	       	var gettpl = document.getElementById('tblist').innerHTML;
	       	laytpl(gettpl).render(json.data, function(html){
	       		$('#list').html(html);
	       		$('.j-lazyGoodsImg').lazyload({ effect: "fadeIn",failurelimit : 10,skip_invisible : false,threshold: 200,placeholder:WST.conf.ROOT+'/'+WST.conf.GOODS_LOGO});//商品默认图片
	       	});
	       	laypage({
		        	 cont: 'pager', 
		        	 pages:json.last_page, 
		        	 curr: json.current_page,
		        	 skin: '#e23e3d',
		        	 groups: 3,
		        	 jump: function(e, first){
		        		    if(!first){
		        		    	queryByPage(e.curr);
		        		    }
		        	 } 
		    });
       	}  
	});
}
function queryMoneyByPage(p){
	$('#list').html('<tr><td colspan="11"><img src="'+WST.conf.ROOT+'/shangtao/home/view/default/img/loading.gif">正在加载数据...</td></tr>');
	var params = {};
	params = {};
	params.goodsName = $.trim($('#goodsName').val());
	params.page = p;
	$.post(WST.AU('auction://users/pageQueryByMoney'),params,function(data,textStatus){
	    var json = WST.toJson(data);
	    if(json.status==1){
	    	json = json.data;
	       	var gettpl = document.getElementById('tblist').innerHTML;
	       	laytpl(gettpl).render(json.data, function(html){
	       		$('#list').html(html);
	       		$('.j-lazyGoodsImg').lazyload({ effect: "fadeIn",failurelimit : 10,skip_invisible : false,threshold: 200,placeholder:WST.conf.ROOT+'/'+WST.conf.GOODS_LOGO});//商品默认图片
	       	});
	       	laypage({
		        	 cont: 'pager', 
		        	 pages:json.last_page, 
		        	 curr: json.current_page,
		        	 skin: '#e23e3d',
		        	 groups: 3,
		        	 jump: function(e, first){
		        		    if(!first){
		        		    	queryByPage(e.curr);
		        		    }
		        	 } 
		    });
       	}  
	});
}