var http = require('../../../../utils/request.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    types: 0,
    termData: [
      { catId: 0, catName: '全部分类', simpleName:'全部分类'}
    ],
    coupon:[],
    page:0
  },
  /**
 * 生命周期函数--监听页面加载
 */
  onLoad: function (options) {
    this.getTerm();
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
  //分类
  getTerm: function () {
    var that = this;
    http.Post("weapp/goodscats/lists", {}, function (res) {
      if (res.status == 1) {
        var termData = that.data.termData;
        termData = termData.concat(res.data);
        that.setData({ termData: termData})
      }
    });
  },
  //列表
  getList: function () {
    var that = this;
    var page = that.data.page;
    var types = that.data.types;
    var tokenId = app.globalData.tokenId;
    if (page == 0) wx.showLoading({ title: '加载中' });
    page = page + 1;
    http.Post("addon/coupon-weapp-pageCouponQuery", { tokenId: tokenId, catId: types, page: page }, function (res) {
      if (res.status == 1) {
        var coupon = that.data.coupon;
        coupon = coupon.concat(res.data.data);
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
    var isout = e.currentTarget.dataset.isout;
    var couponId = e.currentTarget.dataset.couponid;
    var tokenId = app.globalData.tokenId;
    if (isout == 1) return false;
    http.Post("addon/coupon-weapp-receive", { tokenId: tokenId, couponId: couponId}, function (res) {
        that.prompt(res.msg);
    });
  },
  prompt: function (msg) {
    wx.showModal({
      title: '提示',
      content: msg,
      showCancel: false,
      confirmText: "确定"
    })
  }
})