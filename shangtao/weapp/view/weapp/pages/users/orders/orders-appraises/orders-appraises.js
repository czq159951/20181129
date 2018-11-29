var http = require('../../../../utils/request.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    orderId:0,
    data: [],
    currentId: 0,
    gScore: [1, 1, 1, 1, 1],
    sScore: [1, 1, 1, 1, 1],
    tScore: [1, 1, 1, 1, 1],
    goodsScore: 0,
    serviceScore: 0,
    timeScore: 0,
    reason:'',
    imgList:[],
    uploadImg:[],
    evaluateData:[],
    goodsLogo: null,
    domain: app.globalData.domain,
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var orderId = options.orderId;
    this.getData(options.orderId);
    this.setData({
      orderId: orderId,
      goodsLogo: app.globalData.confInfo.goodsLogo
    })
  },
  //列表
  getData: function (id) {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post("weapp/orders/orderAppraise", { tokenId: tokenId, oId: id}, function (res) {
      if (res.status == 1) {
        that.setData({
          data: res.data
        })
      }
    });
  },
  //评价
  evaluate: function (e){
    var that = this;
    var goodsId = e.currentTarget.dataset.goodsid;
    var goodsSpecId = e.currentTarget.dataset.goodsspecid;
    var orderGoodsId = e.currentTarget.dataset.ordergoodsid;
    var orderId = that.data.orderId;
    var tokenId = app.globalData.tokenId;
    http.Post("weapp/goodsappraises/getAppr", { tokenId: tokenId, oId: orderId, gId: goodsId, sId: goodsSpecId, orderGoodsId: orderGoodsId}, function (res) {
      if (res.status == 1) {
        var data = res.data;
        if (data.goodsScore > 0 || data.serviceScore > 0 || data.timeScore > 0 ){
          that.handleScore(data);
        }
        that.setData({
          currentId: goodsId,
          gScore: [1, 1, 1, 1, 1],
          sScore: [1, 1, 1, 1, 1],
          tScore: [1, 1, 1, 1, 1],
          goodsScore: 0,
          serviceScore: 0,
          timeScore: 0,
          reason: '',
          imgList: [],
          uploadImg: [],
        })
      } else {
        app.prompt(res.msg);
      }
    });
  },
  //评分
  toScore: function (e) {
    var that = this;
    var index = e.currentTarget.dataset.index;
    var types = e.currentTarget.dataset.types;
    var array = [1, 1, 1, 1, 1];
    for (var i = 0; i <= 4; i++){
      if (index>=i){
        array[i] = '2';
      }else{
        array[i] = '1';
      }
    }
    if (types == 'goods'){
      that.setData({
        gScore: array,
        goodsScore: index + 1
      })
    }
    if (types == 'service'){
      that.setData({
        sScore: array,
        serviceScore: index + 1
      })
    }
    if (types == 'time'){
      that.setData({
        tScore: array,
        timeScore: index + 1
      })
    }
  },
  handleScore: function (data) {
    var that = this;
    var gScores = [1, 1, 1, 1, 1];
    var sScores = [1, 1, 1, 1, 1];
    var tScores = [1, 1, 1, 1, 1];
    for (var i = 0; i <= 4; i++) {
      if (data.goodsScore > i) {
        gScores[i] = '2';
      } else {
        gScores[i] = '1';
      }
    }
    for (var i = 0; i <= 4; i++) {
      if (data.serviceScore > i) {
        sScores[i] = '2';
      } else {
        sScores[i] = '1';
      }
    }
    for (var i = 0; i <= 4; i++) {
      if (data.timeScore > i) {
        tScores[i] = '2';
      } else {
        tScores[i] = '1';
      }
    }
    that.setData({
      evaluateData: data,
      gScores: gScores,
      sScores: sScores,
      tScores: tScores
    })
  },
  //原因
  reasonText:function(e){
    var that = this;
    that.setData({
      reason: e.detail.value,
    });
  },
  //上传
  upload:function(){
    var that = this;
    var imgList = that.data.imgList;
    var count = 5;
    count = count - imgList.length;
    if (count==0){
      app.prompt('最多只能上传5张');
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
      http.Upload('weapp/users/uploadPic', imgList[i], { tokenId: tokenId, dir: 'appraises', isThumb: 1 }, function (res) {
        if (res.status==1){
          var img = res.savePath+res.name;
          uploadImg = uploadImg.concat(img);
          that.setData({
            uploadImg: uploadImg
          });
        }
      });
    }
  },
  //删除图片
  deleteImg:function(e){
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
    var goodsId = e.currentTarget.dataset.goodsid;
    var goodsSpecId = e.currentTarget.dataset.goodsspecid;
    var orderGoodsId = e.currentTarget.dataset.ordergoodsid;
    var orderId = that.data.orderId;
    var goodsScore = that.data.goodsScore;
    var serviceScore = that.data.serviceScore;
    var timeScore = that.data.timeScore;
    var reason = that.data.reason;
    var uploadImg = that.data.uploadImg;
    var tokenId = app.globalData.tokenId;
    if (goodsScore == 0) {
      app.prompt('请评分商品评分');
      return false;
    }
    if (serviceScore == 0) {
      app.prompt('请评分服务评分');
      return false;
    }
    if (timeScore == 0) {
      app.prompt('请评分时效评分');
      return false;
    }
    if (reason == ''){
      app.prompt('请填写评价内容');
      return false;
    }
    if (uploadImg.length > 0){
      var uploadImg = uploadImg.join(',');
    }
    var data = { tokenId: tokenId, goodsId: goodsId, goodsSpecId: goodsSpecId, orderGoodsId: orderGoodsId, orderId: orderId, goodsScore: goodsScore, serviceScore: serviceScore, timeScore: timeScore, content: reason, images: uploadImg}
    http.Post("weapp/goodsappraises/add", data, function (res) {
      if (res.status == 1) {
        that.getData(orderId);
        that.setData({
          evaluateData: [],
          currentId: 0,
          gScore: [1, 1, 1, 1, 1],
          sScore: [1, 1, 1, 1, 1],
          tScore: [1, 1, 1, 1, 1],
          goodsScore: 0,
          serviceScore: 0,
          timeScore: 0,
          reason: '',
          imgList: [],
          uploadImg: [],
        })
      } else {
        app.prompt(res.msg);
      }
    });
  },
  //商品
  togoods:function(e){
    var goodsId = e.currentTarget.dataset.goodsid;
    wx.navigateTo({
      url: '../../../goods-detail/goods-detail?goodsId=' + goodsId
    })
  }
})