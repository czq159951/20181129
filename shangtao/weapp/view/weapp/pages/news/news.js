// pages/mall-Notice/mall-Notice.js
var app = getApp();
var http = require('../../utils/request.js');
var parse = require('../common/parse/parse.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    domain: app.globalData.domain,
    animationStatus: false,
    pageSize: 6,
    page: 0,
    data: [],
    dataDetail: [],
    id: '',
    newsId: '',
    likeStatus: 0,
    articlesNum: 0,
    hasData: 1
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (e) {
    if (e.id) {
      this.setData({
        newsId: e.id,
        qian: 1
      })
    }
    this.getCatInfosList();
  },
  getNewsList: function () {
    var that = this;
    var pageSize = that.data.pageSize;
    var page = that.data.page + 1;
    var data = that.data.data;
    var catId = that.data.articlesNum;
    var hasData = 1;
    wx.showLoading({ title: '加载中...' });
    http.Post('weapp/news/getNewsList', { pagesize: pageSize, page: page, catId: catId }, function (res) {
      if (res.status == 1) {
        if (res.data.data.length > 0) {
          hasData = 0;
        }
        for (var i = 0; i < res.data.data.length; i++) {
          data.push(res.data.data[i])
        }
        that.setData({
          data: data,
          hasData: hasData,
          page: Number(res.data.current_page),
          TotalPage: res.data.last_page
        })
        wx.hideLoading();

      }
    })
  },
  getCatInfosList: function () {
    var that = this;

    http.Post('weapp/news/getChild', {}, function (res) {
      if (res.status == 1) {
        var a = res.data;
        that.setData({
          CatInfos: res.data,
          articlesNum: res.data.slice(0, 1)['0'].catId
        })
        that.getNewsList();
        if (that.data.qian) {
          that.getNewDetail(that.data.newsId);
        }
      }
    })
  },
  getNewDetail: function (e) {
    var that = this;
    if (typeof (e) == "string") {
      that.setData({
        newsId: e
      })
    } else {
      that.setData({
        newsId: e.currentTarget.dataset.id
      })
    }
    http.Post('weapp/news/geturlNews', { id: that.data.newsId }, function (res) {
      if (res.status == 1) {
        var articleContent = res.data.articleContent;
        if (articleContent) {
          parse.wxParse('articleContent', 'html', articleContent, that);
          that.powerDrawer('open');
        }
        let i = 0;
        that.setData({
          articleTitle: res.data.articleTitle,
          likeNum: res.data.likeNum,
          createTime: res.data.createTime,
          articleId: res.data.articleId
        })
        that.actionLike(res.data.articleId);

      }
    })
  },
  /*展示动画 */
  powerDrawer(e) {
    if (e == 'open') {
      var currentTarget = e;
    } else {
      var currentTarget = e.currentTarget.dataset.statu;
    }
    this.animation(currentTarget);
  },
  animation(currentTarget) {
    var that = this;
    var animation = wx.createAnimation({
      duration: 300,
      timingFunction: "linear",
      delay: 0,
      transformOrigin: "100% 50% 0"
    });
    var animation = animation;
    animation.opacity(1).translateX(375).step();
    that.setData({
      animationData: animation.export()
    });
    setTimeout(function () {
      animation.opacity(1).translateX(0).step();
      that.setData({
        animationData: animation.export()
      });
      if (currentTarget == 'close') {
        that.setData({
          animationStatus: false
        })
      }

    }, 200);
    if (currentTarget == 'open') {
      that.setData({
        animationStatus: true
      })

    }
  },
  actionLike(e) {
    var that = this;
    var newsId = '';
    var newsIds = [];
    if (typeof (e) == 'number') {
      var newsId = e;
      var status = 0;
    } else {
      var newsId = e.currentTarget.dataset.newsid;
      var status = 1;
    }
    var i = 0;
    wx.getStorage({
      key: 'articleId',
      success: function (cache) {
        var data = cache.data;
        that.setData({
          newsIds: data
        })
        if (data.length != 0) {
          for (i; i < data.length; i++) {
            if (newsId == data[i]) {
              var has = true;
            }
          }
          if (has != true && status == 1) {
            that.like(newsId);
          } else if (has != true && status == 0) {
            that.setData({
              likeStatus: 0
            })
          } else if (has == true && status == 0) {
            that.setData({
              likeStatus: 1,
              likeNum: that.data.likeNum
            })
          }
        } else {
          status == 1 ? that.like(newsId) : null;
        }
      },
      fail: function () {
        wx.setStorage({
          key: "articleId",
          data: [],
          success: function () {

          }
        })
      }
    })
  },
  like: function (newsId) {
    var that = this;
    http.Post('weapp/news/like', { id: newsId }, function (res) {
      if (res.status == 1) {
        that.setData({
          likeNum: that.data.likeNum + 1,
          likeStatus: 1
        })
        that.data.newsIds.splice(0, 0, newsId)
        wx.setStorage({
          key: "articleId",
          data: that.data.newsIds
        })
      }
    })
  },
  selected: function (e) {
    var that = this;
    that.setData({
      articlesNum: e.currentTarget.dataset.catid,
      data: [],
      page: 0
    })
    that.getNewsList();
  },
  onReachBottom: function () {
    var page = this.data.page;
    var TotalPage = this.data.TotalPage;
    if (page < TotalPage) {
      this.getNewsList();
    }
  }
})