var http = require('../../utils/request.js');
var app = getApp();
Page({
  data: {
    domain:app.globalData.domain,
    goodsLogo: null,
    switchcss: 0,
    /*排序选择 */
    sortArray: [
      { id: '0', title: '销量', selected: true, img: '/image/img_xia.png', img1: '/image/img_xia2.png', img2: '/image/img_up.png' },
      { id: '1', title: '价格', selected: false, img: '/image/img_xia.png', img1: '/image/img_xia2.png', img2: '/image/img_up.png' },
      { id: '2', title: '人气', selected: false, img: '/image/img_xia.png', img1: '/image/img_xia2.png', img2: '/image/img_up.png' },
    ],
    screenIcon:'/image/screen.png',
    screenIcon1:'/image/screen2.png',
    /*分类商品*/
    assifycommodity: [],
    mainArray:[],     
    hasArray:'',     
    remindContent:'对不起,没有相关商品',
    pagesize:'10',     
    page:1,          
    condition:'0',     
    catId:'',
    data:[],
    keyword:'',
    brandId :'',
    desc:'',
    interPage:false,
    showModalStatus: false,
    screenTier: false,
    minPrice:'',
    maxPrice:'',
    transportType:'',
    vs:[],
    attrs:[],
    attrsed:[],
    priceScreen:'',
    selecded:0,
    TotalPage:'',
    CurrentPage:''
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
    })
    that.getGoodsArray();//调用获取数据
    //that.run1(); 
    that.getHot();
  },
  onReady: function () {
    wx.hideNavigationBarLoading();
    this.screen = this.selectComponent("#screen");
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
    var minPrice = that.data.minPrice;
    var maxPrice = that.data.maxPrice;
    var transportType = that.data.transportType;
    var vs = that.data.vs;
    var attrs = that.data.attrs;
    if (keyword !=''){
      var catId ='';
    }
    if(page==1)wx.showLoading({ title: '加载中' });
    http.Post("weapp/goods/pageQuery", {
      brandId: brandId,
      catId: catId,
      pagesize: pagesize,
      page: page,
      condition: condition,
      keyword: keyword,
      minPrice: minPrice,
      maxPrice: maxPrice,
      vs: vs,
      attrs: attrs,
      isFreeShipping: transportType,
      desc: desc}, function (res) {
        if (res.status == 1) {
          for (var i = 0; i < res.data.goodsPage.data.length; i++) {
            data.push(res.data.goodsPage.data[i]);
          }
          that.setData({
            mainArray: data,
            TotalPage: res.data.goodsPage.last_page,
            CurrentPage: Number(res.data.goodsPage.current_page),
            goodsFilter: res.data.goodsFilter
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
        vs: [],
        attrs: [],
        attrsed: [],
        transportType: '',
        priceScreen: '',
        minPrice: '',
        maxPrice: '',
        scrollimages: scrollimages,
        keyword: keyword,
        interIm: false,
        selecded: false
      })
      that.getGoodsArray();
    }
  },
  //商品
  toGoodsDetail:function(e){
    var goodsId = e.currentTarget.dataset.goodsid;
    wx.navigateTo({
      url: '../goods-detail/goods-detail?goodsId='+goodsId,
    })
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
  openScreenTier: function(e){
    var action = e.currentTarget.dataset.action;
    this.parameterPopup(action);
  },
  parameterPopup: function (action) {
    /* 动画部分 */
    var that = this;
    var animation = wx.createAnimation({
      duration: 300,  //动画时长  
      timingFunction: "linear", //线性  
      delay: 0 , //0则不延迟  
      transformOrigin: "100% 50% 0"
    });
    animation.opacity(0.8).translateX(375).step();
    this.setData({
      parameterData: animation.export()
    })
    setTimeout(function () {
      animation.opacity(1).translateX(0).step();
      this.setData({
        parameterData: animation.export()
      })
      //关闭抽屉  
      if (action == "close") {
        var selecded = 1;
        if (that.data.vs.length == 0 && that.data.minPrice == '' && that.data.maxPrice == '' && that.data.transportType == '')       {
          selecded = 0; }
        that.setData({
          showModalStatus: false,
          screenTier: false,
          selecded: selecded
        });
      }
    }.bind(this), 500)
    // 显示抽屉  
    if (action == "open") {
      that.setData({
        showModalStatus: true,
        screenTier: true,
        selecded:1
      });
    }
  },
  minPrice: function (e) {
          this.setData({
              minPrice:e.detail.value
          });
  },
  maxPrice:function(e){
    this.setData({
      maxPrice: e.detail.value
    });
  },
  selectAttr: function (e) {
    var vs = this.data.vs;
    var attrs = this.data.attrs;
    var attrsed = this.data.attrsed;
    var attrId = e.detail.currentTarget.dataset.id;
    var attrArr = e.detail.currentTarget.dataset.attr;
    var arr = { attrName: e.detail.currentTarget.dataset.type, attr: attrArr, attrId: attrId};
    attrsed.push(arr);
    attrs[attrId] = attrArr;
    vs.push(e.detail.currentTarget.dataset.id)
    this.setData({
          vs:vs,
          attrs: attrs,
          attrsed: attrsed,
          assifycommodity: [],
          data: [],
          page: 1,
          brandId: '',
    })
    this.getGoodsArray();
  },
  selectedSure: function () {
    if (this.data.minPrice != '' || this.data.maxPrice != '') {
      this.setData({
        assifycommodity: [],
        data: [],
        page: 1,
        brandId: '',
      });
      this.getGoodsArray();
    }
    this.parameterPopup('close');
  },
  isFreeShipping:function(e){
    this.setData({
      transportType: e.currentTarget.id
    })

    this.setData({
      assifycommodity: [],
      data: [],
      page: 1,
      brandId: '',
    });
    this.getGoodsArray();
  },
  resetAll: function () {
    this.setData({
      assifycommodity: [],
      data: [],
      page: 1,
      brandId: '',
      vs: [],
      attrs: [],
      attrsed: [],
      transportType: '',
      priceScreen: '',
      minPrice: '',
      maxPrice: ''
    })
    this.getGoodsArray();
  },
  cancelSeled:function(e){
    var vs = this.data.vs;
    var attrsed = this.data.attrsed;
    var attrId = e.currentTarget.dataset.attrid;
    var index = e.currentTarget.dataset.index;
    var key = app.isInArray(vs, attrId);
    vs.splice(key,1);
    attrsed.splice(index,1);
    this.setData({
      assifycommodity: [],
      data: [],
      page: 1,
      vs: vs,
      attrsed: attrsed
    })
    this.getGoodsArray();
  },
  cancelFree: function () {
  this.setData({
    assifycommodity: [],
    data: [],
    page: 1,
    transportType:''
  })
    this.getGoodsArray();
  }
})