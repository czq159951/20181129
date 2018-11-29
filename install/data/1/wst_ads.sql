SET FOREIGN_KEY_CHECKS=0;


DROP TABLE IF EXISTS `wst_ads`;
CREATE TABLE `wst_ads` (
  `adId` int(11) NOT NULL AUTO_INCREMENT,
  `adPositionId` int(11) NOT NULL DEFAULT '0',
  `adFile` varchar(150) NOT NULL,
  `adName` varchar(100) NOT NULL,
  `adURL` varchar(255) DEFAULT NULL,
  `adStartDate` date NOT NULL,
  `adEndDate` date NOT NULL,
  `adSort` int(11) NOT NULL DEFAULT '0',
  `adClickNum` int(11) NOT NULL DEFAULT '0',
  `positionType` tinyint(4) DEFAULT '0',
  `dataFlag` tinyint(4) NOT NULL DEFAULT '1',
  `createTime` datetime NOT NULL,
  `subTitle` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`adId`),
  KEY `adPositionId` (`adPositionId`,`adStartDate`,`adEndDate`),
  KEY `adPositionId_2` (`adPositionId`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;


INSERT INTO `wst_ads` VALUES ('18', '34', 'upload/adspic/2016-09/57d294c289e74.jpg', '首页广告1', '', '2016-09-09', '2017-09-30', '0', '0', '1', '-1', '2016-09-09 18:54:09', null),
('28', '34', 'upload/adspic/2016-10/57f88f34d4550.jpg,upload/adspic/2016-10/57f88f34b5d07.jpg,upload/adspic/2016-10/57f88f3502862.jpg,upload/adspic/2016-10/57f88f35', '首页轮播广告', '', '2016-09-29', '2020-10-01', '0', '0', '1', '-1', '2016-09-29 19:49:11', null),
('29', '34', 'upload/adspic/2016-09/57ed003634bef.jpg', '22', '', '2016-09-29', '2020-09-18', '0', '0', '1', '-1', '2016-09-29 19:51:28', null),
('34', '35', 'upload/adspic/2016-09/57ee2a4cca962.jpg', '首页顶部广告', '', '2013-09-25', '2022-09-09', '1', '0', '1', '1', '2016-09-30 17:03:30', null),
('36', '34', 'upload/adspic/2016-10/57f8c25cc1e53.jpg', '首页轮播广告1', '', '2016-10-08', '2025-10-10', '11', '0', '1', '1', '2016-10-08 17:54:48', null),
('37', '34', 'upload/adspic/2016-10/57f8c2848c9d2.jpg', '首页轮播广告2', '', '2016-10-08', '2023-10-12', '12', '0', '1', '1', '2016-10-08 17:55:31', null),
('38', '34', 'upload/adspic/2016-10/57f8c2f22d96c.jpg', '首页轮播广告3', '', '2016-10-08', '2022-10-14', '13', '0', '1', '1', '2016-10-08 17:56:03', null),
('39', '34', 'upload/adspic/2016-10/57f8c306ec638.jpg', '首页轮播广告4', '', '2016-10-08', '2022-10-14', '14', '0', '1', '1', '2016-10-08 17:57:42', null),
('40', '290', 'upload/adspic/2017-06/59520643d6c51.jpg', '商家入驻广告', null, '2016-01-01', '2066-01-01', '0', '0', '1', '1', '2017-06-29 14:48:17', null),
('41', '290', 'upload/adspic/2017-06/5952047b41189.jpg', '商家入驻广告', null, '2016-01-01', '2066-01-01', '0', '0', '1', '1', '2017-06-29 14:48:17', null);
