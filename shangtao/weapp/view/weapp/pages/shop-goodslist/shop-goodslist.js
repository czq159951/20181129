// pages/commoditySale/commoditySale.js
var app = getApp();
var http = require('../../utils/request.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    domain: app.globalData.domain,
    showModalStatus: false,
    /*排序选择 */
    sortArray: [
      { id: '2', title: '销量', selected: true, img: '/image/img_xia.png', img1: '/image/img_xia2.png',img2: '/image/img_up.png' },
      { id: '3', title: '价格', selected: false, img: '/image/img_xia.png', img1: '/image/img_xia2.png', img2: '/image/img_up.png' },
      { id: '1', title: '人气', selected: false, img: '/image/img_xia.png', img1: '/image/img_xia2.png', img2: '/image/img_up.png' },
      { id: '6', title: '上架时间', selected: false, img: '/image/img_xia.png', img1: '/image/img_xia2.png', img2: '/image/img_up.png' },
    ],
    /*分类商品*/
    assifycommodity: [],
    hasArray: '',       //是否有商品
    remindContent: '对不起,没有相关商品',
    pagesize: '6',     //每次加载量
    page: 1,          //加载层次
    condition: '2',      //默认排序
    catId: '',
    goodsName :'',
    /*分类列表数据 */
    searchContent:'',
    selectedId: 0,
    selectedTitle: '',
    inputText: '',
    shopId: '',     //店铺Id
    msort:'',        //排序种类
    mdesc:'',         //排序方法 
    num :1,          //请求环境
    ct1 :'',         //一级分类
    ct2:'' ,           //二级分类
    shopType:1,
    interIm: false
  },
  /*数据初始化 */
  onLoad: function (e) {
    var that = this;
    if (e.num) {
      var shopId = e.shopId;
      var ct1 = e.ct1;

      that.setData({
        shopId: shopId,
        ct1: ct1
      })
    }else{
    var shopId = e.shopId;
    var goodsName = e.goodsName;
    var selectedId = e.selectedId;
    var shopType = that.data.shopType;
    var ct2 = e.ct2;
    var ct1 = e.ct1;
    that.setData({
      shopId: shopId,
      goodsName: goodsName,
      selectedId: selectedId,
      ct2: ct2,
      ct1: ct1,
      shopType: shopType
    })
    }
    if(shopId != 1){
      shopType = 2;  
    }
    that.getGoodsArray();//调用获取数据
    that.getShopInfo();

    that.setData({
      shopId: shopId,
      goodsLogo: app.globalData.confInfo.goodsLogo
    })
    
  },
  onShow: function () {
  },

  /* 获取数据列表*/
  getGoodsArray: function () {
    var that = this;
    var mainArray = that.data.mainArray;
    var domain = that.data.domain;
    var selectedId = that.data.selectedId;
    var assifycommodity = that.data.assifycommodity;
    /*数据初始化 */
    http.Post('weapp/shops/getShopGoods', {
      shopId: that.data.shopId,
      goodsName: that.data.goodsName,
      msort: that.data.msort,
      mdesc: that.data.mdesc,
      ct1: that.data.ct1,
      ct2: that.data.ct2,
      pagesize: that.data.pagesize,
      page: that.data.page
    }, function (res) {
      // success
      if (res.status == 1) {
        for (var i = 0; i < res.data.data.length; i++) {
          assifycommodity.push(res.data.data[i]);
        }
        var TotalPage = res.data.last_page;
        var CurrentPage = res.data.CurrentPage;
        that.setData({
          TotalPage: TotalPage,
          CurrentPage: CurrentPage,
          assifycommodity: assifycommodity,
          hasArray: true,
          interIm:false
        });
      } else if (res.status == -1) {
        that.setData({
          hasArray: false,
          interIm: false
        })
      }
    });
  },
 
  /* 获取分类列表*/
  getShopInfo: function () {
    var that = this;
    var hot = that.data.hot;
    var domain = that.data.domain;
    var recommond = that.data.recommond;
    var shopArray = that.data.shopArray;
    var sortInfo = that.data.sortInfo;
    var shopId = that.data.shopId;
    var num = that.data.num;
    var shopType = that.data.shopType;

    /*数据初始化 */
    http.Post('weapp/shops/selfshop', { num: num, shopId: shopId }, function (res) {
      // success
      if (res.status == 1 && res.data) {
        
        hot = res.data.hot;
        recommond = res.data.rec;
        shopArray = res.data.shop;
        sortInfo = res.data.shopcats;

        that.setData({
          hot: hot,
          recommond: recommond,
          shopArray: shopArray,
          sortInfo: sortInfo
        })
        //that.run1();
      } else {
        wx.showToast({
          title: '已加载完毕！',
          icon: 'success'
        })
      }

    });
  },

  /*展示分类动画 */
  powerDrawer(e) {
    if (e.currentTarget.dataset.page != 2) {
      var currentTarget = e.currentTarget.dataset.statu;
      var shopId = this.data.shopId;
      if (e.currentTarget.dataset.minid) {
        var ct2 = e.currentTarget.dataset.minid;
        var ct1 = e.currentTarget.dataset.maxid;
      } else {
        var ct2 = this.data.ct2;
        var ct1 = this.data.ct1;
      }
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
      this.search2();
    }
  },
  animation(currentTarget) {
    var that = this;
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
          showModalStatus: false,
          goodsName: '',
          msort: '',
          mdesc: '',
          assifycommodity:[]
        });
          that.getGoodsArray();
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
        selectedTitle: selectedTitle
      });
    };
  },

  /*排序选择 */
  sortSelect(e) {
    if(!e){
       var id = this.data.condition;
    }else{
      var id = e.currentTarget.dataset.id;
    }
    var sortArray = this.data.sortArray;
    var pagesize = this.data.pagesize;
    var msort = id;
    var msorted = this.data.msort;
    var mdesc = this.data.mdesc;
    if(msorted != msort){
      mdesc =1;
    }
    if (mdesc == 0){
      mdesc = 1;
    } else if (mdesc == 1){
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
      data: [],
      page: 1,
      msort: msort,
      mdesc: mdesc
    });
    this.getGoodsArray();//调用获取数据
  },
  /*下拉刷新加载 */
  onReachBottom: function () {
    var page = this.data.page;
    var TotalPage = this.data.TotalPage;
    var CurrentPage = this.data.CurrentPage;

    if (TotalPage > 0 && CurrentPage < TotalPage) {
      page = page + 1;
      this.setData({
        page: page
      });
      this.getGoodsArray();
    } else {

    }
     
  },
  /*获取输入框内容 */
  getText: function (e) {
    var that = this;
    that.setData({
      searchContent: e.detail.value
    });
  },
  /*搜索 */
  search: function () {
    var condition = this.data.condition;
    var goodsName = this.data.searchContent;
    this.setData({
      condition: 2,
      assifycommodity: [],
      page: 1,
      goodsName: goodsName,
      ct1:'',
      ct2:'',
      interIm:false
    });
    this.getGoodsArray();
  },
  /*搜索 */
  search2: function () {
    var condition = this.data.condition;
    var goodsName = this.data.searchContent;
    this.setData({
      condition: 2,
      assifycommodity: [],
      page: 1,
      goodsName: '',
      ct2: '',
      interIm: false
    });
    this.getGoodsArray();
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
      ct2: '',
      ct1:''
    })
    this.getGoodsArray();
  },
  black: function () {
    this.setData({
      interIm: false
    })
  },
})