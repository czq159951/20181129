var app = getApp();
var http = require('../../utils/request.js');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    goodsLogo: null,
    shopLogo: null,
    domain: app.globalData.domain,
    showModalStatus: false,
    /*大轮播图 */
    indicatorDots: true,
    autoplay: true,
    interval: 2000,
    duration: 500,
    circular: true,
    continuous:true,
    /*轮播模块2*/
    scrollimages: [ {},{},{},{}],
    scrollstatus: 'none',
    initial_width: '',
    floatstatus1: 'absolute',
    floatstatus2: '',
    left1: '0',
    left2: '0',
    driftvariable: '1',
    setSpeed1: '50',
    scrollleft: '20',
    moveleft: '',
    /*广告推荐信息模块 */
    shopArray:[],
    recommend: [{}, {}, {},{},],
    hot: [{},{},{},{}],
    /*分类商品*/
    assifycommodity: [],
    currPage:1,
    sortInfo:[],
    /*分类列表数据 */
    selectedId:0,
    selectedTitle: '',
    inputText:'',
    shopId:1 ,    
    ct2:'',
    value:'',
    isFavor:'',
    status:'',
    data:'',
    interIm:false
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    that.getGoodsArray();
    that.getShopInfo();
    //that.run1();
    that.setData({
      goodsLogo: app.globalData.confInfo.goodsLogo,
      shopLogo: app.globalData.confInfo.shopLogo
    })
  }, 
  /* 获取数据列表*/
  getGoodsArray: function () {
    var that = this;
    var domain = that.data.domain;
    var currPage = that.data.currPage;
    var assifycommodity=that.data.assifycommodity;
    var catNameArray = that.data.catNameArray;
    var Array = that.data.Array;
    wx.showLoading({ title: '加载中' });
    /*数据初始化 */
    http.Post('weapp/shops/getFloorData', { currPage: currPage }, function (res) {
      wx.hideLoading();
      if (res.status == 1 && res.data) {
        assifycommodity = assifycommodity.concat(res.data);
        that.setData({
          assifycommodity: assifycommodity,
        })
      } else {
        wx.showToast({
          title: '已加载完毕！',
          icon: 'success'
        })
      }
    });
  },

  /* 获取广告推荐列表*/
  getShopInfo: function () {
    var that = this;
    var value = that.data.value;
    var domain = that.data.domain;
    var shopId = that.data.shopId;
    http.Post('weapp/shops/selfshop', {shopId:shopId,tokenId:app.globalData.tokenId}, function (res) {
      // success
      if (res.status == 1 && res.data) {
        if (res.data.shop.longitude && res.data.shop.latitude) var isLocation = true;
        value = res.data;
        that.setData({
          hot: value.hot,
          recommend: value.rec,
          shopArray: value.shop,
          sortInfo: value.shopcats,
          shopId: value.shop.shopId,
          isFavor: value.isFavor,
          followNum: res.data.followNum,
          isLocation: isLocation,
          markers: [{
            iconPath: "../../image/native.png",
            id: 0,
            latitude: value.shop.latitude,
            longitude: value.shop.longitude,
            width: 40,
            height: 40,
            title: value.shop.shopName,
            callout: {
              content: value.shop.shopName,
              color: "#FFFFFF",
              fontSize: "15",
              bgColor: "#3A9BFF",
              padding: 4,
              display: "ALWAYS"
            }
          }],
        })
      } else {
        wx.showModal({
          title: '提示',
          content: res.msg,
        })
      }
    }); 
  },
  attStatus:function(data,url){
    var that = this;
    http.Post('weapp/Favorites/'+url,data,function(res){
      if(res.status == 1){
        if (url == 'cancel'){
          that.setData({
            followNum: that.data.followNum-1,
            isFavor:0
          })
        } else if (url == 'add'){
          that.setData({
            followNum: that.data.followNum + 1,
            isFavor:1
          })
        }
        wx.showToast({
          title: res.msg,
          success:function(){
          }
        })
      }else{
        wx.showModal({
          title: '提示',
          content: res.msg,
        })
      }
    })
  },
  selectStatus:function(e){
    var isFavor = this.data.isFavor;
    var data = this.data.data;
    
    if (isFavor > 0){
      data = {
        type: 1,
        tokenId: app.globalData.tokenId,
        id: isFavor
      }
      this.attStatus(data,'cancel')
    } else if (isFavor <= 0){
      data = {
        type: 1,
        tokenId: app.globalData.tokenId,
        id: this.data.shopId
      }
      this.attStatus(data,'add')
    }
  },
  /*下拉刷新加载 */
  onReachBottom: function () {
    var that = this;
    var currPage = that.data.currPage;
    currPage++;
    that.setData({
      currPage: currPage
    });
    that.getGoodsArray();
    },

  /*展示分类动画 */
  powerDrawer(e) {
    if (e.currentTarget.dataset.page != 2) {
      var currentTarget = e.currentTarget.dataset.statu;
      var ct2 = e.currentTarget.dataset.minid;
      var ct1 = e.currentTarget.dataset.maxid;
      this.setData({
        ct2: ct2,
        ct1: ct1
      });
      this.animation(currentTarget);
    } else {
      var ct1 = e.currentTarget.dataset.maxid;
      this.setData({
        ct1: ct1,
      });
      this.search();
    }
  },
  animation(currentTarget) {
    var that = this;
    var ct2 = that.data.ct2;
    var ct1 = that.data.ct1;
    var animation = wx.createAnimation({
      duration: 200,
      timingFunction: "linear",
      delay: 0,
      transformOrigin: "100% 50% 0"
    });
    var animation = animation;
    animation.opacity(0.5).translateX(375).step();
    that.setData({
      animationData: animation.export()
    });
    setTimeout(function () {
      animation.opacity(1).translateX(0).step();
      that.setData({
        animationData: animation.export()
      });
      if (currentTarget == 'close') {
        that.setData({
          showModalStatus: false
        })
        if (ct2 && ct1){
          that.search();
        }
      }

    }, 200);
    if (currentTarget == 'open') {
      that.setData({
        showModalStatus: true
      })
      that.switchover();
    }
  },

  /*点击切换样式 */
  switchover(e) {
    var sortInfo = this.data.sortInfo;
    var selectedId = this.data.selectedId;
    var selectedTitle = this.data.selectedTitle;
    if (!e) {
      this.setData({
        selectedId : sortInfo[0].catId,
        selectedTitle : sortInfo[0].catName
      });
    } else {
      var mixId = e.currentTarget.dataset.mixid;
      for (let i = 0; i < sortInfo.length; i++) {
        if (sortInfo[i].catId == mixId) {
            selectedId = sortInfo[i].catId;
            selectedTitle = sortInfo[i].catName;
          }
        };

        this.setData({
          selectedId: selectedId,
          selectedTitle: selectedTitle
        });
      };
  },

  /*轮播模块2 */
  run1: function (e) {

    var that = this;
    var setTimeOne = setInterval(function () {
      if (-that.data.left1 >= 0) {
        if (-that.data.left1 <= that.data.initial_width) {
          that.setData({
            left1: that.data.left1 - that.data.driftvariable,
            left2: that.data.initial_width - (- that.data.left1),
          });
        };
        if (-that.data.left1 == that.data.initial_width) {
          that.setData({
            left1: that.data.initial_width,
          });
        }
      } else {
        that.setData({
          left2: that.data.left2 - that.data.driftvariable,
          left1: that.data.initial_width - (- that.data.left2),
        });
      };
    }, that.data.setSpeed1);
  },

  /*横向*/
  row_scroll: function (e) {
    /*console.log(e);*/
    var windowWidth = wx.getSystemInfoSync().windowWidth;
    var that = this;
    /*console.log(windowWidth);*/
    /*赋予初始宽度*/
    if (that.data.initial_width == '') {
      that.setData({
        initial_width: e.detail.scrollWidth,
        moveleft: e.detail.scrollLeft,
      });
    }
    /*当模块宽度大于屏幕宽度时,赋予循环*/
    if (e.detail.scrollWidth >= windowWidth) {
      that.setData({
        scrollleft: '0',
        scrollstatus: '',
        floatstatus2: 'absolute',
      });
    }
  },

  /*抓取输入框内容 */
  getInputText:function(e){
    var inputText = e.detail.value;
    this.setData({
      inputText : inputText,
    })
  },
  toDetail:function(e){
    var goodsId = e.currentTarget.dataset.goodsid;
    wx.navigateTo({
      url: '../goods-detail/goods-detail?goodsId='+goodsId,
    })
  },
  getMore:function(e){
    var ct1 = e.currentTarget.dataset.catid;
    wx.navigateTo({
      url: '../shop-goodslist/shop-goodslist?ct1='+ct1+'&shopId='+this.data.shopId+"&num=1",
    })
  },
  /*商品搜索 */
  search:function(){
    var that = this;
    var inputText = that.data.inputText;
    var shopId = that.data.shopId;
    var selectedId = that.data.selectedId;
    var ct2 = that.data.ct2;
    var ct1 = that.data.ct1;
    var page = that.data.page;
    wx.navigateTo({
      url: '../shop-goodslist/shop-goodslist?goodsName=' + inputText+'&shopId='+shopId+'&selectedId='+selectedId+'&ct2='+ct2+'&ct1='+ct1+'&page='+page,
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
      path: 'pages/shop-self/shop-self'
    }
  },
  toIntroduce: function () {
    var shopId = this.data.shopId;
    wx.navigateTo({
      url: '../shop-detail/shop-detail?shopId=' + shopId,
    })
  },
  toCarts: function () {
    wx.switchTab({
      url: '../carts/carts',
    })
  },
  interPage: function () {
    this.setData({
      interIm: true
    })
  },
  getAll: function () {
    this.setData({
      shopId: this.data.shopId,
      pagesize: 6,
      page: 1,
      assifycommodity: [],
      selectedId: '',
      ct1:'',
      ct2: ''
    })
    this.search();
  },
  black: function () {
    this.setData({
      interIm: false
    })
  }, 
  locationAnimation(e) {
    var that = this;
    var status = e.currentTarget.dataset.status;
    if (!this.data.isLocation) { return; }
    var locationAnimation = wx.createAnimation({
      duration: 200,
      timingFunction: "linear",
      delay: 0,
      transformOrigin: "100% 50% 0"
    });
    var locationAnimation = locationAnimation;
    locationAnimation.opacity(1).translateX(375).step();
    that.setData({
      mapLayer: locationAnimation.export()
    });
    setTimeout(function () {
      locationAnimation.opacity(1).translateX(0).step();
      that.setData({
        mapLayer: locationAnimation.export()
      });
      if (status == 'close') {
        that.setData({
          mapStatus: false
        })
      }

    }, 200);
    if (status == 'open') {
      that.setData({
        mapStatus: true
      })
    }
  },
  jumpcenter: function (e) {
    app.jumpcenter(e.currentTarget.dataset.url);
  },
})