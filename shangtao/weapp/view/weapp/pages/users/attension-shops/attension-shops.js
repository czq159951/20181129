var app = getApp();
var http = require('../../../utils/request.js');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    hascommodity: true,
    selectedAllStatus: false,
    dataArray:[],
    pagesize:2,
    page:1,
    goodsLogo: null,
    shopLogo: null,
    domain:app.globalData.domain,
    deleteData:[],
    type:1
  },
  onShow:function(){
    wx.showNavigationBarLoading();
    this.getStoreList();
    this.setData({
      goodsLogo: app.globalData.confInfo.goodsLogo,
      shopLogo: app.globalData.confInfo.shopLogo
    })
  },
  onReady: function () {
    wx.hideNavigationBarLoading();
  },
  getStoreList:function(){
    var that = this;
    var tokenId = app.globalData.tokenId;
    var pagesize = this.data.pagesize;
    var page = this.data.page;
    var hascommodity = this.data.hascommodity;
    var dataArray = this.data.dataArray;

    http.Post('weapp/favorites/listShopQuery',{tokenId :tokenId,pagesize:pagesize,page:page},function(res){
     dataArray = res.data.list;
     if (dataArray.length == 0) {
       hascommodity = false;
     }else{
       hascommodity = true;
     }
     that.setData({
       dataArray: dataArray,
       hascommodity: hascommodity
     })
    })
  },

  deleteList: function () {
    var that = this;
    var deleteData = this.data.deleteData;
    var type = this.data.type;
    var tokenId = app.globalData.tokenId;

    http.Post('weapp/Favorites/cancel', { id: deleteData, tokenId: tokenId, type: type }, function (res) {
      if (res.status == 1) {
        wx.showToast({
          title: res.msg + '!',
          icon: 'success',
          success: function () {
            that.getStoreList();
          }
        })
      }
    })
  },
  selectList: function (e) {
    const id = e.currentTarget.dataset.id;
    var dataArray = this.data.dataArray;
    var selectedAllStatus = this.data.selectedAllStatus;
    for (let i = 0; i < dataArray.length; i++) {
      if (dataArray[i].favoriteId == id) {
        let Status = dataArray[i].Status;
        dataArray[i].Status = !Status;
      }
      if (dataArray[i].Status != true) {
        selectedAllStatus=false;
      }
    }
    this.setData({
      dataArray: dataArray,
      selectedAllStatus: selectedAllStatus
    });
  },
  selectAll: function (e) {
    let dataArray = this.data.dataArray;
    let selectedAllStatus = !this.data.selectedAllStatus;
    for (let i = 0; i < dataArray.length; i++) {
      dataArray[i].Status = selectedAllStatus;

    };
   
    this.setData({
      dataArray: dataArray,
      selectedAllStatus: selectedAllStatus,
    });
  },
  
  /*取消关注*/
  cancelAttension: function (e) {
    var that = this;
    let dataArray = this.data.dataArray;
    let deleteData = this.data.deleteData;
    let hascommodity = this.data.hascommodity;
    
    for (let i =0;i < dataArray.length;i++) {
      if (dataArray[i].Status == 1) {
        deleteData.push(dataArray[i].favoriteId);
      }
    };
    
    this.setData({
      dataArray: dataArray,
      hascommodity: hascommodity,
      deleteData: deleteData
    });
    if (deleteData.length == 0) {
      app.prompt('请选择商品');
    } else {
      wx.showModal({
        title: '提示',
        content: '确定要取消关注吗',
        success: function (res) {
          if (res.confirm == true) {
          that.deleteList();
          }
        }
      })
    }
  },
  /*跳转到店铺 */
  toStroePage:function(e){
    var shopId = e.currentTarget.dataset.shopid;
    if(shopId != 1){
      wx.navigateTo({
        url: '../../shop-home/shop-home?shopId=' + shopId,
      })
    }else{
      wx.navigateTo({
        url: '../../shop-self/shop-self?shopId=' + shopId,
      })
    }
  },
  toCommPage: function (e) {
    var goodsId = e.currentTarget.dataset.goodsid;
    wx.navigateTo({
      url: '../../goods-detail/goods-detail?goodsId=' + goodsId,
    })
  },
  //首页
  toIndex: function () {
    wx.switchTab({
      url: '../../index/index'
    })
  },
})