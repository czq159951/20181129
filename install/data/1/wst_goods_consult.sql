SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `wst_goods_consult`;
CREATE TABLE `wst_goods_consult` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goodsId` int(10) unsigned NOT NULL COMMENT '商品id',
  `userId` int(10) unsigned DEFAULT NULL COMMENT '用户id',
  `consultType` tinyint(3) unsigned DEFAULT NULL COMMENT '咨询类别',
  `consultContent` varchar(500) DEFAULT NULL COMMENT '咨询内容',
  `createTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '咨询时间',
  `reply` varchar(255) NOT NULL DEFAULT '' COMMENT '商家回复',
  `replyTime` datetime DEFAULT NULL COMMENT '回复时间',
  `dataFlag` tinyint(4) DEFAULT '1' COMMENT '数据有效标志',
  `isShow` tinyint(4) DEFAULT '1' COMMENT '是否显示数据',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;