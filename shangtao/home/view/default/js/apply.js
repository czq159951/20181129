function initStep2(businessAreaPath,shopAreaIdPath,longitude,latitude,mapLevel){
	if(businessAreaPath!=''){
		var areaIdPath = businessAreaPath.split("_");
	    $('#area_0').val(areaIdPath[0]);
	    var aopts = {id:'area_0',val:areaIdPath[0],childIds:areaIdPath,className:'j-areas',isRequire:true}
		WST.ITSetAreas(aopts);
	}
	if(shopAreaIdPath!=''){
		var areaIdPath = shopAreaIdPath.split("_");
	    $('#carea_0').val(areaIdPath[0]);
	    var aopts = {id:'carea_0',val:areaIdPath[0],childIds:areaIdPath,className:'j-careas',isRequire:true}
		WST.ITSetAreas(aopts);
	}
    WST.upload({
		  	  pick:'#legalCertificateImgPicker',
		  	  formData: {dir:'shops'},
		  	  accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
		  	  callback:function(f){
		  		  var json = WST.toJson(f);
		  		  if(json.status==1){
		  			  $('#legalCertificateImgMsg').empty().hide();
		              $('#legalCertificateImgPreview').attr('src',WST.conf.ROOT+"/"+json.savePath+json.thumb).show();
		              $('#legalCertificateImg').val(json.savePath+json.name);
		              $('#msg_legalCertificateImg').hide();
		  		  }
			  },
			  progress:function(rate){
			      $('#legalCertificateImgMsg').show().html('已上传'+rate+"%");
			  }
		});
    WST.upload({
		  	  pick:'#businessLicenceImgPicker',
		  	  formData: {dir:'shops'},
		  	  accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
		  	  callback:function(f){
		  		  var json = WST.toJson(f);
		  		  if(json.status==1){
		  			  $('#businessLicenceImgMsg').empty().hide();
		              $('#businessLicenceImgPreview').attr('src',WST.conf.ROOT+"/"+json.savePath+json.thumb).show();
		              $('#businessLicenceImg').val(json.savePath+json.name);
		              $('#msg_businessLicenceImg').hide();
		  		  }
			  },
			  progress:function(rate){
			      $('#businessLicenceImgMsg').show().html('已上传'+rate+"%");
			  }
		});
    WST.upload({
		  	  pick:'#bankAccountPermitImgPicker',
		  	  formData: {dir:'shops'},
		  	  accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
		  	  callback:function(f){
		  		  var json = WST.toJson(f);
		  		  if(json.status==1){
		  			  $('#bankAccountPermitImgMsg').empty().hide();
		              $('#bankAccountPermitImgPreview').attr('src',WST.conf.ROOT+"/"+json.savePath+json.thumb).show();
		              $('#bankAccountPermitImg').val(json.savePath+json.name);
		              $('#msg_bankAccountPermitImg').hide();
		  		  }
			  },
			  progress:function(rate){
			      $('#bankAccountPermitImgMsg').show().html('已上传'+rate+"%");
			  }
		});
    WST.upload({
		  	  pick:'#organizationCodeImgPicker',
		  	  formData: {dir:'shops'},
		  	  accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
		  	  callback:function(f){
		  		  var json = WST.toJson(f);
		  		  if(json.status==1){
		  			  $('#organizationCodeImgMsg').empty().hide();
		              $('#organizationCodeImgPreview').attr('src',WST.conf.ROOT+"/"+json.savePath+json.thumb).show();
		              $('#organizationCodeImg').val(json.savePath+json.name);
		              $('#msg_organizationCodeImg').hide();
		  		  }
			  },
			  progress:function(rate){
			      $('#organizationCodeImgMsg').show().html('已上传'+rate+"%");
			  }
		});
    if(window.conf.MAP_KEY){
	    initQQMap(longitude,latitude,mapLevel);
	 }
}
function delVO(obj){
   $(obj).parent().remove();
}
function initStep3(areaPath){
	if(areaPath!=''){
		var areaIdPath = areaPath.split("_");
	    $('#barea_0').val(areaIdPath[0]);
	    var aopts = {id:'barea_0',val:areaIdPath[0],childIds:areaIdPath,className:'j-bareas',isRequire:true}
		WST.ITSetAreas(aopts);
	}
	var uploader = WST.upload({
		  	  pick:'#taxRegistrationCertificateImgPicker',
		  	  formData: {dir:'shops'},
		  	  accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
		  	  fileNumLimit:3,
		  	  callback:function(f,file){
		  		  var json = WST.toJson(f);
		  		  if(json.status==1){
		  			  $('#taxRegistrationCertificateImgMsg').empty().hide();
		  			  var tdiv = $("<div style='width:75px;float:left;margin-right:5px;'>"+
                       "<img class='step_pic"+"' width='75' height='75' src='"+WST.conf.ROOT+"/"+json.savePath+json.thumb+"' v='"+json.savePath+json.name+"'></div>");
			          var btn = $('<div style="position:relative;top:-80px;left:60px;cursor:pointer;" ><img src="'+WST.conf.ROOT+'/shangtao/home/view/default/img/seller_icon_error.png"></div>');
			          tdiv.append(btn);
			          $('#taxRegistrationCertificateImgBox').append(tdiv);
			          $('#msg_taxRegistrationCertificateImg').hide();
			          var imgPath = [];
			          $('.step_pic').each(function(){
                          imgPath.push($(this).attr('v'));
			          });
                      $('#taxRegistrationCertificateImg').val(imgPath.join(','));
			          btn.on('click','img',function(){
			              uploader.removeFile(file);
			              $(this).parent().parent().remove();
			              uploader.refresh();
			              if($('#taxRegistrationCertificateImgBox').children().size()<=0){
			              	  $('#msg_taxRegistrationCertificateImg').show();
			              }
			          });
		  		  }else{
		  		  	  WST.msg(json.msg,{icon:2});
		  		  }
			  },
			  progress:function(rate){
			      $('#taxRegistrationCertificateImgMsg').show().html('已上传'+rate+"%");
			  }
		});
    WST.upload({
		  	  pick:'#taxpayerQualificationImgPicker',
		  	  formData: {dir:'shops'},
		  	  accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
		  	  callback:function(f){
		  		  var json = WST.toJson(f);
		  		  if(json.status==1){
		  			  $('#taxpayerQualificationImgMsg').empty().hide();
		              $('#taxpayerQualificationImgPreview').attr('src',WST.conf.ROOT+"/"+json.savePath+json.thumb).show();
		              $('#taxpayerQualificationImg').val(json.savePath+json.name);
		              $('#msg_taxpayerQualificationImg').hide();
		  		  }
			  },
			  progress:function(rate){
			      $('#taxpayerQualificationImgMsg').show().html('已上传'+rate+"%");
			  }
		}); 
}

