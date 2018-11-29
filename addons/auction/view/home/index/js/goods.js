var auctionStatus = 0;
$(function(){
	WST.dropDownLayer(".item",".dorp-down-layer");
	$('.item-more').click(function(){
		if($(this).attr('v')==1){
			$('.hideItem').show(300);
			$(this).find("span").html("收起");
			$(this).find("i").attr({"class":"drop-up"});
			$(this).attr('v',0);
		}else{
			$('.hideItem').hide(300);
			$(this).find("span").html("更多选项");
			$(this).find("i").attr({"class":"drop-down-icon"});
			$(this).attr('v',1);
		}
	});
	$(".item-more").hover(function(){
		if($(this).find("i").hasClass("drop-down-icon")){
			$(this).find("i").attr({"class":"down-hover"});
		}else{
			$(this).find("i").attr({"class":"up-hover"});
		}
		
	},function(){
		if($(this).find("i").hasClass("down-hover")){
			$(this).find("i").attr({"class":"drop-down"});
		}else{
			$(this).find("i").attr({"class":"drop-up"});
		}
	});
	//图片放大镜效果
	CloudZoom.quickStart();
	imagesMove({id:'.goods-pics',items:'.items'});
	$('#auctionTab').TabPanel({tab:0,callback:function(no){
		if(no==1)getActionLog();
	}});
	var g = $('#auction-time');
	var nowTime = new Date(Date.parse(g.attr('sc').replace(/-/g, "/")));
    var startTime = new Date(Date.parse(g.attr('sv').replace(/-/g, "/")));
    var endTime = new Date(Date.parse(g.attr('ev').replace(/-/g, "/")));
    if(startTime.getTime()> nowTime.getTime()){
        var opts = {
        	nowTime:nowTime,
			endTime: startTime,
			callback: function(data){
			    if(data.last>0){
			    	var html = [];
				    if(data.day>0)html.push(data.day+"天");
				    html.push(data.hour+"小时"+data.mini+"分"+data.sec+"秒");
				    g.html("拍卖活动还有"+html.join('')+"开始");
				    $('.caution').addClass('out');
				    auctionStatus = 0;
			    }else{
			    	var opts2 = {
	                    nowTime: data.nowTime,
						endTime: endTime,
						callback: function(data2){
						    if(data2.last>=0){
						    	var html = [];
							    if(data2.day>0)html.push(data2.day+"天");
							    html.push(data2.hour+"小时"+data2.mini+"分"+data2.sec+"秒");
							    g.html("拍卖活动剩余"+html.join(''));
							    $('#buyBtn').removeClass('un-buy').attr('href','javascript:addCart(1,"#buyNum")');
                                $('.caution').removeClass('out');
                                auctionStatus = 1;
						    }else{
						    	auctionStatus = -1;
						    	$('.caution').addClass('out');
						    	g.html('拍卖活动已结束');
						    }
						    	
						}
					}
			    	WST.countDown(opts2);
			    }		
			}
		};
		WST.countDown(opts);
    }else if(startTime.getTime()<= nowTime.getTime() && endTime.getTime() >=nowTime.getTime()){
        var opts = {
        	nowTime:nowTime,
			endTime: endTime,
			callback: function(data){
			    if(data.last>0){
			    	var html = [];
				    if(data.day>0)html.push(data.day+"天");
				    html.push(data.hour+"小时"+data.mini+"分"+data.sec+"秒");
				    g.html("拍卖活动剩余"+html.join(''));
				    $('.caution').removeClass('out');
				    auctionStatus = 1;
			    }else{
			    	$('#buyBtn').addClass('un-buy').attr('href','javascript:void(0)');
			    	g.html('拍卖活动已结束');
			    	$('.caution').addClass('out');
			    	auctionStatus = -1;
			    }			    	
			}
		};
		WST.countDown(opts);
    }else{
        $('#buyBtn').addClass('un-buy').attr('href','javascript:void(0)');
        g.html('拍卖活动已结束');
        $('.caution').addClass('out');
        auctionStatus = -1;
    }
});

