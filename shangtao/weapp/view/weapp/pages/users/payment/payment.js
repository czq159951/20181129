var http = require('../../../utils/request.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    data:[],
    orderNo: ''
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      orderNo: options.orderNo
    });
    this.getData();
  },
  //获取用户信息
  getData :function(){
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post("weapp/payments/index", { tokenId: tokenId}, function (res) {
      if (res.status == 1) {
        that.setData({
          data: res.data
        })
      }
    });
  },
  //支付
  toPayment: function (e){
    var that = this;
    var code = e.currentTarget.dataset.code;
    var orderNo = that.data.orderNo;
    if (code =='weixinpays'){
      wx.navigateTo({
        url: './weixinpays/weixinpays?orderNo=' + orderNo + '&isBatch=0'
      })
    } else if (code =='wallets'){
      wx.navigateTo({
        url: './wallets/wallets?orderNo=' + orderNo + '&isBatch=0'
      })
    }
  },
})