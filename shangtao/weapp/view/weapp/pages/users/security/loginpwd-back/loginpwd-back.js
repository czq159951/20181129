var http = require('../../../../utils/request.js');
var rsa = require('../../../common/rsa/rsa.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    data:[],
    smsVerfy:0,
    phoneVerfy:'',
    phoneCode:'',
    loginPwd:'',
    cologinPwd: '',
    verifyWord: '获取验证码',
    time: 0,
    isSend: false,
    phDisabled: false,
    nextDisabled: false,
    suDisabled:false,
    sessionId: null,
    step: 0,
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.code();
    this.getData();
    this.setData({
      sessionId: app.globalData.confInfo.sessionId,
      smsVerfy: app.globalData.confInfo.smsVerfy
    })
  },
  //数据
  getData: function () {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post("weapp/users/security", { tokenId: tokenId }, function (res) {
      if (res.status == 1) {
        that.setData({
          data: res.data,
          step: res.data.phoneType
        })
      }
    });
  },
  //验证码
  code: function () {
    var that = this;
    var sessionId = app.globalData.confInfo.sessionId;
    that.setData({
      code: app.globalData.domain + "weapp/index/getVerify?rnd=" + Math.random() + "&sessionId=" + sessionId
    })
  },
  phoneVerfy: function (e) {
    this.setData({
      phoneVerfy: e.detail.value
    })
  },
  phoneCode: function (e) {
    this.setData({
      phoneCode: e.detail.value
    })
  },
  loginPwd: function (e) {
    this.setData({
      loginPwd: e.detail.value
    })
  },
  cologinPwd: function (e) {
    this.setData({
      cologinPwd: e.detail.value
    })
  },
  //绑定手机号码
  jump:function(){
    
  },
  //短信验证码
  pverify: function () {
    var that = this;
    var time = that.data.time;
    var isSend = that.data.isSend;
    var phoneVerfy = that.data.phoneVerfy;
    var sessionId = that.data.sessionId;
    var tokenId = app.globalData.tokenId;
    if (app.globalData.confInfo.smsVerfy == 1) {
      if (phoneVerfy == '') {
        app.prompt('请输入验证码');
        return false;
      }
    }
    if (isSend) return;
    that.setData({ isSend: true })
    http.Post("weapp/users/backloginCode", { tokenId: tokenId, smsVerfy: phoneVerfy, sessionId: sessionId }, function (res) {
      if (res.status == 1) {
        app.prompt('短信已发送');
        time = 120;
        that.setData({ phDisabled: true, verifyWord: '120秒获取' })
        var task = setInterval(function () {
          time--;
          that.setData({ verifyWord: '' + time + "秒获取" })
          if (time == 0) {
            clearInterval(task);
            that.setData({ isSend: false, phDisabled: false, verifyWord: '重新发送' })
          }
        }, 1000);
      } else {
        app.prompt(res.msg);
        that.code();
        that.setData({ isSend: false })
      }
    });
  },
  //下一步
  verify: function () {
    var that = this;
    var phoneCode = that.data.phoneCode;
    var sessionId = that.data.sessionId;
    var tokenId = app.globalData.tokenId;
    if (phoneCode == '' ){
      app.prompt('请输入短信验证码');
      return false;
    }
    that.setData({ nextDisabled: true })
    wx.showLoading({ title: '验证中···' })
    var data = { tokenId: tokenId, phoneCode: phoneCode, sessionId: sessionId}
    http.Post("weapp/users/verifybackLogin", data, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        wx.showToast({
          title: res.msg,
          icon: 'success',
          complete: function (err) {
            that.setData({ step: 2 })
          }
        })
      } else {
        app.prompt(res.msg);
        that.setData({ nextDisabled: false })
      }
    });
  },
  //提交
  submit: function () {
    var that = this;
    var loginPwd = that.data.loginPwd;
    var cologinPwd = that.data.cologinPwd;
    var sessionId = that.data.sessionId;
    var isCryptPwd = app.globalData.confInfo.isCryptPwd;
    var public_key = app.globalData.confInfo.pwdModulusKey;
    var tokenId = app.globalData.tokenId;
    if (loginPwd == '') {
      app.prompt('新密码不能为空')
      return false;
    }
    if (cologinPwd == '') {
      app.prompt('确认密码不能为空')
      return false;
    }
    if (loginPwd.length < 6 || loginPwd.length > 16) {
      app.prompt('请输入密码为6-16位字符');
      return false;
    }
    if (cologinPwd != loginPwd) {
      app.prompt('确认密码不一致')
      return false;
    }
    if (isCryptPwd == 1) {
      var exponent = "10001";
      var rsakey = new rsa.RSAKey();
      rsakey.setPublic(public_key, exponent);
      var loginPwd = rsakey.encrypt(loginPwd);
    }
    that.setData({ suDisabled: true })
    wx.showLoading({ title: '设置中···' })
    var data = { tokenId: tokenId, newPass: loginPwd, sessionId: sessionId}
    http.Post("weapp/users/resetbackLogin", data, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        wx.showToast({
          title: "设置成功",
          icon: 'success',
          complete: function (err) {
            wx.navigateBack({
              delta: 1
            })
          }
        })
      } else {
        app.prompt(res.msg);
        that.setData({ suDisabled: false })
      }
    });
  },
  //找回密码
  back: function () {
    wx.navigateTo({
      url: './pay-pwd/pay-pwd'
    })
  }
})