SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `wst_goods_virtuals`;
CREATE TABLE `wst_goods_virtuals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopId` int(11) NOT NULL,
  `goodsId` int(11) NOT NULL,
  `cardNo` varchar(20) NOT NULL,
  `cardPwd` varchar(20) NOT NULL,
  `orderId` int(11) NOT NULL DEFAULT '0',
  `orderNo` varchar(20) DEFAULT NULL,
  `isUse` tinyint(4) NOT NULL DEFAULT '0',
  `dataFlag` tinyint(4) NOT NULL DEFAULT '1',
  `createTime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `shopId` (`shopId`,`cardNo`),
  KEY `goodsId` (`goodsId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
