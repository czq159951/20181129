SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `wst_informs`;
CREATE TABLE `wst_informs` (
  `informId` int(11) NOT NULL AUTO_INCREMENT,
  `informTargetId` int(11) NOT NULL,
  `goodId` int(11) NOT NULL,
  `shopId` int(11) NOT NULL,
  `informType` int(11) NOT NULL DEFAULT '1',
  `informContent` text,
  `informAnnex` text NOT NULL,
  `informTime` datetime NOT NULL,
  `informStatus` tinyint(4) NOT NULL,
  `respondContent` text,
  `finalHandleStaffId` int(11) DEFAULT NULL,
  `finalHandleTime` datetime DEFAULT NULL,
  `dataFlag` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`informId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
