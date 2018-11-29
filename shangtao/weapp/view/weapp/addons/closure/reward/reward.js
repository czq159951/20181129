var http = require('../../../utils/request.js');
function rewardCarts(data, cb) {
  var addons = data.addons
  var promotion = data.promotion;
  var rewardCartIds = data.rewardCartIds;
  var check = data.check;
  var price = data.price;
  var num = data.num;
  var rewardMoney = 0;
  for (var shopId in promotion) {
    var money = 0;
    var goodsReward = [];
    for (var cartId in rewardCartIds[shopId]['list']) {
      if (rewardCartIds[shopId]['list'][cartId]){
        for (var r in rewardCartIds[shopId]['list'][cartId]) {
          var cid = rewardCartIds[shopId]['list'][cartId][r];
          if (check[shopId].list[cid] == 1) {
            goodsReward = promotion[shopId]['list'][cid]['data']['json'];
            money += price[shopId].list[cid] * num[cid];
          }
        }
      }
    }
    var discount = 0;
    addons[shopId]['words'] = '';
    for (var reward in goodsReward) {
      if (money >= goodsReward[reward].orderMoney) {
        if (goodsReward[reward].favourableJson.chk0) {
          addons[shopId]['words'] = '，已减' + goodsReward[reward].favourableJson.chk0val + '元';
          discount =  goodsReward[reward].favourableJson.chk0val;
        } else {
          addons[shopId]['words'] = '，已满足促销条件';
        }
      } else {
        addons[shopId]['words'] = '，还差' + (goodsReward[reward].orderMoney - money) + '元';
      }
    }
    for (var reward2 in goodsReward) {
      if (discount == goodsReward[reward2].favourableJson.chk0val) {
        addons[shopId]['words'] = '，已减' + goodsReward[reward2].favourableJson.chk0val + '元';
      }
    }
    price[shopId].money = price[shopId].money - discount;
    rewardMoney += discount;
  }
  typeof cb == "function" && cb({ addons: addons, rewardMoney: rewardMoney, price: price});
};
function rewardGoods(data, cb) {
  http.Post("addon/reward-weapp-goodsDetail", { goodsId: data.goodsId, shopId: data.shopId}, function (res) {
    if (res.status == 1) {
      typeof cb == "function" && cb({ rewardData:res.data });
    }
  });
};
module.exports = {
  rewardCarts: rewardCarts,
  rewardGoods: rewardGoods
}