$(function(){
	queryByPage(0,0);
	$('#couponTab').TabPanel({tab:0,callback:function(no){
		queryByPage(no,0);
	}});
})
function queryByPage(status,p){
	$('#list').html('<tr><td colspan="11"><img src="'+WST.conf.ROOT+'/static/images/loading_16x16.gif">正在加载数据...</td></tr>');
	var params = {};
	params = {};
	params.status = status;
	params.page = p;
	$.post(WST.AU('coupon://users/pageQuery'),params,function(data,textStatus){
	    var json = WST.toJson(data);
	    if(json.status==1 && json.data){
	    	if(params.page>json.last_page && json.last_page >0){
               queryByPage(json.last_page);
               return;
            }
            $('#num'+status).html(json.Total);
	       	var gettpl = document.getElementById('couponstpl').innerHTML;
	       	laytpl(gettpl).render(json, function(html){
	       		$('#coupon-box'+status).html(html);
	       	});
	       	laypage({
		        	 cont: 'coupon-pager'+status, 
		        	 pages:json.last_page, 
		        	 curr: json.current_page,
		        	 skin: '#e23e3d',
		        	 groups: 3,
		        	 jump: function(e, first){
		        		    if(!first){
		        		    	queryByPage(status,e.curr);
		        		    }
		        	 } 
		    });
       	}  
	});
}