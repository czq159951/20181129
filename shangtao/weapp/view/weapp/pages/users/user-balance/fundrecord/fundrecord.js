var app = getApp();
var http = require("../../../../utils/request.js");

Page({
  data: {
    lockMoney:null,
    userMoney:null,
    list:[],
    page:0,
    last_page:0
  },

  onLoad: function (options) {
    this.getCapitalInfo();
    this.getCapitalList();
  },

  onReady: function () {
  
  },
  getCapitalInfo: function () {
    var that = this;
    http.Post('weapp/logMoneys/record', { tokenId: app.globalData.tokenId}, function (res) {
      if (res.status == 1) {
        that.setData({
          userMoney: res.data.userMoney,
          lockMoney: res.data.lockMoney
        })
      }else{
        app.prompt(res.msg);
      }
    })
  },
  getCapitalList:function(){
    var that = this;
    var page = that.data.page+1;
    var list = that.data.list;
    wx.showLoading({ title: '加载中' });
    http.Post('weapp/logMoneys/pageQuery',{tokenId:app.globalData.tokenId,type:-1,pagesize:10,page:page},function(res){
      list = list.concat(res.data.data);
      if(res.status){
        that.setData({
          list:list,
          page: Number(res.data.current_page),
          last_page:res.data.last_page
        })
        wx.hideLoading();
      }
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
      this.getCapitalList();
    }
  }
})