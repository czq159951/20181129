// pages/browHistory/browHistory.js
var app = getApp();
var http = require('../../../utils/request.js');

Page({
  data: {
    hascommodity: false,
    carts: [],
    pagesize:10,
    page:1,
    goodsId: [],
    data:[],
    goodsLogo:null,
    domain: app.globalData.domain
  },
  onShow: function (e) {
    var that = this;
    var carts = this.data.carts;
    var hascommodity = this.data.hascommodity;
    var data = this.data.data;
    wx.getStorage({
      key: 'history',
      success: function (cache) {
        data = cache.data;
        if (data.length != 0){
          hascommodity = true
        }
        that.setData({
          carts: data,
          hascommodity: hascommodity,
          goodsLogo: app.globalData.confInfo.goodsLogo,
        })
      }
    })
  },
  toDetail:function(e){
    var goodsId = e.currentTarget.dataset.goodsid;
    wx.navigateTo({
      url: '../../goods-detail/goods-detail?goodsId='+goodsId,
    })
  },
  //首页
  toIndex: function () {
    wx.switchTab({
      url: '../../index/index'
    })
  },
})