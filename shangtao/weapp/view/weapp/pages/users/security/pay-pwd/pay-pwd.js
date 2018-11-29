var http = require('../../../../utils/request.js');
var rsa = require('../../../common/rsa/rsa.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    pwdType:1,
    orpayPwd: '',
    payPwd: '',
    copayPwd: '',
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
        var pwdType = res.data.payPwd;
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
      var word = '修改支付密码';
    }else{
      var word = '设置支付密码';
    }
    wx.setNavigationBarTitle({
      title: word
    })
  },
  orpayPwd: function (e) {
    this.setData({
      orpayPwd: e.detail.value
    })
  },
  payPwd: function (e) {
    this.setData({
      payPwd: e.detail.value
    })
  },
  copayPwd: function (e) {
    this.setData({
      copayPwd: e.detail.value
    })
  },
  //提交
  submit: function () {
    var that = this;
    var orpayPwd = that.data.orpayPwd;
    var payPwd = that.data.payPwd;
    var copayPwd = that.data.copayPwd;
    var pwdType = that.data.pwdType;
    var isCryptPwd = app.globalData.confInfo.isCryptPwd;
    var public_key = app.globalData.confInfo.pwdModulusKey;
    var tokenId = app.globalData.tokenId;
    if (pwdType == 1) {
      if (orpayPwd == '') {
        app.prompt('原密码不能为空')
        return false;
      }
      var word = '修改成功';
    }else{
      var word = '设置成功';
    }
    if (payPwd == '') {
      app.prompt('新密码不能为空')
      return false;
    }
    if (copayPwd == '') {
      app.prompt('确认密码不能为空')
      return false;
    }
    if (payPwd.length != 6) {
      app.prompt('请输入6位数字密码')
      return false;
    }
    if (copayPwd != payPwd) {
      app.prompt('确认密码不一致')
      return false;
    }
    if (isCryptPwd == 1) {
      var exponent = "10001";
      var rsakey = new rsa.RSAKey();
      rsakey.setPublic(public_key, exponent);
      if (pwdType == 1) var orpayPwd = rsakey.encrypt(orpayPwd);
      var payPwd = rsakey.encrypt(payPwd);
    }
    that.setData({ disabled: true })
    wx.showLoading({ title: '设置中···' })
    var data = { tokenId: tokenId, oldPass: orpayPwd, newPass: payPwd}
    http.Post("weapp/users/editpaypwd", data, function (res) {
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
      url: '../paypwd-back/paypwd-back'
    })
  }
})