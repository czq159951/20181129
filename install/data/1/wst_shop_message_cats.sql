SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `wst_shop_message_cats`;
CREATE TABLE `wst_shop_message_cats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msgDataId` int(11) DEFAULT '0',
  `msgType` tinyint(4) DEFAULT '0',
  `msgCode` varchar(100) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

INSERT INTO `wst_shop_message_cats` VALUES ('1', '1', '1', 'GOODS_ALLOW'),
('2', '1', '1', 'GOODS_REJECT'),
('3', '1', '1', 'AUCTION_GOODS_REJECT'),
('4', '1', '1', 'AUCTION_GOODS_ALLOW'),
('5', '2', '1', 'AUCTION_SHOP_RESULT'),
('6', '1', '1', 'BARGAIN_GOODS_REJECT'),
('7', '1', '1', 'BARGAIN_GOODS_ALLOW'),
('8', '1', '1', 'GROUPON_GOODS_REJECT'),
('9', '1', '1', 'GROUPON_GOODS_ALLOW'),
('10', '1', '1', 'PINTUAN_GOODS_REJECT'),
('11', '1', '1', 'PINTUAN_GOODS_ALLOW'),
('12', '2', '1', 'ORDER_SUBMIT'),
('13', '2', '1', 'ORDER_REMINDER'),
('14', '2', '1', 'ORDER_HASPAY'),
('15', '2', '1', 'ORDER_SHOP_AUTO_DELIVERY'),
('16', '4', '1', 'ORDER_SHOP_PAY_TIMEOUT'),
('17', '3', '1', 'ORDER_NEW_COMPLAIN'),
('18', '3', '1', 'ORDER_HANDLED_COMPLAIN'),
('19', '5', '1', 'ORDER_SHOP_REFUND'),
('20', '5', '1', 'ORDER_REFUND_CONFER'),
('21', '5', '1', 'ORDER_REJECT'),
('22', '5', '1', 'ORDER_CANCEL'),
('23', '6', '1', 'SHOP_SETTLEMENT'),
('24', '7', '1', 'ORDER_APPRAISES'),
('25', '8', '1', 'ORDER_RECEIVE'),
('26', '8', '1', 'ORDER_ATUO_RECEIVE'),
('27', '9', '1', 'SHOP_GOODS_INFORM');
