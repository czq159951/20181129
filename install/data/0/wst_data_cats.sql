SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `wst_data_cats`;
CREATE TABLE `wst_data_cats` (
  `catId` int(11) NOT NULL AUTO_INCREMENT,
  `catName` varchar(255) NOT NULL,
  `dataFlag` tinyint(4) DEFAULT '1' COMMENT '数据有效标志1:有效 -1:无效',
  `catCode` varchar(255) NOT NULL COMMENT '数据分类代码',
  PRIMARY KEY (`catId`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;


INSERT INTO `wst_data_cats` VALUES ('1', '订单取消原因', '1', 'ORDER_CANCEL'),
('2', '订单拒收原因', '1', 'ORDER_REJECT'),
('3', '上传目录列表', '1', 'UPLOAD_DIRS'),
('4', '申请退款原因', '1', 'REFUND_TYPE'),
('5', '广告类型', '1', 'ADS_TYPE'),
('6', '系统消息模板', '1', 'TEMPLATE_SYS'),
('7', '邮件模板', '1', 'TEMPLATE_EMAIL'),
('8', '短信模板', '1', 'TEMPLATE_SMS'),
('10', '购买咨询', '1', 'COUSULT_TYPE'),
('11', '执照类型', '1', 'LICENSE_TYPE'),
('12', '法人证件类型', '1', 'LEGAL_LICENSE'),
('13', '纳税人类型', '1', 'TAXPAYER_TYPE'),
('14', '订单投诉原因', '1', 'ORDER_COMPLAINT'),
('15', '违规举报原因', '1', 'INFORMS_TYPE'),
('16', '店铺接收消息权限', '1', 'SHOP_MESSAGE');
