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
    continuous: true,
    status:false,
    /*排序选择 */
    sortArray: [
      { id: '2', title: '销量', selected: true, img: '/image/img_xia.png', img1: '/image/img_xia2.png', img2: '/image/img_up.png' },
      { id: '3', title: '价格', selected: false, img: '/image/img_xia.png', img1: '/image/img_xia2.png', img2: '/image/img_up.png' },
      { id: '1', title: '人气', selected: false, img: '/image/img_xia.png', img1: '/image/img_xia2.png', img2: '/image/img_up.png' },
      { id: '6', title: '上架时间', selected: false, img: '/image/img_xia.png', img1: '/image/img_xia2.png', img2: '/image/img_up.png' },
    ],
    /*轮播模块2*/
    scrollimages: [{},{},{},{}],
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
    recommend: [],
    hasattension:0,
    /*分类商品*/
    shopArray: [],
    hasArray: '',       //是否有商品
    remindContent: '对不起,没有相关商品',
    pagesize: '6',     //每次加载量
    page: 1,          //加载层次
    condition: '2',      //默认排序
    catId: '',
    goodsName: '',
    /*分类列表数据 */
    selectedId: '',
    selectedTitle: '',
    inputText: '',
    shopId: '',     
    msort: '',        
    mdesc: '',       
    num: 1,          
    ct1: '',         
    ct2: '',            
    sortInfo:[],
    isFavor:'',
    totalGoodsMoney:'',
    goodsTotalNum : '',
    select:1,
    assifycommodity:[],
    sortSelect:false,
    recom:[],
    new:[],
    hot:[],
    best:[],
    rec: [
     { name: '店铺推荐' },
     { name: '最新上架' },
     { name: '精品促销' },
     { name: '热销商品' },
    ],
    interIm:false
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (e) {
    var that = this;
    var shopId = e.shopId;
    that.setData({
      shopId: shopId,
      goodsLogo: app.globalData.confInfo.goodsLogo,
      shopLogo: app.globalData.confInfo.shopLogo
    })
    that.getGoodsArray();//调用获取数据
    that.getShopInfo();
    that.recommend();
    //that.run1();
    var query = wx.createSelectorQuery();
    query.select('#mjltest').boundingClientRect()
    query.exec(function (res) {
      //res就是 所有标签为mjltest的元素的信息 的数组
      that.setData({
        offsetTop: res[0].top,
      })
    })
    var scrollHeight = wx.createSelectorQuery();
    scrollHeight.select('#scrollHeight').boundingClientRect()
    scrollHeight.exec(function (res) {
      //res就是 所有标签为mjltest的元素的信息 的数组
      that.setData({
        height: res[0].height,
      })
    })
  },
  /*购物车 */
  toCart: function (e) {
    wx.switchTab({
      url: '../carts/carts'
    });
  },
  /* 获取数据列表*/
  getGoodsArray: function () {
    var that = this;
    var selectedId = that.data.selectedId;
    var assifycommodity = that.data.assifycommodity;
    wx.showLoading({ title: '加载中' });
    http.Post('weapp/shops/getShopGoods', {
      shopId: that.data.shopId,
      goodsName: that.data.goodsName,
      pagesize: that.data.pagesize,
      msort: that.data.msort,
      mdesc: that.data.mdesc,
      ct1: selectedId,
      ct2: that.data.ct2,
      page: that.data.page},function(res){
        wx.hideLoading();
        if (res.status == 1) {
          for (let i = 0; i < res.data.data.length; i++) {
            assifycommodity.push(res.data.data[i]);
          }
          var TotalPage = res.data.last_page;
          var CurrentPage = res.data.current_page;
          if (that.data.interIm == true) {
            that.switchOverMenu(2);
            that.click();
            }
          that.setData({
            TotalPage: TotalPage,
            CurrentPage: CurrentPage,
            assifycommodity: assifycommodity,
            hasArray: true,
            interIm:false
          });
          if (that.data.sortSelect == true){
            that.switchOverMenu(2);
            that.click();
          }
        } else if (res.status == -1) {
          if (that.data.interIm == true) {
            that.switchOverMenu(2);
            that.click();
          }
          that.setData({
            hasArray: false,
            interIm:false
          })
        }
      })
  },
  /* 获取数据列表*/
  recommend: function () {
    var that = this;
    http.Post('weapp/shops/getShopGoods', {
      shopId: that.data.shopId,
      pagesize: 6,
      msort: 2,
      mdesc: 1
    }, function (res) {
      if (res.status == 1) {
        that.setData({
          recommend: res.data.data
        })
      } else if (res.status == -1) {
      }
    })
  },
  /* 获取广告推荐列表*/
  getShopInfo: function () {
    var that = this;
    var domain = that.data.domain;
    var shopId = that.data.shopId;
    var shopArray = that.data.shopArray;
    var hasattension = that.data.hasattension;
    var recommend = that.data.recommend;
    var sortInfo = that.data.sortInfo;
    var isFavor = that.data.isFavor;

    http.Post('weapp/shops/home', {
      shopId: shopId, tokenId: app.globalData.tokenId
    }, function (res) {
      // success
      if (res.status == 1 && res.data) {
        shopArray = res.data.shop;
        hasattension = res.data.isFavor;
        sortInfo = res.data.shopcats;
        isFavor = res.data.isFavor;
        if (shopArray.shopBanner != '' && shopArray.shopBanner != 'null'){
          var shopBanner = that.data.domain+shopArray.shopBanner
        }else{
          var shopBanner = '/image/typeimage/default_shopbanner.jpg'
        }
        if (res.data.shop.longitude && res.data.shop.latitude)var isLocation = true;
        that.setData({
          shopArray: shopArray,
          hasattension: hasattension,
          sortInfo: sortInfo,
          isFavor: isFavor,
          totalGoodsMoney: res.data.carts.goodsTotalMoney,
          goodsTotalNum: res.data.carts.goodsTotalNum,
          followNum: res.data.followNum,
          shopBanner: shopBanner,
          recom: res.data.rec.recom,
          new: res.data.rec.new,
          hot: res.data.rec.hot,
          best: res.data.rec.best,
          isLocation: isLocation,
          markers: [{
            iconPath: "../../image/native.png",
            id: 0,
            latitude: shopArray.latitude,
            longitude: shopArray.longitude,
            width: 40,
            height: 40,
            title: shopArray.shopName,
            callout: {
              content: shopArray.shopName, 
              color: "#FFFFFF",
              fontSize: "15",
              bgColor: "#3A9BFF",
              padding: 4,
              display: "ALWAYS"
            }
          }],
        })
      } else {
        wx.showToast({
          title: '已加载完毕！',
          icon: 'success'
        })
      }
    })
  },

  attStatus: function (data, url) {
    var that = this;
    http.Post('weapp/Favorites/' + url, data, function (res) {
      if (res.status == 1) {
        wx.showToast({
          title: res.msg,
          success: function () {
            that.getShopInfo();
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
    var isFavor = this.data.isFavor;
    var data = this.data.data;

    if (isFavor > 0) {
      data = {
        type: 1,
        tokenId: app.globalData.tokenId,
        id: isFavor
      }
      this.attStatus(data, 'cancel')
    } else if (isFavor <= 0) {
      data = {
        type: 1,
        tokenId: app.globalData.tokenId,
        id: this.data.shopId
      }
      this.attStatus(data, 'add')
    }
  },
  /*下拉刷新加载 
  onReachBottom: function () {
    console.log('yees');
    console.log(this.data.select);
    var page = this.data.page;
    var TotalPage = this.data.TotalPage;
    var CurrentPage = this.data.CurrentPage;

    if (TotalPage > 0 && CurrentPage < TotalPage) {
      page = page + 1;
      this.setData({
        page: page
      });
      if (this.data.select == 2) {
        this.getGoodsArray();
        console.log('yes');
      }
    } else {

    }
  },*/
  

  /*排序选择 */
  sortSelect(e) {
    if (!e) {
      var id = this.data.condition;
    } else {
      var id = e.currentTarget.dataset.id;
    }
    var sortArray = this.data.sortArray;
    var pagesize = this.data.pagesize;
    var msort = id;
    var msorted = this.data.msort;
    var mdesc = this.data.mdesc;
    if (msorted != msort) {
      mdesc = 1;
    }
    if (mdesc == 0) {
      mdesc = 1;
    } else if (mdesc == 1) {
      mdesc = 0;
    }
    for (let i = 0; i < sortArray.length; i++) {
      if (sortArray[i].id != id) {
        sortArray[i].selected = false;
      };
      if (sortArray[i].id == id) {
        sortArray[i].selected = true;
      };
    };
    this.setData({
      sortArray: sortArray,
      assifycommodity: [],
      page: 1,
      msort: msort,
      mdesc: mdesc,
      sortSelect:true,
      interim:true
    });
    this.getGoodsArray();//调用获取数据
  },

  /*展示分类动画 */
  powerDrawer(e) {
    if (e.currentTarget.dataset.page !=2) {
      var currentTarget = e.currentTarget.dataset.statu;
      var ct2 = e.currentTarget.dataset.minid;
      var ct1 = e.currentTarget.dataset.maxid;
      this.setData({
        ct2: ct2,
        ct1: ct1
      });
      this.animation(currentTarget);
  }else{
      var ct1 = e.currentTarget.dataset.maxid;
      this.setData({
        selectedId:ct1,
        page:1,
        assifycommodity:[]
      });
      this.getGoodsArray();
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
        if (ct2 && ct1) {
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
        selectedId: sortInfo[0].catId,
        selectedTitle: sortInfo[0].catName
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
        selectedTitle: selectedTitle,
        interIm:false
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
  getInputText: function (e) {
    var inputText = e.detail.value;
    this.setData({
      inputText: inputText,
    })
  },

  /*商品搜索 */
  search: function () {
    var that = this;
    var inputText = that.data.inputText;
    var shopId = that.data.shopId;
    var selectedId = that.data.selectedId;
    var ct2 = that.data.ct2;
    var ct1 = that.data.ct1;
    wx.navigateTo({
      url: '../shop-goodslist/shop-goodslist?goodsName=' + inputText + '&shopId=' + shopId + '&selectedId=' + selectedId + '&ct2=' + ct2+'&ct1='+ct1,
    })
  },
  toDetail: function (e) {
    var goodsId = e.currentTarget.dataset.goodsid;
    wx.navigateTo({
      url: '../goods-detail/goods-detail?goodsId=' + goodsId,
    })
  },
  toIntroduce:function(){
    var shopId= this.data.shopId;
    wx.navigateTo({
      url: '../shop-detail/shop-detail?shopId='+shopId,
    })
  },
  toCarts:function(){
    wx.switchTab({
      url: '../carts/carts',
    })
  },
  //分享
  onShareAppMessage: function (res) {
    var that = this;
    var shopId = that.data.shopId;
    if (res.from === 'button') {
      // 来自页面内转发按钮
    }
    return {
      title: that.data.data.goodsName,
      path: 'pages/shop-home/shop-home?shopId=' + shopId
    }
  },
  click: function () {
    if (this.data.recommend.length < 3) return;
    if (this.data.status == false) {
      this.setData({
        scrollTop: this.data.offsetTop,
      })
    }
    if (this.data.select == 1 && this.data.height > wx.getSystemInfoSync().windowHeight){
      this.setData({
        status: true
      })
    } else if (this.data.select == 2){
      this.setData({
        status: true
      })
    }
  },
  monitorScroll:function(e){
    if (this.data.offsetTop-20 > e.detail.scrollTop){
      this.setData({
        status: false
      })
    }else{
      this.setData({
        status: true
      })
    }
  },
  switchOverMenu: function (e) {
    if(e == 2){
      if(this.data.recommend.length<3)return;
      this.setData({
        select: e,
      })
    } else {
      if (e.currentTarget.dataset.pattem == 2) {
        this.setData({
          select: e.currentTarget.dataset.pattem,
        })
      } else {
        this.setData({
          select: e.currentTarget.dataset.pattem
        })
      }
    }
  },
  tolower:function(){
    var that = this;
    var page = that.data.page;
    var TotalPage = that.data.TotalPage;
    var CurrentPage = that.data.CurrentPage;
    setTimeout(function () {
      if (that.data.select == 2) {
        if (TotalPage > 0 && page < TotalPage) {
        page = page + 1;
        that.setData({
          page: page
        });
          that.getGoodsArray();
        }
      } 
    },1000)
  },
  interPage: function () {
    this.setData({
      interIm: true
    })
  },
  getAll:function(){
    this.setData({
      shopId: this.data.shopId,
      pagesize:6,
      page:1,
      assifycommodity:[],
      selectedId:'',
      ct2:''
    })
    this.getGoodsArray();
  },
  black: function () {
    this.setData({
      interIm: false
    })
  },
  locationAnimation(e) {
    var that = this;
    var status = e.currentTarget.dataset.status;
    if (!this.data.isLocation){return;}
    var locationAnimation = wx.createAnimation({
      duration: 200,
      timingFunction: "linear",
      delay: 0,
      transformOrigin: "100% 50% 0"
    });
    var locationAnimation = locationAnimation;
    locationAnimation.opacity(0.5).translateX(375).step();
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
          mapStatus:false
        })
      }

    }, 200);
    if (status == 'open') {
      that.setData({
        mapStatus: true
      })
    }
  },
})