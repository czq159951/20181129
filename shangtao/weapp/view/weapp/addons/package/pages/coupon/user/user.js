var http = require('../../../../../utils/request.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    types: 0,
    termData: [
      { types: 0, name: '未使用',},
      { types: 1, name: '已使用',},
      { types: 2, name: '已过期',}
    ],
    coupon:[],
    page:0
  },
  /**
  * 生命周期函数--监听小程序显示
  */
  onShow: function () {
    this.getList();
  }, 
  selected(e) {
    var that = this;
    var types = e.currentTarget.dataset.types;
    that.setData({
      types: types,
      coupon: [],
      page: 0
    });
    that.getList();
  },
  //列表
  getList: function () {
    var that = this;
    var page = that.data.page;
    var types = that.data.types;
    var tokenId = app.globalData.tokenId;
    if (page == 0) wx.showLoading({ title: '加载中' });
    page = page + 1;
    http.Post("addon/coupon-weapp-pageQueryByUser", { tokenId: tokenId, status: types, page: page }, function (res) {
      if (res.status == 1) {
        var coupon = that.data.coupon;
        coupon = coupon.concat(res.data);
        that.setData({
          coupon: coupon,
          page: page
        })
      }
      wx.hideLoading();
    });
  },
  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
    this.getList();
  },
  //领取
  collar: function (e) {
    var that = this;
    var couponId = e.currentTarget.dataset.couponid;
    wx.navigateTo({
      url: "/addons/package/pages/coupon/goods-list/goods-list?couponId=" + couponId ,
    })
  }
})