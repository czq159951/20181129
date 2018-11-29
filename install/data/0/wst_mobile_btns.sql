SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `wst_mobile_btns`;
CREATE TABLE `wst_mobile_btns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `btnName` varchar(255) DEFAULT NULL,
  `btnSrc` tinyint(4) NOT NULL DEFAULT '0',
  `btnUrl` varchar(255) DEFAULT NULL,
  `btnImg` varchar(255) DEFAULT NULL,
  `addonsName` varchar(255) DEFAULT NULL,
  `btnSort` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `btnSrc` (`btnSrc`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

INSERT INTO `wst_mobile_btns` VALUES ('1', '自营超市', '0', 'mobile/shops/selfshop', 'shangtao/mobile/view/default/img/self.png', null, '1');
INSERT INTO `wst_mobile_btns` VALUES ('2', '品牌街', '0', 'mobile/brands/index', 'shangtao/mobile/view/default/img/brand.png', null, '2');
INSERT INTO `wst_mobile_btns` VALUES ('3', '店铺街', '0', 'mobile/shops/shopstreet', 'shangtao/mobile/view/default/img/shopstreet.png', null, '3');
INSERT INTO `wst_mobile_btns` VALUES ('4', '品牌街', '1', 'wechat/brands/index', 'shangtao/wechat/view/default/img/brand.png', null, '2');
INSERT INTO `wst_mobile_btns` VALUES ('5', '我的订单', '0', 'mobile/orders/index', 'shangtao/mobile/view/default/img/order.png', null, '4');
INSERT INTO `wst_mobile_btns` VALUES ('6', '店铺街', '1', 'wechat/shops/shopstreet', 'shangtao/wechat/view/default/img/shopstreet.png', null, '3');
INSERT INTO `wst_mobile_btns` VALUES ('7', '自营超市', '1', 'wechat/shops/selfshop', 'shangtao/wechat/view/default/img/self.png', null, '1');
INSERT INTO `wst_mobile_btns` VALUES ('8', '我的订单', '1', 'wechat/orders/index', 'shangtao/wechat/view/default/img/order.png', null, '4');
