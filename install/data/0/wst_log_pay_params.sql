SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `wst_log_pay_params`;
CREATE TABLE `wst_log_pay_params` (
  `logId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) DEFAULT NULL COMMENT '0',
  `transId` varchar(50) DEFAULT NULL,
  `paramsVa` varchar(500) DEFAULT NULL,
  `payFrom` varchar(20) DEFAULT NULL,
  `createTime` datetime DEFAULT NULL,
  PRIMARY KEY (`logId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

