insert into `wst_datas`(catId,dataName,dataVal) values(6,'团购商品审核通过','GROUPON_GOODS_ALLOW'),
(6,'团购商品审核不通过','GROUPON_GOODS_REJECT'),
(9,'团购商品审核通过','WX_GROUPON_GOODS_ALLOW'),
(9,'团购商品审核不通过','WX_GROUPON_GOODS_REJECT');
insert into `wst_template_msgs`(tplType,tplCode,tplContent,tplDesc) values(0,'GROUPON_GOODS_ALLOW','您的团购商品${GOODS}【${GOODS_SN}】已审核通过。','1.变量说明：${GOODS}：商品名称。${GOODS_SN}：商品编号。${TIME} ：当前时间。<br/>2.为空则不发送。'),
(0,'GROUPON_GOODS_REJECT','您的团购商品${GOODS}【${GOODS_SN}】因【${REASON}】审核不通过。','1.变量说明：${GOODS}：商品名称。${GOODS_SN}：商品编号。${TIME} ：当前时间。${REASON}：不通过原因。<br/>2.为空'),
(3,'WX_GROUPON_GOODS_ALLOW','{{first.DATA}}\n商品名称：{{keyword1.DATA}}\n审核时间：{{keyword2.DATA}}\n{{remark.DATA}}','1.变量说明：${GOODS}：商品名称。${GOODS_SN}：商品编号。${TIME} ：当前时间。<br/>2.为空则不发送。'),
(3,'WX_GROUPON_GOODS_REJECT','{{first.DATA}}\n商品名称：{{keyword1.DATA}}\n失败原因：{{keyword2.DATA}}\n{{remark.DATA}}','1.变量说明：${GOODS}：商品名称。${GOODS_SN}：商品编号。${TIME} ：当前时间。${REASON}：不通过原因。<br/>2.为空');
DROP TABLE IF EXISTS `wst_groupons`;
CREATE TABLE `wst_groupons` (
  `grouponId` int(11) NOT NULL AUTO_INCREMENT,
  `shopId` int(11) NOT NULL,
  `goodsId` int(11) NOT NULL,
  `grouponPrice` decimal(11,2) NOT NULL DEFAULT '0.00',
  `grouponNum` int(11) NOT NULL DEFAULT '0',
  `orderNum` int(11) NOT NULL DEFAULT '0',
  `startTime` datetime NOT NULL,
  `endTime` datetime NOT NULL,
  `grouponDesc` text,
  `grouponStatus` tinyint(4) DEFAULT '1',
  `illegalRemarks` varchar(255) DEFAULT NULL,
  `dataFlag` tinyint(4) NOT NULL,
  `updateTime` datetime NOT NULL,
  `createTime` datetime NOT NULL,
  PRIMARY KEY (`grouponId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
INSERT INTO `wst_navs`(navType,navTitle,navUrl,isShow,isOpen,navSort,createTime) VALUES ('0', '团购活动', 'index.php/addon/groupon-goods-lists.html', '1', '0', '0', '2017-02-16 10:32:01');

insert into `wst_shop_message_cats`(msgDataId,msgType,msgCode) values(1,1,'GROUPON_GOODS_REJECT');
insert into `wst_shop_message_cats`(msgDataId,msgType,msgCode) values(1,4,'WX_GROUPON_GOODS_REJECT');
insert into `wst_shop_message_cats`(msgDataId,msgType,msgCode) values(1,1,'GROUPON_GOODS_ALLOW');
insert into `wst_shop_message_cats`(msgDataId,msgType,msgCode) values(1,4,'WX_GROUPON_GOODS_ALLOW');

INSERT INTO `wst_switchs`(homeURL,mobileURL,wechatURL,urlMark) VALUES ('groupon/goods/lists', 'groupon/goods/molists', 'groupon/goods/wxlists', 'groupon');
INSERT INTO `wst_switchs`(homeURL,mobileURL,wechatURL,urlMark) VALUES ('groupon/goods/detail', 'groupon/goods/modetail', 'groupon/goods/wxdetail', 'groupon');