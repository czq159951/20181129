var app =getApp();
var http = require('../../utils/request.js');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    mainArray:[],
    viceArray:[],
    domain: app.globalData.domain,
    goodsLogo: null,
    select:'',
    hasImage:'',
    ads:[],
    scrollSite:'',
    interIm:false
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function () {
    var that = this;
    var mainArray = that.data.mainArray;
    wx.showLoading({ title: '加载中' });
    /*数据初始化 */
    http.Post('weapp/goodscats/index', {}, function (res) {
      wx.hideLoading();
      if(res.status == 1 && res.data){
        var data = res.data;
        that.setData({
          mainArray: data
        });
        /*默认显示 */
        that.showAssify('1');
      }
    });
    that.setData({
      goodsLogo: app.globalData.confInfo.goodsLogo
    });
    this.getHot();
  },
  onShow:function(){

    app.getCartNum();
  },
  showAssify: function (e) {
    var that = this;
     var viceArray = this.data.viceArray;
     var select = that.data.select;
     var hasImage = that.data.hasImage;
     var ads = that.data.ads;
     var mainArray = that.data.mainArray;
     var scrollSite = that.data.scrollSite;
     /*当e.length为1时   说明显示默认数据 */
     if (e.length) {
       var catId = 0;
       viceArray = mainArray[0]['childList'];
       select = mainArray[0].catId;
       if (!mainArray[0]['ads'].length) {
         hasImage = false;
       } else {
         ads = mainArray[0]['ads'];
         hasImage = true;
       }
     }else{
       var catId = e.currentTarget.dataset.catid;
       var id = e.target.id;
     for (let i = 0; i < mainArray.length; i++) {
       if(mainArray[i].catId == catId){
         viceArray = mainArray[i]['childList'];
         select = mainArray[i].catId;
         if (!mainArray[i]['ads'].length){
           hasImage = false;
         } else {
           ads = mainArray[i]['ads'];
           hasImage = true;
         }
       }
      }
     }
     scrollSite = 74*id;
     that.setData({
       viceArray : viceArray,
       select : select,
       hasImage : hasImage,
       ads: ads,
      scrollSite: scrollSite
     });
  },
  /*跳转到分类列表 */
  goodsList:function(e){
    var catId = e.currentTarget.dataset.catid;
    wx.navigateTo({
      url: "../goods-list/goods-list?catId="+catId,
    })
  },
  interPage: function () {
    this.setData({
      interIm: true
    })
  },
  black: function () {
    this.setData({
      interIm: false
    })
  },
  //楼层商品
  getHot: function () {
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
      wx.showModal({ title: '提示', content: '请输入关键字!', showCancel: false })
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
  },
  jumpcenter: function (e) {
    app.jumpcenter(e.currentTarget.dataset.url);
  }
})