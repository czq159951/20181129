var app = getApp();
var http = require("../../../../utils/request.js");
Page({
  data: {
    modelStatus:false,
    allStatus : true,
    cmpnType: [{ id: 1, text: '承诺的没有做到' },
               { id: 2, text: '未按约定时间发货' }, 
               { id: 3, text: '未按成交价格进行交易' },
               { id: 4, text: '恶意骚扰' }],
    selectId:0,
    reason:'',
    goodsLogo:null,
    domain: app.globalData.domain,
    imgList: [],
    uploadImg: [],
    complainType: '',
    cmpnContent:'请选择投诉类型'
  },
  onLoad: function (e) {
    var orderId = e.orderId;
    this.setData({
      orderId: orderId,
      goodsLogo: app.globalData.confInfo.goodsLogo
    })
     this.getorderInfo();
  },

  
  onReady: function () {
    
  },
  getorderInfo:function(){
    var that = this;
    var value = this.data.value;
    var orderId = this.data.orderId;

    http.Post('weapp/orders/getDetail', { id: orderId,tokenId:app.globalData.tokenId},function(res){
       if(res.status==1){
         value = res.data;
         that.setData({
           orderNo: value.orderNo,
           orderStatus: value.orderStatus,
           shopName: value.shopName,
           createTime: value.createTime,
           goodsMoney: value.goodsMoney,
           deliverMoney: value.deliverMoney,
           realTotalMoney: value.realTotalMoney,
           goods:value.goods
         })
       }
    })
  },
  submitInfo:function(){
    var that = this;
    http.Post('weapp',{},function(res){
    })
  },
  getText:function(e){
     this.setData({
       reason:e.detail.value
     })
  },
  //上传
  upload: function () {
    var that = this;
    var imgList = that.data.imgList;
    var count = 5;
    count = count - imgList.length;
    if (count == 0) {
      app.prompt('最多只能上传5张','');
      return false;
    }
    wx.chooseImage({
      count: count,
      sizeType: ['original', 'compressed'],
      sourceType: ['album', 'camera'],
      success(res) {
        var tempFilePath = res.tempFilePaths;
        imgList = imgList.concat(tempFilePath);
        that.setData({
          imgList: imgList
        });
        if (imgList.length > 0) {
          that.uploadImg();
        }
      }
    })
  },
  uploadImg: function () {
    var that = this;
    var tokenId = app.globalData.tokenId;
    var imgList = that.data.imgList;
    var uploadImg = [];
    for (var i = 0; i < imgList.length; i++) {
      http.Upload('weapp/users/uploadPic', imgList[i], { tokenId: tokenId, dir: 'complains', isThumb: 1 }, function (res) {
        if (res.status == 1) {
          var img = res.savePath + res.name;
          uploadImg = uploadImg.concat(img);
          that.setData({
            uploadImg: uploadImg
          });
        }
      });
    }
  },
  //删除图片
  deleteImg: function (e) {
    var that = this;
    var index = e.currentTarget.dataset.index;
    var imgList = that.data.imgList;
    imgList.splice(index, 1);
    that.setData({
      imgList: imgList
    })
  },
  //提交
  submit: function (e) {
    var that = this;
    var reason = that.data.reason;
    var uploadImg = that.data.uploadImg;
    var tokenId = app.globalData.tokenId;
    var complainType = that.data.complainType ;
    var orderId = that.data.orderId;
    var complainType = that.data.complainType;

    if (complainType == '') {
      app.prompt('请选择投诉类型','');
      return false;
    }
    if (reason == '') {
      app.prompt('请填写评价内容','');
      return false;
    }
    if (uploadImg.length > 0) {
      var uploadImg = uploadImg.join(',');
    }
    var data = { tokenId: tokenId, orderId: orderId, complainContent: reason, complainType: complainType, complainAnnex: uploadImg }
    http.Post("weapp/ordercomplains/saveComplain", data, function (res) {
      if (res.status == 1) {
        wx.showToast({
          title: res.msg,
          icon: 'success',
          duration: 2000,
          success: function () {
            setTimeout(function () {
              wx.redirectTo({
                url: '../../complain-mng/complain-mng',
              })
            }, 200)
          }
        })
      } else {
        app.prompt(res.msg,'');
      }
    });
  },
  
  powerDrawer: function (e) {
    var currentStatu = e.currentTarget.dataset.statu;
    this.util(currentStatu)
  },
  util: function (currentStatu) {
    var animation = wx.createAnimation({
      duration: 300,  
      timingFunction: "linear", 
      delay: 0 ,
      transformOrigin: "100% 50% 0"
    });
    this.animation = animation;
    animation.opacity(0.5). translateY(400).step();
    this.setData({
      animationData: animation.export()
    })
    setTimeout(function () {
      animation.opacity(1).translateY(0).step();
      this.setData({
        animationData: animation.export()
      })

      //关闭抽屉  
      if (currentStatu == "close") {
        this.setData(
          {
            modelStatus: false,
            allStatus : true       
          }
        );
      }
    }.bind(this), 200)
    // 显示抽屉  
    if (currentStatu == "open") {
      this.setData(
        {
          modelStatus: true,
          allStatus : false
        }
      );
    }
  },
  selectId:function(e){
    var selectId = e.currentTarget.dataset.id;
    var cmpnType = this.data.cmpnType;
    var cmpnContent = this.data.cmpnContent;
    
    for (let i = 0; i<cmpnType.length;i++){
      if (selectId == cmpnType[i].id){
        cmpnContent = cmpnType[i].text
      }
    }
    this.setData({
      selectId:selectId,
      cmpnContent: cmpnContent
    })
    
  },
  selectIded:function(){
    this.setData({
      complainType: this.data.selectId
    })
  }
})