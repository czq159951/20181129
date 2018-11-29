var app = getApp();
var http = require('../../../utils/request.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    hascommodity:false,
    shoppingcar:'/image/icon_gzspcart.png',
    selectedAllStatus:false,
    pagesize:10,
    page:1,
    attList:[],
    goodsLogo: null,
    domain:app.globalData.domain,
    deleteData:[],
    type:0,
    goodsId:'',
    buyNum:1,
    dataArray:[],
    Status:''
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onShow: function (options) {
    this.getAttList();
    this.setData({
      goodsLogo: app.globalData.confInfo.goodsLogo
    })
    app.getCartNum();
  },
  getAttList:function(){
    var that = this;
    var pagesize = that.data.pagesize;
    var page = that.data.page;
    var tokenId = app.globalData.tokenId;
    var attList = that.data.attList;
    var hascommodity = that.data.hascommodity;

    wx.showLoading({ title: '加载中' });
    http.Post('weapp/favorites/listGoodsQuery',{tokenId: tokenId,pagesize:pagesize,page:page},function(res){
      if (res.status == 1) {
        if (res.data.list.length != 0) {
          attList = res.data.list;
          hascommodity = true
        }else{
          hascommodity= false
        }
       that.setData({
         attList: attList,
         hascommodity: hascommodity
        })
        wx.hideLoading();
      }
    })
  },
  deleteList:function(){
   var that = this;
   var deleteData = this.data.deleteData;
   var type = this.data.type;
   var tokenId = app.globalData.tokenId;

   http.Post('weapp/Favorites/cancel', {id:deleteData,tokenId:tokenId,type:type},function(res){
     if (res.status == 1) {
       wx.showToast({
         title: res.msg + '!',
         icon: 'success',
         success: function () {
           that.getAttList();
           that.setData({
             deleteData:[]
           })
         }
       })
     }
   })
  },
  addCart:function(e){
   var tokenId = app.globalData.tokenId;
   var goodsId = e.currentTarget.dataset.goodsid;
   var buyNum = this.data.buyNum;

   http.Post('weapp/carts/addCart', { tokenId: tokenId, goodsId: goodsId, buyNum: buyNum},function(res){
     if (res.status == 1) {
       app.prompt(res.msg, 'success');
     } else if (res.status == -1){
       app.prompt(res.msg);
     }
   })
  },
  selectList: function (e) {
    const id = e.currentTarget.id;
    let attList = this.data.attList;
    var selectedAllStatus = this.data.selectedAllStatus;
    
    for (let i = 0; i < attList.length;i++){
      if (attList[i].favoriteId == id){
        let Status = attList[i].Status;
        attList[i].Status = !Status;
      }
      if (attList[i].Status != true) {
        selectedAllStatus = false;
      }
    }
    this.setData({
      attList: attList,
      selectedAllStatus: selectedAllStatus
    });
  },
  selectAll: function (e) {
    let attList = this.data.attList;
    let selectedAllStatus = !this.data.selectedAllStatus;
    for (let i = 0; i < attList.length;i++){
      attList[i].Status = selectedAllStatus;
      
    };

    this.setData({
      attList: attList,
      selectedAllStatus :selectedAllStatus,
    });
  },
  /*加入购物车*/
  addShopping: function (e){
    
  },
  /*取消关注*/
  cancelAttension: function (e){
    let attList = this.data.attList;
    let deleteData = this.data.deleteData;
    let hascommodity = this.data.hascommodity;
    var that = this;
    for (let i = 0; i < attList.length; i++) {
      if (attList[i].Status == 1) {
        deleteData.push(attList[i].favoriteId);
      }
    }
    this.setData({
      attList: attList,
      hascommodity:hascommodity,
      deleteData: deleteData
    });
    if(deleteData.length == 0){
      app.prompt('请选择商品');
    } else {
      wx.showModal({
        title: '提示',
        content: '确定取消选中商品？',
        success:function(res){
          if (res.confirm == true){
            that.deleteList();
         }
        }
      })
    }
  },
  //首页
  toIndex: function () {
    wx.switchTab({
      url: '../../index/index'
    })
  },
})