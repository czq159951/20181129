var app = getApp();
var http = require("../../../utils/request.js");
Page({

  /**
   * 页面的初始数据
   */
  data: {
     dataStatus:false,
     animationStatus: false,
     list:[],
     url:'',
     value:'',
     domain:app.globalData.domain,
     str:null
  },
  onShow: function () {
    this.getList();
  },
  onReady: function () {
  
  },
  getList:function(){
    var that = this;
    var dataStatus = this.data.dataStatus;
    http.Post('weapp/orderComplains/complainByPage',{tokenId:app.globalData.tokenId,pagesize:10,page:1},function(res){
      if(res.status == 1){
        if (res.data.data.length != 0){
          dataStatus = true
        }else{
          dataStatus = false
        }
        that.setData({
          list:res.data.data,
          dataStatus: dataStatus
        })
      }else{
        app.prompt(res.msg);
      }
    })
  },
  getDetail:function(id){
    var that = this;
    var value = this.data.value;
    http.Post('weapp/orderComplains/getComplainDetail',{tokenId:app.globalData.tokenId,id:id},function(res){
      if(res.status == 1){
        value = res.data.list
        that.setData({
          orderNo: value.orderNo,
          complainContent: value.complainContent,
          complainType: value.complainType,
          complainAnnex: value.complainAnnex,
          complainTime: value.complainTime,
          complainStatus: value.complainStatus,
          finalResult: value.finalResult,
          finalResultTime: value.finalResultTime,
          needRespond: value.needRespond,
          respondContent: value.respondContent,
          respondAnnex: value.respondAnnex,
          respondTime: value.respondTime
        })
        that.blank(value.respondContent)
      }else{
        app.prompt(res.msg);
      }
    })
  },
  toDetail:function(e){
    var id = e.currentTarget.dataset.id;
    this.getDetail(id)
    this.powerDrawer('open')
  },
  toShop:function(e){
    var url = this.data.url;
    var shopId = e.currentTarget.dataset.shopid;
    if(shopId == 1){
      url = 'shop-self/shop-self'
    }else{
      url = 'shop-home/shop-home?shopId='+shopId
    }
    wx.navigateTo({
      url: '../../'+url,
    })
  },
  blank : function (str, defaultVal) {
    if (str == '0000-00-00') str = '';
    if (str == '0000-00-00 00:00:00') str = '';
    if (!str) str = '';
    if (typeof (str) == 'null') str = '';
    if (typeof (str) == 'undefined') str = '';
    if (str == '' && defaultVal) str = defaultVal;
    this.setData({
      str : str
    })
  },
  /*展示分类动画 */
  powerDrawer(e) {
    if(e == 'open'){
      var currentTarget = e;
    }else{
    var currentTarget = e.currentTarget.dataset.statu;
    }
    this.animation(currentTarget);
  },
  animation(currentTarget) {
    var that = this;
    var animation = wx.createAnimation({
      duration: 500,
      timingFunction: "linear",
      delay: 0,
      transformOrigin: "100% 50% 0"
    });
    var animation = animation;
    animation.opacity(0.5).translateX(375).step();
    that.setData({
      animationData: animation.export()
    });
    setTimeout(function () {
      animation.opacity(1).translateX(0).step();
      that.setData({
        animationData: animation.export()
      });
      if (currentTarget == 'close') {
        that.setData({
          animationStatus: false
        })
      }

    }, 200);
    if (currentTarget == 'open') {
      that.setData({
        animationStatus: true
      })

    }
  }
})