var http = require('../../../../../utils/request.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    domain:app.globalData.domain,
    goodsLogo: null,
    switchcss: 0,
    /*排序选择 */
    sortArray: [
      { id: '0', title: '销量', selected: true, img: '/image/img_xia.png', img1: '/image/img_xia2.png', img2: '/image/img_up.png' },
      { id: '1', title: '价格', selected: false, img: '/image/img_xia.png', img1: '/image/img_xia2.png', img2: '/image/img_up.png' },
      { id: '2', title: '人气', selected: false, img: '/image/img_xia.png', img1: '/image/img_xia2.png', img2: '/image/img_up.png' },
      { id: '3', title: '上架时间', selected: false, img: '/image/img_xia.png', img1: '/image/img_xia2.png', img2: '/image/img_up.png'   },
    ],
    /*分类商品*/
    assifycommodity: [],
    mainArray:[],     
    hasArray:'',     
    remindContent:'对不起,没有相关商品',
    pagesize:'6',     
    page:1,          
    condition:'0',     
    catId:'',
    data:[],
    keyword:'',
    brandId :'',
    desc:'',
    interPage:false,
    couponId:0
  },
  /*数据初始化 */
  onLoad: function (e) {
    wx.showNavigationBarLoading();
    var that = this;
    var catId = e.catId;
    if (e.brandId != undefined) {
      var brandId = e.brandId;
    }else{
      var brandId = '';
    }
    if (e.wordKey != undefined){
      var keyword = e.wordKey;
      var catId = 4;
    }else{
      var keyword = '';
    }
    that.setData({
      catId: catId,
      brandId: brandId,
      keyword: keyword,
      goodsLogo: app.globalData.confInfo.goodsLogo,
      couponId: e.couponId
    })
    that.getGoodsArray();//调用获取数据
    //that.run1(); 
    that.getHot();
  },
  onReady: function () {
    wx.hideNavigationBarLoading()
  },
  /* 获取数据列表*/
  getGoodsArray: function (){
    var that = this;
    var mainArray = that.data.mainArray;
    var domain = that.data.domain;
    var data = that.data.data;
    var catId = that.data.catId;
    var brandId = that.data.brandId;
    var keyword = that.data.keyword;
    var pagesize = that.data.pagesize;
    var page = that.data.page;
    var condition = that.data.condition;
    var desc = that.data.desc;
    var couponId = that.data.couponId;
    if (keyword !=''){
      var catId ='';
    }
    if(page==1)wx.showLoading({ title: '加载中' });
    http.Post("addon/coupon-weapp-pageQueryByCouponGoods", {
      brandId: brandId,
      catId: catId,
      pagesize: pagesize,
      page: page,
      condition: condition,
      keyword: keyword,
      desc: desc, couponId: couponId}, function (res) {
        if (res.status == 1) {
          for (var i = 0; i < res.data.data.length; i++) {
            data.push(res.data.data[i]);
          }
          var TotalPage = res.data.last_page;
          var CurrentPage = res.data.current_page;
          that.setData({
            mainArray: data,
            TotalPage: TotalPage,
            CurrentPage: CurrentPage
          });
          that.getGoodsList();
        }
        wx.hideLoading();
    }); 
  },
  /*遍历列表 */
  getGoodsList:function(){
   var that = this;
   var hasArray = that.data.hasArray;
   var mainArray = that.data.mainArray;
   if (!mainArray.length) {
     var hasArray = false;
   }else if(mainArray.length){
     var assifycommodity = mainArray;
     var hasArray = true;
   }
   that.setData({
     assifycommodity :assifycommodity,
     hasArray:hasArray
   });
  },

  /*排序选择 */
  sortSelect(e) {
    var id = e.currentTarget.dataset.id;
    var sortArray = this.data.sortArray;
    var condition = id;
    var conditioned = this.data.condition;
    var desc = this.data.desc;
    if (conditioned != condition) {
      desc = 1;
    }
    if (desc == 0) {
      desc = 1;
    } else if (desc == 1) {
      desc = 0;
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
      condition:condition,
      assifycommodity:[],
      data:[],
      page:1,
      desc: desc
    });
    this.getGoodsArray();//调用获取数据
  },
  /*下拉刷新加载 */
  onReachBottom:function(){
    var page = this.data.page;
    var TotalPage = this.data.last_page;
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
  getText:function(e){
    var that = this;
    that.setData({
      keyword :e.detail.value
    });
  },
  /*搜索 */
  search:function(e){
    var that = this;
    var scrollimages = that.data.scrollimages;
    
    if (e.currentTarget.dataset.key) {
      var keyword = e.currentTarget.dataset.key;
    } else {
      var keyword = that.data.wordKey;
    }
    if (!keyword) {
      wx.showModal({ title: '提示', content: '请输入关键字!', showCancel: false })
    } else {
      that.setData({
        assifycommodity: [],
        data: [],
        page: 1,
        brandId: '',
        scrollimages: scrollimages,
        keyword: keyword,
        interIm:false
      });
      that.getGoodsArray();
    }
  },
  //切换
  inSwitch: function () {
    var that = this;
    var switchcss = that.data.switchcss;
    if (switchcss == 0) {
      that.setData({switchcss: 1});
    } else {
      that.setData({switchcss: 0});
    }
  },
  interPage: function () {
    this.setData({
      interIm: true
    })
  },
  black: function () {
    this.setData({
      interIm: false
    })
  },
  //楼层商品
  getHot: function () {
    var that = this;
    http.Post("weapp/index/hots", {}, function (res) {
      that.setData({
        hots: res.data
      })
    });
  },
  //搜索
  onSearch: function (e) {
    var that = this;
    if (e.currentTarget.dataset.key) {
      var wordKey = e.currentTarget.dataset.key;
    } else {
      var wordKey = that.data.wordKey;
    }
    if (!wordKey) {
      wx.showModal({ title: '提示', content: '请输入关键字!', showCancel: false })
    } else {
      wx.navigateTo({
        url: '../goods-list/goods-list?wordKey=' + wordKey
      })
    }
  },
  nameInput: function (e) {
    var that = this;
    that.setData({
      wordKey: e.detail.value,
    });
  },
})