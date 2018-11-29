var mmg;
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'店铺编号', name:'shopSn', width: 30,sortable: true},
            {title:'店铺账号', name:'loginName',width: 60,sortable: true},
            {title:'店铺名称', name:'shopName',width: 120,sortable: true},
            {title:'店主姓名', name:'shopkeeper',width: 40,hidden: true,sortable: true},
            {title:'店主联系电话', name:'telephone',width: 30,hidden: true,sortable: true},
            {title:'店铺地址', name:'shopAddress',width:300 },
            {title:'所属公司', name:'shopCompany',width: 60,hidden: true},
            {title:'营业状态', name:'shopAtive' ,width: 20,sortable: true,renderer: function (val,item,rowIndex){
	        	return (item['shopAtive']==1)?"<span class='statu-yes'><i class='fa fa-check-circle'></i> 营业中</span>":"<span class='statu-wait'><i class='fa fa-coffee'></i> 休息中</span>";
	        }},
            {title:'操作', name:'' ,width:150, align:'center', renderer: function(val,item,rowIndex){
                var h = "";
	            if(WST.GRANT.DPGL_02)h += "<a class='btn btn-blue' href='javascript:toEdit(" + item['shopId'] + ")'><i class='fa fa-pencil'></i>修改</a> ";
	            if(WST.GRANT.DPGL_03 && item['shopId']!=1)h += "<a class='btn btn-red' href='javascript:toDel(" + item['shopId'] + ")'><i class='fa fa-trash-o'></i>删除</a> "; 
	            h += "<a class='btn btn-blue' href='"+WST.U('admin/logmoneys/tologmoneys','id='+item['shopId'])+"&type=1'><i class='fa fa-search'></i>商家资金</a>";
	            return h;
            }}
            ];
 
    mmg = $('.mmg').mmGrid({height: (h-85),indexCol: true, cols: cols,method:'POST',nowrap:true,
        url: WST.U('admin/shops/pageQuery'), fullWidthRows: true, autoLoad: true,
        remoteSort:true ,
        sortName: 'shopSn',
        sortStatus: 'desc',
        plugins: [
            $('#pg').mmPaginator({})
        ]
    }); 
}
function loadGrid(){
	var params = WST.getParams('.j-ipt');
	params.areaIdPath = WST.ITGetAllAreaVals('areaId1','j-areas').join('_');
	params.page = 1;
	mmg.load(params);
}
function initApplyGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'申请人账号', name:'loginName', width: 30},
            {title:'店铺名称', name:'shopName',width:100 },
            {title:'所属公司', name:'shopCompany',width:100 },
            {title:'申请联系人', name:'applyLinkMan',width:30 },
            {title:'申请联系人电话', name:'applyLinkTel',width:30 },
            {title:'对接商城招商人员', name:'applyLinkTel' ,width:50,renderer: function (val,item,rowIndex){
	        	return (item['isInvestment']==1)?item['investmentStaff']:'-';
	        }},
            {title:'申请日期', name:'applyTime' },
            {title:'申请状态', name:'applyStatus' ,width:30,renderer: function (val,item,rowIndex){
	        	if(item['applyStatus']==1){
                    return "<span class='statu-wait'><i class='fa fa-clock-o'></i> 待处理</span>";
	        	}else if(item['applyStatus']==0){
                    return "<span class='statu-wait'><i class='fa fa-clock-o'></i> 填写中</span>";
	        	}else{
                    return "<span class='statu-no'><i class='fa fa-ban'></i> 申请失败</span>";
	        	}
	        }},
            {title:'操作', name:'' ,width:80, align:'center', renderer: function(val,item,rowIndex){
                var h = "";
	            if(WST.GRANT.DPSQ_04)h += "<a class='btn btn-blue' href='javascript:toHandle(" + item['shopId'] + ")'><i class='fa fa-pencil'></i>操作</a> ";
	            if(WST.GRANT.DPSQ_03)h += "<a class='btn btn-red' href='javascript:toDelApply(" + item['shopId'] + ")'><i class='fa fa-trash-o'></i>删除</a> "; 
	            return h;
            }}
            ];
 
    mmg = $('.mmg').mmGrid({height: (h-85),indexCol: true, cols: cols,method:'POST',nowrap:true,
        url: WST.U('admin/shops/pageQueryByApply'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    }); 
}
function loadApplyGrid(){
	var params = WST.getParams('.j-ipt');
	params.areaIdPath = WST.ITGetAllAreaVals('areaId1','j-areas').join('_');
	params.page = 1;
	mmg.load(params);
}
function toHandle(id){
	location.href = WST.U('admin/shops/toHandleApply','id='+id);
}
function toDelApply(id){
	var box = WST.confirm({content:"您确定要彻底删除该店铺申请信息吗?",yes:function(){
	           var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	           $.post(WST.U('admin/shops/delApply'),{id:id},function(data,textStatus){
	           			  layer.close(loading);
	           			  var json = WST.toAdminJson(data);
	           			  if(json.status=='1'){
	           			    	WST.msg("操作成功",{icon:1});
	           			    	layer.close(box);
	           		            loadApplyGrid();
	           			  }else{
	           			    	WST.msg(json.msg,{icon:2});
	           			  }
	           		});
	            }});
}
function initStopGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'店铺编号', name:'shopSn', width: 30},
            {title:'店铺账号', name:'loginName', width: 60},
            {title:'店铺名称', name:'shopName',width: 120},
            {title:'店主姓名', name:'shopkeeper',width: 40,hidden: true},
            {title:'店主联系电话', name:'telephone',hidden: true},
            {title:'店铺地址', name:'shopAddress',width:350 },
            {title:'所属公司', name:'shopCompany',hidden: true },
            {title:'操作', name:'' ,width:80, align:'center', renderer: function(val,item,rowIndex){
                var h = "";
	            h += "<a class='btn btn-blue' href='javascript:toEdit(" + item['shopId'] + ")'><i class='fa fa-pencil'></i>修改</a> ";
	            h += "<a class='btn btn-red' href='javascript:toDel(" + item['shopId'] + ")'><i class='fa fa-trash-o'></i>删除</a> "; 
	            return h;
            }}
            ];
 
    mmg = $('.mmg').mmGrid({height: (h-85),indexCol: true, cols: cols,method:'POST',nowrap:true,
        url: WST.U('admin/shops/pageStopQuery'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    }); 
}
function loadStopGrid(){
	var params = WST.getParams('.j-ipt');
	params.areaIdPath = WST.ITGetAllAreaVals('areaId1','j-areas').join('_');
	params.page = 1;
	mmg.load(params);
}
var initTab2 = false,initTab3 = false;
function initUpload(isEdit){
	if(!isEdit){
        legalCertificateImgUpload();
		businessLicenceImgUpload();
		bankAccountPermitImgUpload();
		organizationCodeUpload();
		taxRegistrationCertificateUpload();
		taxpayerQualificationUpload();
	}else{
		var element = layui.element;
		element.on('tab(msgTab)', function(data){
		   if(data.index==1){
		   	   if(initTab2)return;
		       initTab2 = true;
               legalCertificateImgUpload();
			   businessLicenceImgUpload();
			   bankAccountPermitImgUpload();
			   organizationCodeUpload();
		   }else if(data.index==2){
		   	   if(initTab3)return;
		       initTab3 = true;
               taxRegistrationCertificateUpload();
			   taxpayerQualificationUpload();
		   }
	    });
	}
}
function legalCertificateImgUpload (){
	WST.upload({
			pick:'#legalCertificateImgPicker',
			formData: {dir:'shops'},
			accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
			callback:function(f){
				var json = WST.toAdminJson(f);
				if(json.status==1){
				  	$('#legalCertificateImgMsg').empty().hide();
				    $('#legalCertificateImgPreview').attr('src',WST.conf.ROOT+"/"+json.savePath+json.thumb).show();
				    $('#legalCertificateImgPreview_a').attr('href',WST.conf.ROOT+"/"+json.savePath+json.name);
				    $('#legalCertificateImg').val(json.savePath+json.name);
				    $('#msg_legalCertificateImg').hide();
				}
			},
			progress:function(rate){
				$('#legalCertificateImgMsg').show().html('已上传'+rate+"%");
			}
		});
}
function businessLicenceImgUpload(){
	WST.upload({
			pick:'#businessLicenceImgPicker',
			formData: {dir:'shops'},
			accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
			callback:function(f){
				var json = WST.toAdminJson(f);
				if(json.status==1){
					$('#businessLicenceImgMsg').empty().hide();
					$('#businessLicenceImgPreview').attr('src',WST.conf.ROOT+"/"+json.savePath+json.thumb).show();
					$('#businessLicenceImgPreview_a').attr('href',WST.conf.ROOT+"/"+json.savePath+json.name);
					$('#businessLicenceImg').val(json.savePath+json.name);
					$('#msg_businessLicenceImg').hide();
				}
			},
			progress:function(rate){
				$('#businessLicenceImgMsg').show().html('已上传'+rate+"%");
			}
		});
}
function bankAccountPermitImgUpload(){
	WST.upload({
			pick:'#bankAccountPermitImgPicker',
			formData: {dir:'shops'},
			accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
			callback:function(f){
				var json = WST.toAdminJson(f);
				if(json.status==1){
					$('#bankAccountPermitImgMsg').empty().hide();
					$('#bankAccountPermitImgPreview').attr('src',WST.conf.ROOT+"/"+json.savePath+json.thumb).show();
					$('#bankAccountPermitImgPreview_a').attr('href',WST.conf.ROOT+"/"+json.savePath+json.name);
					$('#bankAccountPermitImg').val(json.savePath+json.name);
					$('#msg_bankAccountPermitImg').hide();
				}
			},
			progress:function(rate){
				$('#bankAccountPermitImgMsg').show().html('已上传'+rate+"%");
			}
		});
}
function organizationCodeUpload(){
	WST.upload({
			pick:'#organizationCodeImgPicker',
			formData: {dir:'shops'},
			accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
			callback:function(f){
				var json = WST.toAdminJson(f);
				if(json.status==1){
					$('#organizationCodeImgMsg').empty().hide();
					$('#organizationCodeImgPreview').attr('src',WST.conf.ROOT+"/"+json.savePath+json.thumb).show();
					$('#organizationCodeImgPreview_a').attr('href',WST.conf.ROOT+"/"+json.savePath+json.name);
					$('#organizationCodeImg').val(json.savePath+json.name);
					$('#msg_organizationCodeImg').hide();
				}
			},
			progress:function(rate){
				$('#organizationCodeImgMsg').show().html('已上传'+rate+"%");
			}
		});
}
function taxRegistrationCertificateUpload(){
	var uploader = WST.upload({
				pick:'#taxRegistrationCertificateImgPicker',
			    formData: {dir:'shops'},
				accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
				fileNumLimit:3,
				callback:function(f,file){
					var json = WST.toAdminJson(f);
					if(json.status==1){
					  	$('#taxRegistrationCertificateImgMsg').empty().hide();
					  	var tdiv = $("<div style='width:75px;float:left;margin-right:5px;'><a target='_blank' href='"+json.savePath+json.name+"'>"+
			                       "<img class='step_pic"+"' width='75' height='75' src='"+WST.conf.ROOT+"/"+json.savePath+json.thumb+"' v='"+json.savePath+json.name+"'></a></div>");
						var btn = $('<div style="position:relative;top:-80px;left:60px;cursor:pointer;" ><img src="'+WST.conf.ROOT+'/shangtao/home/View/default/img/seller_icon_error.png"></div>');
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
}
function taxpayerQualificationUpload(){
	WST.upload({
			pick:'#taxpayerQualificationImgPicker',
			formData: {dir:'shops'},
			accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
			callback:function(f){
				var json = WST.toAdminJson(f);
				if(json.status==1){
					$('#taxpayerQualificationImgMsg').empty().hide();
					$('#taxpayerQualificationImgPreview').attr('src',WST.conf.ROOT+"/"+json.savePath+json.thumb).show();
					$('#taxpayerQualificationImgPreview_a').attr('href',WST.conf.ROOT+"/"+json.savePath+json.name);
					$('#taxpayerQualificationImg').val(json.savePath+json.name);
					$('#msg_taxpayerQualificationImg').hide();
				}
			},
			progress:function(rate){
				$('#taxpayerQualificationImgMsg').show().html('已上传'+rate+"%");
			}
	});
}
function initEdit(opts){
	var laydate = layui.laydate;
	laydate.render({elem: '#establishmentDate'});
	laydate.render({elem: '#businessStartDate'});
	laydate.render({elem: '#businessEndDate'});
	laydate.render({elem: '#legalCertificateStartDate'});
	laydate.render({elem: '#legalCertificateEndDate'});
	laydate.render({elem: '#organizationCodeStartDate'});
	laydate.render({elem: '#organizationCodeEndDate'});
	WST.upload({
	  	  pick:'#shopImgPicker',
	  	  formData: {dir:'shops'},
	  	  accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
	  	  callback:function(f){
	  		  var json = WST.toAdminJson(f);
	  		  if(json.status==1){
	  			$('#uploadMsg').empty().hide();
	            $('#preview').attr('src',WST.conf.ROOT+"/"+json.savePath+json.thumb);
	            $('#shopImg').val(json.savePath+json.name);
	            $('#editFrom').validator('hideMsg', '#shopImg');
	  		  }
		  },
		  progress:function(rate){
		      $('#uploadMsg').show().html('已上传'+rate+"%");
		  }
	});
	initTime('#serviceStartTime',opts.serviceStartTime);
	initTime('#serviceEndTime',opts.serviceEndTime);
	if($('#shopId').val()>0){
		var areaIdPath = opts.areaIdPath.split("_");
    	$('#area_0').val(areaIdPath[0]);
    	var aopts = {id:'area_0',val:areaIdPath[0],childIds:areaIdPath,className:'j-areas',isRequire:true}
		WST.ITSetAreas(aopts);
		if(opts.bankAreaIdPath!=''){
			var areaIdPath = opts.bankAreaIdPath.split("_");
    	    $('#barea_0').val(areaIdPath[0]);
    	    var aopts = {id:'barea_0',val:areaIdPath[0],childIds:areaIdPath,className:'j-bareas',isRequire:true}
		    WST.ITSetAreas(aopts);
		}
		if(opts.businessAreaPath!=''){
			var areaIdPath = opts.businessAreaPath.split("_");
		    $('#carea_0').val(areaIdPath[0]);
		    var aopts = {id:'carea_0',val:areaIdPath[0],childIds:areaIdPath,className:'j-careas',isRequire:false}
			WST.ITSetAreas(aopts);
		}
	}
    if(window.conf.MAP_KEY){
	initQQMap(opts.longitude,opts.latitude,opts.mapLevel);
    }
	initUpload(opts.isEdit);
}
function delVO(obj){
    $(obj).parent().remove();
    var imgPath = [];
	$('.step_pic').each(function(){
        imgPath.push($(this).attr('v'));
	});
	$('#taxRegistrationCertificateImg').val(imgPath.join(','));
}
function toEdit(id){
	location.href=WST.U('admin/shops/toEdit','id='+id);
}
function toDel(id){
	var box = WST.confirm({content:"您确定要删除该店铺吗?",yes:function(){
	           var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	           $.post(WST.U('admin/shops/del'),{id:id},function(data,textStatus){
	           			  layer.close(loading);
	           			  var json = WST.toAdminJson(data);
	           			  if(json.status=='1'){
	           			    	WST.msg("操作成功",{icon:1});
	           			    	layer.close(box);
	           		            loadGrid();
	           			  }else{
	           			    	WST.msg(json.msg,{icon:2});
	           			  }
	           		});
	            }});
}
function checkLoginKey(obj){
	if($.trim(obj.value)=='')return;
	var params = {key:obj.value,userId:0};
	var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
    $.post(WST.U('admin/users/checkLoginKey'),params,function(data,textStatus){
    	layer.close(loading);
    	var json = WST.toAdminJson(data);
    	if(json.status!='1'){
    		WST.msg(json.msg,{icon:2});
    		obj.value = '';
    	}
    });
}
function save(){
	$('#editFrom').isValid(function(v){
		if(v){
			var params = WST.getParams('.ipt');
			params.areaId = WST.ITGetAreaVal('j-areas');
			params.bankAreaId = WST.ITGetAreaVal('j-bareas');
			params.businessAreaPath0 = WST.ITGetAreaVal('j-careas');
			var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
		    $.post(WST.U('admin/shops/edit'),params,function(data,textStatus){
		    	layer.close(loading);
		    	var json = WST.toAdminJson(data);
		    	if(json.status=='1'){
		    		WST.msg("操作成功",{icon:1,time:1000},function(){
		    			if(params.shopStatus==1){
			    			location.href=WST.U('admin/shops/index');
			    		}else{
                            location.href=WST.U('admin/shops/stopIndex');
			    		}
		    		});
		    		
		    	}else{
		    		WST.msg(json.msg,{icon:2});
		    	}
		    });
		}
	});
}
function getUserByKey(){
	if($.trim($('#keyName').val())=='')return;
	$('#keyNameBox').html('');
	$('#shopUserId').val(0);
	var loading = WST.msg('正在查询用户信息...', {icon: 16,time:60000});
    $.post(WST.U('admin/users/getUserByKey'),{key:$('#keyName').val()},function(data,textStatus){
		layer.close(loading);
		var json = WST.toAdminJson(data);
		if(json.status=='1'){
		    $('#keyNameBox').html('用户：'+json.data.loginName);
		    $('#shopUserId').val(json.data.userId);
		}else{
		    WST.msg(json.msg,{icon:2});
		}
    });
}
function add(){
	$('#editFrom').isValid(function(v){
		if(v){
			var params = WST.getParams('.ipt');
			params.areaId = WST.ITGetAreaVal('j-areas');
			params.bankAreaId = WST.ITGetAreaVal('j-bareas');
			params.businessAreaPath0 = WST.ITGetAreaVal('j-careas');
			var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
		    $.post(WST.U('admin/shops/add'),params,function(data,textStatus){
		    	layer.close(loading);
		    	var json = WST.toAdminJson(data);
		    	if(json.status=='1'){
		    		WST.msg("操作成功",{icon:1,time:1000},function(){
			    		location.href=WST.U('admin/shops/index');
		    		});
		    		
		    	}else{
		    		WST.msg(json.msg,{icon:2});
		    	}
		    });
		}
	});
}
function apply(){
	$('#editFrom').isValid(function(v){
		if(v){
			var params = WST.getParams('.ipt');
			params.areaId = WST.ITGetAreaVal('j-areas');
			params.bankAreaId = WST.ITGetAreaVal('j-bareas');
			params.businessAreaPath0 = WST.ITGetAreaVal('j-careas');
			if(params.applyStatus==-1 && params.applyDesc==''){
				 WST.msg('请输入审核不通过原因!',{icon:2});
				 return;
			}
			var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
		    $.post(WST.U('admin/shops/handleApply'),params,function(data,textStatus){
		    	layer.close(loading);
		    	var json = WST.toAdminJson(data);
		    	if(json.status=='1'){
		    		WST.msg("操作成功",{icon:1,time:1000},function(){
			    		location.href=WST.U('admin/shops/apply');
		    		});
		    		
		    	}else{
		    		WST.msg(json.msg,{icon:2});
		    	}
		    });
		}
	});
}
function initTime($id,val){
	var html = [],t0,t1;
	var str = val.split(':');
	for(var i=0;i<24;i++){
		t0 = (val.indexOf(':00')>-1 && (parseInt(str[0],10)==i))?'selected':'';
		t1 = (val.indexOf(':30')>-1 && (parseInt(str[0],10)==i))?'selected':'';
		html.push('<option value="'+i+':00" '+t0+'>'+i+':00</option>');
		html.push('<option value="'+i+':30" '+t1+'>'+i+':30</option>');
	}
	$($id).append(html.join(''));
}
var container,map,label,marker,mapLevel = 15;
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
	    url3 = encodeURI(window.conf.__HTTP__+'apis.map.qq.com/ws/geocoder/v1/?location=' + e.latLng.getLat() + "," + e.latLng.getLng() + "&key="+window.conf.MAP_KEY+"&output=jsonp&&callback=?");
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
    $('.j-areas').each(function(){
        citys.push($(this).find('option:selected').text());
    })
    if(citys.length==0)return;
    var url2 = encodeURI(window.conf.__HTTP__+'apis.map.qq.com/ws/geocoder/v1/?region=' + citys.join('') + "&address=" + citys.join('') + "&key="+window.conf.MAP_KEY+"&output=jsonp&&callback=?");
    $.getJSON(url2, function (result) {
        if(result.result.location){
            map.setCenter(new qq.maps.LatLng(result.result.location.lat, result.result.location.lng));
            map.setZoom(mapLevel);
        }
    });
}