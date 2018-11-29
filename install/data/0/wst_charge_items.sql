SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `wst_charge_items`;
CREATE TABLE `wst_charge_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chargeMoney` int(11) DEFAULT '0',
  `giveMoney` decimal(11,1) DEFAULT '0.0',
  `itemSort` int(11) DEFAULT '0',
  `dataFlag` tinyint(4) DEFAULT '1',
  `createTime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;