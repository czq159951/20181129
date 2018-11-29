alter table `wst_goods` add isDistribut int default 0;
alter table `wst_goods` add commission decimal(11,2) default 0;

alter table `wst_shop_configs` add isDistribut tinyint default 0;
alter table `wst_shop_configs` add distributType tinyint default 1;
alter table `wst_shop_configs` add distributOrderRate int default 0;


alter table `wst_orders` add distributType tinyint default 0;
alter table `wst_orders` add distributOrderRate int default 0;
alter table `wst_orders` add distributRate varchar(20);
alter table `wst_orders` add totalCommission decimal(11,2) default 0;

alter table `wst_order_goods` add commission decimal(11,2) default 0;

alter table `wst_users` add distributMoney decimal(11,2) default 0;
alter table `wst_users` add isBuyer tinyint default 0;

DROP TABLE IF EXISTS `wst_distribut_moneys`;
CREATE TABLE `wst_distribut_moneys` (
  `moneyId` int(11) NOT NULL AUTO_INCREMENT,
  `shopId` int(11) DEFAULT '0',
  `orderId` int(11) DEFAULT '0',
  `userId` int(11) DEFAULT '0',
  `buyerId` int(11) DEFAULT NULL,
  `remark` varchar(200) DEFAULT NULL,
  `distributType` tinyint(4) DEFAULT '0',
  `dataId` int(11) DEFAULT '0',
  `money` decimal(11,2) DEFAULT '0.00',
  `distributMoney` decimal(11,2) DEFAULT '0.00',
  `createTime` datetime DEFAULT NULL,
  `moneyType` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`moneyId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `wst_distribut_users`;
CREATE TABLE `wst_distribut_users` (
  `distributId` int(11) NOT NULL AUTO_INCREMENT,
  `grandpaId` int(11) DEFAULT '0',
  `parentId` int(11) DEFAULT '0',
  `userId` int(11) DEFAULT '0',
  `createTime` datetime DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`distributId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `wst_navs`(navType,navTitle,navUrl,isShow,isOpen,navSort,createTime) VALUES ('0', '分销商品', 'index.php/addon/distribut-goods-glist', '1', '0', '0', '2015-07-12 20:10:00');

INSERT INTO `wst_switchs`(homeURL,mobileURL,wechatURL,urlMark) VALUES ('distribut/goods/glist', 'distribut/distribut/mobiledistributgoods', 'distribut/distribut/wechatdistributgoods', 'distribut');
