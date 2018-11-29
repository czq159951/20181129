var http = require('../../utils/request.js');
var reward = require('../../addons/closure/reward/reward.js');
var reward = require('../../addons/closure/coupon/coupon.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    data:[],
    addressId:0,
    areaId2: 0,
    price:[],
    goodsLogo: null,
    domain: app.globalData.domain,
    shopRemarks:[],
    payment:[],
    payCodes:[],
    payCode:'',
    paymentWord: '',
    isOnlines:[],
    payType:0,
    gives: ['快递运输','自提'],
    givesWord:'快递运输',
    deliverType:0,
    invoice: false,
    isInvoice: 0,
    invoiceClient: '个人',
    invoiceId: 0,
    isInvoice2: 0,
    invoiceClient2: '个人',
    invoiceId2: 0,
    invoiceCompany:false,
    invoiceHead:'',
    invoiceCode:'',
    invoicelist:[],
    invoiceWord: '不开发票',
    isinvoicelist:false,
    integral:['是','否'],
    integralWord: '否',
    isOpenScorePay:0,
    isUseScore:0,
    disabled:false,
    promotion:[],
    addons: [],
    coupons:[],
    couponIds:[],
    tag:true,
    defaults:'填写订单备注'
  },
  /**
  * 生命周期函数--监听小程序显示
  */
  onShow: function () {
    this.getData();
    this.setData({
      goodsLogo: app.globalData.confInfo.goodsLogo,
      addons: app.globalData.confInfo.addons
    })
  },
  //数据
  getData:function(){
    var that = this;
    var addressId = that.data.addressId;
    var paymentWord = app.globalData.paymentWord;
    var payCode = app.globalData.payCode;
    var payType = app.globalData.payType;
    var tokenId = app.globalData.tokenId;
    http.Post("weapp/carts/settlement", { tokenId: tokenId, addressId: addressId}, function (res) {
      if (res.status == 1) {
        var payment = res.data.payNames;
        var payCodes = res.data.payCodes;
        var isOnlines = res.data.isOnlines;
        if (payCodes!=''){
          paymentWord = payment[0];
          payCode = payCodes[0];
          payType = isOnlines[0];
          for (var p in payCodes) {
            if (payCodes[p] == 'weixinpays'){
              paymentWord = payment[p];
              payCode = payCodes[p];
              payType = isOnlines[p];
            }
          }
        }
        that.setData({
          data: res.data,
          addressId: res.data.userAddress.addressId,
          areaId2: res.data.userAddress.areaId2,
          payment: payment,
          payCodes: payCodes,
          payCode: payCode,
          isOnlines: isOnlines,
          payType: payType,
          paymentWord: paymentWord,
          isOpenScorePay: res.data.isOpenScorePay,
          promotion: res.data.promotion,
          coupons: res.data.coupons
        })
        that.getTotal();
      }
    });
  },
  //计算运费/价格
  getTotal:function(){
    var that = this;
    var data = that.data.data;
    var areaId2 = that.data.areaId2;
    var deliverType = that.data.deliverType;
    var isUseScore = that.data.isUseScore;
    var tokenId = app.globalData.tokenId;
    var coupons = that.data.coupons;
    var couponIds = [];
    for (var li in coupons) {
      couponIds.push(li + ':' + coupons[li]['couponId']);
    }
    couponIds = couponIds.join(',');
    http.Post("weapp/carts/getCartMoney", { tokenId: tokenId, areaId2: areaId2, deliverType: deliverType, isUseScore: isUseScore, useScore: data.userOrderScore, couponIds: couponIds}, function (res) {
      if (res.status == 1){
        that.setData({
          price: res.data,
        })
      }
    });
  },
  //店铺
  toshops: function (e) {
    var shopId = e.currentTarget.dataset.shopid;
    if (shopId == 1) {
      wx.navigateTo({
        url: '../shop-self/shop-self'
      })
    } else {
      wx.navigateTo({
        url: '../shop-home/shop-home?shopId=' + shopId
      })
    }
  },
  //商品
  togoods: function (e) {
    var goodsId = e.currentTarget.dataset.goodsid;
    wx.navigateTo({
      url: '../goods-detail/goods-detail?goodsId=' + goodsId
    })
  },
  /*跳转到地址管理 */
  address:function (){
    wx.navigateTo({
      url: '../users/address-mng/address-mng?type=1'
    })
  },
  //备注
  inRemarks:function(e){
    var that = this;
    var value = e.detail.value;
    var shopid = e.currentTarget.dataset.shopid;
    var shopRemarks = that.data.shopRemarks;
    shopRemarks[shopid] = value;
    that.setData({
      shopRemarks: shopRemarks
    })
  },
  //选择
  onChoice: function (e) {
    var that = this;
    var mode = e.currentTarget.dataset.mode; 
    var payment = that.data.payment;
    var payCodes = that.data.payCodes;
    var payCode = that.data.payCode;
    var isOnlines = that.data.isOnlines;
    var payType = that.data.payType;
    var gives = that.data.gives;
    var integral = that.data.integral;
    if (mode =='payment'){
      var itemList = payment;
    } else if (mode == 'gives'){
      var itemList =  gives;
    } else if (mode == 'integral'){
      var itemList = integral;
    }
    wx.showActionSheet({
      itemList: itemList,
      success: function (e) {
        if (e.tapIndex > 0 || e.tapIndex == 0){
          if (mode == 'payment') {
            that.setData({
              paymentWord: payment[e.tapIndex],
              payCode: payCodes[e.tapIndex],
              payType: isOnlines[e.tapIndex]
            })
          } else if (mode == 'gives') {
            that.setData({
              givesWord: gives[e.tapIndex],
              deliverType: e.tapIndex
            })
          } else if (mode == 'integral') {
            that.setData({
              integralWord: integral[e.tapIndex],
              isUseScore: (e.tapIndex == 1) ? 0 : 1
            })
          }
          that.getTotal();
        }
      }
    })
  },
  //发票
  inInvoice:function(e){
    var that = this;
    var invoice = e.currentTarget.dataset.invoice;
    if (invoice==1){
      that.setData({
        invoice: true,
      })
    }else{
      that.setData({
        invoice: false,
      })
    }
  },
  ifInvoice: function (e) {
    var that = this;
    var value = e.detail.value;
    if (value==0){
      that.setData({
        invoiceClient2:'个人',
        invoiceCompany:false
      });
    }else{
      that.setData({
        invoiceClient2: '单位',
        invoiceCompany:true
      });
    } 
  },
  invoiceHead: function (e) {
    var that = this;
    that.setData({
      invoiceHead: e.detail.value,
    });
  },
  invoiceHead2:function(e){
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post("weapp/invoices/pageQuery", { tokenId: tokenId}, function (res) {
      if (res.status == 1) {
        that.setData({
          invoicelist: res.data,
          isinvoicelist:true
        });
      }
    });
  },
  invoiceHead3: function (e) {
    var that = this;
    var id = e.currentTarget.dataset.id;
    var invoiceHead = e.currentTarget.dataset.head;
    var invoiceCode = e.currentTarget.dataset.code;
    that.setData({
      invoiceId2:id,
      invoiceHead: invoiceHead,
      invoiceCode: invoiceCode,
      isinvoicelist: false
    });
  },
  invoiceHead4: function(){
    var that = this;
    that.setData({
      isinvoicelist: false
    });
  },
  invoiceCode: function (e) {
    var that = this;
    that.setData({
      invoiceCode: e.detail.value,
    });
  },
  ifInvoice2: function (e) {
    var that = this;
    that.setData({
      isInvoice2: e.detail.value,
    });
  },
  invoice:function(){
    var that = this;
    var isInvoice2 = that.data.isInvoice2;
    var invoiceClient2 = that.data.invoiceClient2;
    var invoiceId2 = that.data.invoiceId2;
    var invoiceHead = that.data.invoiceHead;
    var invoiceCode = that.data.invoiceCode;
    var tokenId = app.globalData.tokenId;
    if (isInvoice2==1){
      if (invoiceClient2 != '个人') {
        if (invoiceHead == '') {
          app.prompt('请输入发票抬头');
          return false;
        }
        if (invoiceId2 > 0) {
          var url = 'weapp/invoices/edit';
          var data = { tokenId: tokenId, invoiceHead: invoiceHead, invoiceCode: invoiceCode, id: invoiceId2 };
        } else {
          var url = 'weapp/invoices/add';
          var data = { tokenId: tokenId, invoiceHead: invoiceHead, invoiceCode: invoiceCode };
        }
        http.Post(url, data, function (res) {
          if (res.status == 1) {
            if (invoiceId2 > 0) {
              that.setData({ invoiceId: invoiceId2 });
            } else {
              that.setData({ invoiceId2: res.data.id, invoiceId: res.data.id, });
            }
            that.setData({
              invoiceWord: '普通发票（纸质） ' + invoiceHead + ' 明细',
              invoiceClient: invoiceHead,
              isInvoice: isInvoice2
            });
          } else {
            app.prompt(res.msg);
          }
        });
      } else {
        that.setData({
          invoiceWord: '普通发票（纸质） 个人 明细',
          invoiceId: 1,
          invoiceClient: invoiceClient2,
          isInvoice: isInvoice2
        });
      }
    }
    that.setData({invoice: false});
  },
  //结算
  submit:function(){
    var that = this;
    var data = that.data.data;
    var addressId = that.data.addressId;
    var shopRemarks = that.data.shopRemarks;
    var payType = that.data.payType;
    var payCode = that.data.payCode;
    var deliverType = that.data.deliverType;
    var price = that.data.price;
    var isUseScore = that.data.isUseScore;
    var isInvoice = that.data.isInvoice;
    var invoiceId = that.data.invoiceId;
    var invoiceClient = that.data.invoiceClient;
    var tokenId = app.globalData.tokenId;
    var addons = that.data.addons;
    var coupons = that.data.coupons;
    var defaults = that.data.defaults;
    if (defaults == '填写订单备注'){
      this.setData({
        defaults:''
      })
    }
    if (deliverType == 0 && addressId == 0) {
      app.prompt('请选择收货地址');
      return false;
    }
    that.setData({ disabled: true })
    wx.showLoading({ title: '提交中···' });
    var datas = new Array();
    datas['tokenId'] = tokenId;
    datas['s_addressId'] = addressId;
    datas['payType'] = payType;
    datas['payCode'] = payCode;
    datas['deliverType'] = deliverType;
    datas['isUseScore'] = isUseScore;
    datas['useScore'] = price.maxScore;
    datas['isInvoice'] = isInvoice;
    datas['invoiceId'] = invoiceId;
    datas['invoiceClient'] = invoiceClient;
    for (var c in data.carts) {
      if (c != '') {
        if (shopRemarks[c]){
          datas['remark_' + c] = shopRemarks[c];
        }else{
          datas['remark_' + c] = '';
        }
        if (addons.Coupon == 1) {
          datas['couponId_' + c] = coupons[c]['couponId'];
        }
      }
    }
    http.Post("weapp/orders/submit", datas, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        if (payType == 1 && price.realTotalMoney>0){
          wx.showToast({
            title: res.msg,
            icon: 'success',
            complete: function (err) {
              if (payCode == 'weixinpays' || payCode == ''){
                wx.redirectTo({
                  url: '../users/payment/weixinpays/weixinpays?orderNo=' + res.data + '&isBatch=1'
                })
              } else if (payCode == 'wallets'){
                wx.redirectTo({
                  url: '../users/payment/wallets/wallets?orderNo=' + res.data + '&isBatch=1'
                })
              }
            }
          })
        }else{
          wx.showToast({
            title: res.msg,
            icon: 'success',
            complete: function (err) {
              wx.redirectTo({
                url: '../users/orders/orders',
              })
            }
          })
        }
      } else {
        app.prompt(res.msg);
        that.setData({ disabled: false })
      }
    });
  },
  //插件
  onCoupon: function (e) {
    var that = this;
    var statu = e.currentTarget.dataset.statu;
    var shopId = e.currentTarget.dataset.shopid;
    var coupons = that.data.coupons;
    that.couponPopup(statu)
    that.setData({
      couponsInfo: coupons[shopId],
      couponsshopId: shopId,
      tag:false
    })
  },
  couponPopup: function (statu) {
    /* 动画部分 */
    var animation = wx.createAnimation({
      duration: 300,  //动画时长  
      timingFunction: "linear", //线性  
      delay: 0  //0则不延迟  
    });
    animation.translateY(600).step();
    this.setData({
      couponData: animation.export()
    })
    setTimeout(function () {
      animation.translateY(0).step()
      this.setData({
        couponData: animation.export()
      })
      //关闭抽屉  
      if (statu == "close") {
        this.setData({
          couponStatus: false,
          tag:true
        });
      }
    }.bind(this), 200)
    // 显示抽屉  
    if (statu == "open") {
      this.setData({
        couponStatus: true,
      });
    }
  },
  ifCoupon:function(e){
    var that = this;
    var couponId = e.detail.value;
    var coupons = that.data.coupons;
    var shopId = that.data.couponsshopId;
    coupons[shopId]['couponId'] = couponId;
    if (couponId==0){
      coupons[shopId]['words'] = '不使用优惠';
    }else{
      for (var co in coupons[shopId]['list']){
        if (coupons[shopId]['list'][co]['couponId'] == couponId){
          coupons[shopId]['words'] = coupons[shopId]['list'][co]['couponValue'] + '元优惠券';
        }
      }
    }
    that.setData({
      coupons: coupons,
      couponsInfo: coupons[shopId],
      tag:true
    });
    that.couponPopup('close');
    that.getTotal();
  },
  focus:function(){
    this.setData({
      defaults:''
    })
  },
  loseFocus:function(){
    wx.setTopBarText({
      text: 'hello, world!'
    })
    if (this.data.shopRemarks == ''){
      this.setData({
        defaults: '填写订单备注'
      })
    }
  }
})