function checkChks(obj,cobj){
	WST.checkChks(obj,cobj);
	$(cobj).each(function(){
		id = $(this).val();
		if(obj.checked){
			$(this).addClass('selected');
		}else{
			$(this).removeClass('selected');
		}
		var cid = $(this).find(".j-chk").val();
		if(cid!=''){
		    WST.changeCartGoods(cid,$('#buyNum_'+cid).val(),obj.checked?1:0);
		    statCartMoney();
	    }
	})
}
function statCartMoney(){
	var cartMoney = 0,goodsTotalPrice,id;
	$('.j-gchk').each(function(){
		id = $(this).val();
		goodsTotalPrice = parseFloat($(this).attr('mval'))*parseInt($('#buyNum_'+id).val());
		$('#tprice_'+id).html(goodsTotalPrice);
		if($(this).prop('checked')){	
			cartMoney = cartMoney + goodsTotalPrice;
		}
	});
	$('#totalMoney').html(cartMoney);
}

function addrBoxOver(t){
	$(t).addClass('radio-box-hover');
	$(t).find('.operate-box').show();
}
function addrBoxOut(t){
	$(t).removeClass('radio-box-hover');
	$(t).find('.operate-box').hide();
}

function setDeaultAddr(id){
	$.post(WST.U('home/useraddress/setDefault'),{id:id},function(data){
		var json = WST.toJson(data);
		if(json.status==1){
			getAddressList();
			changeAddrId(id);
		}
	});
}


function changeAddrId(id){
	$.post(WST.U('home/useraddress/getById'),{id:id},function(data){
		var json = WST.toJson(data);
		if(json.status==1){
			inEffect($('#addr-'+id),1);
			$('#s_addressId').val(json.data.addressId);
			$("select[id^='area_0_']").remove();
			var areaIdPath = json.data.areaIdPath.split("_");
			// 设置收货地区市级id
			$('#s_areaId').val(areaIdPath[1]);
             
	     	$('#area_0').val(areaIdPath[0]);
	     	// 计算运费
			getCartMoney();
	     	var aopts = {id:'area_0',val:areaIdPath[0],childIds:areaIdPath,className:'j-areas'}
	 		WST.ITSetAreas(aopts);
			WST.setValues(json.data);
		}
	})
}

function delAddr(id){
	WST.confirm({content:'您确定要删除该地址吗？',yes:function(index){
		$.post(WST.U('home/useraddress/del'),{id:id},function(data,textStatus){
		     var json = WST.toJson(data);
		     if(json.status==1){
		    	 WST.msg(json.msg,{icon:1});
		    	 getAddressList();
		     }else{
		    	 WST.msg(json.msg,{icon:2});
		     }
		});
	}});
}

function getAddressList(obj){
	var id = $('#s_addressId').val();
	var load = WST.load({msg:'正在加载记录，请稍后...'});
	$.post(WST.U('home/useraddress/listQuery'),{rnd:Math.random()},function(data,textStatus){
		 layer.close(load);
	     var json = WST.toJson(data);
	     if(json.status==1){
	    	 if(json.data && json.data && json.data.length){
	    		 var html = [],tmp;
	    		 for(var i=0;i<json.data.length;i++){
	    			 tmp = json.data[i];
	    			 var selected = (id==tmp.addressId)?'j-selected':'';
	    			 html.push(
	    					 '<div class="wst-frame1 '+selected+'" onclick="javascript:changeAddrId('+tmp.addressId+')" id="addr-'+tmp.addressId+'" >'+tmp.userName+'<i></i></div>',
	    					 '<li class="radio-box" onmouseover="addrBoxOver(this)" onmouseout="addrBoxOut(this)">',
	    					 tmp.userName,
	    					 '&nbsp;&nbsp;',
	    					 tmp.areaName+tmp.userAddress,
	    					 '&nbsp;&nbsp;&nbsp;&nbsp;',
	    					 tmp.userPhone
	    					 )
	    			if(tmp.isDefault==1){
	    				html.push('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="j-default">默认地址</span>')
	    			}		
	    			html.push('<div class="operate-box">');
	    			if(tmp.isDefault!=1){
	    				html.push('<a href="javascript:;" onclick="setDeaultAddr('+tmp.addressId+')">设为默认地址</a>&nbsp;&nbsp;');
	    			}
	    			html.push('<a href="javascript:void(0)" onclick="javascript:toEditAddress('+tmp.addressId+',this,1,1)">编辑</a>&nbsp;&nbsp;');
	    			if(json.data.length>1){
	    				html.push('<a href="javascript:void(0)" onclick="javascript:delAddr('+tmp.addressId+',this)">删除</a></div>');
	    			}
	    			html.push('<div class="wst-clear"></div>','</li>');
	    		 }
	    		 html.push('<a style="color:#1c9eff" onclick="editAddress()" href="javascript:;">收起地址</a>'); 


	    		 $('#addressList').html(html.join(''));
	    	 }else{
	    		 $('#addressList').empty();
	    	 }
	     }else{
	    	 $('#addressList').empty();
	     }
	})
}

