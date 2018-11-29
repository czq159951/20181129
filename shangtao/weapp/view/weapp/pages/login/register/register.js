var http = require('../../../utils/request.js');
var rsa = require('../../common/rsa/rsa.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    regName: '',
    regPwd: '',
    regcoPwd: '',
    regVerfy: '',
    phoneCode: '',
    mobileCode: '',
    isType: 2,
    isEmail: true,
    isPhone: false,
    isPhoneVerify: false,
    time: 0,
    isSend: false,
    verifyWord: '获取验证码',
    sessionId:null,
    phDisabled: false,
    reDisabled: false,
    radioType:1
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.pcode();
    this.setData({
      sessionId: app.globalData.confInfo.sessionId
    })
    if (app.globalData.confInfo.smsVerfy == 1) {
      this.setData({
        isPhone: true,
      })
    }
    if (app.globalData.confInfo.smsOpen == 1) {
      this.setData({
        isPhoneVerify: true,
      })
    }
  },
  //验证码
  pcode: function () {
    var that = this;
    var sessionId = app.globalData.confInfo.sessionId;
    that.setData({
      pcode: app.globalData.domain + "weapp/index/getVerify?rnd=" + Math.random() + "&sessionId=" + sessionId
    })
  },
  name: function(e){
    var that = this; 
    var regName = e.detail.value;
    if (regName){
      var regMobile = /^0?1[3|4|5|8][0-9]\d{8}$/;
      if (regMobile.test(regName)) {//手机
        http.Post("weapp/users/checkUserPhone", { userPhone: regName}, function (res) {
          if (res.status == -1) {
            app.prompt('手机号已注册');
          }
        });
      }
    }
    that.setData({
      regName: regName,
    })
  },
  pwd: function (e) {
    this.setData({
      regPwd: e.detail.value
    })
  },
  back: function (e) {
    this.setData({
      regcoPwd: e.detail.value
    })
  },
  phoneverfy: function (e) {
    this.setData({
      phoneCode: e.detail.value
    })
  },
  checkCode : function (e) {
    this.setData({
      mobileCode: e.detail.value
    })
  },
  //短信验证码
  pverify: function (e) {
    var that = this;
    var time = that.data.time;
    var isSend = that.data.isSend;
    var regName = that.data.regName;
    var phoneCode = that.data.phoneCode;
    var sessionId = that.data.sessionId;
    if (app.globalData.confInfo.smsVerfy == 1){
      if (phoneCode == '') {
        app.prompt('请输入验证码');
        return false;
      }
    }
    if (isSend) return;
    that.setData({ isSend: true })
    http.Post("weapp/users/getphonecode", { userPhone: regName, smsVerfy: phoneCode, sessionId: sessionId}, function (res) {
    if (res.status == 1) {
        wx.showToast({
          title: '短信已发送',
          icon: 'success'
        })
        time = 120;
        that.setData({phDisabled: true, verifyWord:'120秒获取'})
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
        that.setData({ isSend: false })
      }
    });
  },
  //注册
  register: function (){
    var that = this;
    var regName = that.data.regName;
    var regPwd = that.data.regPwd;
    var regcoPwd = that.data.regcoPwd;
    var regVerfy = that.data.regVerfy;
    var mobileCode = that.data.mobileCode;
    var radioType = that.data.radioType;
    var sessionId = that.data.sessionId;
    var sessionKey = app.globalData.sessionKey;
    var unionKey = app.globalData.unionKey;
    var avatarUrl = app.globalData.userInfo.avatarUrl;
    var nickName = app.globalData.userInfo.nickName;
    var gender = app.globalData.userInfo.gender;
    var isCryptPwd = app.globalData.confInfo.isCryptPwd;
    var public_key = app.globalData.confInfo.pwdModulusKey;
    if (radioType == 0) {
      app.prompt('请阅读用户注册协议');
      return false;
    }
    if (regName == '') {
      app.prompt('请输入账号');
      return false;
    }
    if (regName.length < 11) {
      app.prompt('请输入正确的手机号码');
      return false;
    }
    if (regPwd == '') {
      app.prompt('请输入密码');
      return false;
    }
    if (regPwd.length < 6 || regPwd.length > 16) {
      app.prompt('请输入密码为6-16位字符');
      return false;
    }
    if (regcoPwd == '') {
      app.prompt('确认密码不能为空');
      return false;
    }
    if (regPwd != regcoPwd) {
      app.prompt('确认密码不一致');
      return false;
    }
    if (app.globalData.confInfo.smsOpen == 1 && mobileCode == '') {
      app.prompt('请输入短信验证码');
      return false;
    }
    if (isCryptPwd == 1) {
      var exponent = "10001";
      var rsakey = new rsa.RSAKey();
      rsakey.setPublic(public_key, exponent);
      var regPwd = rsakey.encrypt(regPwd);
    }
    that.setData({reDisabled : true})
    wx.showLoading({ title: '注册中···'})
    var data = { loginName: regName, loginPwd: regPwd, verifyCode: regVerfy, mobileCode: mobileCode, sessionKey: sessionKey, unionKey: unionKey,avatarUrl: avatarUrl, nickName: nickName, gender: gender, sessionId: sessionId}
    http.Post("weapp/users/register", data, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        app.globalData.tokenId = res.data;
        wx.setStorageSync('tokenId', res.data);
        wx.showToast({
          title: '注册成功',
          icon: 'success',
          complete: function(err) {
            wx.reLaunch({
              url: '../../users/users',
            })
          }
        })
      } else {
        app.prompt(res.msg);
        that.code();
        that.setData({ reDisabled: false })
      }
    });
  },
  //是否勾选
  inAgree: function(){
    var that = this;
    var radioType = that.data.radioType;
    if (radioType==1){
      that.setData({ radioType: 0 })
    }else{
      that.setData({ radioType: 1 })
    }
  },
  //注册协议
  agreement: function () {
    wx.navigateTo({
      url: '../../login/agreement/agreement'
    })
  }
})