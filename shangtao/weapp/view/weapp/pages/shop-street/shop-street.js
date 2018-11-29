// pages/Store/Stores.js
var app = getApp();
var http = require('../../utils/request.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    shopLogo: null,
    domain: app.globalData.domain,
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
    /*排序选择 */
    mainArray: { id: '0', title: '主营' },
    mainArray2:'主营',
    sortArray: 
    { id: '1', title: '好评度', img: '/image/img_up.png', img1: '/image/img_xia2.png', img2:'/image/img_xia.png'}
    ,
    /*店铺数据 */
    hasDate:true,
    storeArray:[],     //店铺列表
    pagesize:10,        //初始化加载个数
    page:1,            //加载层数 
    catId:'',          //种类Id
    /*种类弹出 */
    selectType: false,  //弹出状态
    selected:'',        //选择状态
    typeArray: ["主营"],      //种类数据
    selectArea:'',      //选择位置
    condition:'',       //选择位置/自带
    desc:0,             //排列方式
    TotalPage:'',        //总页数
    CurrentPage:'',       //当前页数
    num:0 ,              //切换状态数
    keyword:'' ,          //搜索关键字
    allStatus:true,
    interIm:false,
    latitude:'',
    longitude:'',
    service: { attrName: '店铺服务', attrId:0,attrVal: [] },
    graded: {attrName: '好评率', attrId: 1, attrVal: []},
    totalScore:'',
    accredId:'',
    accred:'',
    scoreSection:''
  },
  onLoad: function () {
    var that = this;
    var catId = that.data.catId;
    /*获取经纬度*/
    var that = this
    wx.getLocation({
      type: 'wgs84',
      success: function (res) {
        var latitude = res.latitude
        var longitude = res.longitude
        console.log(res)
        that.setData({
          latitude: latitude,
          longitude: longitude
        })
        that.getGoodsArray(catId);
      },
      fail: function () {
        that.getGoodsArray(catId);
        console.log('调用失败')
      }
    })
    //that.run1();
    that.getsortArray();
    app.getSize(function (res){
      var width = res.windowWidth;
      that.setData({
        windowWidth: width+'rpx'
      })
    });
    that.setData({
      shopLogo: app.globalData.confInfo.shopLogo,
    })
    
  },
  /* 获取数据列表*/
  getGoodsArray: function (catId) {
    var that = this;
    var domain = that.data.domain;
    var pagesize = that.data.pagesize;
    var page = that.data.page;
    var storeArray = that.data.storeArray;
    var condition = that.data.condition;
    var desc = that.data.desc;
    var keyword = that.data.keyword;
    var latitude = that.data.latitude;
    var longitude = that.data.longitude;
    var service = that.data.service;
    var graded = that.data.graded;
    var totalScore = that.data.totalScore;
    var accredId = that.data.accredId;
    wx.showLoading({ title: '加载中'});
    /*数据初始化 */
    http.Post('weapp/shops/pageQuery',{
        pagesize: pagesize,
        page:page,
        id:catId,
        condition:condition,
        desc:desc,
        keyword:keyword,
        latitude: latitude,
        longitude: longitude,
        totalScore: totalScore,
        accredId: accredId
    }, function (res) {
      wx.hideLoading();
      if (res.status == 1 && res.data.data.length>0) {
        for (let i = 0; i < res.data.data.length; i++) {
          storeArray.push(res.data.data[i]);
        }
        if (Number(res.data.current_page) == 1) {
          if (res.data.screen.accreds.length != 0) {
                for (var i in res.data.screen.accreds){

                  service.attrVal.push(res.data.screen.accreds[i]['accredName']);
                  that.setData({
                    service: service,
                    accreds: res.data.screen.accreds
                  })
                }
            }else{
              that.setData({
                service: '',
              })
            }
            if (res.data.screen.scores.length != 0) {
              for (var i in res.data.screen.scores) {

                graded.attrVal.push(res.data.screen.scores[i]);
                that.setData({
                  graded: graded,
                  scores: res.data.screen.scores
                })
                }
            }else{
 
              that.setData({
                graded: '',
              })
            }
        }
        that.setData({
          hasDate:true,
          storeArray: storeArray,
          TotalPage: res.data.last_page,
          CurrentPage: res.data.current_page,
        });
      }else{
        that.setData({
          hasDate: false,
          service: '',
          graded: ''
        });
      }
    });
  },
  /* 获取分类列表*/
  getsortArray: function () {
    var that = this;
    var domain = that.data.domain;
    var typeArray = that.data.typeArray;
    var scrollimages = that.data.scrollimages;
    wx.showLoading({ title: '加载中' });
    /*数据初始化 */
    http.Post('weapp/shops/shopStreet', {}, function (res) {
      // success
      if (res.status == 1) {
        for (let i = 0; i < res.data.goodscats.length; i++) {
          typeArray.push(res.data.goodscats[i]['catName']);
        }
        scrollimages = res.data.swiper;
        that.setData({
          typeArray: typeArray,
          typeObj: res.data.goodscats,
          scrollimages: scrollimages
        })
      }
      wx.hideLoading();
    });
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
  /*排序选择 */
  sortSelect(e) {

    var num = this.data.num;
    var catId = this.data.catId;
    var desc = this.data.desc;
    
    var condition = e.currentTarget.dataset.id;
    if (condition == '0') {
      var e = e.currentTarget.dataset.status;
      var selected = this.data.selected;
      this.setData({
        selected: condition, 
        condition: condition,
        num:0
      });
      this.powerDrawer(e);
    } else if (condition == '1') {
      this.setData({
        selected: condition,
        condition: condition
      });
      if(num == 0){
        num++;
        this.setData({
          num:num
        })
      }else{
      if(desc == 0){
        desc = 1;
      }else if(desc == 1){
        desc = 0;
      }

      this.setData({
        desc: desc,
        storeArray: [],
        page: 1,
      service: { attrName: '店铺服务', attrId: 0, attrVal: [] },
        graded: { attrName: '好评率', attrId: 1, attrVal: [] },
      });
      this.getGoodsArray(catId, desc);
     }
    }
  },
  distance(e) {
    var condition = e.currentTarget.dataset.id;
    var catId = this.data.catId;
    var desc = this.data.desc;

    if (desc == 0) {
      desc = 1;
    }else{
      desc = 0;
    }
    this.setData({
      condition: condition,
      storeArray: [],
      page: 1,
      desc: desc,
      selected: 2,
      service: { attrName: '店铺服务', attrId: 0, attrVal: [] },
      graded: { attrName: '好评率', attrId: 1, attrVal: [] },
    })
     this.getGoodsArray(catId, desc);
  },
  /*下拉刷新加载 */
  onReachBottom: function () {
    var catId = this.data.catId;
    var page = this.data.page;
    var pagesize = this.data.pagesize;
    var TotalPage = this.data.TotalPage;
    var CurrentPage = this.data.CurrentPage;
    var keyword = this .data.keyword;
    if (TotalPage > 0 && CurrentPage < TotalPage) {
      page = page + 1;
      this.setData({
        page: page
      });
      this.getGoodsArray(catId, pagesize, page, keyword);
    } else {
    }

  },
  //点击切换
  mySelect: function (e) {
    var that = this;
    var typeObj = that.data.typeObj;
    var mainArray = that.data.mainArray;
    var catId = e.currentTarget.dataset.id;
    var page = that.data.page;
    mainArray.title = e.currentTarget.dataset.type;
    that.setData({
      mainArray: mainArray,
      mainArray2: mainArray.title,
      storeArray: [],
      page: 1,
      catId:catId,
      keyword: '',
      totalScore: '',
      accredId: '',
      scoreSection:'',
      accred:'',
      service: { attrName: '店铺服务', attrId: 0, attrVal: [] },
      graded: { attrName: '好评率', attrId: 1, attrVal: [] },
    })
    setTimeout(function () {
      that.setData({
        selectType: false,
        interIm:false
      });
    }, 100);
    that.getGoodsArray(catId);
    that.powerDrawer(e);
  },
  /*颜色转换*/
  //触摸开始
  touchstart: function (e) {
    var typeArray = this.data.typeArray;
    var selectArea = this.data.selectArea;
    var id = e.target.dataset.id;
    for (let i = 0; i < typeArray.length; i++) {
      if (typeArray[i].catId == id) {
        selectArea = id;
        this.setData({
          selectArea: selectArea
        });
      }
    }

  },
  /*动画模块 */
  powerDrawer: function (e) {
    if (e != 'close' && e != 'open'){
       var currentStatus = e.currentTarget.dataset.status;
    }else{
       var currentStatus = e;
    }
    this.util(currentStatus);
  },
  util: function (currentStatus) {
     
      //关闭抽屉
      if (currentStatus == 'close') {
        this.setData({
          showModalStatus: false,
          selectType: false,
          allStatus:true
        });
        var e = 'close';
      }

    //打开抽屉
    if (currentStatus == 'open') {
      this.setData({
        showModalStatus: true,
        allStatus: false
      });
    }
  },
  /*获取Input内容 */
  getSearch:function(e){
    var keyword = e.detail.value;
    this.setData({
      keyword:keyword
    });
  },
    /*点击搜索 */
    search:function(e){
      var catId = null;
      var keyword = this.data.keyword;
      var scrollimages = this.data.scrollimages;
      if (!keyword) {
        wx.showModal({ title: '提示', content: '请输入店铺名称!', showCancel: false })
      } else {
      this.setData({
        page: 1,
        storeArray: [],
        scrollimages: scrollimages,
        keyword:keyword,
        interIm:false
      });
      this.getGoodsArray(catId);
      }
    },
    /*跳转到店铺详情 */
    jumpShopDetail:function(e){
      var shopId = e.currentTarget.dataset.shopid;
      if(shopId == '1'){
      wx.navigateTo({
        url: '../shop-self/shop-self',
      })
      }else{
      wx.navigateTo({
        url: '../shop-home/shop-home?&shopId='+shopId,
      })};
    },
    interPage:function(){
      this.setData({
        interIm:true
      })
    },
    black: function () {
      this.setData({
        interIm: false
      })
    },
    //点击选择类型
    bindPickerChange: function (e) {

      var typeObj = this.data.typeObj;
      var typeArray = this.data.typeArray;
      var index, catId;
      if (e.detail.value == 0){
        index = '主营';
      } else {
          for (var i in typeObj) {
            if (typeArray[e.detail.value] == typeObj[i]['catName']) {
              index = typeObj[i]['catName'];
              catId = typeObj[i]['catId'];
            }
          }
      }
      this.setData({
        mainArray2: index,
        storeArray: [],
        page: 1,
        catId: catId,
        keyword: '',
        service: { attrName: '店铺服务', attrId: 0, attrVal: [] },
        graded: { attrName: '好评率', attrId: 1, attrVal: [] },
        totalScore:'',
        accredId:'',
        accred:'',
        scoreSection:''
      })
      this.getGoodsArray(catId);
    },
    openScreenTier: function (e) {
      var action = e.currentTarget.dataset.action;
      this.parameterPopup(action);
    },
    parameterPopup: function (action) {
      /* 动画部分 */
      var that = this;
      var animation = wx.createAnimation({
        duration: 300,  //动画时长  
        timingFunction: "linear", //线性  
        delay: 0, //0则不延迟  
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
          that.setData({
            showModalStatus: false,
            screenTier: false,
          });
        }
      }.bind(this), 500)
      // 显示抽屉  
      if (action == "open") {
        that.setData({
          showModalStatus: true,
          screenTier: true
        });
      }
    },
    selectAttr: function (e) {
      var scores = this.data.scores;
      var accreds = this.data.accreds;
      var catId = this.data.catId;
      var checked = e.detail.currentTarget.dataset.attr;
      var checkedId = e.detail.currentTarget.dataset.id;
      var accredId = this.data.accredId;
      var totalScore = this.data.totalScore;
      var service = this.data.service;
      var graded = this.data.graded;
      if (checkedId == 0){
        for (var i in accreds){
          if(accreds[i]['accredName'] == checked){
            accredId = accreds[i]['accredId'];
            this.setData({
              accred : checked,
              service: '',
            })
            if (graded != '') {
              this.setData({
                graded: { attrName: '好评率', attrId: 1, attrVal: [] },
              })
            }
          }
        }
      }else{
        for (var i in scores) {
          if (scores[i] == checked) {
            totalScore = i;
            this.setData({
              scoreSection: checked,
              graded: '',
            })
            if(service != ''){
            this.setData({
              service: { attrName: '店铺服务', attrId: 0, attrVal: [] },
            })
            }
          }
        }
      }
      this.setData({
        totalScore: totalScore,
        accredId: accredId,
        storeArray:[],
        page:1,
      })

      this.getGoodsArray(catId);
    },
    resetAll: function () {
      var catId = this.data.catId;
      this.setData({
        totalScore: '',
        accredId: '',
        storeArray: [],
        page: 1,
        accred:'',
        scoreSection:'',
        service: { attrName: '店铺服务', attrId: 0, attrVal: [] },
        graded: { attrName: '好评率', attrId: 1, attrVal: [] },
      })
      this.getGoodsArray(catId);
    },
    cancelAccred: function () {
      var catId = this.data.catId;
      var totalScore = this.data.totalScore;
      this.setData({
        accred: '',
        accredId:'',
        service: { attrName: '店铺服务', attrId: 0, attrVal: [] },
        storeArray: [],
        page: 1,
      })
      if (totalScore == ''){
        this.setData({graded: { attrName: '好评率', attrId: 1, attrVal: [] }})
      }
      this.getGoodsArray(catId);
    },
    cancelScore: function () {
      var catId = this.data.catId;
      var accredId = this.data.accredId;
      this.setData({
        scoreSection: '',
        totalScore: '',
        graded: { attrName: '好评率', attrId: 1, attrVal: [] },
        storeArray: [],
        page: 1,
      })
      if (accredId == '') {
        this.setData({service: { attrName: '店铺服务', attrId: 0, attrVal: [] } })
      }
      this.getGoodsArray(catId);
    }
})