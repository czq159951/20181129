SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `wst_invoices`;
CREATE TABLE `wst_invoices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `invoiceHead` varchar(255) NOT NULL COMMENT '发票抬头',
  `invoiceCode` varchar(255) NOT NULL DEFAULT '' COMMENT '纳税人识别号',
  `userId` int(10) unsigned NOT NULL COMMENT '用户id',
  `dataFlag` tinyint(4) NOT NULL DEFAULT '1' COMMENT '数据有效标记',
  `createTime` datetime NOT NULL COMMENT '数据创建时间',
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
