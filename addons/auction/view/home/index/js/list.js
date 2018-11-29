$(function(){
	$('.goodsImg2').lazyload({ effect: "fadeIn",failurelimit : 10,skip_invisible : false,threshold: 100,placeholder:WST.conf.ROOT+'/'+WST.conf.GOODS_LOGO});//商品默认图片
	var nowTime = new Date(Date.parse($('#auction-container').attr('sc').replace(/-/g, "/")));
	$('.goods').each(function(){
        var g = $(this);
        var startTime = new Date(Date.parse(g.attr('sv').replace(/-/g, "/")));
        var endTime = new Date(Date.parse(g.attr('ev').replace(/-/g, "/")));
        if(startTime.getTime()> nowTime){
            var opts = {
	            nowTime: nowTime,
			    endTime: startTime,
			    callback: function(data){
			    	if(data.last>0){
			    		var html = [];
				    	if(data.day>0)html.push(data.day+"天");
				    	html.push(data.hour+"小时"+data.mini+"分"+data.sec+"秒");
				        g.find('.countDown').html("还有"+html.join('')+"开始");
			    	}else{
			    		var opts2 = {
				            nowTime: data.nowTime,
						    endTime: endTime,
						    callback: function(data2){
						    	if(data2.last>0){
						    		var html = [];
							    	if(data2.day>0)html.push(data2.day+"天");
							    	html.push(data2.hour+"小时"+data2.mini+"分"+data2.sec+"秒");
							        g.find('.countDown').html("剩余"+html.join(''));
						    	}else{
						    		g.addClass('out');
						    		g.find('.countDown').html('拍卖活动已结束');
						    	}
						    	
						    }
						}
			    	    WST.countDown(opts2);
			    	}
			    		
			    }
			};
			WST.countDown(opts);
        }else if(startTime.getTime()<= nowTime && endTime.getTime() >=nowTime){
            var opts = {
	            nowTime: nowTime,
			    endTime: endTime,
			    callback: function(data){
			    	if(data.last>0){
			    		var html = [];
				    	if(data.day>0)html.push(data.day+"天");
				    	html.push(data.hour+"小时"+data.mini+"分"+data.sec+"秒");
				        g.find('.countDown').html("剩余"+html.join(''));
			    	}else{
			    		g.addClass('out');
			    		g.find('.countDown').html('拍卖活动已结束');
			    	}
			    	
			    }
			};
			WST.countDown(opts);
        }else{
        	g.addClass('out');
        	g.find('.countDown').html('拍卖活动已结束');
        }
		
	})
});
