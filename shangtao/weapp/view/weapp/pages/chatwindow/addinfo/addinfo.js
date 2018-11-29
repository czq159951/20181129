// pages/chatWindow/addInfo/addInfo.js
var app = getApp();
var http = require('../../../utils/request.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    typeId: 1,
    selectType: false,
    selectStyle: false,
    typeArray: [],
    content: '',
    index: '咨询类型'
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (e) {
    var goodsId = e.goodsId;
    this.setData({
      goodsId: goodsId
    })
    this.getType();
  },
  getType: function () {
    var that = this;
    var typeArray = this.data.typeArray;
    http.Post('weapp/Goodsconsult/getConsultType', {}, function (res) {
      if (res.status == 1) {
        for (var i in res.data) {
          typeArray.push(res.data[i]['dataName'])
        }
        that.setData({
          typeArray: typeArray,
          typeObj: res.data,
          index: typeArray[0]
        })
      }
    })
  },
  addInfo: function () {
    var that = this;
    var goodsId = that.data.goodsId;
    var typeId = that.data.typeId;
    var content = that.data.content;

    http.Post('weapp/Goodsconsult/add', { goodsId: goodsId, consultType: typeId, consultContent: content }, function (res) {
      if (res.status == 1) {
        wx.showToast({
          title: '添加成功！正在跳转.....',
          icon: 'loading'
        })
        setTimeout(function () {
          wx.redirectTo({
            url: '../chatwindow?goodsId=' + goodsId,
          })

        }, 500);
      } else {
        wx.showToast({
          title: res.msg,
          icon: 'none',
          duration: 2000
        })
      }

    })

  },
  getContent: function (e) {
    var content = e.detail.value;
    this.setData({
      content: content
    })
  },
  submit: function () {
    this.addInfo();
  },
  //点击选择类型
  bindPickerChange: function (e) {
    var typeObj = this.data.typeObj;
    var typeArray = this.data.typeArray;
    var index, typeId;
    for (var i in typeObj) {
      if (typeArray[e.detail.value] == typeObj[i]['dataName']) {
        index = typeObj[i]['dataName'];
        typeId = typeObj[i]['dataVal'];
      }
    }
    this.setData({
      index: index,
      typeId: typeId
    })
  }
})