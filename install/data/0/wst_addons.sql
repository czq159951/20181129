
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `wst_addons`;
CREATE TABLE `wst_addons` (
  `addonId` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `config` text COMMENT '配置',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `version` varchar(20) DEFAULT '' COMMENT '版本号',
  `createTime` datetime NOT NULL COMMENT '安装时间',
  `dataFlag` tinyint(4) DEFAULT '1',
  `isConfig` tinyint(4) DEFAULT '0',
  `updateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`addonId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='插件表';
