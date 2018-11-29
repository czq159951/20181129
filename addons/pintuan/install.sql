insert into  `wst_datas`(catId,dataName,dataVal) values(6,'拼团商品审核通过','PINTUAN_GOODS_ALLOW'),
(6,'拼团商品审核不通过','PINTUAN_GOODS_REJECT'),
(9,'拼团商品审核通过','WX_PINTUAN_GOODS_ALLOW'),
(9,'拼团商品审核不通过','WX_PINTUAN_GOODS_REJECT'),
(9,'拼团失败退款通知','WX_PINTUAN_REFUND'),
(9,'拼团成功通知','WX_PINTUAN_SUCCESS');
insert into `wst_template_msgs`(tplType,tplCode,tplContent,tplDesc) values(0,'PINTUAN_GOODS_ALLOW','您的拼团商品${GOODS}【${GOODS_SN}】已审核通过。','1.变量说明：${GOODS}：商品名称。${GOODS_SN}：商品编号。${TIME} ：当前时间。<br/>2.为空则不发送。'),
(0,'PINTUAN_GOODS_REJECT','您的拼团商品${GOODS}【${GOODS_SN}】因【${REASON}】审核不通过。','1.变量说明：${GOODS}：商品名称。${GOODS_SN}：商品编号。${TIME} ：当前时间。${REASON}：不通过原因。<br/>2.为空'),
(3,'WX_PINTUAN_GOODS_ALLOW','{{first.DATA}}\n商品名称：{{keyword1.DATA}}\n审核时间：{{keyword2.DATA}}\n{{remark.DATA}}','1.变量说明：${GOODS}：商品名称。${GOODS_SN}：商品编号。${TIME} ：当前时间。<br/>2.为空则不发送。'),
(3,'WX_PINTUAN_GOODS_REJECT','{{first.DATA}}\n商品名称：{{keyword1.DATA}}\n失败原因：{{keyword2.DATA}}\n{{remark.DATA}}','1.变量说明：${GOODS}：商品名称。${GOODS_SN}：商品编号。${TIME} ：当前时间。${REASON}：不通过原因。<br/>2.为空'),
(3,'WX_PINTUAN_REFUND','{{first.DATA}}\n拼团商品：{{keyword1.DATA}}\n商品金额：{{keyword2.DATA}}\n退款金额：{{keyword3.DATA}}\n{{remark.DATA}}','1.变量说明：${GOODS}：商品名称。${TUAN_MONEY}：商品金额。${FREUND_MONEY} ：退款金额。<br/>2.为空'),
(3,'WX_PINTUAN_SUCCESS','{{first.DATA}}\n商品：{{keyword1.DATA}}\n拼单成员：{{keyword2.DATA}}\n发货时间：{{keyword3.DATA}}\n{{remark.DATA}}','1.变量说明：${GOODS}：商品名称。${MEMBERS}：拼单成员。${TIME} ：发货时间。<br/>2.为空');
DROP TABLE IF EXISTS `wst_pintuans`;
CREATE TABLE `wst_pintuans` (
  `tuanId` int(11) NOT NULL AUTO_INCREMENT,
  `shopId` int(11) DEFAULT '0',
  `goodsId` int(11) DEFAULT '0',
  `goodsName` varchar(50) DEFAULT NULL,
  `goodsImg` varchar(150) DEFAULT NULL,
  `goodsSeoKeywords` varchar(255) DEFAULT NULL,
  `goodsJson` text,
  `tuanPrice` decimal(11,2) NOT NULL DEFAULT '0',
  `goodsNum` int(11) DEFAULT '0',
  `tuanTime` int(11) DEFAULT '24',
  `saleNum` int(11) DEFAULT '0',
  `tuanNum` int(11) DEFAULT '0',
  `orderNum` int(11) DEFAULT '0',
  `tuanDesc` text,
  `tuanStatus` tinyint(4) DEFAULT '0',
  `illegalRemarks` text,
  `dataFlag` tinyint(4) DEFAULT '0',
  `createTime` datetime DEFAULT NULL,
  `updateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`tuanId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `wst_pintuan_users`;
CREATE TABLE `wst_pintuan_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) DEFAULT '0',
  `tuanId` int(11) DEFAULT '0',
  `shopId` int(11) DEFAULT '0',
  `orderId` int(11) DEFAULT '0',
  `orderNo` varchar(20) DEFAULT NULL,
  `goodsId` int(11) DEFAULT '0',
  `tuanNo` int(11) DEFAULT '0',
  `goodsNum` int(11) DEFAULT '0',
  `tuanStatus` tinyint(4) DEFAULT '1',
  `isHead` tinyint(4) DEFAULT '0',
  `needNum` int(11) DEFAULT '0',
  `areaId` int(11) DEFAULT '0',
  `areaIdPath` varchar(100) DEFAULT NULL,
  `orderType` tinyint(4) DEFAULT '0',
  `goodsMoney` decimal(11,2) DEFAULT '0.00',
  `totalMoney` decimal(11,2) DEFAULT '0.00',
  `realTotalMoney` decimal(11,2) DEFAULT '0.00',
  `deliverType` tinyint(4) DEFAULT '0',
  `deliverMoney` decimal(11,2) DEFAULT NULL,
  `payType` tinyint(4) DEFAULT '0',
  `payFrom` varchar(20) DEFAULT NULL,
  `isPay` tinyint(4) DEFAULT '0',
  `userName` varchar(20) DEFAULT NULL,
  `userAddress` varchar(255) NOT NULL,
  `userPhone` char(11) DEFAULT NULL,
  `orderScore` int(11) NOT NULL DEFAULT '0',
  `isInvoice` tinyint(4) NOT NULL DEFAULT '0',
  `invoiceClient` varchar(255) DEFAULT NULL,
  `orderRemarks` varchar(255) DEFAULT NULL,
  `orderSrc` tinyint(4) NOT NULL DEFAULT '0',
  `needPay` decimal(11,2) DEFAULT '0.00',
  `payRand` int(11) DEFAULT '1',
  `orderunique` varchar(50) DEFAULT NULL,
  `useScore` int(11) DEFAULT '0',
  `scoreMoney` decimal(11,2) DEFAULT '0.00',
  `commissionFee` decimal(11,2) DEFAULT '0.00',
  `commissionRate` decimal(11,2) DEFAULT '0.00',
  `tradeNo` varchar(100) DEFAULT NULL,
  `createTime` datetime DEFAULT NULL,
  `extraJson` text,
  `lockCashMoney` decimal(11,2) DEFAULT '0.00',
  `refundStatus` tinyint(4) NOT NULL DEFAULT '0',
  `refundTime` datetime DEFAULT NULL,
  `refundTradeNo` varchar(100) DEFAULT NULL,
  `dataFlag` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

INSERT INTO `wst_crons`(cronName,cronCode,isEnable,cronJson,cronUrl,cronDesc,cronCycle,cronDay,cronWeek,cronHour,cronMinute,runTime,nextTime,author,authorUrl) VALUES ('拼团失败退款', 'autoPintuanRefund', '1', 'b:0;', 'addon/pintuan-cron-tuanRefund.html', '到期拼团失败的退款', '2', '0', '0', '-1', '0,5,10,15,20,25,30,35,40,45,50,55', '2017-11-03 16:20:13', '2017-11-03 16:25:00','shangtao', 'http://www.shangtao.net');

insert into `wst_shop_message_cats`(msgDataId,msgType,msgCode) values(1,1,'PINTUAN_GOODS_REJECT');
insert into `wst_shop_message_cats`(msgDataId,msgType,msgCode) values(1,4,'WX_PINTUAN_GOODS_REJECT');
insert into `wst_shop_message_cats`(msgDataId,msgType,msgCode) values(1,1,'PINTUAN_GOODS_ALLOW');
insert into `wst_shop_message_cats`(msgDataId,msgType,msgCode) values(1,4,'WX_PINTUAN_GOODS_ALLOW');
