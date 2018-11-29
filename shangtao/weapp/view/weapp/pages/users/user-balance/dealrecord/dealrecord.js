var app = getApp();
var http = require("../../../../utils/request.js");

Page({
  data: {
     list:[],
     page:0,
     last_page:0
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function () {
     this.getRecord();
  },

  onReady: function () {
  
  },
  getRecord:function(){
    var that = this;
     wx.showLoading({ title: '加载中' });
    var page = that.data.page + 1;
    var list = that.data.list;
    http.Post('weapp/cashdraws/pageQuery',{tokenId:app.globalData.tokenId,pagesize:10,page:page},function(res){
      list = list.concat(res.data.data);
     if(res.status==1){
       that.setData({
         list:list,
         page: Number(res.data.current_page),
         last_page: res.data.last_page
       })
      }
      wx.hideLoading();
     })
  },
  onPullDownRefresh: function () {
  
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
    var last_page = this.data.last_page;
    var page = this.data.page;
    if (page < last_page) {
      this.getRecord();
      }
  } 
})