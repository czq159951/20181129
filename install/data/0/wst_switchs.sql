SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `wst_switchs`;
CREATE TABLE `wst_switchs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `homeURL` varchar(255) DEFAULT NULL,
  `mobileURL` varchar(255) DEFAULT NULL,
  `wechatURL` varchar(255) DEFAULT NULL,
  `urlMark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

INSERT INTO `wst_switchs` VALUES ('1', 'home/goods/detail', 'mobile/goods/detail', 'wechat/goods/detail', null),
('2', 'home/goods/lists', 'mobile/goods/lists', 'mobile/goods/lists', null),
('4', 'home/shops/shopstreet', 'mobile/shops/shopstreet', 'wechat/shops/shopstreet', null),
('5', 'home/shops/selfshop', 'mobile/shops/selfshop', 'wechat/shops/selfshop', null),
('6', 'home/index/index', 'mobile/index/index', 'wechat/index/index', null),
('7', '', 'mobile/shops/shopgoodslist', 'wechat/shops/shopgoodslist', null),
('8', 'home/brands/index', 'mobile/brands/index', 'wechat/brands/index', null),
('9', '', 'mobile/news/view', 'wechat/news/view', null),
('10', 'home/users/login', 'mobile/users/login', '', null),
('11', 'home/users/regist', 'mobile/users/toregister', '', null),
('12', 'home/users/forgetpass', 'mobile/users/forgetpass', '', null),
('13', 'home/shops/home', 'mobile/shops/home', 'wechat/shops/home', null),
('14', '', 'mobile/goodscats/index', 'wechat/goodscats/index', null);
