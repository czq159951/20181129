// pages/accountManage/accountManage.js
var app = getApp();
var http = require('../../../utils/request.js');


let cropper = require('../../common/we-cropper/we-cropper.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
     userName:'132022440785',
     user_image: '/image/user_head.png',
     model_id: '',
     modelStatus:'',
     titleContent:'',
     select_id:'',
     gender:'',
     userName:'',
     userData:[],
     userSex:'',
     loginName:'',
     userName:'',
     userPhoto:'',
     userPhoto2: '',
     domain: app.globalData.domain,
     value:'',
     parameterStatus:true

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onLoad: function (e) {
    var that = this;
    // 获取显示区域长宽
    const device = wx.getSystemInfoSync()
    const W = device.windowWidth
    const H = device.windowHeight - 50
    // 初始化组件数据和绑定事件
    cropper.init.apply(that, [W, H]);

    this.getUserInfo();
  },
  getUserInfo: function () {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post('weapp/users/index', { tokenId: tokenId }, function (res) {
      that.setData({
        userSex: res.data.userSex,
        userName: res.data.userName,
        loginName: res.data.loginName,
        userPhoto: res.data.userPhoto
      })
    });
  },
  editUserData:function(){
   var that = this;
   var userSex = that.data.userSex;
   var userName = that.data.userName;
   var tokenId = app.globalData.tokenId;
   var userPhoto = that.data.userPhoto2;
   var datas = new Array();
   datas['tokenId'] = tokenId;
   datas['userSex'] = userSex;
   datas['userName'] = userName;
   datas['tokenId'] = tokenId;
   datas['tokenId'] = tokenId;
   if (userPhoto)datas['userPhoto'] = userPhoto;
   http.Post('weapp/users/edit', datas ,function(res){
     res = JSON.parse(res.msg);
     if (res.status == 1) {
       wx.showToast({
         title: res.msg,
         icon: 'success',
         success: function () {
           setTimeout(function () {
             let currentStatu = 'close';
             that.util(currentStatu);
           }, 500);
         },
         
       });
       that.hideCropper()
     }
   })
  },
  selectTap(e) {
    let that = this
    let mode = e.currentTarget.dataset.mode

    wx.chooseImage({
      count: 1, // 默认9
      sizeType: ['original', 'compressed'], 
      sourceType: ['album', 'camera'], 
      success(res) {
        const tempFilePath = res.tempFilePaths[0]
       
        that.showCropper({
          src: tempFilePath,
          mode: mode,
          sizeType: ['original', 'compressed'],   
          callback: (res) => {
            if (mode == 'rectangle') {
              that.setData({
                tempFilePaths: [res]
              })
              that.uploadFile2();
            }
            else {
              wx.showModal({
                title: '',
                content: JSON.stringify(res),
              })
            }
          }
        })
      }
    })
  },
 
  uploadFile2: function () {
    var that = this;
    var domain = that.data.domain;
    var tokenId = app.globalData.tokenId;
    var value = that.data.value;
    var tempFilePaths = that.data.tempFilePaths;
    http.Upload('weapp/users/uploadPic',tempFilePaths[0],{tokenId:tokenId, dir:'users',isTumb:1 }, function (res) {
      var userPhoto = res.savePath + res.name;
       that.setData({
         userPhoto: domain + userPhoto,
         userPhoto2: userPhoto
        }) 
       that.editUserData();
      });
  },
  
  ylimg: function (e) {
    wx.previewImage({
      current: e.target.dataset.src,
      urls: this.data.tempFilePaths, // 需要预览的图片http链接列表
      success:function(){
        //var e = !that.data.parameterStatus;
        //that.powerDrawer(e);
        //console.log(e);
      }
    })
  },
  /*昵称修改 */
  nickNameChange(e){
    this.setData({
      model_id:'1',
      titleContent:'修改昵称'
    });
    this.powerDrawer(e);
  },
  /*性别 修改 */
  genderChange(e){
     this.setData({
       model_id:'2',
       titleContent:'修改性别'
     });
     this.powerDrawer(e);
  },
  
  /*性别提交 */
  genderSelect(){
  var that = this;
   var select_id = this.data.select_id;
   var userSex = '';
   if(select_id == '0'){ 
     userSex = '0';
   }else if(select_id == '1'){
     userSex = '1';
   }else if(select_id == '2'){
     userSex = '2';
   };
     this.setData({
       userSex: userSex
   });
     this.editUserData(); 
  },
  /*性别选中 */
  genderSelected(e){
    let select_id = e.currentTarget.dataset.id;
    this.setData({
      select_id:select_id
    });
   this.genderSelect();
  },
  /*昵称修改 */
  nickName(e){
    
    this.setData({
      text:e.detail.value
    });
  },
  /*文本提交 */
  textSubmit(){
    var that = this;
    if(that.data.text == undefined) {
      app.prompt('昵称不能为空');
    } else { 
    that.setData({
      userName:that.data.text
    });
    this.editUserData();
    }
  },
  powerDrawer: function (e) {
    if (e.currentTarget == undefined){
      var currentStatu = e;
    } else {

      var currentStatu = e.currentTarget.dataset.statu;
    }
    this.util(currentStatu);
  },
  util: function (currentStatu) {
    /* 动画部分 */
    // 第1步：创建动画实例
    var animation = wx.createAnimation({
      duration: 300,  //动画时长  
      timingFunction: "linear", //线性  
      delay: 0  //0则不延迟  
    });

    // 第2步：这个动画实例赋给当前的动画实例   
    this.animation = animation;

    // 第3步：执行第一组动画：Y轴偏移260px后(盒子高度是240px)，停  
    animation.translateY(400).step();

    // 第4步：导出动画对象赋给数据对象储存  
    this.setData({
      animationData: animation.export()
    })


    // 第5步：设置定时器到指定时候后，执行第二组动画  
    setTimeout(function () {
      // 执行第二组动画：Y轴不偏移，停  
      animation.translateY(0).step()
      // 给数据对象储存的第一组动画，更替为执行完第二组动画的动画对象  
      this.setData({
        animationData: animation
      })

      //关闭抽屉  
      if (currentStatu == "close") {
        this.setData(
          {
            modelStatus: false
          }
        );
      }
    }.bind(this), 200)
    // 显示抽屉  
    if (currentStatu == "open") {
      this.setData(
        {
          modelStatus: true
        }
      );
    }
  }
})