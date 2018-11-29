SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `wst_message_queues`;
CREATE TABLE `wst_message_queues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) DEFAULT NULL,
  `msgType` tinyint(4) DEFAULT '0',
  `paramJson` text,
  `msgJson` text,
  `createTime` datetime DEFAULT NULL,
  `sendTime` datetime DEFAULT NULL,
  `sendStatus` tinyint(4) DEFAULT '0',
  `msgCode` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
