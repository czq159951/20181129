var http = require('../../../../utils/request.js');
var rsa = require('../../../common/rsa/rsa.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    data:[],
    domain: app.globalData.domain,
    orderNo:'',
    isBatch:0,
    pwdType:1,
    payPwd:'',
    confirmPwd:'',
    disabled:false
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      orderNo: options.orderNo,
      isBatch: options.isBatch
    });
  },
  /**
  * 生命周期函数--监听小程序显示
  */
  onShow: function () {
    this.getData();
  },
  //数据
  getData:function(){
    var that = this;
    var orderNo = that.data.orderNo;
    var isBatch = that.data.isBatch;
    var tokenId = app.globalData.tokenId;
    http.Post("weapp/wallets/payment", { tokenId: tokenId, orderNo: orderNo, isBatch: isBatch }, function (res) {
      if (res.status == 1) {
        that.setData({
          data:res.data,
          pwdType: res.data.payPwd,
        })
      }else{
        wx.showModal({
          title: '提示',
          content: res.msg,
          showCancel: false,
          confirmText: "确定",
          success: function (res) {
            if (res.confirm) {
              wx.navigateBack({
                delta: 1
              })
            }
          }
        })
      }
    });
  },
  payPwd: function (e) {
    this.setData({
      payPwd: e.detail.value
    })
  },
  confirmPwd: function (e) {
    this.setData({
      confirmPwd: e.detail.value
    })
  },
  //支付
  payment:function(e){
    var that = this;
    var payPwd = that.data.payPwd;
    var confirmPwd = that.data.confirmPwd;
    var pwdType = that.data.pwdType;
    var orderNo = that.data.orderNo;
    var isBatch = that.data.isBatch;
    var isCryptPwd = app.globalData.confInfo.isCryptPwd;
    var public_key = app.globalData.confInfo.pwdModulusKey;
    var tokenId = app.globalData.tokenId;
    if (payPwd == '') {
      app.prompt('请输入支付密码');
      return false;
    }
    if (confirmPwd == '' && pwdType==0) {
      app.prompt('确认密码不能为空');
      return false;
    }
    if (payPwd != confirmPwd && pwdType == 0) {
      app.prompt('确认密码不一致');
      return false;
    }
    if (isCryptPwd==1){
      var exponent = "10001";
      var rsakey = new rsa.RSAKey();
      rsakey.setPublic(public_key, exponent);
      var confirmPwd = rsakey.encrypt(confirmPwd);
      var payPwd = rsakey.encrypt(payPwd);
    }
    that.setData({ disabled: true })
    wx.showLoading({ title: '支付中···' })
    if (pwdType == 0){
      http.Post("weapp/users/editpayPwd", { tokenId: tokenId, oldPass: confirmPwd, newPass: payPwd }, function (res) {
      });
    }
    http.Post("weapp/wallets/payByWallet", { tokenId: tokenId, payPwd: payPwd, orderNo: orderNo, isBatch: isBatch}, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        wx.showToast({
          title: res.msg,
          icon: 'success',
          complete: function (err) {
            wx.navigateBack({
              delta: 2
            })
          }
        })
      }else{
        app.prompt(res.msg);
        that.setData({ disabled: false })
      }
    });
  },
  //忘记密码
  forget:function(){
    wx.navigateTo({
      url: '../../security/paypwd-back/paypwd-back'
    })
  }
})