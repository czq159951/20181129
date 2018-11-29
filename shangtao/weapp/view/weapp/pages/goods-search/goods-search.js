//index.js
var http = require('../../utils/request.js');
//获取应用实例
const app = getApp()
Page({
  /**
   * 页面的初始数据
   */
  data: {
    wordKey: null,
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.getData();
  },
  //信息
  getData: function () {
    var that = this;
    http.Post("weapp/index/hots", {}, function (res) {
      that.setData({
        hots: res.data
      })
    });
  },
  //搜索
  onSearch: function (e) {
    var that = this;
    if (e.currentTarget.dataset.key) {
      var wordKey = e.currentTarget.dataset.key;
    } else {
      var wordKey = that.data.wordKey;
    }
    if (!wordKey) {
      app.prompt('请输入关键字');
    } else {
      wx.navigateTo({
        url: '../goods-list/goods-list?wordKey=' + wordKey
      })
    }
  },
  nameInput: function (e) {
    var that = this;
    that.setData({
      wordKey: e.detail.value,
    });
  }
})
