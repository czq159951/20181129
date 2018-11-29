insert into `wst_datas`(catId,dataName,dataVal) values(6,'拍卖商品审核通过','AUCTION_GOODS_ALLOW'),
(6,'拍卖商品审核不通过','AUCTION_GOODS_REJECT'),
(6,'拍卖结果提醒(用户)','AUCTION_USER_RESULT'),
(6,'拍卖结果提醒(商家)','AUCTION_SHOP_RESULT'),
(9,'拍卖商品审核通过','WX_AUCTION_GOODS_ALLOW'),
(9,'拍卖商品审核不通过','WX_AUCTION_GOODS_REJECT'),
(9,'拍卖结果提醒(用户)','WX_AUCTION_USER_RESULT'),
(9,'拍卖结果提醒(商家)','WX_AUCTION_SHOP_RESULT');
insert into `wst_template_msgs`(tplType,tplCode,tplContent,tplDesc) values
(0,'AUCTION_GOODS_ALLOW','您的拍卖商品${GOODS}已审核通过。','1.变量说明：${GOODS}：商品名称。${TIME} ：当前时间。<br/>2.为空则不发送。'),
(0,'AUCTION_GOODS_REJECT','您的拍卖商品${GOODS}因【${REASON}】审核不通过。','1.变量说明：${GOODS}：商品名称。${TIME} ：当前时间。${REASON}：不通过原因。<br/>2.为空'),
(0,'AUCTION_USER_RESULT','您参加的拍卖活动【${GOODS}】拍卖结果为：${RESULT}，请留意。','1.变量说明：${GOODS}：商品名称。${JOIN_TIME} ：竞拍时间。${ASTART_TIME}：拍卖开始时间。${RESULT}：拍卖结果。<br/>2.为空则不发送。'),
(0,'AUCTION_SHOP_RESULT','你的拍卖活动【${GOODS}】拍卖结果为：${RESULT}，请留意。','1.变量说明：${GOODS}：商品名称。${ASTART_TIME}：拍卖开始时间。${RESULT}：拍卖结果。<br/>2.为空则不发送。'),
(3,'WX_AUCTION_GOODS_ALLOW','{{first.DATA}}\n商品名称：{{keyword1.DATA}}\n审核时间：{{keyword2.DATA}}\n{{remark.DATA}}','1.变量说明：${GOODS}：商品名称。${TIME} ：当前时间。<br/>2.为空则不发送。'),
(3,'WX_AUCTION_GOODS_REJECT','{{first.DATA}}\n商品名称：{{keyword1.DATA}}\n失败原因：{{keyword2.DATA}}\n{{remark.DATA}}','1.变量说明：${GOODS}：商品名称。${TIME} ：当前时间。${REASON}：不通过原因。<br/>2.为空'),
(3,'WX_AUCTION_USER_RESULT','{{first.DATA}}\n拍卖商品：{{keyword1.DATA}}\n参与时间：{{keyword2.DATA}}\n竞拍结果：{{keyword3.DATA}}\n{{remark.DATA}}','1.变量说明：${GOODS}：商品名称。${JOIN_TIME} ：竞拍时间。${ASTART_TIME}：拍卖开始时间。${RESULT}：拍卖结果。<br/>2.为空则不发送。'),
(3,'WX_AUCTION_SHOP_RESULT','{{first.DATA}}\n拍卖商品：{{keyword1.DATA}}\n参与时间：{{keyword2.DATA}}\n竞拍结果：{{keyword3.DATA}}\n{{remark.DATA}}','1.变量说明：${GOODS}：商品名称。${ASTART_TIME}：拍卖开始时间。${RESULT}：拍卖结果。<br/>2.为空则不发送。');

