function queryByPage(p){
	$('#list').html('<tr><td colspan="11"><img src="'+WST.conf.ROOT+'/static/images/loading_16x16.gif">正在加载数据...</td></tr>');
	var params = {};
	params = {};
	params.rewardTitle = $('#rewardTitle').val();
	params.page = p;
	$.post(WST.AU('reward://shops/pageQuery'),params,function(data,textStatus){
	    var json = WST.toJson(data);
	    if(json.status==1 && json.data){
	    	if(params.page>json.last_page && json.last_page >0){
               queryByPage(json.last_page);
               return;
            }
	       	var gettpl = document.getElementById('rewardtpl').innerHTML;
	       	laytpl(gettpl).render(json.data, function(html){
	       		$('#rewardlist').html(html);
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

function toEdit(id){
    location.href = WST.AU('reward://shops/edit','id='+id);
}
function toView(id){
	location.href = WST.AU('reward://goods/detail','id='+id);
}
var rewardNo = 0;
function initReward(){
	if(rewardObj.rewardId>0){
		var json = null,lastNo = 0,favourableJson = null;
        for(var i=0;i<rewardObj.rewardJson.length;i++){
        	addRewardLayer();
        	lastNo = rewardNo-1;
        	json = rewardObj.rewardJson[i];
        	$('#money-'+lastNo).val(json.orderMoney);
        	favourableJson = json.favourableJson;
        	if(favourableJson.chk0){
        		$('#j-chk-0-'+lastNo).click();
        		$('#j-reward-c-0-'+lastNo).val(favourableJson.chk0val);
        	}
        	if(favourableJson.chk1){
        		$('#j-chk-1-'+lastNo).click();
        		getGoods(lastNo,favourableJson.chk1val);
        	}
        	if(favourableJson.chk2)$('#j-chk-2-'+lastNo).attr('checked',true);
        	if(favourableJson.chk3){
        		$('#j-chk-3-'+lastNo).click();;
        		getCoupons(lastNo,favourableJson.chk3val);
        	}
        }
	}else{
		initRewardLayer();
	}
}
function initRewardLayer(){
	$('#rewardBox').empty();
	addRewardLayer();
}
function addRewardLayer(){
   var c = $('#rewardBox').children().size();
   if(c>=5){
   	   WST.msg('优惠层级最多只能建立五级',{icon:2});
   	   return;
   }
   var tpl = $('#rewardTPLBox').html();
   tpl = tpl.replace('{C}',(c+1));
   tpl = tpl.replace(/{NO}/g,rewardNo);
   $('#rewardBox').append(tpl);
   rewardNo++;
}
function checkReward(obj,no,rno){
	var v = obj.value;
	if(v==0 || v==1 || v==3){
        if($(obj).attr('dataval')=='0'){
        	$('#reward-'+rno+'-1-'+no).show();
        	$('#reward-'+rno+'-0-'+no).hide();
        	$(obj).attr('dataval',1);
        	if(v==1)getGoods(no,0);
            if(v==3)getCoupons(no,0);
        }else{
        	$('#reward-'+rno+'-0-'+no).show();
        	$('#reward-'+rno+'-1-'+no).hide();
        	$(obj).attr('dataval',0);
        }
	}
}
function getGoods(no,defaultValue){
    $('#j-reward-c-1-'+no).empty();
    $.post(WST.AU("reward://shops/getSaleGoods"),{},function(data,textStatus){
	    var json = WST.toJson(data);
	    if(json.status==1 && json.data){
	    	var html = [];
	    	for(var i=0;i<json.data.length;i++){
	    		html.push('<option value="'+json.data[i].goodsId+'">'+json.data[i].goodsName+'</option>');
	    	}
	    	$('#j-reward-c-1-'+no).html(html.join(''));
	    	if(defaultValue!=0)$('#j-reward-c-1-'+no).val(defaultValue);
	    }
	});
}
function getCoupons(no,defaultValue){
    $('#j-reward-c-3-'+no).empty();
    $.post(WST.AU("reward://shops/getCoupons"),{},function(data,textStatus){
	    var json = WST.toJson(data);
	    if(json.status==1 && json.data){
	    	var html = [];
	    	for(var i=0;i<json.data.length;i++){
	    		if(json.data[i].useCondition==1){
                    html.push('<option value="'+json.data[i].couponId+'">满'+json.data[i].useMoney+'减'+json.data[i].couponValue+'优惠券</option>');
	    		}else{
                    html.push('<option value="'+json.data[i].couponId+'">￥'+json.data[i].couponValue+'优惠券</option>');
	    		}
	    	}
	    	$('#j-reward-c-3-'+no).html(html.join(''));
	    	if(defaultValue!=0)$('#j-reward-c-3-'+no).val(defaultValue);
	    }
	});
}
function delRewardContent(no){
	if($('#rewardBox').children().size()<=1){
		WST.msg('至少要保留一个优惠内容',{icon:2});
        return;
	}
    $('#rewardContentTr_'+no).remove();
}
function save(){
    $('#rewardform').isValid(function(v){
		if(v){
			var params = WST.getParams('.ipt');
			var no = 0;
			$('.j-reward-money').each(function(){
				var n = $(this).attr('dataval');
				var isSet = false;
				if(n!='{NO}'){
					params['money-'+no] = $('#money-'+n).val();
					if(params['money-'+no]<=0){
						WST.msg('消费金额必须大于0',{icon:2});
						return;
					}
					params['chk-0-'+no] = $('#j-chk-0-'+n)[0].checked?1:0;
					params['j-reward-c-0-'+no] = $('#j-reward-c-0-'+n).val();
					if(params['chk-0-'+no] && params['j-reward-c-0-'+no]<=0){
						WST.msg('优惠金额必须大于0',{icon:2});
						return;
					}
					params['chk-1-'+no] = $('#j-chk-1-'+n)[0].checked?1:0;
					params['j-reward-c-1-'+no] = $('#j-reward-c-1-'+n).val();
					params['chk-2-'+no] = $('#j-chk-2-'+n)[0].checked?1:0;
					params['chk-3-'+no] = $('#j-chk-3-'+n)[0].checked?1:0;
					params['j-reward-c-3-'+no] = $('#j-reward-c-3-'+n).val();
					if(!params['chk-0-'+no] && !params['chk-1-'+no] && !params['chk-2-'+no] && !params['chk-3-'+no]){
						WST.msg('请选择优惠内容',{icon:2});
						return;
					}
					no++;
				}
			});
			params['no'] = no;
			var loading = WST.load({msg:'正在提交数据，请稍后...'});
			$.post(WST.AU("reward://shops/toEdit"),params,function(data,textStatus){
				layer.close(loading);
			    var json = WST.toJson(data);
			    if(json.status==1){
		            WST.msg(json.msg,{icon:1},function(){
		            	location.href = WST.AU('reward://shops/index');
		            });
			    }else{
			    	WST.msg(json.msg,{icon:2});
			    }
			});
		}
	});
}
function del(id){
    var box = WST.confirm({content:"您确定删除该活动吗?",yes:function(){
		layer.close(box);
		var loading = WST.load({msg:'正在提交请求，请稍后...'});
		$.post(WST.AU("reward://shops/del"),{id:id},function(data,textStatus){
			layer.close(loading);
		    var json = WST.toJson(data);
			if(json.status==1){
			    WST.msg(json.msg,{icon:1},function(){
			        queryByPage(WSTCurrPage);
			    });
		    }else{
				WST.msg(json.msg,{icon:2});
			}
		});
    }});
}

function searchGoods(){
	var params = WST.getParams('.s-ipt');
    var loading = WST.load({msg:'正在查询数据，请稍后...'});
	$.post(WST.AU("reward://shops/searchGoods"),params,function(data,textStatus){
		layer.close(loading);
	    var json = WST.toJson(data);
	    if(json.status==1 && json.data){
	    	var gettpl = document.getElementById('tblist').innerHTML;
	       	laytpl(gettpl).render(json.data, function(html){
	       		$('#goodsSearchBox').html(html);
	       	});
	    }
	});
}

function moveRight(){
	if($('.lchk').size()<=0)return;
	var ids = $('#useObjectIds').val();
	if(ids.length>0){
		ids = ids.split(',');
	}else{
		ids = [];
	}
	$('.lchk').each(function(){
		if($(this)[0].checked){
	        $(this).attr('class','rchk');
	        $('#goodsResultBox').append($(this).parent().parent());
	        ids.push($(this).val());
	    }
	})
	$('#useObjectIds').val(ids.join(','));
}

function moveLeft(){
	if($('.rchk').size()<=0)return;
	var ids = $('#useObjectIds').val().split(',');
	$('.rchk').each(function(){
		if($(this)[0].checked){
	        $(this).attr('class','lchk');
	        $('#goodsSearchBox').append($(this).parent().parent());
	        for(var i=0;i<ids.length;i++){
	        	if(ids[i]==$(this).val())ids.splice(i, 1);
	        }
	    }
	})
    $('#useObjectIds').val(ids.join(','));
}