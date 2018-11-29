var http = require('../../../utils/request.js');
var rsa = require('../../common/rsa/rsa.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    num:'',
    data:'',
    hiddenmodalput: false,  
    payPwd:'',
    value:null
  },
  onShow: function () {
    this.getCapitalInfo();
  },
  getCapitalInfo:function(){
    var that = this;
    http.Post('weapp/Logmoneys/usermoneys',{tokenId:app.globalData.tokenId},function(res){
      that.setData({
        data: res.data,
        num: res.data.num
      })
    })
  },
  checkPayPwd: function () {
    var that = this;
    var payPwd = that.data.payPwd;
    var isCryptPwd = app.globalData.confInfo.isCryptPwd;
    var public_key = app.globalData.confInfo.pwdModulusKey;
    var tokenId = app.globalData.tokenId;
    if (payPwd == '') {
      app.prompt('密码不能为空');
      return false;
    }
    if (isCryptPwd == 1) {
      var exponent = "10001";
      var rsakey = new rsa.RSAKey();
      rsakey.setPublic(public_key, exponent);
      var payPwd = rsakey.encrypt(payPwd);
    }
    http.Post('weapp/Logmoneys/checkPayPwd', { tokenId: tokenId, payPwd: payPwd }, function (res) {
      if(res.status == 1){
        wx.navigateTo({
          url: './account-mng/account-mng',
        })
      }else{
        app.prompt(res.msg);
      }
    })
  },
  modalinput: function () {
    var that = this;
    var isSetPayPwd = that.data.data.isSetPayPwd;
    if (isSetPayPwd==0){
      wx.showModal({
        title: '提示',
        content: '未设置密码',
        confirmText:'去设置',
        success: function (res) {
          if (res.confirm) {
            wx.navigateTo({
              url: '../security/pay-pwd/pay-pwd'
            })
          }
        }
      })
    }else{
      that.setData({
        hiddenmodalput: true
      })
    }
  },
  //取消按钮  
  cancel: function () {
    this.setData({
      hiddenmodalput: false,
      value:null
    });
  },
  //确认  
  confirm: function () {
    this.setData({
      hiddenmodalput: false,
      value: null
    })
    this.checkPayPwd();
  },
  getInput: function(e){
     this.setData({
       payPwd: e.detail.value
     })
  },
  /*跳转到资金记录 */
  fundRecord(){
    wx.navigateTo({
      url: './fundrecord/fundrecord',
    })
  },
  dealRecord() {
    wx.navigateTo({
      url: './dealrecord/dealrecord',
    })
  },
  /*跳转到提现页面 */
  withdrawDeposit(){
    wx.navigateTo({
      url: './drawmon/drawmon',
    })
  },
  /*跳转到充值画面 */
  recharge(){
    wx.navigateTo({
      url: './recharge/recharge',
    })
  }
})