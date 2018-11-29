var app = getApp();
var http = require('../../../../utils/request.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    dataStatu:true,
    dataList:[]
  },
  onShow:function(){
    this.pageQuery();
  },
  pageQuery:function(){
    var that = this;
    var dataStatu = this.data.dataStatu;
    http.Post('weapp/cashconfigs/pageQuery',{tokenId: app.globalData.tokenId,pagesize:10,page:1},function(res){
         if (res.data.list.length != 0){
           dataStatu = true
         }else{
           dataStatu = false
         }
         that.setData({
           dataList:res.data.list,
           dataStatu : dataStatu
         })
    })
  },
  delInfo:function(e){
    var that = this;
    wx.showModal({
      title: '提示',
      content: '您确定要删除该账号？',
      success:function(res){
        if (res.confirm == true){

          http.Post('weapp/cashconfigs/del', { tokenId: app.globalData.tokenId, id: e.currentTarget.dataset.id }, function (res) {
            if (res.status == 1) {
              wx.showToast({
                title: res.msg,
                success: function () {
                  that.pageQuery();
                }
              })
            }
          })
        }
      }
    })
  },
  /*跳转至账号添加 */
  addAccount(e) {
    wx.navigateTo({
      url: '/pages/users/user-balance/account-mng/addaccount/addaccount',
    })
  },
  /*账号删除 */
  deleteAccount(e){
    var dataArray = this.data.dataArray;
    var dataStatu = this.data.dataStatu;
    var id = e.currentTarget.dataset.id;
    for(let i=0;i<dataArray.length;i++){
       if(dataArray[i].id == id){
         dataArray.splice(i,1);
       }
    }
    if(dataArray.length == 0){
      dataStatu = false;
    }
    this.setData({
      dataStatu : dataStatu,
      dataArray : dataArray
    });
  },
  /*跳转到信息页面 */
  jumpAccountInfo(e){
    var id = e.currentTarget.dataset.id;
    var areaName = e.currentTarget.dataset.areaname;
    var accNo = e.currentTarget.dataset.accno;
    var accUser = e.currentTarget.dataset.accuser;
    var bankName = e.currentTarget.dataset.bankname;
    wx.navigateTo({
      url: "/pages/users/user-balance/account-mng/addaccount/addaccount?id=" + id + "&areaName=" + areaName+"&accNo="+accNo+"&accUser="+accUser+"&bankName="+bankName+"&status=1"
    }) 
  }
})