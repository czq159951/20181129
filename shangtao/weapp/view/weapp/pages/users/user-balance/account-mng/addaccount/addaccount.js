// pages/user/userBalance/accountManage/addAccount/addAccount.js
var app = getApp();
var http = require('../../../../../utils/request.js');

Page({

  /**
   * 页面的初始数据
   */
  data: {
      selectStaus: false,
      selectStyle:false,
      banks:[{bankId:0,bankName:"请选择"}],
      firstContent:"请选择",
      address1: '请选择',
      address2: '请选择',
      address3: '请选择',
      areaName: '请选择账户地址',
      data:null,
      total: null,
      Area: [],
      Area1: [],
      Area2: [],
      setDefault: false,
      pattern: 1,
      page: 0,
      total: null,
      accAreaId:'',
      accNo:'',
      id:'',
      status:1,
      accTargetId:''

    },
    /*页面开启时加载数据 */
    onLoad:function(e){
      if (e.id != undefined){
        if(e.status == 1){
          wx.setNavigationBarTitle({
            title: "修改提现账号"
          })
        }
       this.setData({
         id :e.id,
         areaName: e.areaName,
         firstContent: e.bankName,
         accUser: e.accUser,
         accNo: e.accNo,
         status:2
        })
        this.getInfo();
      }
      this.getBasicInfo();
    },
    getBasicInfo:function(){
      var that = this;
      var banks = this.data.banks;
      http.Post('weapp/cashconfigs/index',{tokenId:app.globalData.tokenId,pagesize:10,page:1},function(res){
        
        if(res.status == 1){
          that.setData({
            banks: banks.concat(res.data.banks),
            areas:res.data.areas
          })
        }
      })
    },
    getInfo:function(){
      var that = this;
      http.Post('weapp/cashConfigs/getById',{tokenId :app.globalData.tokenId,id : this.data.id},function(res){
        
        if(res.status == 1){
          that.setData({
            accAreaId: res.data.accAreaId,
            accTargetId: res.data.accTargetId
          })
        }
      })
    },
    getEditInfo: function (data, url) {
      var that = this;
      var Area1 = this.data.Area1;
      var Area2 = this.data.Area2;
      var pattern = this.data.pattern;
      http.Post('weapp/' + url, data, function (res) {
        if (res.status == 1) {
          if (pattern == 2) {
            Area1 = res.data
            that.setData({
              Area1: Area1
            })
          } else if (pattern == 3) {
            
            Area2 = res.data
            that.setData({
              Area2: Area2
            })
          }
          if (res.msg == '新增成功' || res.msg == '编辑成功') {
            wx.showToast({
              title: res.msg,
              success: function () {
                setTimeout(function () {
                  wx.navigateBack({
                    delta: 1
                  })
                }, 1000)
              }
            })
          }
        }else{
          wx.showToast({
            title: res.msg,
            icon:'loading'
          })
        }
      })
    },
    infoSave: function () {
      var data = this.data.data;
      var accAreaId = this.data.accAreaId
      var accUser = this.data.accUser
      var accNo = this.data.accNo
      var accTargetId = this.data.accTargetId
      var tokenId = app.globalData.tokenId
      var id = this.data.id;
      if (accTargetId == '' || accTargetId ==0){
        app.prompt('请选择账户类型')
      } else if (accAreaId==''){
        app.prompt('请选择账户地址')
      } else if (accUser == ''){
        app.prompt('请输入持卡人姓名')
      } else if (accNo == ''){
        app.prompt('请输入银行卡号')
      }else{
      data = {
        accAreaId: accAreaId,
        accUser: accUser,
        accNo: accNo,
        accTargetId: accTargetId,
        tokenId: tokenId,
        id : id
      }
      if (this.data.status == 1) {
        this.getEditInfo(data, 'cashconfigs/add');
      }else{
        this.getEditInfo(data, 'cashconfigs/edit');
      }
    }
    },
    //点击选择类型
    clickArea: function (e) {
      var page = this.data.page;
      var data = this.data.data;
      var pattern = this.data.pattern;
      var total = this.data.total;
      var address1 = this.data.address1;
      var address2 = this.data.address2;
      var address3 = this.data.address3;
      var accAreaId = this.data.accAreaId;
      page++;
      var areaId = e.currentTarget.dataset.areaid;
      if (page == 1) {
        var areas = this.data.areas;

        for (let i = 0; i < areas.length; i++) {
          if (areas[i].areaId == areaId) {
            address1 = areas[i].areaName;
            pattern = 2;
          }
        }
      } else if (page == 2) {
        var Area1 = this.data.Area1;

        for (let i = 0; i < Area1.length; i++) {
          if (Area1[i].areaId == areaId) {
            address2 = Area1[i].areaName;
            pattern = 3;
          }
        }
      } else if (page == 3) {
        var Area2 = this.data.Area2;

        for (let i = 0; i < Area2.length; i++) {
          if (Area2[i].areaId == areaId) {
            address3 = Area2[i].areaName;
            accAreaId = areaId;
            pattern = 4;
          }
        }
        this.setData({
          total: address1 + address2 + address3
        })
        this.powerDrawer('close');
      }
      this.setData({
        address1: address1,
        address2: address2,
        address3: address3,
        page: page,
        pattern: pattern,
        accAreaId: accAreaId
      })
      data = {
        tokenId: app.globalData.tokenId,
        parentId: areaId
      }
      this.getEditInfo(data, 'areas/listQuery')

    },
    //点击切换
    mySelect: function (e) {
      var id = e.currentTarget.id;
      var pattern = this.data.pattern;
      var page = this.data.page;
      if (id == 1) {
        pattern = 1;
        page = 0;
      } else if (id == 2) {
        pattern = 2;
        page = 1;
      } else if (id == 3) {
        pattern = 3;
        page = 2;
      }
      this.setData({
        pattern: pattern,
        page: page
      })
    },
    powerDrawer: function (e) {
      if (e == 'close') {
        var currentStatus = e;
      } else {
        var currentStatus = e.currentTarget.dataset.status;
      }
      this.util(currentStatus);
    },
    util: function (currentStatus) {
      var that = this;
      var animation = wx.createAnimation({
        duration: 200,
        timingFunction: "linear",
        delay: 200,
        transformOrigin: "50% 50% 0",
      });
      var animation = animation;
      animation.opacity(0).rotateX(-100).step();
      this.setData({
        animationData: animation.export()
      });
      setTimeout(function () {
        animation.opacity(1).rotateX(0).step()
        that.setData({
          animationData: animation.export()
        })
        if (currentStatus == 'close') {
          that.setData({
            showModalStatus: false
          });
        }
      }, 500);
      if (currentStatus == 'open') {
        that.setData({
          showModalStatus: true
        });
      }
    },
   seleDrawer: function (e) {
     var currentStatus = e.currentTarget.dataset.status;
     this.util1(currentStatus);
   },
   util1: function (currentStatus) {
     var that = this;
     var animation = wx.createAnimation({
       duration: 200,
       timingFunction: "linear",
       delay: 0,
       transformOrigin: "50% 50% 0",
     });
     var animation = animation;
     animation.opacity(0).rotateX(-100).step();
     this.setData({
       animationSele: animation.export()
     });
     setTimeout(function () {
       animation.opacity(1).rotateX(0).step()
       that.setData({
         animationSele: animation.export()
       })
       if (currentStatus == 'close') {
         that.setData({
           selectStaus: false
         });
       }
     }, 400);
     if (currentStatus == 'open') {
       that.setData({
         selectStaus: true
       });
     }
   },
   radioChange :function(e){
      var banks = this.data.banks;
      var id = e.detail.value;
      var firstContent = this.data.firstContent;
      for (let i = 0; i < banks.length;i++){
        if (banks[i].bankId == id){
          firstContent = banks[i].bankName;
        }
      }
      this.setData({
        firstContent: firstContent,
        accTargetId : id
      })
   },
   /*获取NameInput */
   userName(e){
     var accUser = e.detail.value;
    this.setData({
      accUser: accUser
    })
   },
   /*获取卡号 */
   userNumber(e){
     var accNo = e.detail.value;
     this.setData({
       accNo: accNo
     })
   }
})