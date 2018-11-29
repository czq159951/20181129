DROP TABLE IF EXISTS `wst_shop_decoration_blocks`;
CREATE TABLE `wst_shop_decoration_blocks` (
  `blockId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `decorationId` int(10) unsigned NOT NULL,
  `shopId` int(10) unsigned NOT NULL,
  `blockLayout` varchar(50) NOT NULL ,
  `blockContent` text,
  `blockModuleType` varchar(50) DEFAULT NULL ,
  `blockFullWidth` tinyint(3) unsigned DEFAULT NULL,
  `blockSort` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`blockId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `wst_shop_decorations`;
CREATE TABLE `wst_shop_decorations` (
  `decorationId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `decorationName` varchar(50) NOT NULL,
  `shopId` int(10) unsigned NOT NULL ,
  `decorationSetting` varchar(500) DEFAULT NULL ,
  `decorationNav` varchar(5000) DEFAULT NULL,
  `decorationBanner` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`decorationId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

alter table `wst_shop_configs` add userDecoration tinyint(4) default 0;;