function imagesMove(opts){
	var tempLength = 0; //临时变量,当前移动的长度
	var viewNum = 5; //设置每次显示图片的个数量
	var moveNum = 2; //每次移动的数量
	var moveTime = 300; //移动速度,毫秒
	var scrollDiv = $(opts.id+" "+opts.items+" ul"); //进行移动动画的容器
	var scrollItems = $(opts.id+" "+opts.items+" ul li"); //移动容器里的集合
	var moveLength = scrollItems.eq(0).width() * moveNum; //计算每次移动的长度
	var countLength = (scrollItems.length - viewNum) * scrollItems.eq(0).width(); //计算总长度,总个数*单个长度
	  
	//下一张
	$(opts.id+" .next").bind("click",function(){
		if(tempLength < countLength){
			if((countLength - tempLength) > moveLength){
				scrollDiv.animate({left:"-=" + moveLength + "px"}, moveTime);
				tempLength += moveLength;
			}else{
				scrollDiv.animate({left:"-=" + (countLength - tempLength) + "px"}, moveTime);
				tempLength += (countLength - tempLength);
			}
		}
	});
	//上一张
	$(opts.id+" .prev").bind("click",function(){
		if(tempLength > 0){
			if(tempLength > moveLength){
				scrollDiv.animate({left: "+=" + moveLength + "px"}, moveTime);
				tempLength -= moveLength;
			}else{
				scrollDiv.animate({left: "+=" + tempLength + "px"}, moveTime);
				tempLength = 0;
			}
		}
	});
}

function payCaution(){
	if(WST.conf.IS_LOGIN==0){
		WST.loginWindow();
		return;
	}
	if(auctionStatus==0){
		return;
	}else if(auctionStatus==-1){
		WST.msg('拍卖已结束',{icon:2});
		return;
	}
	layer.open({
	    type: 1,
	    title: '在线支付提示',
	    shadeClose: true,
	    shade: 0.65,
	    closeBtn:0,
	    shadeClose:false,
	    area: ['600px', 280 +'px'],
	    content: $('#aaa'),
	    btn: ['支付完成','支付遇到问题'],
	    yes: function(index, layero){
	    	layer.close(index);
	    	location.reload();
	    },
	    cancel: function(index){
	    	layer.close(index);
		}
	});
	var auctionId = $("#auctionId").val();
	$("#blank").attr("href",WST.AU('auction://auction/toPay',{"auctionId":auctionId}));
	document.getElementById("blank").click(); 
}
function addAcution(){
	if(WST.conf.IS_LOGIN==0){
		WST.loginWindow();
		return;
	}
	var box = WST.confirm({content:"您确定要出价￥"+$('#payPrice').val()+"竞拍商品吗?",yes:function(){
		layer.close(box);
		var loading = WST.load({msg:'正在提交请求，请稍后...'});
		$.post(WST.AU('auction://auction/addAcution'),{id:goodsInfo.auctionId,payPrice:$('#payPrice').val(),rnd:Math.random()},function(data,textStatus){
		     layer.close(loading);
		     var json = WST.toJson(data);
		     if(json.status==1){
		    	 WST.msg(json.msg,{icon:1},function(){
		    	 	location.reload();
		    	 });
		     }else{
		    	 WST.msg(json.msg,{icon:2});
		     }
		});
	}});
}
function getActionLog(p){
	$.post(WST.AU('auction://auction/pageQueryByAuctionLog'),{page:p,id:goodsInfo.auctionId,rnd:Math.random()},function(data,textStatus){
		var json = WST.toJson(data);
		if(json.status==1 && json.data){
		     json = json.data;
		     var gettpl = document.getElementById('tblist').innerHTML;
	       	 laytpl(gettpl).render(json.data, function(html){
	       		$('#auction-log-box').html(html);
	       	 });
	       	 laypage({
		        cont: 'pager', 
		        pages:json.last_page, 
		        curr: json.current_page,
		        skin: '#e23e3d',
		        groups: 3,
		        jump: function(e, first){
		        	if(!first){
		        		getActionLog(e.curr);
		        	}
		        } 
		     });
		}
	});
}