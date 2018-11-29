alter table `wst_orders` drop column userCouponId;
alter table `wst_orders` drop column userCouponJson;
DROP TABLE IF EXISTS `wst_coupon_goods`;
DROP TABLE IF EXISTS `wst_coupon_cats`;
DROP TABLE IF EXISTS `wst_coupon_users`;
DROP TABLE IF EXISTS `wst_coupons`;
delete from `wst_navs` where navUrl='addon/coupon-coupons-index.html';

delete from `wst_switchs` where urlMark='coupon';