
DROP TABLE IF EXISTS `wst_integral_goods`;
CREATE TABLE `wst_integral_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopId` int(11) NOT NULL,
  `goodsId` int(11) NOT NULL,
  `goodsPrice` decimal(11,2) NOT NULL DEFAULT '0.00',
  `integralNum` int(11) NOT NULL DEFAULT '0',
  `totalNum` int(11) NOT NULL DEFAULT '0',
  `orderNum` int(11) NOT NULL DEFAULT '0',
  `startTime` datetime NOT NULL,
  `endTime` datetime NOT NULL,
  `integralDesc` text,
  `integralStatus` tinyint(4) DEFAULT '1',
  `dataFlag` tinyint(4) NOT NULL,
  `updateTime` datetime NOT NULL,
  `createTime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
INSERT INTO `wst_navs`(navType,navTitle,navUrl,isShow,isOpen,navSort,createTime) VALUES ('0', '积分商城', 'addon/integral-goods-lists.html', '1', '0', '0', '2017-02-16 10:32:01');
INSERT INTO `wst_ad_positions`(positionType,positionName,positionWidth,positionHeight,dataFlag,positionCode,apSort) VALUES ( '1', '积分商城轮播广告', '1920', '320', '1', 'ads-integral', '1');


INSERT INTO `wst_switchs`(homeURL,mobileURL,wechatURL,urlMark) VALUES ('integral/goods/lists', 'integral/goods/molists', 'integral/goods/wxlists', 'integral');
INSERT INTO `wst_switchs`(homeURL,mobileURL,wechatURL,urlMark) VALUES ('integral/goods/detail', 'integral/goods/modetail', 'integral/goods/wxdetail', 'integral');