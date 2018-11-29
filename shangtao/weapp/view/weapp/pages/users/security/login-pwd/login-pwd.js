var http = require('../../../../utils/request.js');
var rsa = require('../../../common/rsa/rsa.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    pwdType:1,
    orloginPwd: '',
    loginPwd: '',
    cologinPwd: '',
    disabled:false
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.getData();
  },
  //数据
  getData: function () {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post("weapp/users/security", { tokenId: tokenId }, function (res) {
      if (res.status == 1) {
        var pwdType = res.data.loginPwd;
        that.title(pwdType);
        that.setData({
          pwdType: pwdType
        })
      }
    });
  },
  //标题
  title: function (e) {
    if (e==1){
      var word = '修改登录密码';
    }else{
      var word = '设置登录密码';
    }
    wx.setNavigationBarTitle({
      title: word
    })
  },
  orloginPwd: function (e) {
    this.setData({
      orloginPwd: e.detail.value
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
  //提交
  submit: function () {
    var that = this;
    var orloginPwd = that.data.orloginPwd;
    var loginPwd = that.data.loginPwd;
    var cologinPwd = that.data.cologinPwd;
    var pwdType = that.data.pwdType;
    var isCryptPwd = app.globalData.confInfo.isCryptPwd;
    var public_key = app.globalData.confInfo.pwdModulusKey;
    var tokenId = app.globalData.tokenId;
    if (pwdType == 1) {
      if (orloginPwd == '') {
        app.prompt('原密码不能为空')
        return false;
      }
      var word = '修改成功';
    }else{
      var word = '设置成功';
    }
    if (loginPwd == '') {
      app.prompt('新密码不能为空')
      return false;
    }
    if (cologinPwd == '') {
      app.prompt('确认密码不能为空')
      return false;
    }
    if (loginPwd.length < 6) {
      app.prompt('请输入6位以上数字或者字母的密码')
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
      if (pwdType == 1)var orloginPwd = rsakey.encrypt(orloginPwd);
      var loginPwd = rsakey.encrypt(loginPwd);
    }
    that.setData({ disabled: true })
    wx.showLoading({ title: '设置中···' })
    var data = { tokenId: tokenId, oldPass: orloginPwd, newPass: loginPwd}
    http.Post("weapp/users/editloginpwd", data, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        wx.showToast({
          title: word,
          icon: 'success',
          complete: function (err) {
            wx.navigateBack({
              delta: 1
            })
          }
        })
      } else {
        app.prompt(res.msg);
        that.setData({ disabled: false })
      }
    });
  },
  //找回密码
  back: function () {
    wx.redirectTo({
      url: '../loginpwd-back/loginpwd-back'
    })
  }
})