function initStep4(){
    WST.upload({
		  	  pick:'#shopImgPicker',
		  	  formData: {dir:'shops'},
		  	  accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
		  	  callback:function(f){
		  		  var json = WST.toJson(f);
		  		  if(json.status==1){
		  			  $('#shopImgMsg').empty().hide();
		              $('#shopImgPreview').attr('src',WST.conf.ROOT+"/"+json.savePath+json.thumb).show();
		              $('#shopImg').val(json.savePath+json.name);
		              $('#msg_shopImg').hide();
		  		  }
			  },
			  progress:function(rate){
			      $('#shopImgMsg').show().html('已上传'+rate+"%");
			  }
		}); 
    initTime('#serviceStartTime',$('#serviceStartTime').attr('v'));
	initTime('#serviceEndTime',$('#serviceEndTime').attr('v'));
}
function initTime(id,val){
	var html = [],t0,t1;
	var str = val.split(':');
	for(var i=0;i<24;i++){
		t0 = (val.indexOf(':00')>-1 && (parseInt(str[0],10)==i))?'selected':'';
		t1 = (val.indexOf(':30')>-1 && (parseInt(str[0],10)==i))?'selected':'';
		html.push('<option value="'+i+':00" '+t0+'>'+i+':00</option>');
		html.push('<option value="'+i+':30" '+t1+'>'+i+':30</option>');
	}
	$(id).append(html.join(''));
}
function checkProtocol(obj){
    if(obj.checked){
    	$('.msg-box').hide();
    }else{
    	$('.msg-box').show();
    }
}
function saveStep1(){
	if($('#protocol')[0].checked){
        location.href=WST.U('home/shops/joinStep2');
	}else{
		$('.msg-box').show();
	}
}
function saveStep2(){
	$('#applyFrom').isValid(function(v){
		if(v){
			var params = WST.getParams('.a-ipt');
			var load = WST.load({msg:'正在提交请求，请稍后...'});
		    $.post(WST.U('home/shops/saveStep2'),params,function(data,textStatus){
		        var json = WST.toJson(data);
		        if(json.status==1){
		            location.href = WST.U('home/shops/joinStep3');
		        }else{
		        	layer.close(load);
		        	WST.msg(json.msg,{icon:5});
		        }
		    });
		}
	});
}
function saveStep3(){
	$('#applyFrom').isValid(function(v){
		if(v){
			var params = WST.getParams('.a-ipt');
			if((params.isLongbusinessDate=='' || params.isLongbusinessDate==0) && params.businessEndDate==''){
				WST.msg('请选择营业期限结束日期',{icon:5});
				return;
			}
			if((params.isLonglegalCertificateDate=='' || params.isLonglegalCertificateDate==0) && params.legalCertificateEndDate==''){
				WST.msg('请选择法定代表人证件有效期结束日期',{icon:5});
				return;
			}
			if((params.isLongOrganizationCodeDate=='' || params.isLongOrganizationCodeDate==0) && params.organizationCodeEndDate==''){
				WST.msg('请选择组织机构代码证有效期结束日期',{icon:5});
				return;
			}
			params.businessAreaPath0 = WST.ITGetAreaVal('j-areas');
			params.areaIdPath0 = WST.ITGetAreaVal('j-careas');
			var load = WST.load({msg:'正在提交请求，请稍后...'});
		    $.post(WST.U('home/shops/saveStep3'),params,function(data,textStatus){
		        var json = WST.toJson(data);
		        if(json.status==1){
		            location.href = WST.U('home/shops/joinStep4');
		        }else{
		        	layer.close(load);
		        	WST.msg(json.msg,{icon:5});
		        }
		    });
		}
	});
}
function saveStep4(){
	$('#applyFrom').isValid(function(v){
		if(v){
			var load = WST.load({msg:'正在提交请求，请稍后...'});
			var params = WST.getParams('.a-ipt');
			params.bankAreaId = WST.ITGetAreaVal('j-bareas');
		    $.post(WST.U('home/shops/saveStep4'),params,function(data,textStatus){
		        var json = WST.toJson(data);
		        if(json.status==1){
		            location.href = WST.U('home/shops/joinStep5');
		        }else{
		        	layer.close(load);
		        	WST.msg(json.msg,{icon:5});
		        }
		    });
		}
	});
}
function saveStep5(){
	$('#applyFrom').isValid(function(v){
		if(v){
			var load = WST.load({msg:'正在提交入驻请求，请稍后...'});
			var params = WST.getParams('.a-ipt');
		    $.post(WST.U('home/shops/saveStep5'),params,function(data,textStatus){
		        var json = WST.toJson(data);
		        if(json.status==1){
		            location.href = WST.U('home/shops/joinSuccess');
		        }else{
		        	layer.close(load);
		        	WST.msg(json.msg,{icon:5});
		        }
		    });
		}
	});
}
var container,map,label,marker,mapLevel;
function initQQMap(longitude,latitude,mapLevel){
    var container = document.getElementById("container");
    mapLevel = WST.blank(mapLevel,13);
    var mapopts,center = null;
    mapopts = {zoom: parseInt(mapLevel)};
	map = new qq.maps.Map(container, mapopts);
	if(WST.blank(longitude)=='' || WST.blank(latitude)==''){
		var cityservice = new qq.maps.CityService({
		    complete: function (result) {
		        map.setCenter(result.detail.latLng);
		    }
		});
		cityservice.searchLocalCity();
	}else{
        marker = new qq.maps.Marker({
            position:new qq.maps.LatLng(latitude,longitude), 
            map:map
        });
        map.panTo(new qq.maps.LatLng(latitude,longitude));
	}
	var url3;
	qq.maps.event.addListener(map, "click", function (e) {
		if(marker)marker.setMap(null); 
		marker = new qq.maps.Marker({
            position:e.latLng, 
            map:map
        });    
	    $('#latitude').val(e.latLng.getLat().toFixed(6));
	    $('#longitude').val(e.latLng.getLng().toFixed(6));
	    url3 = encodeURI(window.conf.HTTP+'apis.map.qq.com/ws/geocoder/v1/?location=' + e.latLng.getLat() + "," + e.latLng.getLng() + "&key="+window.conf.MAP_KEY+"&output=jsonp&&callback=?");
	    $.getJSON(url3, function (result) {
	        if(result.result!=undefined){
	            document.getElementById("shopAddress").value = result.result.address;
	        }else{
	            document.getElementById("shopAddress").value = "";
	        }

	    })
	});
	qq.maps.event.addListener(map,'zoom_changed',function() {
        $('#mapLevel').val(map.getZoom());
    });
}
function mapCity(){
    var citys = [];
    $('.j-careas').each(function(){
        citys.push($(this).find('option:selected').text());
    })
    if(citys.length==0)return;
    var url2 = encodeURI(window.conf.HTTP+'apis.map.qq.com/ws/geocoder/v1/?region=' + citys.join('') + "&address=" + citys.join('') + "&key="+window.conf.MAP_KEY+"&output=jsonp&&callback=?");
    $.getJSON(url2, function (result) {
        if(result.result.location){
            map.setCenter(new qq.maps.LatLng(result.result.location.lat, result.result.location.lng));
        }
    });
}