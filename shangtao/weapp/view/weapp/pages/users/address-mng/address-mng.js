var app = getApp();
var http = require('../../../utils/request.js');
Page({
  /**
   * 页面的初始数据
   */
  data: {
     dataStatu:true,
     dataList:[],
     types:0
  },
  addAddress(e){
    if (e.currentTarget.dataset.id) {
      var id = e.currentTarget.dataset.id;
    }else{
      var id = '';
    }
   wx.navigateTo({
     url: './addaddress/addaddress?id='+id,
   })
  },
  onLoad: function (options) {
    var types = options.type || 0;
    this.setData({
      types: types
    })
  },
  onShow: function (options) {
    this.getAddress();
  },
  getAddress: function () {
    var that = this;
    var dataList = that.data.dataList;
    var dataStatu = that.data.dataStatu;
    var tokenId = app.globalData.tokenId;
    http.Post('weapp/UserAddress/index', {tokenId:tokenId}, function (res) {
      if (res.status == 1) {
        if(res.data.list !=0){
          dataStatu = true
        }else{
          dataStatu = false
        }
        that.setData({
          dataList: res.data.list,
          dataStatu: dataStatu
        })
      }
    })
  },
  operateAddress: function (id,url) {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post('weapp/UserAddress/'+url, { tokenId: tokenId ,id :id}, function (res) {
      if(res.status == 1){
        app.prompt(res.msg, 'success');
        that.getAddress();
      }else{
        app.prompt(res.msg);
        that.getAddress();
      }
    })
  },
  del: function (e) {
    var that = this;
    var id = e.currentTarget.dataset.id;
    var url = e.currentTarget.dataset.url;
    
    wx.showModal({
      title: '提示',
      content: '确定删除吗？',
      success:function(res){
        if (res.cancel== true){
          return false;
        }else{
          that.operateAddress(id,url)
        }
      }
    })
  },
  setStatu:function(e){
    var id = e.currentTarget.dataset.id;
    var url = e.currentTarget.dataset.url;
    this.operateAddress(id, url)
  },
  //结算地址切换
  switchAddress: function (e) {
    var that = this;
    var types = that.data.types;
    var id = e.currentTarget.dataset.id;
    if (types == 1) {
      var pages = getCurrentPages();
      var currPage = pages[pages.length - 1];
      var prevPage = pages[pages.length - 2];
      prevPage.setData({
        addressId: id
      })
      wx.navigateBack({
        delta: 1
      })
    }
  }
})