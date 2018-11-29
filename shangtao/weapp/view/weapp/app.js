//app.js
var config = require('config.js');
var http = require('./utils/request.js');
App({
  /**
   * 当小程序初始化完成时，会触发 onLaunch（全局只触发一次）
   */
  onLaunch: function () {
    // 本地存储
    var that = this;
    var tokenId = wx.getStorageSync('tokenId') || null;
    if (tokenId){
      that.globalData.tokenId = tokenId;
    }else{
      that.checkLogin();
    }
    this.getdata();
    wx.getStorageInfo({
      success: function (res) {
        // 判断商品浏览记录是否存在，没有则创建
        if (!('history' in res.keys)) {
          wx.setStorage({
            key: 'history',
            data: []
          })
        }
      }
    })
    this.getCartNum();
  },
  //微信用户信息
  getUserInfo: function (cb) {
    var that = this
    if (this.globalData.userInfo=='') {
      wx.login({
        success: function (res) {
          if (res.code) {
            var code = res.code;
            http.Post("weapp/request/index", { code: code }, function (res) {
              if (res.status==1) {
                that.globalData.sessionKey = res.data;
                wx.getSetting({
                  success(res2) {
                    if (res2.authSetting['scope.userInfo']) {
                        wx.getUserInfo({
                          success: function (res3) {
                            that.globalData.userInfo = res3.userInfo;
                            res3['sessionKey'] = res.data;
                            http.Post("weapp/request/bizdata", res3, function (res4) {
                              if (res4.status == 1) {
                                that.globalData.unionKey = res4.data;
                                typeof cb == "function" && cb(res3);
                              }
                            });
                          }
                        });
                    }
                  }
                })
              }
            });
          } else {
            typeof cb == "function" && cb('获取用户登录态失败！');
          }
        }
      });
    }
  },
  //检测登录
  checkLogin: function (cb) {
    var that = this;
    var sessionKey = that.globalData.sessionKey;
    var unionKey = that.globalData.unionKey;
    if (sessionKey) {
      that.triggerLogin(sessionKey, unionKey,function (res2) {
        typeof cb == "function" && cb(res2);
      })
    }else{
      that.getUserInfo(function (res) {
        if (res.userInfo) {
          var sessionKey = that.globalData.sessionKey;
          var unionKey = that.globalData.unionKey;
          if (sessionKey) {
            that.triggerLogin(sessionKey, unionKey,function (res2) {
              typeof cb == "function" && cb(res2);
            })
          }
        }
      })
    }
  },
  //自动登录
  triggerLogin: function (key, key2, cb) {
    var that = this;
    http.Post("weapp/users/handleLogin", { sessionKey: key, unionKey: key2}, function (res) {
      if (res.status == 1) {
        that.globalData.tokenId = res.data;
        wx.setStorageSync('tokenId', res.data);
        typeof cb == "function" && cb(1);
      } else {
        typeof cb == "function" && cb(0);
      }
    });
  },
  getCartNum:function(){
   // if (this.globalData.tokenId != null){
        http.Post("weapp/carts/getCartNum", { tokenId:this.globalData.tokenId }, function (res) {
            if(res.status == 1){
              if (res.data != 0 && res.data != undefined){
                wx.setTabBarBadge({
                  index: 2,
                  text: String(res.data)
                })
              }else{
                wx.removeTabBarBadge({
                  index:2
                })
              }
            }
        })
   // }
  },

  getdata: function () {
    var that = this;
    http.Get("weapp/index/confInfo",{}, function (res) {
      if (res.status == 1){
        that.globalData.confInfo = res.data;
      }
    });
  },
  /*获取屏幕信息*/
  getSize: function (cb) {
    wx.getSystemInfo({
      success: function (res) {
        typeof cb == "function" && cb(res);
      }
    }) 
  },
  /*处理用户头像 */
  userPhoto: function(userPhoto){
    // 外网头像
    if (userPhoto && userPhoto.indexOf('http') != -1) {
      userPhoto = userPhoto;
    } else if (userPhoto) {
      userPhoto = this.globalData.domain + userPhoto;
    } else {
      // 使用默认头像
      //userPhoto = this.globalData.domain + global.confInfo.userLogo;
    }
    return userPhoto;
  },
/*广告跳转 */
  jumpcenter: function (url) {
    var url = url;
    if (url == '') return;
    if (url.indexOf('/pages') == 0) {
      url = url.replace('/pages/', '');
      if (url.indexOf('addons') == 0) {
        wx.navigateTo({
          url: '../../' + url,
        })
      } else {
          wx.navigateTo({
            url: '../' + url,
            fail:function(){
              wx.switchTab({
                url: '../' + url,
              })
            }
          })
      }
    } else {
      wx.navigateTo({
        url: '../webview/webview?url=' + url,
      })
    }
  },
  /*判断字符串是否在数组内 ,有则返回索引*/
  isInArray: function (arr, value) {
    for (var i = 0; i < arr.length; i++) {
      if (value === arr[i]) {
        return i;
      }
    }
    return false;
  },
  /*提示信息,icon：有效值 "success", "loading", "none"*/
  prompt: function (msg, icon = 'none') {
    wx.showToast({
      title: msg,
      icon: icon,
      duration: 2000
    })
  },
  /**
   * 当小程序发生脚本错误，或者 api 调用失败时，会触发 onError 并带上错误信息
   */
  onError: function (msg) {
    console.log(msg)
  },
  globalData: {
    userInfo: [],
    sessionKey: null,
    unionKey:null,
    tokenId: null,
    domain: config.domain,
    confInfo: null
  }
})
