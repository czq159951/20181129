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


INSERT INTO `wst_ads` VALUES ('40', '290', 'upload/adspic/2017-06/59520643d6c51.jpg', '商家入驻广告', null, '2016-01-01', '2066-01-01', '0', '0', '1', '1', '2017-06-29 14:48:17', null),
('41', '290', 'upload/adspic/2017-06/5952047b41189.jpg', '商家入驻广告', null, '2016-01-01', '2066-01-01', '0', '0', '1', '1', '2017-06-29 14:48:17', null);
