SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `wst_shop_users`;
CREATE TABLE `wst_shop_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopId` int(11) NOT NULL DEFAULT '0',
  `userId` int(11) NOT NULL DEFAULT '0',
  `roleId` int(11) NOT NULL DEFAULT '0',
  `dataFlag` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;


INSERT INTO `wst_shop_users` VALUES ('1', '1', '1', '0', '1'),
('2', '2', '3', '0', '1'),
('3', '3', '4', '0', '1'),
('4', '4', '7', '0', '1'),
('5', '5', '8', '0', '1'),
('6', '6', '9', '0', '1'),
('7', '7', '10', '0', '1'),
('8', '8', '11', '0', '1'),
('9', '9', '12', '0', '1'),
('10', '10', '13', '0', '1'),
('11', '11', '14', '0', '1');
