// pages/chatWindow/chatWindow.js
var app = getApp();
var http = require('../../utils/request.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    goodsId:'',
    pagesize: 10,
    page: 1,
    hasData:false,
    InfoArray:[]
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (e) {
    
   var goodsId = e.goodsId;
   this.setData({
     goodsId:goodsId
   })
   this.getInfo();
  },
  getInfo:function(){
    var that = this;
    var goodsId = that.data.goodsId;
    var data = that.data.InfoArray;
    var hasData = that.data.hasData;
    var pagesize = that.data.pagesize;
    var page = that.data.page;

    http.Post('weapp/goodsconsult/listQuery', { goodsId: goodsId,pagesize:pagesize,page:page }, function (res) {
      data = data.concat(res.data.data);
      if (!data.length){
        hasData = false;
        
      }else{
        hasData = true;
      }
      that.setData({
        hasData:hasData,
        InfoArray: data,
        totalPage: res.data.last_page
      })
    })
  },
  addInfo:function(){
    var goodsId = this.data.goodsId;
    wx.navigateTo({
       url: './addinfo/addinfo?goodsId='+goodsId,
     })
  },
  onReachBottom:function(){
    var totalPage = this.data.totalPage;
    var page = this.data.page;
    if(page < totalPage){
      this.setData({
           page :page+1
      })
      this.getInfo();
    }
  }
})