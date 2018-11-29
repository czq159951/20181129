insert into `wst_datas`(catId,dataName,dataVal) values(6,'砍价商品审核通过','BARGAIN_GOODS_ALLOW'),
(6,'砍价商品审核不通过','BARGAIN_GOODS_REJECT'),
(9,'砍价商品审核通过','WX_BARGAIN_GOODS_ALLOW'),
(9,'砍价商品审核不通过','WX_BARGAIN_GOODS_REJECT');

insert into `wst_template_msgs`(tplType,tplCode,tplContent,tplDesc) values
(0,'BARGAIN_GOODS_ALLOW','您的砍价商品${GOODS}已审核通过。','1.变量说明：${GOODS}：商品名称。${TIME} ：当前时间。<br/>2.为空则不发送。'),
(0,'BARGAIN_GOODS_REJECT','您的砍价商品${GOODS}因【${REASON}】审核不通过。','1.变量说明：${GOODS}：商品名称。${TIME} ：当前时间。${REASON}：不通过原因。<br/>2.为空'),
(3,'WX_BARGAIN_GOODS_ALLOW','{{first.DATA}}\n商品名称：{{keyword1.DATA}}\n审核时间：{{keyword2.DATA}}\n{{remark.DATA}}','1.变量说明：${GOODS}：商品名称。${TIME} ：当前时间。<br/>2.为空则不发送。'),
(3,'WX_BARGAIN_GOODS_REJECT','{{first.DATA}}\n商品名称：{{keyword1.DATA}}\n失败原因：{{keyword2.DATA}}\n{{remark.DATA}}','1.变量说明：${GOODS}：商品名称。${TIME} ：当前时间。${REASON}：不通过原因。<br/>2.为空');

DROP TABLE IF EXISTS `wst_bargain_helps`;
CREATE TABLE `wst_bargain_helps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bargainJoinId` int(11) NOT NULL,
  `userName` varchar(50) NOT NULL,
  `userPhoto` varchar(150) NOT NULL,
  `openId` varchar(120) NOT NULL,
  `minusMoney` decimal(11,2) NOT NULL DEFAULT '0.00',
  `bargainId` int(11) NOT NULL,
  `createTime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `wst_bargain_users`;
CREATE TABLE `wst_bargain_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bargainId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `helpNum` int(11) DEFAULT '0',
  `currPrice` decimal(11,2) NOT NULL DEFAULT '0.00',
  `orderId` int(11) DEFAULT '0',
  `orderNo` varchar(50) DEFAULT NULL,
  `createTime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `wst_bargains`;
CREATE TABLE `wst_bargains` (
  `bargainId` int(11) NOT NULL AUTO_INCREMENT,
  `shopId` int(11) NOT NULL,
  `goodsId` int(11) NOT NULL,
  `startPrice` decimal(11,2) NOT NULL DEFAULT '0.00',
  `floorPrice` decimal(11,2) NOT NULL DEFAULT '0.00',
  `goodsStock` int(11) NOT NULL DEFAULT '1',
  `minusNum` int(11) NOT NULL DEFAULT '1',
  `minusType` tinyint(4) NOT NULL DEFAULT '0',
  `joinNum` int(11) NOT NULL DEFAULT '0',
  `orderNum` int(11) DEFAULT '0',
  `startTime` datetime NOT NULL,
  `endTime` datetime NOT NULL,
  `updateTime` datetime NOT NULL,
  `bargainStatus` tinyint(4) NOT NULL DEFAULT '0',
  `illegalRemarks` varchar(255) DEFAULT NULL,
  `dataFlag` tinyint(4) NOT NULL DEFAULT '1',
  `createTime` datetime NOT NULL,
  PRIMARY KEY (`bargainId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `wst_ad_positions`(positionType,positionName,positionWidth,positionHeight,dataFlag,positionCode,apSort) VALUES ('2', '移动版砍价广告', '375', '188', '1', 'wx-ads-bargain', '1021');

insert into `wst_shop_message_cats`(msgDataId,msgType,msgCode) values(1,1,'BARGAIN_GOODS_REJECT');
insert into `wst_shop_message_cats`(msgDataId,msgType,msgCode) values(1,4,'WX_BARGAIN_GOODS_REJECT');
insert into `wst_shop_message_cats`(msgDataId,msgType,msgCode) values(1,1,'BARGAIN_GOODS_ALLOW');
insert into `wst_shop_message_cats`(msgDataId,msgType,msgCode) values(1,4,'WX_BARGAIN_GOODS_ALLOW');