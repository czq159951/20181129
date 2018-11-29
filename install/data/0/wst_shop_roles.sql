SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `wst_shop_roles`;
CREATE TABLE `wst_shop_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopId` int(11) DEFAULT '0',
  `roleName` varchar(100) DEFAULT NULL,
  `privilegeMsgs` text,
  `privilegeUrls` text,
  `createTime` datetime DEFAULT NULL,
  `dataFlag` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
