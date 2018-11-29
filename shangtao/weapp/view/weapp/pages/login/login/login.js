var http = require('../../../utils/request.js');
var rsa = require('../../common/rsa/rsa.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    loginName: '',
    loginPwd: '',
    verifyCode: '',
    loDisabled: false,
    sessionId: null
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.code();
    this.setData({
      sessionId: app.globalData.confInfo.sessionId
    })
  },
  //验证码
  code: function () {
    var that = this;
    var sessionId = app.globalData.confInfo.sessionId;
    that.setData({
      code: app.globalData.domain + "weapp/index/getVerify?rnd=" + Math.random() + "&sessionId=" + sessionId
    })
  },
  name : function(e){
    this.setData({
      loginName:e.detail.value
    })
  },
  pwd : function(e){
    this.setData({
      loginPwd:e.detail.value
    })
  },
  verify: function (e) {
    this.setData({
      verifyCode: e.detail.value
    })
  },
  login: function () {
    var that = this;
    var loginName = that.data.loginName;
    var loginPwd = that.data.loginPwd;
    var verifyCode = that.data.verifyCode;
    var sessionId = that.data.sessionId;
    var sessionKey = app.globalData.sessionKey;
    var unionKey = app.globalData.unionKey;
    var isCryptPwd = app.globalData.confInfo.isCryptPwd;
    var public_key = app.globalData.confInfo.pwdModulusKey;
    if (loginName ==''){
      app.prompt('请输入用户名');
      return false;
    }
    if (loginPwd == '') {
      app.prompt('请输入密码');
      return false;
    }
    if (verifyCode == '') {
      app.prompt('请输入验证码');
      return false;
    }
    if (isCryptPwd == 1) {
      var exponent = "10001";
      var rsakey = new rsa.RSAKey();
      rsakey.setPublic(public_key, exponent);
      var loginPwd = rsakey.encrypt(loginPwd);
    }
    that.setData({ loDisabled: true })
    wx.showLoading({ title: '登陆中···' })
    var data = { loginName: loginName, loginPwd: loginPwd, verifyCode: verifyCode, sessionId: sessionId, sessionKey: sessionKey, unionKey: unionKey}
    http.Post("weapp/users/login", data, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        app.globalData.tokenId = res.data; 
        wx.setStorageSync('tokenId', res.data);
        wx.showToast({
          title: '登录成功',
          icon: 'success',
          complete: function (err) {
            wx.reLaunch({
              url: '../../users/users',
            })
          }
        })
      } else {
        app.prompt(res.msg);
        that.code();
        that.setData({ loDisabled: false })
      }
    });
  },
  //忘记密码
  forget:function (){
    wx.redirectTo({
      url: '../loginpwd-forget/loginpwd-forget'
    })
  }
})