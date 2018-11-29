SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `wst_ad_positions`;
CREATE TABLE `wst_ad_positions` (
  `positionId` int(11) NOT NULL AUTO_INCREMENT,
  `positionType` tinyint(4) NOT NULL DEFAULT '0',
  `positionName` varchar(100) NOT NULL,
  `positionWidth` int(11) NOT NULL DEFAULT '0',
  `positionHeight` int(11) NOT NULL DEFAULT '0',
  `dataFlag` tinyint(4) NOT NULL DEFAULT '1',
  `positionCode` varchar(20) DEFAULT NULL,
  `apSort` int(11) NOT NULL,
  PRIMARY KEY (`positionId`),
  KEY `dataFlag` (`positionType`) USING BTREE,
  KEY `positionCode` (`positionCode`)
) ENGINE=InnoDB AUTO_INCREMENT=307 DEFAULT CHARSET=utf8;


INSERT INTO `wst_ad_positions` VALUES ('34', '1', '首页轮播广告', '1920', '420', '1', 'ads-index', '99'),
('35', '1', '首页顶部广告', '1200', '100', '1', 'index-top-ads', '100'),
('63', '1', '首页分层1F顶部广告', '400', '110', '1', 'ads-1-1', '0'),
('69', '1', '首页分层3F顶部广告', '400', '110', '1', 'ads-3-1', '0'),
('75', '1', '首页分层5F顶部广告', '400', '110', '1', 'ads-5-1', '0'),
('81', '1', '首页分层7F顶部广告', '400', '110', '1', 'ads-7-1', '0'),
('93', '1', '首页轮播广告', '1920', '420', '1', 'ads-index', '99'),
('94', '1', '首页顶部广告', '1200', '100', '1', 'index-top-ads', '100'),
('95', '1', '首页资讯上方广告', '210', '128', '1', 'index-art', '1'),
('290', '1', '商家入驻广告', '1920', '350', '1', 'ads-shop-apply', '0'),
('291', '1', '首页广告墙左上', '448', '237', '1', 'wall-left-top', '0'),
('292', '1', '首页广告墙左下', '448', '237', '1', 'wall-left-bottom', '0'),
('293', '1', '首页广告墙中间', '292', '480', '1', 'wall-center', '0'),
('294', '1', '首页广告墙右上', '448', '237', '1', 'wall-right-top', '0'),
('295', '1', '首页广告墙右下', '448', '237', '1', 'wall-right-bottom', '0'),
('296', '1', '品牌街下方左侧广告', '329', '500', '1', 'rbnh-left-ads', '0'),
('297', '1', '自营店铺1f广告', '1200', '320', '1', 'self-shop-f1', '0'),
('298', '1', '自营店铺2f广告', '1200', '320', '1', 'self-shop-f2', '0'),
('299', '1', '自营店铺3f广告', '1200', '320', '1', 'self-shop-f3', '0'),
('300', '1', '自营店铺4f广告', '1200', '320', '1', 'self-shop-f4', '0'),
('301', '1', '自营店铺5f广告', '1200', '320', '1', 'self-shop-f5', '0'),
('302', '1', '自营店铺6f广告', '1200', '320', '1', 'self-shop-f6', '0'),
('303', '1', '1F楼层左侧背景图', '260', '480', '1', 'index-floor-left-1', '0'),
('304', '1', '3F楼层左侧背景图', '260', '480', '1', 'index-floor-left-3', '0'),
('305', '1', '5F楼层左侧背景图', '260', '480', '1', 'index-floor-left-5', '0'),
('306', '1', '7F楼层左侧背景图', '260', '480', '1', 'index-floor-left-7', '0');
