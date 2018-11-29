var http = require('../../utils/request.js');
var parse = require('../common/parse/parse.js');
var reward = require('../../addons/closure/reward/reward.js');
var coupon = require('../../addons/closure/coupon/coupon.js');
//获取应用实例
const app = getApp()
Page({
  /**
   * 页面的初始数据
   */
  data: {
    goodsId:0,
    goodsType:0,
    goodsLogo: null,
    shopLogo: null,
    domain: app.globalData.domain,
    data: [],
    goodsDesc: '',
    evaluate: [],
    currPage: 0,
    frontPage: 0,
    confInfo: null,
    moreStatus: false,
    moreGoods:1,
    parameterData: false,
    parameterStatus: false,
    animationData: false,
    showModalStatus: false,
    backStatus: true,
    storeStatus: true,
    detailsStatus:false,
    commentStatus: false,
    num:1,
    cartsType: 0,
    goodsPrice:0,
    marketPrice:0,
    goodsStock:0,
    minStock:1,
    maxStock:0,
    arraySpec:[],
    goodsSpecId:0,
    windowStatus:true,
    favGood:0,
    addons: [],
    rewardData: [],
    coupon:[],
    descImage:'',
    type:'',
    pagesize:10
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      goodsId: options.goodsId,
      confInfo: app.globalData.confInfo,
      goodsLogo: app.globalData.confInfo.goodsLogo,
      shopLogo: app.globalData.confInfo.shopLogo,
      addons: app.globalData.confInfo.addons
    })
    this.getData(options.goodsId);
  },
  //商品信息
  getData: function (id) {
    var that = this;
    var goodsId = that.data.goodsId;
    var tokenId = app.globalData.tokenId;
    var addons = that.data.addons;
    wx.showLoading({ title: '加载中' });
    http.Post("weapp/goods/index", { tokenId:tokenId,goodsId: id}, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        that.record(res.data);
        that.title(res.data.goodsName);
        that.setData({
          data: res.data,
          goodsType: res.data.goodsType,
          favGood: res.data.favGood,
          goodsId: res.data.goodsId,
          descImage: res.data.goodsImg,
          maxStock: res.data.goodsStock
        })
        //插件
        if (addons.Reward==1){
          reward.rewardGoods({ goodsId: goodsId, shopId: res.data.shopId }, function (res) {
            if (res) {
              that.setData({ rewardData: res.rewardData });
            }
          });
        }
        if (addons.Coupon == 1) {
          coupon.couponGoods({ tokenId: tokenId, goodsId: goodsId }, function (res) {
            if (res) {
              that.setData({ coupon: res.couponGoods });
            }
          });
        }
      }
    });
  },
  //标题
  title: function (e) {
    wx.setNavigationBarTitle({
      title: e
    })
  },
  //记录浏览
  record:function (e){
    var history = [];
    var data = [];
    wx.getStorage({
      key: 'history',
      success: function (cache) {
        history = cache.data
        var goodsInfo = {
          goodsId: e.goodsId,
          goodsName: e.goodsName,
          goodsImg: e.goodsImg,
          saleNum: e.saleNum,
          shopPrice: e.shopPrice
        }
        if (history.length > 0) { // 判断是否为空
          for (var i = 0; i < history.length; i++) {
            if (history[i].goodsId == e.goodsId) {
              history.splice(i, 1);
            }
          }
        }
        history.splice(0, 0, goodsInfo);
        wx.setStorage({
          key: 'history',
          data: history
        })
      }
    })
  },
  //商品详情
  getDetail: function (id) {
    var that = this;
    http.Post("weapp/goods/goodsDetail", { goodsId: id }, function (res) {
      if (res.status == 1) {
        var goodsDesc = res.data.goodsDesc;
        if (goodsDesc) {
          parse.wxParse('goodsDesc', 'html', goodsDesc, that, 5);
        }
      }
    });
  },
  /*关注 */
  attStatus: function (data, url) {
    var that = this;
    var goodsId = this.data.goodsId;
    http.Post('weapp/Favorites/' + url, data, function (res) {
      if (res.status == 1) {
        wx.showToast({
          title: res.msg,
          success: function () {
            if (data.favType==1){
              that.setData({
                favGood: 0
              })
            }else{
              that.setData({
                favGood: res.data.fId
              })
            }
          }
        })
      } else {
        wx.showModal({
          title: '提示',
          content: res.msg,
        })
      }
    })
  },
  selectStatus: function (e) {
    var favGood = this.data.favGood;
    var data = this.data.data;

    if (favGood > 0) {
      data = {
        type:0,
        favType: 1,
        tokenId: app.globalData.tokenId,
        id: favGood
      }
      this.attStatus(data, 'cancel')
    } else{
      data = {
        type: 0,
        favType: 0,
        tokenId: app.globalData.tokenId,
        id: this.data.goodsId
      }
      this.attStatus(data, 'add')
    }
  },
  //评价列表
  getEvaluate: function (id, page) {
    var that = this;
    var frontPage = that.data.frontPage;
    var type = that.data.type;
    var pagesize = that.data.pagesize;
    if (frontPage != page || page==0){
      that.setData({ frontPage: page, })
      var page = page + 1;
      http.Post("weapp/goodsappraises/getById", { pagesize:pagesize,goodsId: id, page: page, type: type }, function (res) {
        if (res.status == 1) {
          var list = that.data.evaluate;
          var appraises = res.data;
          var data = res.data.data;
          if (data.length > 0) {
            list = list.concat(data);
            for(var i in list){
              list[i]['userPhoto'] = app.userPhoto(list[i]['userPhoto']);
            }
            that.setData({
              page: page,
              evaluate: list,
              appraises: appraises,
              totalPage: res.data.last_page
            })
          }
        }
      });
    }
  },
  loadEvaluate: function (e) {
    var that = this;
    this.getEvaluate(that.data.goodsId,that.data.currPage);
  },
  //处理数据
  handleData: function (catid, itemid,itemimg) {
    var that = this;
    var data = that.data.data;
    var arraySpec = that.data.arraySpec;
    if (itemimg){
          this.setData({
            descImage: itemimg
          })
    }
    //规格
    if (data.isSpec == 1) {
      if (arraySpec!=''){
        arraySpec[catid] = itemid;
      }else{
        //默认规格
        var defaultSpec = data.defaultSpec;
        for (var i in data.spec) {
          arraySpec[i] = defaultSpec[i];
        }
      }
      this.setData({
        arraySpec: arraySpec
      });
    }
    //价格/库存
    if (data.isSpec==0){
      var goodsPrice = data.shopPrice;
      var marketPrice = data.marketPrice;
      var goodsStock = data.goodsStock;
    }else{
      var saleSpec = data.saleSpec
      var list = arraySpec;
      var list2 = [];
      list.sort(function (a, b) { return a - b; });
      for (var l = 0; l < list.length; l++) {
        if (list[l]){
          list2 = list2.concat(list[l]);
        }
      }
      list2 = list2.join(':')
      if (saleSpec[list2]){
        var goodsPrice = saleSpec[list2].specPrice;
        var marketPrice = saleSpec[list2].marketPrice;
        var goodsStock = saleSpec[list2].specStock;
        var goodsSpecId = saleSpec[list2].id;
      }
    }
    this.setData({
      goodsPrice: goodsPrice,
      marketPrice: marketPrice,
      goodsStock: goodsStock,
      maxStock: goodsStock,
      goodsSpecId: goodsSpecId
    });
  },
  //菜单
  inMore: function (e) {
    var statu = e.currentTarget.dataset.statu;
    if (statu== 1){
      this.setData({ moreStatus: true });
    }else{
      this.setData({ moreStatus: false });
    }
  },
  //首页
  toIndex: function (e) {
    wx.switchTab({
      url: '../index/index'
    });
  },
  //分类
  toClassify: function (e) {
    wx.switchTab({
      url: '../classify/classify'
    });
  },
  //购物车
  toCart: function (e) {
    wx.switchTab({
      url: '../carts/carts'
    });
  },
  //关注
  toAttension: function (e) {
    wx.switchTab({
      url: '../users/attension/attension'
    });
  },
  //个人中心
  toUser: function (e) {
    wx.switchTab({
      url: '../users/users'
    });
  },
  //切换规格
  switchSpec: function (e) {
    var catid = e.currentTarget.dataset.catid;
    var itemid = e.currentTarget.dataset.itemid;
    var itemimg = e.currentTarget.dataset.itemimg;
    this.handleData(catid, itemid, itemimg)
  },
  //导航
  layoutCut:function (e) {
    var that = this;
    let id = e.currentTarget.id;
    let storeStatus =this.data.storeStatus;
    let detailsStatus = this.data.detailsStatus;
    let commentStatus = this.data.commentStatus;
    if(id == 1 && storeStatus == false){
      storeStatus = !storeStatus;
      this.setData({
        storeStatus: storeStatus,
        detailsStatus: false,
        commentStatus: false
      });
    } else if (id == 2 && detailsStatus == false){
      detailsStatus = !detailsStatus;
      this.setData({
        storeStatus: false,
        detailsStatus: detailsStatus,
        commentStatus: false
      });
      if (!that.data.goodsDesc){
        this.getDetail(that.data.goodsId);
      }
    } else if (id == 3 && commentStatus == false){
      commentStatus = !commentStatus;
      this.setData({
        storeStatus: false,
        detailsStatus: false,
        commentStatus: commentStatus
      });
      this.getEvaluate(that.data.goodsId,0);
    };
  },
  /*跳转到评论模块 */
  backTrackComent: function (e){
    this.setData({
      moreGoods:0,
      storeStatus: false,
      detailsStatus: false,
      commentStatus: true
    });
    this.getEvaluate(this.data.goodsId, 0);
  },
  /*跳转到店铺 */
  store: function (e){
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
  /*参数*/
  parameter: function (e) {
    var statu = e.currentTarget.dataset.statu;
    this.parameterPopup(statu)
  },
  parameterPopup: function (statu) {
    /* 动画部分 */
    var animation = wx.createAnimation({
      duration: 300,  //动画时长  
      timingFunction: "linear", //线性  
      delay: 0  //0则不延迟  
    });
    animation.translateY(600).step();
    this.setData({
      parameterData: animation.export()
    })
    setTimeout(function () {
      animation.translateY(0).step()
      this.setData({
        parameterData: animation.export()
      })
      //关闭抽屉  
      if (statu == "close") {
        this.setData({
          parameterStatus: false,
          backStatus: true
        });
      }
    }.bind(this), 200)
    // 显示抽屉  
    if (statu == "open") {
      this.setData({
        parameterStatus: true,
        backStatus: false
      });
    }
  },
  //购物资讯
  chatWindow:function(e){
    var goodsId = e.currentTarget.dataset.goodsid;
    wx.navigateTo({
      url: '../chatwindow/chatwindow?&goodsId='+goodsId,
    })
  },
  /*加入购物车 */
  powerDrawer: function (e) {
    var statu = e.currentTarget.dataset.statu;
    this.setData({
      cartsType: e.currentTarget.dataset.type
    });
    this.util(statu);
  },
  util: function (statu,types) {
    /* 动画部分 */
    // 第1步：创建动画实例   
    var animation = wx.createAnimation({
      duration: 300,  //动画时长  
      timingFunction: "linear", //线性  
      delay: 0  //0则不延迟  
    });

    // 第2步：这个动画实例赋给当前的动画实例  
    this.animation = animation;

    // 第3步：执行第一组动画：Y轴偏移260px后(盒子高度是240px)，停  
    animation.translateY(400).step();

    // 第4步：导出动画对象赋给数据对象储存  
    this.setData({
      animationData: animation.export()
    })

    // 第5步：设置定时器到指定时候后，执行第二组动画  
    setTimeout(function () {
      // 执行第二组动画：Y轴不偏移，停  
      animation.translateY(0).step()
      // 给数据对象储存的第一组动画，更替为执行完第二组动画的动画对象  
      this.setData({
        animationData: animation.export()
      })

      //关闭抽屉  
      if (statu == "close") {
        this.setData({
            showModalStatus: false,
            backStatus: true
        });
      }
    }.bind(this), 200)
    // 显示抽屉  
    if (statu == "open") {
      this.handleData();
      this.setData({
          showModalStatus: true,
          backStatus: false
      });
    }
  },
  /*数量变化 */
  changeNum(e){
    var that = this;
    var mode = e.currentTarget.dataset.mode;
    var minStock = e.currentTarget.dataset.min;
    var maxStock = e.currentTarget.dataset.max;
    var num = that.data.num;
    if (mode == 'plus' && maxStock > num){
      num++;
    } else if (mode == 'reduce' && num > minStock){
      num--;
    }else{
      return false;
    }
    that.setData({
      num: num
    });
  },
  //加入购物车
  join:function(e){
    var that = this;
    var types = e.currentTarget.dataset.type;
    var goodsId = that.data.goodsId; 
    var goodsType = that.data.goodsType;
    var data = that.data.data;
    var goodsSpecId = 0;
    var num = that.data.num;
    var sessionId = app.globalData.confInfo.sessionId;
    var tokenId = app.globalData.tokenId;
    if (data.isSpec==1){
      goodsSpecId = that.data.goodsSpecId;
    }
    http.Post("weapp/carts/addCart", { tokenId: tokenId, goodsId: goodsId, goodsSpecId: goodsSpecId, buyNum: num, types: types, sessionId: sessionId, rnd: Math.random()}, function (res) {
      if (res.status == 1) {
        if (types==1){
          if (goodsType==1){
            wx.navigateTo({
              url: '../settlement-quick/settlement-quick'
            })
          }else{
            wx.navigateTo({
              url: '../settlement/settlement'
            })
          }
        }else{
          app.prompt(res.msg);
          that.getData(goodsId);
        }
        that.util('close');
      }else{
        app.prompt(res.msg);
      }
    });
  },
  onRoll:function(e){
    var that = this;
    var scrollTop = e.detail.scrollTop;
    if (scrollTop>42){
      this.setData({moreGoods: 0});
    }else{
      this.setData({ moreGoods: 1});
    }
  },
  //呼叫
  toCall: function () {
    var that = this;
    wx.makePhoneCall({
      phoneNumber: that.data.data.shop.shopTel
    })
  },
  //分享
  onShareAppMessage: function (res) {
    var that = this;
    if (res.from === 'button') {
      // 来自页面内转发按钮
    }
    return {
      title: that.data.data.goodsName,
      path: 'pages/goods-detail/goods-detail?goodsId=' + that.data.data.goodsId
    }
  },
  //评论
  chooseAppraises: function (e) {
    this.setData({
      type: e.currentTarget.dataset.type,
      evaluate: []
    })
    this.getEvaluate(this.data.goodsId, 0);
  },
  onReachBottom: function () {
    var totalPage = this.data.totalPage;
    var page = this.data.page;
    console.log(page)
    if (page < totalPage) {
      this.getEvaluate(this.data.goodsId, page);
    }
  },
  //预览
  preview: function (e) {
    var that = this;
    var evaluate = that.data.evaluate;
    var domain = app.globalData.domain;
    var id = e.currentTarget.dataset.id;
    var img = e.currentTarget.dataset.img;
    var imgs = [];
    for (var e in evaluate) {
      if (evaluate[e].id == id) {
        for (var i in evaluate[e].images) {
          imgs[i] = domain + evaluate[e].images[i];
        }
      }
    }
    wx.previewImage({
      current: img,
      urls: imgs
    })
  },
  //插件
  //满就送
  rewardState: function () {
    var that = this;
    var rewardData = that.data.rewardData;
    rewardData.state = (rewardData.state == 1) ? 0 : 1;
    that.setData({
      rewardData: rewardData
    })
  },
  //优惠券
  coupon: function (e) {
    var that = this;
    var statu = e.currentTarget.dataset.statu;
    that.couponPopup(statu);
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
          backStatus: true
        });
      }
    }.bind(this), 200)
    // 显示抽屉  
    if (statu == "open") {
      this.setData({
        couponStatus: true,
        backStatus: false
      });
    }
  },
  collar: function (e) {
    var that = this;
    var isout = e.currentTarget.dataset.isout;
    var couponId = e.currentTarget.dataset.couponid;
    var tokenId = app.globalData.tokenId;
    if (isout == 1) return false;
    http.Post("addon/coupon-weapp-receive", { tokenId: tokenId, couponId: couponId }, function (res) {
      app.prompt(res.msg);
    });
  }
})