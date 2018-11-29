function queryByPage(p){
	$('#list').html('<tr><td colspan="11"><img src="'+WST.conf.ROOT+'/shangtao/home/view/default/img/loading.gif">正在加载数据...</td></tr>');
	var params = {};
	params = {};
	params.goodsName = $.trim($('#goodsName').val());
	params.page = p;
	$.post(WST.AU('auction://shops/pageQuery'),params,function(data,textStatus){
	    var json = WST.toJson(data);
	    if(json.status==1 && json.data){
	    	if(params.page>json.last_page && json.last_page >0){
               queryByPage(json.last_page);
               return;
            }
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
function searchGoods(){
	var params = {};
	params.shopCatId1 = $('#shopCatId1').val();
	params.shopCatId2 = $('#shopCatId2').val();
    params.goodsName = $('#goodsName').val();
    if(params.shopCatId1=='' && params.goodsName==''){
		 WST.msg('请至少选择商品分类',{icon:2});
		 return;
	}
	$('#goodsId').empty();
    var loading = WST.load({msg:'正在查询数据，请稍后...'});
	$.post(WST.AU("auction://shops/searchGoods"),params,function(data,textStatus){
		layer.close(loading);
	    var json = WST.toJson(data);
	    if(json.status==1 && json.data){
	    	var html = [];
	    	var option1 = [];
	    	for(var i=0;i<json.data.length;i++){
	    		if(i==0)option1 = json.data[i];
                html.push('<option value="'+json.data[i].goodsId+'" gt="'+json.data[i].goodsType+'" mp="'+json.data[i].marketPrice+'" sp="'+json.data[i].marketPrice+'">'+json.data[i].goodsName+'</option>');
	    	}
	    	$('#goodsId').html(html.join(''));
	    	$('#marketPrice').html("￥"+option1.marketPrice);
	    }
	});
}
function changeGoods(obj){
    var opts = $(obj).find("option:selected");
    $('#marketPrice').html(opts.attr('mp'));
}
function toEdit(id){
    location.href = WST.AU('auction://shops/edit','id='+id);
}
function toView(id){
	location.href = WST.AU('auction://goods/detail','id='+id);
}

function save(){
    $('#editform').isValid(function(v){
		if(v){
			var params = WST.getParams('.ipt');
			if(params.goodsId==''){
				WST.msg('请选择要参与拍卖的商品',{icon:2});
				return;
			}
			var loading = WST.load({msg:'正在提交数据，请稍后...'});
			$.post(WST.AU("auction://shops/toEdit"),params,function(data,textStatus){
				layer.close(loading);
			    var json = WST.toJson(data);
			    if(json.status==1){
		            WST.msg(json.msg,{icon:1},function(){
		            	location.href = WST.AU('auction://shops/auction');
		            });
			    }else{
			    	WST.msg(json.msg,{icon:2});
			    }
			});
		}
	});
}
function del(id){
	var box = WST.confirm({content:"您确定删除该拍卖商品吗?",yes:function(){
		layer.close(box);
		var loading = WST.load({msg:'正在提交请求，请稍后...'});
		$.post(WST.AU("auction://shops/del"),{id:id},function(data,textStatus){
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
function listByPage(p){
	$('#loading').show();
	var params = {};
	params = WST.getParams('.s-ipt');
	params.page = p;
	$.post(WST.AU('auction://shops/pageAuctionLogQueryByShops'),params,function(data,textStatus){
		$('#loading').hide();
	    var json = WST.toJson(data);
	    $('.j-order-row').remove();
	    if(json.status==1){
	    	json = json.data;
	       	var gettpl = document.getElementById('tblist').innerHTML;
	       	laytpl(gettpl).render(json.data, function(html){
	       		$(html).insertAfter('#loadingBdy');
	       		$('.gImg').lazyload({ effect: "fadeIn",failurelimit : 10,skip_invisible : false,threshold: 200,placeholder:WST.conf.ROOT+'/'+WST.conf.GOODS_LOGO});
	       	});
	       	laypage({
		        cont: 'pager', 
		        pages:json.last_page, 
		        curr: json.current_page,
		        skin: '#e23e3d',
		        groups: 3,
		        jump: function(e, first){
		        	if(!first){
		        			 listByPage(e.curr);
		        	}
		        } 
		    });
       	} 
	});
}
function view(id){
    location.href=WST.U('home/orders/view','id='+id);
}
var editor1;
function initForm(){
	KindEditor.ready(function(K) {
		editor1 = K.create('textarea[name="auctionDesc"]', {
			height:'550px',
			width:'100%',
			uploadJson : WST.conf.ROOT+'/home/goods/editorUpload',
			allowFileManager : false,
			allowImageUpload : true,
			allowMediaUpload : false,
			items:[
				'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
				'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
				'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
				'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
				'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
				'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|','image','multiimage','media','table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
				'anchor', 'link', 'unlink', '|', 'about'
			],
			afterBlur: function(){ this.sync(); }
		});
	});
}