DROP TABLE IF EXISTS `wst_auction_moneys`;
CREATE TABLE `wst_auction_moneys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auctionId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `cautionMoney` int(11) NOT NULL DEFAULT '0',
  `cautionStatus` tinyint(4) NOT NULL DEFAULT '0',
  `createTime` datetime NOT NULL,
  `payType` varchar(50) DEFAULT '',
  `tradeNo` varchar(100) DEFAULT NULL,
  `moneyType` tinyint(4) DEFAULT '0',
  `lockCashMoney` decimal(11,2) DEFAULT '0.00',
  `refundStatus` tinyint(4) DEFAULT '0',
  `refundTime` datetime DEFAULT NULL,
  `refundTradeNo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `auctionId` (`auctionId`,`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `wst_auction_logs`;
CREATE TABLE `wst_auction_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auctionId` int(11) NOT NULL DEFAULT '0',
  `userId` int(11) NOT NULL,
  `payPrice` decimal(11,2) NOT NULL DEFAULT '0.00',
  `dataFlag` tinyint(4) DEFAULT '1',
  `isTop` tinyint(4) DEFAULT '0',
  `createTime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `auctionId` (`auctionId`,`payPrice`),
  KEY `auctionId_2` (`auctionId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `wst_auctions`;
CREATE TABLE `wst_auctions` (
  `auctionId` int(11) NOT NULL AUTO_INCREMENT,
  `shopId` int(11) NOT NULL,
  `goodsId` int(11) NOT NULL,
  `goodsName` varchar(50) DEFAULT NULL,
  `goodsImg` varchar(150) DEFAULT NULL,
  `goodsSeoKeywords` varchar(255) DEFAULT NULL,
  `goodsJson` text,
  `auctionPrice` decimal(11,2) NOT NULL DEFAULT '0.00',
  `currPrice` decimal(11,2) NOT NULL DEFAULT '0.00',
  `fareInc` int(11) NOT NULL DEFAULT '0',
  `cautionMoney` int(11) NOT NULL DEFAULT '0',
  `auctionNum` int(11) NOT NULL DEFAULT '0',
  `startTime` datetime NOT NULL,
  `endTime` datetime NOT NULL,
  `visitNum` int(11) NOT NULL DEFAULT '0',
  `auctionDesc` text,
  `auctionStatus` tinyint(4) DEFAULT '1',
  `illegalRemarks` varchar(255) DEFAULT NULL,
  `dataFlag` tinyint(4) NOT NULL,
  `updateTime` datetime NOT NULL,
  `createTime` datetime NOT NULL,
  `orderId` int(11) NOT NULL DEFAULT '0',
  `bidLogId` int(11) NOT NULL DEFAULT '0',
  `isPay` tinyint(4) DEFAULT '0',
  `isClose` tinyint(4) DEFAULT '0',
  `endPayTime` datetime DEFAULT NULL,
  PRIMARY KEY (`auctionId`),
  KEY `shopId` (`shopId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `wst_crons`(cronName,cronCode,isEnable,cronJson,cronUrl,cronDesc,cronCycle,cronDay,cronWeek,cronHour,cronMinute,runTime,nextTime,author,authorUrl) VALUES ('完成拍卖', 'autoAuctionEnd', '0', 'a:1:{i:0;a:3:{s:10:\"fieldLabel\";s:21:\"每次执行记录数\";s:9:\"fieldCode\";s:7:\"cronNum\";s:8:\"fieldVal\";s:1:\"5\";}}', 'addon/auction-cron-scanTask.html', '将到期的拍卖活动结束并计算出拍卖胜出者', '2', '9', '4', '-1', '0,5,10,15,20,25,30,35,40,45,50,55', '2017-03-10 16:05:59', '2017-03-10 16:10:00', 'WSTMart', 'http://www.wstmart.net');

INSERT INTO `wst_navs`(navType,navTitle,navUrl,isShow,isOpen,navSort,createTime) VALUES ('0', '拍卖活动', 'index.php/addon/auction-goods-lists.html', '1', '0', '0', '2017-02-25 10:32:01');

insert into `wst_shop_message_cats`(msgDataId,msgType,msgCode) values(1,1,'AUCTION_GOODS_REJECT');
insert into `wst_shop_message_cats`(msgDataId,msgType,msgCode) values(1,4,'WX_AUCTION_GOODS_REJECT');
insert into `wst_shop_message_cats`(msgDataId,msgType,msgCode) values(1,1,'AUCTION_GOODS_ALLOW');
insert into `wst_shop_message_cats`(msgDataId,msgType,msgCode) values(1,4,'WX_AUCTION_GOODS_ALLOW');
insert into `wst_shop_message_cats`(msgDataId,msgType,msgCode) values(2,1,'AUCTION_SHOP_RESULT');
insert into `wst_shop_message_cats`(msgDataId,msgType,msgCode) values(2,4,'WX_AUCTION_SHOP_RESULT');

INSERT INTO `wst_switchs`(homeURL,mobileURL,wechatURL,urlMark) VALUES ('auction/goods/lists', 'auction/goods/molists', 'auction/goods/wxlists', 'auction');
INSERT INTO `wst_switchs`(homeURL,mobileURL,wechatURL,urlMark) VALUES ('auction/goods/detail', 'auction/goods/modetail', 'auction/goods/wxdetail', 'auction');

