var http = require('../../../utils/request.js');
function couponGoods(data, cb) {
  http.Post("addon/coupon-weapp-getCouponsByGoods", { tokenId: data.tokenId, goodsId: data.goodsId }, function (res) {
    if (res.status == 1) {
      typeof cb == "function" && cb({ couponGoods: res.data});
    }
  });
};
function couponUsers(data,cb) {
  http.Post("addon/coupon-weapp-couponsNum", { tokenId: data.tokenId}, function (res) {
    console.log();
    if (res.status == 1) {
      typeof cb == "function" && cb({ couponsNum: res.data });
    }
  });
};
module.exports = {
  couponGoods: couponGoods,
  couponUsers: couponUsers
}