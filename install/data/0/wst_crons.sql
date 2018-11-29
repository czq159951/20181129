SET FOREIGN_KEY_CHECKS=0;


DROP TABLE IF EXISTS `wst_crons`;
CREATE TABLE `wst_crons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cronName` varchar(100) NOT NULL,
  `cronCode` varchar(20) NOT NULL,
  `isEnable` tinyint(4) NOT NULL DEFAULT '0',
  `isRunning` tinyint(4) NOT NULL DEFAULT '0',
  `cronJson` text,
  `cronUrl` varchar(255) NOT NULL,
  `cronDesc` varchar(255) DEFAULT NULL,
  `cronCycle` tinyint(4) NOT NULL DEFAULT '0',
  `cronDay` tinyint(4) DEFAULT '1',
  `cronWeek` tinyint(4) DEFAULT '0',
  `cronHour` tinyint(4) DEFAULT NULL,
  `cronMinute` varchar(255) DEFAULT NULL,
  `runTime` varchar(20) DEFAULT NULL,
  `nextTime` varchar(20) DEFAULT NULL,
  `isRunSuccess` tinyint(4) NOT NULL DEFAULT '1',
  `author` varchar(255) DEFAULT NULL,
  `authorUrl` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;


INSERT INTO `wst_crons` VALUES ('2', '取消未付款订单', 'autoCancelNoPay', '0', '0', 'b:0;', 'admin/CronJobs/autoCancelNoPay.html', '取消超时未付款的订单', '2', '1', '0', '-1', '0,5,10,15,20,25,30,35,40,45,50,55', '2017-03-10 16:05:56', '2017-03-10 16:10:00', '1', 'shangtao', 'http://www.shangtao.net'),
('3', '自动收货', 'autoReceive', '0', '0', 'b:0;', 'admin/CronJobs/autoReceive.html', '将超时未收货的订单设置为已收货', '2', '1', '0', '0', '0', '2017-03-10 16:05:56', '2017-03-10 00:00:00', '1', 'shangtao', 'http://www.shangtao.net'),
('4', '自动好评', 'autoAppraise', '0', '0', 'b:0;', 'admin/CronJobs/autoAppraise.html', '将超时未评价的订单设置为好评', '2', '1', '0', '0', '0', '2017-03-10 16:05:56', '2017-03-10 00:00:00', '1', 'shangtao', 'http://www.shangtao.net'),
('5', '发送队列消息', 'autoSendMsg', '0', '0', 'b:0;', 'admin/CronJobs/autoSendMsg.html', '定时发送队列消息', '2', '1', '0', '-1', '0,5,10,15,20,25,30,35,40,45,50,55', '2017-12-10 16:05:56', '2017-12-10 16:10:00', '1', 'shangtao', 'http://www.shangtao.net'),
('6', '生成sitemap.xml', 'autoFileXml', '0', '0', 'b:0;', 'admin/CronJobs/autoFileXml.html', '定时生成sitemap.xml文件', '2', '1', '0', '-1', '0,5,10,15,20,25,30,35,40,45,50,55', '2017-12-10 16:05:56', '2017-12-10 16:10:00', '1', 'shangtao', 'http://www.shangtao.net');
