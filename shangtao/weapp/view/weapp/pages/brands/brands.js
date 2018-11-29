var http = require('../../utils/request.js');
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    catList: [],
    catId: 0,
    domain: app.globalData.domain,
    /*默认链接*/
    storeUrl:'../brands/brandStore/brandStore',
    brandsArray:[]
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    that.getGoodsArray();
  },
  /* 获取数据列表*/
  getGoodsArray: function () {
    var that = this;
    var domain = that.data.domain;
    var brandsArray = that.data.brandsArray;
    var id = that.data.catId;
    /*数据初始化 */
    wx.showLoading({ title: '加载中' });
    http.Get('weapp/Brands/pageQuery', {id:id}, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        brandsArray = res.data.list;
        that.setData({
          catId: res.data.catId,
          catList: res.data.cat,
          brandsArray: brandsArray
        })
      }
    });
  },
  /*选择 */
  choice(e) {
    var that = this;
    var catId = e.currentTarget.dataset.catid;
    that.setData({
      catId: catId,
      orders: [],
      page: 0
    });
    that.getGoodsArray();
  },
  /*跳转到内容页 */
  brandDetail:function(e){
     var brandId = e.currentTarget.dataset.id;
     var catId = e.currentTarget.dataset.catid;
     wx.navigateTo({
       url: '../goods-list/goods-list?brandId='+brandId+'&catId='+catId,
     });
  }
})