var app = getApp();
var http = require('../../../../utils/request.js');
var parse = require('../../../common/parse/parse.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
     content:[],
     msgId:'',
     tokenId:'',
     domain: app.globalData.domain
  },

  onLoad: function (e) {
    var msgId = e.msgId;
    this.setData({
      msgId: msgId
    })
   this.getManagerList();
  },

  getManagerList: function () {
    var that = this;
    var tokenId = app.globalData.tokenId;
    var msgId = this.data.msgId;
    var managerInfo = this.data.managerInfo;

    http.Post('weapp/messages/getById', { msgId: msgId, tokenId: tokenId }, function (res) {
      managerInfo = res.data;
      parse.wxParse('managerInfo', 'html', managerInfo.msgContent, that);
      that.setData({
        createTime: managerInfo.createTime
      })
    })
  }
})