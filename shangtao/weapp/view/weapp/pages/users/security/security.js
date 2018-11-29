var http = require('../../../utils/request.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    data: []
  },
  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function (options) {
    this.getData()
  },
  //数据
  getData: function (){
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post("weapp/users/security", { tokenId: tokenId}, function (res) {
      if (res.status == 1) {
        that.setData({
          data: res.data
        })
      }
    });
  },
  //登陆密码
  loginPwd:function(){
    wx.navigateTo({
      url: './login-pwd/login-pwd'
    })
  },
  //支付密码
  payPwd: function () {
    wx.navigateTo({
      url: './pay-pwd/pay-pwd'
    })
  },
  //手机号码
  phoneNum: function () {
    wx.navigateTo({
      url: './phone-number/phone-number'
    })
  }
})