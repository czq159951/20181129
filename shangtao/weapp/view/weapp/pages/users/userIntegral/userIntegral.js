// pages/user/userIntegral/userIntegral.js
var app = getApp();
var http = require('../../../utils/request.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onShow: function (options) {
  this.getInfo();
  this.getDetailInfo();
  },
  getInfo: function () {
    var that = this;
    http.Post('weapp/userscores/index', {
      tokenId: app.globalData.tokenId, type: -1, pagesize: 10, page: 1
    }, function (res) {
      that.setData({
        userScore: res.data.userScore
      })
    })
  },
  getDetailInfo:function(){
    var that = this;
    http.Post('weapp/userscores/pageQuery', {
      tokenId: app.globalData.tokenId, type: -1,pagesize: 10,page: 1},function(res){
      that.setData({
        list:res.data.list
      })
    })
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },
  /*展示分类动画 */
  powerDrawer(e) {
    var currentTarget = e.currentTarget.dataset.statu;
    
    this.animation(currentTarget);
  },
  animation(currentTarget) {
    var that = this;
    var animation = wx.createAnimation({
      duration: 200,
      timingFunction: "linear",
      delay: 0,
      transformOrigin: "100% 50% 0"
    });
    var animation = animation;
    animation.opacity(0.5).translateX(375).step();
    that.setData({
      animationData: animation.export()
    });
    setTimeout(function () {
      animation.opacity(1).translateX(0).step();
      that.setData({
        animationData: animation.export()
      });
      if (currentTarget == 'close') {
        that.setData({
          animationStatus: false
        })
      }

    }, 200);
    if (currentTarget == 'open') {
      that.setData({
        animationStatus: true
      })
      wx.setNavigationBarTitle({

        title: "积分使用规则"

      })
    }
  },
  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
  
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
  
  }
})