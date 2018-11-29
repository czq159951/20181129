SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `wst_shop_extras`;
CREATE TABLE `wst_shop_extras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shopId` int(11) NOT NULL,
  `businessLicenceType` tinyint(4) DEFAULT '0',
  `businessLicence` varchar(100) DEFAULT NULL,
  `licenseAddress` varchar(500) DEFAULT NULL,
  `businessAreaPath` varchar(100) DEFAULT NULL,
  `legalPersonName` varchar(100) DEFAULT NULL,
  `establishmentDate` date DEFAULT NULL,
  `businessStartDate` date DEFAULT NULL,
  `businessEndDate` date DEFAULT NULL,
  `isLongbusinessDate` tinyint(4) DEFAULT '0',
  `registeredCapital` decimal(11,0) DEFAULT '0',
  `empiricalRange` varchar(1000) DEFAULT NULL,
  `legalCertificateType` tinyint(4) DEFAULT '0',
  `legalCertificate` varchar(50) DEFAULT NULL,
  `legalCertificateStartDate` date DEFAULT NULL,
  `legalCertificateEndDate` date DEFAULT NULL,
  `isLonglegalCertificateDate` tinyint(4) DEFAULT '0',
  `legalCertificateImg` varchar(150) DEFAULT NULL,
  `businessLicenceImg` varchar(150) DEFAULT NULL,
  `bankAccountPermitImg` varchar(150) DEFAULT NULL,
  `organizationCode` varchar(100) DEFAULT NULL,
  `organizationCodeStartDate` date DEFAULT NULL,
  `organizationCodeEndDate` date DEFAULT NULL,
  `isLongOrganizationCodeDate` tinyint(4) DEFAULT '0',
  `organizationCodeImg` varchar(150) DEFAULT NULL,
  `taxRegistrationCertificateImg` varchar(450) DEFAULT NULL,
  `taxpayerQualificationImg` varchar(150) DEFAULT NULL,
  `taxpayerType` tinyint(4) DEFAULT '0',
  `taxpayerNo` varchar(100) DEFAULT NULL,
  `applyLinkMan` varchar(50) DEFAULT NULL,
  `applyLinkTel` varchar(50) DEFAULT NULL,
  `applyLinkEmail` varchar(50) DEFAULT NULL,
  `isInvestment` tinyint(4) NOT NULL DEFAULT '0',
  `investmentStaff` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;


INSERT INTO `wst_shop_extras` VALUES ('1', '1', '0', null, null, null, null, null, null, null, '0', '0', null, '0', null, null, null, '0', null, null, null, null, null, null, '0', null, null, null, '0', null, null, null, null, '0', null);