var http = require('../../../../utils/request.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    data: [],
    userPhone: '',
    biVerfy: '',
    biCode: '',
    biWord: '获取验证码',
    biTime: 0,
    biSend: false,
    bioDisabled: false,
    biDisabled: false,
    moVerfy: '',
    moCode: '',
    moWord: '获取验证码',
    moTime: 0,
    moSend: false,
    mooDisabled: false,
    moDisabled: false,
    smsVerfy: 0,
    sessionId: null,
    step:0,
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
        var step = res.data.phoneType;
        that.title(step);
        that.setData({
          data: res.data,
          step: res.data.phoneType
        })
      }
    });
  },
  //标题
  title: function (e) {
    if (e == 1) {
      var word = '修改手机号码';
    } else {
      var word = '绑定手机号码';
    }
    wx.setNavigationBarTitle({
      title: word
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
  userPhone : function(e){
    this.setData({
      userPhone:e.detail.value
    })
  },
  biVerfy : function(e){
    this.setData({
      biVerfy:e.detail.value
    })
  },
  biCode: function (e) {
    this.setData({
      biCode: e.detail.value
    })
  },
  moVerfy: function (e) {
    this.setData({
      moVerfy: e.detail.value
    })
  },
  moCode: function (e) {
    this.setData({
      moCode: e.detail.value
    })
  },
  //短信验证码/绑定
  biObtain: function () {
    var that = this;
    var biTime = that.data.biTime;
    var biSend = that.data.biSend;
    var userPhone = that.data.userPhone;
    var biVerfy = that.data.biVerfy;
    var sessionId = that.data.sessionId;
    var tokenId = app.globalData.tokenId;
    if(userPhone ==''){
      app.prompt('请输入手机号码');
      return false;
    }
    if (app.globalData.confInfo.smsVerfy == 1) {
      if (biVerfy == '') {
        app.prompt('请输入验证码');
        return false;
      }
    }
    if (biSend) return;
    that.setData({ biSend: true })
    http.Post("weapp/users/sendCodeTie", { tokenId: tokenId, userPhone: userPhone, smsVerfy: biVerfy, sessionId: sessionId }, function (res) {
      if (res.status == 1) {
        app.prompt('短信已发送');
        biTime = 120;
        that.setData({ bioDisabled: true, biWord: '120秒获取' })
        var task = setInterval(function () {
          biTime--;
          that.setData({ biWord: '' + biTime + "秒获取" })
          if (biTime == 0) {
            clearInterval(task);
            that.setData({ biSend: false, bioDisabled: false, biWord: '重新发送' })
          }
        }, 1000);
      } else {
        app.prompt(res.msg);
        that.code();
        that.setData({ biSend: false })
      }
    });
  },
  //绑定
  binding: function () {
    var that = this;
    var userPhone = that.data.userPhone;
    var biCode = that.data.biCode;
    var sessionId = that.data.sessionId;
    var tokenId = app.globalData.tokenId;
    if (userPhone == '') {
      app.prompt('请输入手机号码');
      return false;
    }
    if (biCode == '') {
      app.prompt('请输入短信验证码');
      return false;
    }
    that.setData({ biDisabled: true })
    wx.showLoading({ title: '绑定中···' })
    var data = { tokenId: tokenId, phoneCode: biCode, sessionId: sessionId }
    http.Post("weapp/users/phoneEdit", data, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        wx.showToast({
          title: res.msg,
          icon: 'success',
          complete: function (err) {
            wx.navigateBack({
              delta: 1
            })
          }
        })
      } else {
        app.prompt(res.msg);
        that.setData({ biDisabled: false })
      }
    });
  },
  //短信验证码/修改
  moObtain: function () {
    var that = this;
    var moTime = that.data.moTime;
    var moSend = that.data.moSend;
    var moVerfy = that.data.moVerfy;
    var sessionId = that.data.sessionId;
    var tokenId = app.globalData.tokenId;
    if (app.globalData.confInfo.smsVerfy == 1) {
      if (moVerfy == '') {
        app.prompt('请输入验证码');
        return false;
      }
    }
    if (moSend) return;
    that.setData({ moSend: true })
    http.Post("weapp/users/sendCodeEdit", { tokenId: tokenId, smsVerfy: moVerfy, sessionId: sessionId }, function (res) {
      if (res.status == 1) {
        app.prompt('短信已发送');
        moTime = 120;
        that.setData({ mooDisabled: true, moWord: '120秒获取' })
        var task = setInterval(function () {
          moTime--;
          that.setData({ moWord: '' + moTime + "秒获取" })
          if (moTime == 0) {
            clearInterval(task);
            that.setData({ moSend: false, mooDisabled: false, moWord: '重新发送' })
          }
        }, 1000);
      } else {
        app.prompt(res.msg);
        that.code();
        that.setData({ moSend: false })
      }
    });
  },
  //修改
  modify: function () {
    var that = this;
    var moCode = that.data.moCode;
    var sessionId = that.data.sessionId;
    var tokenId = app.globalData.tokenId;
    if (moCode == '') {
      app.prompt('请输入短信验证码');
      return false;
    }
    that.setData({ moDisabled: true })
    wx.showLoading({ title: '绑定中···' })
    var data = { tokenId: tokenId, phoneCode: moCode, sessionId: sessionId }
    http.Post("weapp/users/phoneEdito", data, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        wx.showToast({
          title: res.msg,
          icon: 'success',
          complete: function (err) {
            that.code();
            that.setData({ step: 0 })
          }
        })
      } else {
        app.prompt(res.msg);
        that.setData({ moDisabled: false })
      }
    });
  }
})