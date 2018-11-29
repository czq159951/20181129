var http = require('../../../../utils/request.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    data:[],
    payments:[],
    sign:0,
    signId:0,
    money:1,
    payCode:'',
    disabled:false,
    loading:false
  },
  /**
 * 生命周期函数--监听页面加载
 */
  onLoad: function (options) {
    this.getData();
  },
  //获取金额
  getData:function(){
    var that = this;
    var signId = that.data.signId;
    var payCode = that.data.payCode;
    var tokenId = app.globalData.tokenId;
    http.Post("weapp/logmoneys/toRecharge", { tokenId: tokenId}, function (res) {
      if (res.status == 1) {
        var payments = res.data.payments;
        if (payments!=''){
          payCode = payments[0].payCode;
          for (var p in payments) {
            if (payments[p] == 'weixinpays') payCode = payments[p].payCode;
          }
        }
        var chargeItems = res.data.chargeItems;
        if (chargeItems != '') {
          signId = chargeItems[0].id;
        }
        that.setData({
          data: chargeItems,
          payments: payments,
          signId: signId,
          payCode: payCode,
          userMoney: res.data.userMoney.userMoney
        })
      }
    });
  },
  //选择金额
  inSwitch:function(e){
    var that = this;
    var id = e.currentTarget.dataset.id;
    var sign = e.currentTarget.dataset.sign;
    var money = e.currentTarget.dataset.money;
    that.setData({
      signId: id,
      sign: sign,
      money: money
    })
  },
  money: function (e) {
    var that = this;
    that.setData({
      money: parseInt(e.detail.value)
    })
  },
  payTerm:function(e){
    var that = this;
    that.setData({
      payCode: e.detail.value
    });
  },
  //支付
  submit:function(){
    var that = this;
    var sign = that.data.sign;
    var signId = that.data.signId;
    var money = that.data.money;
    var payCode = that.data.payCode;
    var tokenId = app.globalData.tokenId;
    var payObj = 'recharge';
    var targetType = 0;
    if (sign == -1 && money<1){
      app.prompt('请填写金额');
      return false;
    }
    var data = { tokenId: tokenId, payObj: payObj, targetType: targetType, itemId: signId, needPay: money, payCode: payCode};
    that.setData({ disabled: true, loading: true });
    http.Post("weapp/weixinpays/toPay", data, function (res) {
      that.setData({ loading: false })
      if (res.status == 1) {
        var payargs = res.data;
        wx.requestPayment({
          timeStamp: payargs.timeStamp,
          nonceStr: payargs.nonceStr,
          package: payargs.package,
          signType: payargs.signType,
          paySign: payargs.paySign,
          success: function (res) {
            wx.showModal({
              title: '提示',
              content: '支付成功',
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
          },
          fail: function (res) {
            that.setData({ disabled: false })
          }
        })
      } else {
        app.prompt(res.msg);
        that.setData({ disabled: false })
      }
    });
  }
})