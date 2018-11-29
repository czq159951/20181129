var app = getApp();
var http = require('../../../../utils/request.js');
var rsa = require('../../../common/rsa/rsa.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    banks: [ '请选择账号'],
    firstContent:'',
    accId:0,
    payPwd:'',
    money: 0,
    putMoney: 0


  },
  onLoad: function () {
     this.getAccount();
  },
  getAccount:function(){
    var that = this;
    var banks = this.data.banks;
    http.Post('weapp/cashconfigs/pageQuery',{tokenId:app.globalData.tokenId},function(res){
      if(res.status == 1){
        for (var i in res.data.list) {
          banks.push(res.data.list[i]['accUser'] + '|' + res.data.list[i]['accNo'])
        }
        that.setData({
          banks: banks,
          typeObj: res.data.list,
          drawCashLimit: res.data.drawCashLimit,
          putMoney: res.data.putMoney
        })
      }
    })
  },
  saveData: function (data) {
    var that = this;
    http.Post('weapp/cashdraws/drawMoney',data, function (res) {
      if (res.status == 1) {
        wx.showToast({
          title: res.msg,
          success:function(){
            wx.showLoading({
              title: '跳转中...',
            })
            setTimeout(function(){
              wx.navigateBack({})
            },500)
          }
        })
      }else{
       app.prompt(res.msg)
      }
    })
  },
  /*获取金额 */
  money(e) {
    var money = e.detail.value;
    
    this.setData({
      money: money
    })
  },
  /*获取支付密码 */
  payPwd(e) {
    var that = this;
    var payPwd = e.detail.value;
    that.setData({
      payPwd: payPwd
    })
  },
  infoSave: function () {
    var that = this;
    var data = that.data.data;
    var accId = that.data.accId;
    var money = that.data.money;
    var payPwd = that.data.payPwd;
    var isCryptPwd = app.globalData.confInfo.isCryptPwd;
    var public_key = app.globalData.confInfo.pwdModulusKey;
    var tokenId = app.globalData.tokenId;
    var drawCashLimit = parseInt(that.data.drawCashLimit);
    if (accId == 0) {
      app.prompt('请选择提现账号')
    } else if (money < drawCashLimit){
      app.prompt('提现金额不能小于' + drawCashLimit)
    } else if (payPwd == ''){
      app.prompt('请输入支付密码')
    }else{
      if (isCryptPwd == 1) {
        var exponent = "10001";
        var rsakey = new rsa.RSAKey();
        rsakey.setPublic(public_key, exponent);
        var payPwd = rsakey.encrypt(payPwd);
      }
      data = { payPwd: payPwd, money: money, accId: accId, tokenId: tokenId}
      that.saveData(data);
    }
  },
  //点击选择类型
  bindPickerChange: function (e) {
    var typeObj = this.data.typeObj;
    var banks = this.data.banks;
    var index, accId;
    if (e.detail.value == 0){
      index = '';
      accId = '';
    } else {
      for (var i in typeObj) {
        if (banks[e.detail.value] == typeObj[i]['accUser'] + '|' + typeObj[i]['accNo']) {
          index = typeObj[i]['accUser'] + '|' + typeObj[i]['accNo'];
          accId = typeObj[i]['id'];
        }
      }
    }
    this.setData({
      firstContent: index,
      accId: accId
    })
  }
})