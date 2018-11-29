//index.js
var http = require('../../utils/request.js');
//获取应用实例
const app = getApp()
Page({
  /**
   * 页面的初始数据
   */
  data: {
    select:'1',
    indicatorDots:true,
    autoplay:true,
    interval:3000,
    duration:500,
    circular:true,
    data: [],
    domain: app.globalData.domain,
    goodsLogo: null,
    isLogin:0,
    /*分类商品*/
    currPage: -1,
    goods:[],
    num:'',
    frame:false,
    load: 2
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    wx.showNavigationBarLoading()
    this.getData();
    this.getGoods();
    this.authorize();
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
    wx.hideNavigationBarLoading()
  },
  //信息
  getData: function () {
    var that = this; 
    var tokenId = app.globalData.tokenId;
    http.Get("weapp/index/getIndexData", { tokenId: tokenId}, function (res) {
      wx.hideNavigationBarLoading() //完成停止加载
      if (res.status == 1) {
        that.title(app.globalData.confInfo.mallName);
        that.setData({
          data: res.data,
          goodsLogo: app.globalData.confInfo.goodsLogo
        })
      }
    });
  },
  //标题
  title: function (e) {
    wx.setNavigationBarTitle({
      title: e
    })
  },
  //楼层商品
  getGoods: function () {
    var that = this;
    var currPage = that.data.currPage;
    if (currPage > 0) that.setData({ load: 0})
    if (currPage<10){
      currPage = currPage+1;
      http.Post("weapp/index/pageQuery", { currPage: currPage }, function (res) {
        if (res.status == 1) {
          var goods = that.data.goods;
          goods = goods.concat(res.data);
          that.setData({
            goods: goods,
            currPage: currPage
          })
          wx.hideLoading();
        }else{
          that.setData({ load: 1 })
        }
      });
    } else {
      that.setData({ load: 1 });
    }
  },
  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    var that = this;
    var length = '';//文字长度
    that.setData({
    	length:length,
    	white_strip:length < 400 ? 400-length : that.data.white_strip
    });
    var tokenId = wx.getStorageSync('tokenId') || null;
    if (tokenId == null) {
      that.setData({ isLogin:0 });
    } else {
      that.setData({ isLogin: 1 });
    }
    app.getCartNum();
  },

  getMore:function(){
    wx.navigateTo({
      url:'../news/news',
    })
  },
  toNews:function(e){
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "../news/news?id="+id+"&status=open",
    })
  },
  //消息
  getClassify: function () {
    wx.switchTab({
      url: '../classify/classify'
    })
  },
  //登陆
  getLogin: function () {
    wx.redirectTo({
      url: '../login/login'
    })
  },
  //消息
  getMessages: function () {
    wx.navigateTo({
      url: '../users/messages/messages',
    })
  },
  onReachBottom: function () {
    this.getGoods();
  },
  onPullDownRefresh: function () {
    this.getData();
    wx.stopPullDownRefresh(); //停止下拉刷新
  },
  jumpcenter: function (e) {
    app.jumpcenter(e.currentTarget.dataset.url);
  },

  //分享
  onShareAppMessage: function (res) {
    var that = this;
    if (res.from === 'button') {
      // 来自页面内转发按钮
    }
    return {
      title: app.globalData.confInfo.mallName,
      path: 'pages/index/index'
    }
  },
  //搜索
  toSearch: function () {
    wx.navigateTo({
      url: '../goods-search/goods-search'
    })
  },
  //授权
  authorize: function () {
    var that = this;
    wx.getSetting({
      success(res2) {
        if (!res2.authSetting['scope.userInfo']) {
          that.setData({
            frame: true
          })
        }
      }
    })
  },
  //关闭
  cancel: function (e) {
    this.setData({
      frame: false
    })
  }
})
