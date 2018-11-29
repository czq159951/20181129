WST.countDown = function(opts){
	var f = {
		zero: function(n){
			var n = parseInt(n, 10);
			if(n > 0){
				if(n <= 9){
					n = "0" + n;	
				}
				return String(n);
			}else{
				return "0";	
			}
		},
		count: function(){
			if(opts.nowTime){
				var d = new Date();
				d.setTime(opts.nowTime.getTime()+100);
				opts.nowTime = d;
				d = null;
			}else{
				opts.nowTime = new Date();
			}
			//现在将来秒差值
			var dtv = (opts.endTime.getTime() - opts.nowTime.getTime()), pms = {
				msec: "0",
				sec : "0",
				mini: "0",
				hour: "0",
				day : "0"
			};
			
			if(dtv > 0){
				var dur = Math.round(dtv / 1000);
				pms.dtv = dtv;
				pms.msec = (dtv % 1000)/100;
				pms.sec = f.zero(dur % 60);
				pms.mini = Math.floor((dur / 60)) > 0? f.zero(Math.floor((dur / 60)) % 60) : "0";
				pms.hour = Math.floor((dur / 3600)) > 0? f.zero(Math.floor((dur / 3600)) % 24) : "0";
				pms.day = Math.floor((dur / 86400)) > 0? Math.floor(dur / 86400) : "0";
			}
			pms.last = dtv;
			pms.nowTime = opts.nowTime;
			opts.callback(pms);
			if(pms.last>0)setTimeout(f.count, 100);
		}
	};	
	f.count();
};



function time(){
	var nowTime = new Date(Date.parse($('#wst-di-tuan').attr('sc').replace(/-/g, "/")));
	$('.timer').each(function(){
        var g = $(this);
        var startTime = new Date(Date.parse(g.attr('sv').replace(/-/g, "/")));
        var endTime = new Date(Date.parse(g.attr('ev').replace(/-/g, "/")));
        var gruopStatus = g.attr('st');
        if(gruopStatus==-1){
            g.addClass('wst-shl-list2');
			g.find('.countDown').html('拼团已结束');
        }else{
	        if(startTime.getTime()> nowTime){
	            var opts = {
		            nowTime: nowTime,
				    endTime: startTime,
				    callback: function(data){
				    	if(data.last>0){
				    		var html = [];
					    	if(data.day>0)html.push(data.day+"天");
					    	html.push(data.hour+":"+data.mini+":"+data.sec);
					        g.find('.countDown').html("还有"+html.join('')+"开始");
				    	}else{
				    		var opts2 = {
					            nowTime: data.nowTime,
							    endTime: endTime,
							    callback: function(data2){
							    	if(data2.last>0){
							    		var html = [];
								    	if(data2.day>0)html.push(data2.day+"天");
								    	html.push(data2.hour+":"+data2.mini+":"+data2.sec+"."+data2.msec);
								        g.find('.countDown').html(html.join(''));
							    	}else{
							    		g.addClass('wst-shl-list2');
							    		g.find('.countDown').html('拼团已结束');
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
					    	html.push(data.hour+":"+data.mini+":"+data.sec+"."+data.msec);
					        g.find('.countDown').html(html.join(''));
				    	}else{
				    		g.addClass('wst-shl-list2');
				    		g.find('.countDown').html('拼团已结束');
				    	}
				    	
				    }
				};
				WST.countDown(opts);
	        }else{
	        	g.addClass('wst-shl-list2');
	        	g.find('.countDown').html('拼团已结束');
	        }
		}
	})
}