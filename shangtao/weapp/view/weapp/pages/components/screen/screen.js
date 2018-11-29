var goodsList = require('../../goods-list/goods-list.js');
Component({
  properties: {
    screenData: {
      type: Object,
      value: {},
    }
  },
  data: {
    // 这里是一些组件内部数据  
    status: 0,
  },
  methods: {
    // 这里放置自定义方法  
    modal_click_Hidden: function () {
      this.setData({
        modalHidden: true,
      })
    },
    // 确定  
    Sure: function () {
      console.log(this.data.text)
    },

    showAll: function () {
     this.setData({
       status:!this.data.status
     })
    },
    selectAttr2: function (e) {
      var eventDetail = e;
      var eventOption = {
      }
      this.triggerEvent("myevent", eventDetail, eventOption);
    }
  }
})  