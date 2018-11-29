var app = getApp();
var http = require('../../../utils/request.js');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    pagesize:10,
    page:1,
    hascommodity: true,
    managerInfo:[],
    selectedAllStatus:true,
    deleteData:[],
    status:[{status:0}]
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onShow: function (e) {
    this.getManagerList();
  },

  getManagerList:function(){
    var that = this;
    var pagesize = this.data.pagesize;
    var page = this.data.page;
    var tokenId = app.globalData.tokenId;
    var managerInfo = this.data.managerInfo;
    var status = this.data.status;

    wx.showLoading({ title: '加载中' });
    http.Post('weapp/messages/pageQuery',{pagesize:pagesize,page:page,tokenId:tokenId},function(res){
      managerInfo = res.data.data;
      that.setData({
        managerInfo : managerInfo
      })
      wx.hideLoading();
    })
  },
  deleteList:function(e){
    var deleteData = this.data.deleteData;
    var tokenId = app.globalData.tokenId;
    var that = this;

    http.Post('weapp/messages/del', { ids: deleteData, tokenId: tokenId},function(res){
      if(res.status == 1){
        wx.showToast({
          title: res.msg,
          icon:'success',
          success:function(){
            that.getManagerList();
          }
        })
      }

    })
  },
  /*删除商品 */

  /*选择商品*/
  selectList(e) {
    const id = e.currentTarget.dataset.id;           
    let managerInfo = this.data.managerInfo;      
    for (let i = 0; i < managerInfo.length; i++) {
      if (managerInfo[i].id == id) {
        const status = managerInfo[i].status;       
        managerInfo[i].status = !status;            
      }
    }
    this.setData({
      managerInfo: managerInfo,
    });
  },
  /*选择所有*/
  selectAll() {
    let selectedAllStatus = this.data.selectedAllStatus;    // 是否全选状态
    selectedAllStatus = !selectedAllStatus;
    let managerInfo = this.data.managerInfo;
    for (let i = 0; i < managerInfo.length; i++) {
      managerInfo[i].status = selectedAllStatus;            // 改变所有商品状态
    }
    this.setData({
      selectedAllStatus: selectedAllStatus,
      managerInfo: managerInfo
    });
  },
  //删除商品
  deleteData(e) {
    var deleteData = this.data.deleteData;
    var managerInfo = this.data.managerInfo;
    for (let i = 0; i < managerInfo.length; i++){
      if (managerInfo[i].status == 1) {
        deleteData.push(managerInfo[i].id);
      }
    }
    this.setData({
      deleteData:deleteData
    })
    this.deleteList();
  },
  /*跳转到详情 */
  toDetail:function(e){
    var msgId = e.currentTarget.dataset.msgid;
    wx.navigateTo({
      url: './messages-detail/messages-detail?msgId='+msgId,
    })
  }
})