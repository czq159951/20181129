DROP TABLE IF EXISTS `wst_reward_favourables`;
CREATE TABLE `wst_reward_favourables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rewardId` int(11) NOT NULL,
  `orderMoney` int(11) DEFAULT '0',
  `favourableJson` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `wst_reward_goods`;
CREATE TABLE `wst_reward_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rewardId` int(11) NOT NULL,
  `goodsId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `goodsId` (`goodsId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `wst_rewards`;
CREATE TABLE `wst_rewards` (
  `rewardId` int(11) NOT NULL AUTO_INCREMENT,
  `shopId` int(11) NOT NULL,
  `rewardTitle` varchar(255) NOT NULL COMMENT '活动标题',
  `startDate` date NOT NULL COMMENT '开始时间',
  `endDate` date NOT NULL COMMENT '结束时间',
  `rewardType` tinyint(4) NOT NULL DEFAULT '0' COMMENT '优惠方式',
  `useObjects` tinyint(4) NOT NULL DEFAULT '0' COMMENT '适用对象',
  `useObjectIds` text,
  `dataFlag` tinyint(4) NOT NULL DEFAULT '1',
  `createTime` datetime NOT NULL,
  PRIMARY KEY (`rewardId`),
  KEY `shopId` (`shopId`,`dataFlag`),
  KEY `startDate` (`startDate`,`endDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;