function inEffect(obj,n){
	$(obj).addClass('j-selected').siblings('.wst-frame'+n).removeClass('j-selected');
}
function editAddress(){
	var isNoSelected = false;
	$('.j-areas').each(function(){
		isSelected = true;
		if($(this).val()==''){
			isNoSelected = true;
			return;
		}
	})
	if(isNoSelected){
		WST.msg('请选择完整收货地址！',{icon:2});
		return;
	}
	layer.close(layerbox);
	var load = WST.load({msg:'正在提交数据，请稍后...'});
	var params = WST.getParams('.j-eipt');
	params.areaId = WST.ITGetAreaVal('j-areas');
	$.post(WST.U('home/useraddress/'+((params.addressId>0)?'toEdit':'add')),params,function(data,textStatus){
		layer.close(load);
		var json = WST.toJson(data);
	     if(json.status==1){
	    	 $('.j-edit-box').hide();
	    	 $('.j-list-box').hide();
	    	 $('.j-show-box').show();
	    	 if(params.addressId==0){
	    		 $('#s_addressId').val(json.data.addressId);
	    	 }else{
	    		 $('#s_addressId').val(params.addressId);
	    	 }
	    	 var areaIds = WST.ITGetAllAreaVals('area_0','j-areas');
	    	 $('#s_areaId').val(areaIds[1]);
	    	 getCartMoney();
	    	 var areaNames = [];
	    	 $('.j-areas').each(function(){
	    		 areaNames.push($('#'+$(this).attr('id')+' option:selected').text());
	    	 })
	    	 $('#s_userName').html(params.userName+'<i></i>');
	    	 $('#s_address').html(params.userName+'&nbsp;&nbsp;&nbsp;'+areaNames.join('')+'&nbsp;&nbsp;'+params.userAddress+'&nbsp;&nbsp;'+params.userPhone);

	    	 $('#s_address').siblings('.operate-box').find('a').attr('onclick','toEditAddress('+params.addressId+',this,1,1,1)');

	    	 if(params.isDefault==1){
	    		 $('#isdefault').html('默认地址').addClass('j-default');
	    	 }else{
	    		 $('#isdefault').html('').removeClass('j-default');
	    	 }
	     }else{
	    	 WST.msg(json.msg,{icon:2});
	     }
	});
}
var layerbox;
function showEditAddressBox(){
	getAddressList();
	toEditAddress();
}
function emptyAddress(obj,n){
	inEffect(obj,n);
	$('#addressForm')[0].reset();
	$('#s_addressId').val(0);
	$('#addressId').val(0);
	$("select[id^='area_0_']").remove();

	layerbox =	layer.open({
					title:'用户地址',
					type: 1,
					area: ['800px', '300px'],
					content: $('.j-edit-box')
					});
}
function toEditAddress(id,obj,n,flag,type){
	inEffect(obj,n);
	id = (id>0)?id:$('#s_addressId').val();
	$.post(WST.U('home/useraddress/getById'),{id:id},function(data,textStatus){
	     var json = WST.toJson(data);
	     if(json.status==1){
	     	if(flag){
		     	layerbox =	layer.open({
					title:'用户地址',
					type: 1,
					area: ['800px', '300px'], //宽高
					content: $('.j-edit-box')
				});
	     	}
	     	if(type!=1){
				 $('.j-list-box').show();
		    	 $('.j-show-box').hide();
	     	}
	    	 WST.setValues(json.data);
	    	 $('input[name="addrUserPhone"]').val(json.data.userPhone)
	    	 $("select[id^='area_0_']").remove();
	    	 if(id>0){
		    	 var areaIdPath = json.data.areaIdPath.split("_");
		     	 $('#area_0').val(areaIdPath[0]);
		     	 var aopts = {id:'area_0',val:areaIdPath[0],childIds:areaIdPath,className:'j-areas'}
		 		 WST.ITSetAreas(aopts);
	    	 }
	     }else{
	    	 WST.msg(json.msg,{icon:2});
	     }
	});
}
function getCartMoney(){
	var params = {};
	params.isUseScore = $('#isUseScore').prop('checked')?1:0;
	params.useScore = $('#useScore').val();
	params.areaId2 = $('#s_areaId').val();
	params.rnd = Math.random();
	params.deliverType = $('#deliverType').val();
	var load = WST.load({msg:'正在计算订单价格，请稍后...'});
	$.post(WST.AU('pintuan://carts/getCartMoney'),params,function(data,textStatus){
		layer.close(load);  
		var json = WST.toJson(data);
		if(json.status==1){
		    json = json.data;
		    var shopFreight = 0;
		    // 设置每间店铺的运费及总价格
		    $('#shopF_'+json.shops['shopId']).html(json.shops['freight']);
		    $('#shopC_'+json.shops['shopId']).html(json.shops['goodsMoney']);
		    shopFreight = shopFreight + json.shops['freight'];
		    $('#deliverMoney').html(shopFreight);
		    $('#useScore').val(json.useScore);
		    $('#scoreMoney2').html(json.scoreMoney);
		 	$('#totalMoney').html(json.realTotalMoney);
		}
	});
}
function changeDeliverType(n,index,obj){
	changeSelected(n,index,obj);
	getCartMoney();
}
function submitOrder(){
	var params = WST.getParams('.j-ipt');
	params.isUseScore = $('#isUseScore').prop('checked')?1:0
	var load = WST.load({msg:'正在提交，请稍后...'});
	$.post(WST.AU('pintuan://carts/submit'),params,function(data,textStatus){
		layer.close(load);   
		var json = WST.toJson(data);
	    if(json.status==1){
	    	 WST.msg(json.msg,{icon:1},function(){
	    		 location.href=WST.U('home/orders/succeed','orderNo='+json.data);
	    	 });
	    }else{
	    	WST.msg(json.msg,{icon:2});
	    }
	});
}



function changeInvoice(t,str,obj){
	WST.showHide(t,str);
	changeSelected(t,'isInvoice',obj);
}
function changeSelected(n,index,obj){
	$('#'+index).val(n);
	inEffect(obj,2);
}

function checkScoreBox(v){
    if(v){
    	var val = $('#isUseScore').attr('dataval');
    	$('#useScore').val(val);
        $('#scoreMoney').show();

    }else{
    	$('#scoreMoney').hide();
    }
    getCartMoney();
}