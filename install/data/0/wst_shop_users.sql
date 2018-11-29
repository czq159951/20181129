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


INSERT INTO `wst_shop_users` VALUES ('1', '1', '1', '0', '1');
