var http = require('../../utils/request.js');
var coupon = require('../../addons/closure/coupon/coupon.js');
//获取应用实例
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    userData:[],
    domain: app.globalData.domain,
    jumpCenter:[{name:"关注商品",img:"/image/img_users_icon10.png",url:"../users/attension/attension"},
                {name:"关注店铺",img:"/image/img_users_icon11.png",url:"../users/attension-shops/attension-shops"},
                {name:"浏览记录",img:"/image/img_users_icon12.png",url:"../users/browhistory/browhistory"},
                {name:"账户安全",img:"/image/img_users_icon13.png",url:"./security/security"},
                {name:"资金管理",img:"/image/img_users_icon14.png",url:"/pages/users/user-balance/user-balance"},
                {name:"我的积分",img:"/image/img_users_icon15.png",url:"/pages/users/userIntegral/userIntegral"},
                {name:"地址管理",img:"/image/img_users_icon18.png",url:"../users/address-mng/address-mng"},
                {name:"订单投诉",img:"/image/img_users_icon20.png",url:"/pages/users/complain-mng/complain-mng"}
    ],
    addons: [],
    couponsNum:0
  },
  /**
 * 生命周期函数--监听页面加载
 */
  onLoad: function (options) {
    this.setData({
      addons: app.globalData.confInfo.addons,
      userPhoto: app.globalData.confInfo.userLogo
    })
  },
  /**
   * 生命周期函数--监听小程序显示
   */
  onShow: function (e) {
    if (app.globalData.tokenId == null) {
      wx.navigateTo({
        url: '../login/login'
      })
    } else {
      this.getUserInfo();
    }
    app.getCartNum();
  }, 
  getUserInfo: function () {
    var that = this;
    var tokenId = app.globalData.tokenId;
    var addons = that.data.addons;
    http.Post('weapp/users/index', { tokenId: tokenId }, function (res) {
      if (res.status == 1){
        var integralWord = (res.data.isSign) ? 1 : 0;
        that.setData({
          userData: res.data,
          integralWord: integralWord
        })
        if (addons.Coupon == 1) {
          coupon.couponUsers({ tokenId: tokenId }, function (res) {
            if (res) {
              that.setData({ couponsNum: res.couponsNum });
            }
          });
        }
      }
     });
  },
  //预览
  preview:function(){
    var that = this;
    var userData = that.data.userData
    wx.previewImage({
      current: userData.userPhoto,
      urls: [userData.userPhoto] 
    })
  },
  statusSign: function(){
    var that = this;
    var userData = that.data.userData
    var tokenId = app.globalData.tokenId;
    if (!userData.isSign){
      http.Post('weapp/userscores/sign', { tokenId: tokenId }, function (res) {
        if (res.status == 1) {
          app.prompt(res.msg);
          that.getUserInfo();
          that.setData({
            integralWord: 1
          })
        } else {
          app.prompt(res.msg);
        }
      })
    }
  },
  jumpCenter:function(e){
  	var url = e.currentTarget.dataset.url;
  	wx.navigateTo({
      url: url,
      fail:function(){
        wx.switchTab({
          url: url,
        })
      }
    })
  },
  /*账户管理 */
  accountManageUrl(){
    wx.navigateTo({
      url: './userset/userset'
    })
  },
  /*商城信息 */
  toMail:function(){
    wx.navigateTo({
      url: './messages/messages',
    })
  },
  /*分享赚钱 */
  shareMoneyUrl(){
    wx.navigateTo({
      url: '/pages/user/shareMoney/shareMoney',
    })
  },
  /*商家订单 */
  sellerOrders() {
    wx.navigateTo({
      url: './sellerorders/sellerorders',
    })
  },
  /*全部订单 */
  userOrders(){
    wx.navigateTo({
      url: './orders/orders'
    })
  },
  /*跳转至订单列表 */
  toOrders: function (e) {
    var types = e.currentTarget.dataset.types;
    wx.navigateTo({
      url: './orders/orders?types=' + types,
    })
  }
})