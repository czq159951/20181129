var app = getApp();
var http = require('../../../../utils/request.js');
Page({
  /**
   * 页面的初始数据
   */
  data: {
    selectType: false,
    selectStyle: false,
    showModalStatus: false,
    status1: false,
    status2: false,
    areaName: '请选择收货地址',
    address1: '请选择',
    address2: '请选择',
    address3: '请选择',
    data: null,
    Area: [],
    Area1: [],
    Area2: [],
    setDefault: false,
    pattern: 1,
    page: 0,
    total: null,
    attensionAddress: '',
    phoneNumber: '',
    userName: '',
    areaId: '',
    isDefault: 0,
    addressId: ''
  },
  /*页面开启时加载数据 */
  onLoad: function (e) {
    var data = this.data.data;
    if (e.id) {
      this.setData({
        addressId: e.id
      })
      data = {
        tokenId: app.globalData.tokenId,
        addressId: e.id
      }
      this.getAddressInfo(data, 'getById');
    } else {
      data = {
        tokenId: app.globalData.tokenId,
      }
      this.getAddressInfo(data, 'index');
    }
  },
  getAddressInfo: function (data, url) {
    var that = this;
    var Area = this.data.Area;
    var isDefault = this.data.isDefault;
    http.Post('weapp/UserAddress/' + url, data, function (res) {
      if (res.status == 1) {
        if (res.data.area1) {
          Area = res.data.area1;
        } else {
          Area = res.data.area;
        }
        if (res.data.isDefault) {
          isDefault = res.data.isDefault;
        }
        that.setData({
          userName: res.data.userName,
          userPhone: res.data.userPhone,
          areaName: res.data.areaName,
          userAddress: res.data.userAddress,
          isDefault: isDefault,
          Area: Area,
          areaId: res.data.areaId
        })
      }
    })
  },
  getEditInfo: function (data, url) {
    var that = this;
    var Area1 = this.data.Area1;
    var Area2 = this.data.Area2;
    var pattern = this.data.pattern;
    http.Post('weapp/UserAddress/' + url, data, function (res) {
      if (res.status == 1) {
        if (pattern == 2) {
          Area1 = res.data.area
          that.setData({
            Area1: Area1
          })
        } else if (pattern == 3) {
          Area2 = res.data.area
          that.setData({
            Area2: Area2
          })
        }
        if (res.status == 1 && res.msg !='请求成功') {
          wx.showToast({
            title: res.msg,
            success: function () {
              setTimeout(function () {
                wx.navigateBack({
                  delta: 1
                })
              }, 1000)
            }
          })
        }
      }
    })
  },
  infoSave: function () {
    var data = this.data.data;
    data = {
      addressId: this.data.addressId,
      userName: this.data.userName,
      userPhone: this.data.userPhone,
      userAddress: this.data.userAddress,
      isDefault: this.data.isDefault,
      areaId: this.data.areaId,
      tokenId: app.globalData.tokenId
    }
    this.getEditInfo(data, 'edits');
  },
  //点击选择类型
  clickArea: function (e) {
    var page = this.data.page;
    var data = this.data.data;
    var pattern = this.data.pattern;
    var total = this.data.total;
    var address1 = this.data.address1;
    var address2 = this.data.address2;
    var address3 = this.data.address3;
    page++;
    var areaId = e.currentTarget.dataset.areaid;
    if (page == 1) {
      var Area = this.data.Area;

      for (let i = 0; i < Area.length; i++) {
        if (Area[i].areaId == areaId) {
          address1 = Area[i].areaName;
          pattern = 2;
        }
      }
    } else if (page == 2) {
      var Area1 = this.data.Area1;

      for (let i = 0; i < Area1.length; i++) {
        if (Area1[i].areaId == areaId) {
          address2 = Area1[i].areaName;
          pattern = 3;
        }
      }
    } else if (page == 3) {
      var Area2 = this.data.Area2;

      for (let i = 0; i < Area2.length; i++) {
        if (Area2[i].areaId == areaId) {
          address3 = Area2[i].areaName;
          areaId = areaId;
          pattern = 4;
        }
      }
      this.setData({
        total: address1 + address2 + address3
      })
      this.powerDrawer('close');
    }
    this.setData({
      address1: address1,
      address2: address2,
      address3: address3,
      page: page,
      pattern: pattern,
      areaId: areaId
    })
    data = {
      tokenId: app.globalData.tokenId,
      parentId: areaId
    }
    this.getEditInfo(data, 'index')

  },
  //点击切换
  mySelect: function (e) {
    var id = e.currentTarget.id;
    var pattern = this.data.pattern;
    var page = this.data.page;
    if (id == 1) {
      pattern = 1;
      page = 0;
    } else if (id == 2) {
      pattern = 2;
      page = 0;
    } else if (id == 3) {
      pattern = 3;
      page = 0;
    }
    this.setData({
      pattern: pattern,
      page: page
    })
  },
  isDefault: function (e) {
    var isDefault = e.detail.value;
    if (isDefault == true) {
      isDefault = 1;
    } else {
      isDefault = 0;
    }
    this.setData({
      isDefault: isDefault
    })
  },

  /*动画模块 */
  powerDrawer: function (e) {
    if (e == 'close') {
      var currentStatus = e;
    } else {
      var currentStatus = e.currentTarget.dataset.status;
    }
    this.util(currentStatus);
  },
  util: function (currentStatus) {
    var that = this;
    var animation = wx.createAnimation({
      duration: 200,
      timingFunction: "linear",
      delay: 200,
      transformOrigin: "50% 50% 0",
    });
    /*第二步*/
    var animation = animation;
    /*第三步 */
    animation.opacity(0).rotateX(-100).step();
    /*第四步*/
    this.setData({
      animationData: animation.export()
    });
    setTimeout(function () {
      /*第五步 */
      animation.opacity(1).rotateX(0).step()
      that.setData({
        animationData: animation.export()
      })

      //关闭抽屉
      if (currentStatus == 'close') {
        that.setData({
          showModalStatus: false
        });
      }
    }, 500);

    //打开抽屉
    if (currentStatus == 'open') {
      that.setData({
        showModalStatus: true
      });
    }
  },
  /*获取NameInput */
  userName(e) {
    var userName = e.detail.value;
    this.setData({
      userName: userName
    })
  },
  /*获取电话 */
  phoneNumber(e) {
    var userPhone = e.detail.value;
    this.setData({
      userPhone: userPhone
    })
  },
  /*获取详细地址 */
  attensionAddress(e) {
    var userAddress = e.detail.value;
    this.setData({
      userAddress: userAddress
    })
  }
})