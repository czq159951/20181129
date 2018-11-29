SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `wst_payments`;
CREATE TABLE `wst_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payCode` varchar(20) DEFAULT NULL,
  `payName` varchar(255) DEFAULT NULL,
  `payDesc` text,
  `payOrder` int(11) DEFAULT '0',
  `payConfig` text,
  `enabled` tinyint(4) DEFAULT '0',
  `isOnline` tinyint(4) DEFAULT '0',
  `payFor` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payCode` (`payCode`,`enabled`,`isOnline`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


INSERT INTO `wst_payments` VALUES ('1', 'cod', '货到付款', '开通城市', '1', '', '1', '0', '1,2,3,4'),
('2', 'alipays', '支付宝(及时到帐)', '支付宝(及时到帐)', '4', '', '0', '1', '1,2,4'),
('3', 'weixinpays', '微信支付', '微信支付', '0', '', '0', '1', '1,2,3'),
('4', 'wallets', '余额支付', '余额支付', '5', '', '1', '1', '1,2,3,4');
