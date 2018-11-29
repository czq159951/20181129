
var app = getApp();
var http = require('../../../../utils/request.js');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    domain: app.globalData.domain
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function () {
    this.getInfo();
  },
  getInfo: function () {
    var that = this;
    http.Post('weapp/users/aboutUs', { tokenId: app.globalData.tokenId }, function (res) {
      if (res.status == 1) {
        that.setData({
          data: res.data
        })
      }
    })
  }
})