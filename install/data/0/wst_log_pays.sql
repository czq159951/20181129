SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `wst_log_pays`;
CREATE TABLE `wst_log_pays` (
  `logId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) DEFAULT NULL COMMENT '0',
  `transId` varchar(50) DEFAULT NULL,
  `createTime` datetime DEFAULT NULL,
  PRIMARY KEY (`logId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

