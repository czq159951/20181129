var http = require('../../../utils/request.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    termData:[
      { types: '',title:'全部'},
      { types: 'waitPay', title: '待付款'},
      { types: 'waitDeliver', title: '待发货'},
      { types: 'waitReceive', title: '待收货'},
      { types: 'waitAppraise', title: '待评价'},
      { types: 'finish', title: '已完成'},
      { types: 'abnormal', title: '取消拒收'},
    ],
    types:'',
    goodsLogo: null,
    domain: app.globalData.domain,
    orders: [],
    page: 0,
    currentId:0,
    cancelId:0,
    cancelIndex: [],
    cancelData: [],
    cancelFrame:false,
    cancelWords: '请选择您取消订单的原因',
    rejectId: 0,
    rejectIndex: [],
    rejectData: [],
    rejectFrame: false,
    rejectWords: '请选择您拒收订单的原因',
    rejectContent:'',
    refundId: 0,
    refundIndex: [],
    refundData: [],
    refundFrame: false,
    refundWords: '请选择您申请退款的原因',
    refundContent: '',
    realTotalMoney: 0,
    useScore: 0,
    scoreMoney: 0,
    isScroll:true
  }, 
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var types = options.types;
    if (types){
      this.setData({
        types: types
      });
    }
    this.setData({
      goodsLogo: app.globalData.confInfo.goodsLogo
    });
  },
  /**
  * 生命周期函数--监听小程序显示
  */
  onShow: function () {
    var that = this;
    that.setData({
      orders: [],
      cancelIndex: [],
      cancelData: [],
      rejectIndex: [],
      rejectData: [],
      refundIndex: [],
      refundData: [],
      page: 0
    });
    that.getList();
  },
  selected(e){
    var that = this;
    var types = e.currentTarget.dataset.types;
    that.setData({
      types: types,
      orders: [],
      page: 0
    });
    that.getList();
  },
  //列表
  getList: function () {
    var that = this;
    var page = that.data.page;
    var types = that.data.types;
    var tokenId = app.globalData.tokenId;
    if(page == 0)wx.showLoading({ title: '加载中' });
    page = page + 1;
    http.Post("weapp/orders/getOrderList", { tokenId: tokenId, types: types, page: page }, function (res) {
      if (res.status == 1) {
        var orders = that.data.orders;
        orders = orders.concat(res.data.data);
        that.setData({
          orders: orders,
          cancelIndex: res.data.cancelIndex,
          cancelData: res.data.cancelData,
          rejectIndex: res.data.rejectIndex,
          rejectData: res.data.rejectData,
          refundIndex: res.data.refundIndex,
          refundData: res.data.refundData,
          page: page
        })
      }
      wx.hideLoading();
    });
  },
  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
    this.getList();
  },
  //店铺
  toshops: function(e) {
    var shopId = e.currentTarget.dataset.shopid;
    if (shopId==1){
      wx.navigateTo({
        url: '../../shop-self/shop-self'
      })
    }else{
      wx.navigateTo({
        url: '../../shop-home/shop-home?shopId=' + shopId
      })
    }
  },
  //详情
  todetail: function (e) {
    var orderId = e.currentTarget.dataset.orderid;
    wx.navigateTo({
      url: './orders-detail/orders-detail?orderId=' + orderId + '&types=1'
    })
  },
  //评价
  toevaluate: function (e) {
    var orderId = e.currentTarget.dataset.orderid;
    wx.navigateTo({
      url: './orders-appraises/orders-appraises?orderId=' + orderId
    })
  },
  //提醒发货
  remind: function (e) {
    var that = this;
    var orderId = e.currentTarget.dataset.orderid;
    var tokenId = app.globalData.tokenId;
    wx.showModal({
      title: '提示',
      content: '您确定要提醒发货吗?',
      success: function (res) {
        if (res.confirm) {
          http.Post("weapp/orders/noticeDeliver", { tokenId: tokenId, id: orderId }, function (res) {
            if (res.status == 1) {
              that.setData({
                orders: [],
                page: 0
              });
              that.getList();
            }else{
              app.prompt(res.msg);
            }
          });
        }
      }
    })
  },
  //确认收货
  confirm: function (e) {
    var that = this;
    var orderId = e.currentTarget.dataset.orderid;
    var tokenId = app.globalData.tokenId;
    wx.showModal({
      title: '提示',
      content: '你确定已收货吗?',
      success: function (res) {
        if (res.confirm) {
          http.Post("weapp/orders/receive", { tokenId: tokenId, id: orderId }, function (res) {
            if (res.status == 1) {
              that.setData({
                orders: [],
                page: 0
              });
              that.getList();
            } else {
              app.prompt(res.msg);
            }
          });
        }
      }
    })
  },
  //取消订单
  cancel: function (e) {
    var that = this;
    var orderId = e.currentTarget.dataset.orderid;
    that.setData({
      currentId: orderId,
      cancelFrame: true,
      cancelId: 0,
      cancelWords: '请选择您取消订单的原因'
    });
  },
  toCancel: function () {
    var that = this;
    var id = that.data.cancelId;
    var orderId = that.data.currentId;
    var tokenId = app.globalData.tokenId;
    if (id==0){
      app.prompt('请选择原因');
      return false
    }
    http.Post("weapp/orders/cancellation", { tokenId: tokenId, id: orderId, reason:id}, function (res) {
      if (res.status == 1) {
        that.setData({
          orders: [],
          page: 0
        });
        that.hide();
        that.getList();
      } else {
        app.prompt(res.msg);
      }
    });
  },
  cancelMenu: function (e) {
    var that = this;
    var cancelData = that.data.cancelData;
    var cancelIndex = that.data.cancelIndex;
    var index = e.detail.value;
    that.setData({
      cancelId: cancelIndex[index],
      cancelWords: cancelData[index]
    });
  },
  //拒收
  reject: function (e) {
    var that = this;
    var orderId = e.currentTarget.dataset.orderid;
    that.setData({
      currentId: orderId,
      rejectFrame: true,
      rejectId: 0,
      rejectWords: '请选择您拒收订单的原因'
    });
  },
  toReject: function () {
    var that = this;
    var id = that.data.rejectId;
    var orderId = that.data.currentId;
    var content = that.data.rejectContent;
    var tokenId = app.globalData.tokenId;
    if (id == 0) {
      app.prompt('请选择原因');
      return false
    }
    if (id == 10000 && content=='') {
      app.prompt('请填写原因');
      return false
    }
    http.Post("weapp/orders/reject", { tokenId: tokenId, id: orderId, reason: id, content: content}, function (res) {
      if (res.status == 1) {
        that.setData({
          orders: [],
          page: 0
        });
        that.hide();
        that.getList();
      } else {
        app.prompt(res.msg);
      }
    });
  },
  rejectMenu: function (e) {
    var that = this;
    var rejectData = that.data.rejectData;
    var rejectIndex = that.data.rejectIndex;
    var index = e.detail.value;
    that.setData({
      rejectId: rejectIndex[index],
      rejectWords: rejectData[index]
    });
  },
  rejectText: function (e) {
    var that = this;
    that.setData({
      rejectContent: e.detail.value,
    });
  },
  //申请退款
  refund: function (e) {
    var that = this;
    var orderId = e.currentTarget.dataset.orderid;
    var tokenId = app.globalData.tokenId;
    http.Post("weapp/orders/getRefund", { tokenId: tokenId, id: orderId }, function (res) {
      if (res.status == 1) {
        that.setData({
          realTotalMoney: res.data.realTotalMoney,
          useScore: res.data.useScore,
          scoreMoney: res.data.scoreMoney,
          currentId: orderId,
          refundFrame: true,
          refundId: 0,
          refundWords: '请选择您申请退款的原因',
          isScroll:false
        });
      }
    });
  },
  toRefund: function () {
    var that = this;
    var id = that.data.refundId;
    var orderId = that.data.currentId;
    var content = that.data.refundContent;
    var money = that.data.refundQuota;
    var tokenId = app.globalData.tokenId;
    if (id == 0) {
      app.prompt('请选择原因');
      return false
    }
    if (id == 10000 && content == '') {
      app.prompt('请填写原因');
      return false
    }
    if (money<0 || money == '') {
      app.prompt('无效的退款金额');
      return false
    }
    http.Post("weapp/orderrefunds/refund", { tokenId: tokenId, id: orderId, reason: id, content: content, money: money}, function (res) {
      if (res.status == 1) {
        that.setData({
          orders: [],
          page: 0,
          isScroll:true
        });
        that.hide();
        that.getList();
      } else {
        app.prompt(res.msg);
      }
    });
  },
  refundMenu: function (e) {
    var that = this;
    var refundData = that.data.refundData;
    var refundIndex = that.data.refundIndex;
    var index = e.detail.value;
    that.setData({
      refundId: refundIndex[index],
      refundWords: refundData[index]
    });
  },
  refundText: function (e) {
    var that = this;
    that.setData({
      refundContent: e.detail.value,
    });
  },
  refundQuota: function (e) {
    var that = this;
    that.setData({
      refundQuota: e.detail.value,
    });
  },
  hide: function () {
    var that = this;
    that.setData({
      currentId: 0,
      cancelFrame: false,
      rejectFrame: false,
      refundFrame: false,
      isScroll:true
    });
  },
  //投诉
  complaint: function (e) {
    var orderid = e.currentTarget.dataset.orderid;
    wx.navigateTo({
      url: './orders-complain/orders-complain?orderId=' + orderid,
    })
  },
  //支付
  payment: function (e){
    var orderNo = e.currentTarget.dataset.orderno;
    wx.navigateTo({
      url: '../payment/payment?orderNo=' + orderNo + '&isBatch=0'
    })
  },
  //首页
  toIndex: function () {
    wx.switchTab({
      url: '../../index/index'
    })
  }
})