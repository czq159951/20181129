function qrCode(){
    $("#wst-dialog5").dialog("show");
}
function inChoice(obj,type){
	$('#type').val(type);
	if(type==0){
		$(obj).addClass('active').siblings('.wst-sha-head .choose').removeClass('active');
	}else{
		$(obj).addClass('active').siblings('.wst-sha-head .choose').removeClass('active');
	}
	$('#totalPage').val(0);
	$('#currPage').val(0);
	$('#infoList').html('');
	getCommissionList();
}
function getCommissionList(){
	  $('#Load').show();
	  loading = true;
	  var param = {};
	  param.pageSize = 10;
	  param.currPage = Number( $('#currPage').val() ) + 1;
	  param.type = $('#type').val();
	  $('#data-list').empty();
	  $.post(WST.U('addon/distribut-distribut-querywechatdistributmoneys'), param,function(data){
	        var json = WST.toJson(data);
	        $('#currPage').val(json.current_page);
	        $('#totalPage').val(json.last_page);
	        var gettpl = document.getElementById('list').innerHTML;
	        laytpl(gettpl).render(json.data, function(html){
	            $('#data-list').append(html);
	        });
	        WST.imgAdapt('j-imgAdapt');
	        loading = false;
	        $('#Load').hide();
	        echo.init();//图片懒加载
	    });
	  
}


function getusersList(){
	  $('#Load').show();
	  loading = true;
	  var param = {};
	  param.pageSize = 10;
	  param.currPage = Number( $('#currPage').val() ) + 1;
	  $('#data-list').empty();
	  $.post(WST.U('addon/distribut-distribut-querywechatdistributusers'), param, function(data){
		  var json = WST.toJson(data);
	        $('#currPage').val(json.current_page);
	        $('#totalPage').val(json.last_page);
	        var gettpl = document.getElementById('list').innerHTML;
	        laytpl(gettpl).render(json.data, function(html){
	            $('#data-list').append(html);
	        });
	        WST.imgAdapt('j-imgAdapt');
	        loading = false;
	        $('#Load').hide();
	        echo.init();//图片懒加载
	  });
}
