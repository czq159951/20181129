
Page({
  /**
   * 页面的初始数据
   */
  data: {
    data: []
  },
  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function (options) {
   
  },
  
  //个人中心
  personal: function () {
    wx.navigateTo({
      url: '../edit/edit'
    })
  },
  //账户安全
  security: function () {
    wx.navigateTo({
      url: '../security/security'
    })
  },
  //手机号码
  about: function () {
    wx.navigateTo({
      url: './about/about'
    })
  }
})