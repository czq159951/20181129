SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `wst_order_goods`;
CREATE TABLE `wst_order_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderId` int(11) NOT NULL,
  `goodsId` int(11) NOT NULL,
  `goodsNum` int(11) NOT NULL DEFAULT '0',
  `goodsPrice` decimal(11,2) NOT NULL DEFAULT '0.00',
  `goodsSpecId` int(11) DEFAULT '0',
  `goodsSpecNames` varchar(500) DEFAULT NULL,
  `goodsName` varchar(50) NOT NULL,
  `goodsImg` varchar(150) NOT NULL,
  `extraJson` text,
  `goodsType` tinyint(4) NOT NULL DEFAULT '0',
  `commissionRate` decimal(11,2) DEFAULT '0.00',
  `goodsCode` varchar(20) DEFAULT NULL,
  `promotionJson` text,
  PRIMARY KEY (`id`),
  KEY `goodsId` (`goodsId`),
  KEY `orderId` (`orderId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
