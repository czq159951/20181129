SET FOREIGN_KEY_CHECKS=0;


DROP TABLE IF EXISTS `wst_shop_configs`;
CREATE TABLE `wst_shop_configs` (
  `configId` int(11) NOT NULL AUTO_INCREMENT,
  `shopId` int(11) NOT NULL,
  `shopTitle` varchar(255) DEFAULT NULL,
  `shopKeywords` varchar(255) DEFAULT NULL,
  `shopDesc` varchar(255) DEFAULT NULL,
  `shopBanner` varchar(150) DEFAULT NULL,
  `shopAds` text,
  `shopAdsUrl` text,
  `shopServicer` varchar(100) DEFAULT NULL,
  `shopHotWords` varchar(255) DEFAULT NULL,
  `shopStreetImg` varchar(150) DEFAULT '' COMMENT '店铺街背景',
  `shopMoveBanner` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`configId`),
  KEY `shopId` (`shopId`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;


INSERT INTO `wst_shop_configs` VALUES ('1', '1', '', '官方自营', '', '', 'upload/shopads/2016-10/57fefa2ba0ccc.jpg,upload/shopads/2016-10/57fefa2bac3e7.jpg,upload/shopads/2016-10/57fefa2bce938.jpg', ',,', '', '自营,维达纸巾,美食', '', null);
