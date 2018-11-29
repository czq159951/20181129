// pages/shop-detail/shop-detail.js
var http = require('../../utils/request.js');
var QR = require("../common/qrcode/qrcode.js");
var app = getApp();
Page({

  data: {
     shop:[],
     isFavor:'',
     domain:app.globalData.domain,
     shopId:'',
     maskHidden:true,
     imagePath:'',
     placeholder:''//默认二维码生成文本
  },

  onLoad: function (e) {
     this.setData({
       shopId: e.shopId,
       placeholder: 'http://www.baidu.com'
     })
     this.getStoreInfo();
    // this.getCode();
  },
  getStoreInfo:function(){
    var that = this;
    var shopId = this.data.shopId;
    http.Post('weapp/shops/index', { tokenId: app.globalData.tokenId, shopId: shopId},function(res){
      if(res.status == 1){
        that.setData({
          isFavor: res.data.isFavor,
          shop: res.data.shop,
          shopId: res.data.shop.shopId
        })
      }
    })
  },
  /*呼叫 */
  toCall: function () {
    var that = this;
    wx.makePhoneCall({
      phoneNumber: that.data.shop.shopTel
    })
  },
  
  onReady:function(){
  	var size = this.setCanvasSize();//动态设置画布大小
    var initUrl = this.data.placeholder;
    this.createQrCode(initUrl,"mycanvas",size.w,size.h);
  },
  onShow: function () {
  
  },
  attStatus: function (data, url) {
    var that = this;
    http.Post('weapp/Favorites/' + url, data, function (res) {
      if (res.status == 1) {
        wx.showToast({
          title: res.msg,
          success: function () {
            that.getStoreInfo();
          }
        })
      } else {
        wx.showModal({
          title: '提示',
          content: res.msg,
        })
      }
    })
  },
  selectStatus: function (e) {
    var isFavor = this.data.isFavor;
    var data = this.data.data;

    if (isFavor > 0) {
      data = {
        type: 1,
        tokenId: app.globalData.tokenId,
        id: isFavor
      }
      this.attStatus(data, 'cancel')
    } else if (isFavor <= 0) {
      data = {
        type: 1,
        tokenId: app.globalData.tokenId,
        id: this.data.shopId
      }
      this.attStatus(data, 'add')
    }
  },
  toAllgoods:function(){
    var shopId = this.data.shopId;
    wx.redirectTo({
      url: '../shop-home/shop-home?shopId='+shopId,
    })
  },
  //适配不同屏幕大小的canvas
  setCanvasSize:function(){
    var size={};
    try {
        var res = wx.getSystemInfoSync();
        var scale = 750/686;//不同屏幕下canvas的适配比例；设计稿是750宽
        var width = res.windowWidth/scale;
        var height = width;//canvas画布为正方形
        size.w = width/2;
        size.h = height/2;
      } catch (e) {
        // Do something when catch error
     //   console.log("获取设备信息失败"+e);
      } 
    return size;
  } ,
  createQrCode:function(url,canvasId,cavW,cavH){
    //调用插件中的draw方法，绘制二维码图片
    QR.qrApi.draw(url,canvasId,cavW,cavH);

  },
  //获取临时缓存照片路径，存入data中
  canvasToTempImage:function(){
    var that = this;
    wx.canvasToTempFilePath({
      canvasId: 'mycanvas',
      success: function (res) {
          var tempFilePath = res.tempFilePath;
       //   console.log("********"+tempFilePath);
          that.setData({
              imagePath:tempFilePath,
          });
      },
      fail: function (res) {
        //  console.log(res);
      }
    });
  },
  //点击图片进行预览，长按保存分享图片
  previewImg:function(e){
    wx.canvasToTempFilePath({
      canvasId: 'mycanvas',
      success: function (res) {
          var tempFilePath = res.tempFilePath;
					wx.previewImage({
      			current: tempFilePath, // 当前显示图片的http链接
      			urls: [tempFilePath] // 需要预览的图片http链接列表
    			})
      },
      fail: function (res) {
        //  console.log(res);
      }
    });
    
  },
/* getCode: function () {
   wx.request({
     // 获取token
     url: 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential',
     data: {
       appid: 'wx7785b91b499ed52f',
       secret: '1efdec4a7cfbaa97c40e36690519cc37'
     },
     success(res) {
       console.log(res);
    wx.request({
      // 调用接口C
      url: 'https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=' + res.data.access_token,
      method: 'POST',
      data: {
        "path": "pages/shop-home/shop-home",
        "width": 430
      },
      success(res) {
         console.log(res);
      }
    })

    }
   })
 }*/
      
})