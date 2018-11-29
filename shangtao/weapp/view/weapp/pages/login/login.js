var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
     user:'',
     info:''
  },
  /**
   * 生命周期函数--监听小程序显示
   */
  onShow: function (e) {
    this.getUser();
    if (app.globalData.tokenId == null) {
      app.checkLogin(function (res) {
        if (res==1){
          wx.showToast({
            title: "登录中···",
            icon: "loading",
            duration: 2000,
            complete: function(res) {
              setTimeout(function () {
                wx.navigateBack({
                  delta: 1
                })
              }, 1500);
            }
          })
        }
      })
    }
  },
  //获取用户信息
  getUser :function(){
    var that = this;
    var user = app.globalData.userInfo;
    if (user.nickName) {
      that.setData({
        user: user,
        info: app.globalData.confInfo.mallName
      })
    } else {
      wx.showLoading({ title: '加载中' });
      app.getUserInfo(function (res) {
        wx.hideLoading();
        if (res.userInfo) {
          that.setData({
            user: res.userInfo,
            info: app.globalData.confInfo.mallName
          })
        }
      });
    }
  },
  //登陆
  login: function (){
    wx.navigateTo({
      url: '../login/login/login'
    })
  },
  //注册
  register: function () {
    wx.navigateTo({
      url: '../login/register/register'
    })
  },
  //首页
  index:function(){
    wx.switchTab({
      url: '../index/index'
    })
  }
})