SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `wst_express`;
CREATE TABLE `wst_express` (
  `expressId` int(11) NOT NULL AUTO_INCREMENT,
  `expressName` varchar(50) NOT NULL,
  `dataFlag` tinyint(4) NOT NULL DEFAULT '1',
  `expressCode` varchar(50) DEFAULT '',
  PRIMARY KEY (`expressId`),
  KEY `dataFlag` (`dataFlag`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
