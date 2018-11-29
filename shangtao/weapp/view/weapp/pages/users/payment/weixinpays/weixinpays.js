var http = require('../../../../utils/request.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    data:[],
    domain: app.globalData.domain,
    orderNo:'',
    isBatch:0,
    payPwd:'',
    confirmPwd:'',
    disabled:false,
    loading:false
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      orderNo: options.orderNo,
      isBatch: options.isBatch
    });
  },
  /**
  * 生命周期函数--监听小程序显示
  */
  onShow: function () {
    this.getData();
  },
  //数据
  getData:function(){
    var that = this;
    var orderNo = that.data.orderNo;
    var isBatch = that.data.isBatch;
    var tokenId = app.globalData.tokenId;
    http.Post("weapp/wallets/payment", { tokenId: tokenId, orderNo: orderNo, isBatch: isBatch }, function (res) {
      if (res.status == 1) {
        that.setData({
          data:res.data,
        })
      }else{
        wx.showModal({
          title: '提示',
          content: res.msg,
          showCancel: false,
          confirmText: "确定",
          success: function (res) {
            if (res.confirm) {
              wx.navigateBack({
                delta: 1
              })
            }
          }
        })
      }
    });
  },
  //支付
  payment:function(e){
    var that = this;
    var orderNo = that.data.orderNo;
    var isBatch = that.data.isBatch;
    var tokenId = app.globalData.tokenId;
    that.setData({ disabled: true, loading: true})
    http.Post("weapp/weixinpays/toPay", { tokenId: tokenId, orderNo: orderNo, isBatch: isBatch}, function (res) {
      that.setData({loading: false })
      if (res.status == 1) {
        var payargs = res.data;
        wx.requestPayment({
          timeStamp: payargs.timeStamp,
          nonceStr: payargs.nonceStr,
          package: payargs.package,
          signType: payargs.signType,
          paySign: payargs.paySign,
          success: function (res) {
            wx.showModal({
              title: '提示',
              content: '支付成功',
              showCancel: false,
              confirmText: "确定",
              success: function (res) {
                if (res.confirm) {
                  wx.navigateBack({
                    delta: 2
                  })
                }
              }
            })
          },
          fail: function (res) {
            that.setData({ disabled: false })
          }
        })
      }else{
        app.prompt(res.msg);
        that.setData({ disabled: false})
      }
    });
  }
})