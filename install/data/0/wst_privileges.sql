SET FOREIGN_KEY_CHECKS=0;


DROP TABLE IF EXISTS `wst_privileges`;
CREATE TABLE `wst_privileges` (
  `privilegeId` int(11) NOT NULL AUTO_INCREMENT,
  `menuId` int(11) NOT NULL,
  `privilegeCode` varchar(20) NOT NULL,
  `privilegeName` varchar(30) NOT NULL,
  `isMenuPrivilege` tinyint(4) NOT NULL DEFAULT '0',
  `privilegeUrl` varchar(255) DEFAULT NULL,
  `otherPrivilegeUrl` text,
  `dataFlag` tinyint(4) NOT NULL DEFAULT '1',
  `isEnable` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`privilegeId`),
  UNIQUE KEY `privilegeCode` (`privilegeCode`),
  KEY `menuId` (`menuId`,`dataFlag`)
) ENGINE=InnoDB AUTO_INCREMENT=269 DEFAULT CHARSET=utf8;


INSERT INTO `wst_privileges` VALUES ('2', '2', 'XTGL_00', '查看系统管理', '1', null, null, '1', '1'),
('3', '3', 'CDGL_00', '查看菜单权限', '1', 'admin/menus/index', 'admin/menus/listQuery,admin/menus/get,admin/privileges/listQuery,admin/privileges/get', '1', '1'),
('4', '3', 'CDGL_01', '新增菜单', '0', 'admin/menus/add', null, '1', '1'),
('5', '3', 'CDGL_02', '编辑菜单', '0', 'admin/menus/edit', null, '1', '1'),
('6', '3', 'CDGL_03', '删除菜单', '0', 'admin/menus/del', null, '1', '1'),
('7', '3', 'QXGL_01', '新增权限', '0', 'admin/privileges/add', null, '1', '1'),
('8', '3', 'QXGL_02', '编辑权限', '0', 'admin/privileges/edit', null, '1', '1'),
('9', '3', 'QXGL_03', '删除菜单', '0', 'admin/privileges/del', null, '1', '1'),
('29', '4', 'JSGL_00', '查看角色管理', '1', 'admin/roles/index', 'admin/roles/pageQuery', '1', '1'),
('30', '4', 'JSGL_01', '新增角色', '0', 'admin/roles/add', 'admin/roles/toEdit,admin/privileges/listQueryByRole', '1', '1'),
('31', '4', 'JSGL_02', '编辑角色', '0', 'admin/roles/edit', 'admin/roles/toEdit,admin/privileges/listQueryByRole', '1', '1'),
('32', '4', 'JSGL_03', '删除角色', '0', 'admin/roles/del', null, '1', '1'),
('35', '1', 'SY_001', '查看平台', '0', '#', '', '1', '1'),
('36', '5', 'ZYGL_00', '查看职员管理', '1', 'admin/staffs/index', 'admin/staffs/pageQuery', '1', '1'),
('37', '5', 'ZYGL_01', '新增职员', '0', 'admin/staffs/add', 'admin/staffs/toAdd', '1', '1'),
('38', '5', 'ZYGL_02', '编辑职员', '0', 'admin/staffs/edit', 'admin/staffs/toEdit,admin/staffs/editPass', '1', '1'),
('39', '5', 'ZYGL_03', '删除职员', '0', 'admin/staffs/del', null, '1', '1'),
('40', '10', 'DHGL_00', '查看导航管理', '1', 'admin/navs/index', 'admin/navs/pageQuery', '1', '1'),
('41', '10', 'DHGL_01', '新增导航', '0', 'admin/nav/add', 'admin/nav/toEdit', '1', '1'),
('42', '11', 'GGGL_00', '查看广告管理', '1', 'admin/ads/index', 'admin/ads/pageQuery', '1', '1'),
('43', '12', 'ZFGL_00', '查看支付管理', '1', 'admin/payments/index', 'admin/payments/pageQuery', '1', '1'),
('44', '13', 'YHGL_00', '查看银行管理', '1', 'admin/banks/index', 'admin/banks/pageQuery', '1', '1'),
('45', '14', 'YQGL_00', '查看友情链接', '1', 'admin/friendlinks/index', 'admin/friendlinks/pageQuery', '1', '1'),
('46', '10', 'DHGL_02', '修改导航', '0', 'admin/nav/edit', 'admin/nav/toEdit,admin/nav/editiIsShow', '1', '1'),
('47', '10', 'DHGL_03', '删除导航', '0', 'admin/nav/del', null, '1', '1'),
('48', '11', 'GGGL_01', '新增广告', '0', 'admin/ads/add', 'admin/ads/toEdit', '1', '1'),
('49', '11', 'GGGL_02', '修改广告', '0', 'admin/ads/edit', 'admin/ads/toEdit,admin/ads/changeSort', '1', '1'),
('50', '11', 'GGGL_03', '删除广告', '0', 'admin/ads/del', null, '1', '1'),
('51', '12', 'ZFGL_02', '编辑支付', '0', 'admin/payments/edit', 'admin/payments/toEdit', '1', '1'),
('52', '12', 'ZFGL_03', '卸载支付', '0', 'admin/payments/del', null, '1', '1'),
('53', '13', 'YHGL_01', '新增银行', '0', 'admin/banks/add', null, '1', '1'),
('54', '13', 'YHGL_02', '修改银行', '0', 'admin/banks/edit', null, '1', '1'),
('55', '13', 'YHGL_03', '删除银行', '0', 'admin/banks/del', null, '1', '1'),
('56', '14', 'YQGL_01', '新增友情链接', '0', 'admin/friendlinks/add', 'admin/friendlinks/toEdit', '1', '1'),
('57', '14', 'YQGL_02', '修改友情链接', '0', 'admin/friendlinks/edit', 'admin/friendlinks/toEdit', '1', '1'),
('58', '14', 'YQGL_03', '删除友情链接', '0', 'admin/friendlinks/del', '', '1', '1'),
('59', '16', 'DQGL_00', '查看地区管理', '1', 'admin/areas/index', 'admin/areas/pageQuery', '1', '1'),
('60', '16', 'DQGL_01', '新增地区', '0', 'admin/areas/add', null, '1', '1'),
('61', '16', 'DQGL_02', '编辑地区', '0', 'admin/areas/edit', 'admin/areas/editiIsShow', '1', '1'),
('62', '16', 'DQGL_03', '删除地区', '0', 'admin/areas/del', null, '1', '1'),
('67', '24', 'SPFL_00', '查看商品分类', '1', 'admin/goodscats/index', 'admin/goodscats/pageQuery', '1', '1'),
('68', '19', 'HYDJ_00', '查看会员等级', '1', 'admin/userranks/index', 'admin/userranks/pageQuery', '1', '1'),
('69', '19', 'HYDJ_01', '新增会员等级', '0', 'admin/userranks/add', 'admin/userranks/toEdit', '1', '1'),
('70', '19', 'HYDJ_02', '编辑会员等级', '0', 'admin/userranks/edit', 'admin/userranks/toEdit', '1', '1'),
('71', '19', 'HYDJ_03', '删除会员等级', '0', 'admin/userranks/del', '', '1', '1'),
('72', '20', 'HYGL_00', '查看会员管理', '1', 'admin/users/index', 'admin/users/pageQuery,admin/logmoneys/tologmoneys,admin/logmoneys/pageQueryByUser,admin/logmoneys/pageQueryByShop,admin/userscores/touserscores,admin/userscores/pageQuery,admin/userscores/toAdd,admin/userscores/add', '1', '1'),
('73', '20', 'HYGL_01', '新增会员管理', '0', 'admin/users/add', 'admin/users/toEdit', '1', '1'),
('74', '20', 'HYGL_02', '编辑会员管理', '0', 'admin/users/edit', 'admin/users/toEdit', '1', '1'),
('75', '20', 'HYGL_03', '删除会员管理', '0', 'admin/users/del', '', '1', '1'),
('76', '24', 'SPFL_01', '新增商品分类', '0', 'admin/goodscats/add', null, '1', '1'),
('77', '24', 'SPFL_02', '编辑商品分类', '0', 'admin/goodscats/edit', 'admin/goodscats/editiIsFloor,admin/goodscats/editiIsShow,admin/goodscats/editName,admin/goodscats/editOrder', '1', '1'),
('78', '24', 'SPFL_03', '删除商品分类', '0', 'admin/goodscats/del', null, '1', '1'),
('79', '25', 'PPGL_00', '查看品牌管理', '1', 'admin/brands/index', 'admin/brands/pageQuery', '1', '1'),
('80', '25', 'PPGL_01', '新增品牌', '0', 'admin/brands/add', 'admin/brands/toEdit', '1', '1'),
('81', '25', 'PPGL_02', '编辑品牌', '0', 'admin/brands/edit', 'admin/brands/toEdit', '1', '1'),
('82', '25', 'PPGL_03', '删除品牌', '0', 'admin/brands/del', null, '1', '1'),
('83', '34', 'PJGL_00', '查看评价管理', '1', 'admin/goodsappraises/index', 'admin/goodsappraises/pageQuery', '1', '1'),
('84', '34', 'PJGL_02', '编辑评价', '0', 'admin/goodsappraises/edit', 'admin/goodsappraises/toEdit', '1', '1'),
('85', '34', 'PJGL_03', '删除评价', '0', 'admin/goodsappraises/del', null, '1', '1'),
('86', '6', 'DLRZ_00', '查看登录日志', '1', 'admin/Logstafflogins/index', 'admin/Logstafflogins/pageQuery', '1', '1'),
('87', '35', 'DDGL_00', '查看商城', '0', '#', '', '1', '1'),
('88', '7', 'CZRZ_00', '查看操作日志', '1', 'admin/logoperates/index', 'admin/logoperates/pageQuery,admin/logoperates/toView', '1', '1'),
('89', '42', 'GGWZ_00', '查看广告位置', '1', 'admin/adpositions/index', 'admin/adpositions/pageQuery', '1', '1'),
('90', '42', 'GGWZ_01', '新增广告位置', '0', 'admin/adpositions/add', 'admin/adpositions/toEdit', '1', '1'),
('91', '42', 'GGWZ_02', '编辑广告位置', '0', 'admin/adpositions/edit', 'admin/adpositions/toEdit', '1', '1'),
('92', '42', 'GGWZ_03', '删除广告位置', '0', 'admin/adpositions/del', '', '1', '1'),
('93', '31', 'WZGL_00', '查看文章管理', '1', 'admin/articles/index', 'admin/articles/pageQuery', '1', '1'),
('94', '31', 'WZGL_01', '新增文章', '0', 'admin/articles/add', 'admin/articles/toEdit', '1', '1'),
('95', '31', 'WZGL_02', '编辑文章', '0', 'admin/articles/edit', 'admin/articles/toEdit,admin/articles/editiIsShow', '1', '1'),
('96', '31', 'WZGL_03', '删除文章', '0', 'admin/articles/del', 'admin/articles/delByBatch', '1', '1'),
('97', '30', 'WZFL_00', '查看文章分类', '1', 'admin/articlecats/index', 'admin/articlecats/pageQuery', '1', '1'),
('98', '30', 'WZFL_01', '新增文章分类', '0', 'admin/articlecats/add', '', '1', '1'),
('99', '30', 'WZFL_02', '编辑文章分类', '0', 'admin/articlecats/edit', 'admin/articlecats/editiIsShow,admin/articlecats/editName', '1', '1'),
('100', '30', 'WZFL_03', '删除文章分类', '0', 'admin/articlecats/del', '', '1', '1'),
('101', '43', 'QTCD_00', '前台菜单管理', '1', 'admin/homemenus/index', 'admin/homemenus/pageQuery', '1', '1'),
('102', '21', 'ZHGL_00', '查看账号管理', '1', 'admin/users/accountindex', 'admin/users/pageQuery', '1', '1'),
('103', '9', 'SCPZ_00', '查看商城配置', '1', 'admin/sysconfigs/index', '', '1', '1'),
('104', '9', 'SCPZ_02', '编辑商城配置', '0', 'admin/sysconfigs/edit', '', '1', '1'),
('105', '44', 'RZGL_00', '查看认证', '1', 'admin/accreds/index', 'admin/accreds/pageQuery', '1', '1'),
('106', '44', 'RZGL_01', '新增认证', '0', 'admin/accreds/add', '', '1', '1'),
('107', '44', 'RZGL_02', '编辑认证', '0', 'admin/accreds/edit', '', '1', '1'),
('108', '44', 'RZGL_03', '删除认证', '0', 'admin/accreds/del', '', '1', '1'),
('109', '1', '3434', '3434', '0', '', '', '-1', '1'),
('110', '15', 'DQSZ_00', '查看地区管理', '0', '', '', '1', '1'),
('111', '8', 'SCSZ_00', '查看商城设置', '0', '', '', '1', '1'),
('112', '43', 'QTCD_01', '新增前台菜单', '0', 'admin/homemenus/add', '', '1', '1'),
('113', '43', 'QTCD_02', '编辑前台菜单', '0', 'admin/homemenus/edit', 'admin/homemenus/setToggle', '1', '1'),
('114', '43', 'QTCD_03', '删除前台菜单', '0', 'admin/homemenus/del', '', '1', '1'),
('115', '18', 'HYSZ_00', '查看会员管理', '0', '', '', '1', '1'),
('116', '29', 'WZSZ_00', '查看文章管理', '0', '', '', '1', '1'),
('117', '21', 'ZHGL_02', '编辑账号信息', '0', 'admin/users/editAccount', 'admin/users/changeUserStatus', '1', '1'),
('118', '39', 'DPSZ_00', '店铺管理', '0', '', '', '1', '1'),
('119', '38', 'PTRZ_00', '查看日志管理', '0', '', '', '1', '1'),
('120', '22', 'PTZY_00', '查看职员管理', '0', '', '', '1', '1'),
('121', '23', 'SPSZ_00', '查看商品管理', '0', '', '', '1', '1'),
('122', '45', 'DPSQ_00', '查看开店申请', '1', 'admin/shops/apply', 'admin/shops/pageQueryByApply', '1', '1'),
('123', '45', 'DPSQ_03', '删除开店申请', '0', 'admin/shops/delApply', '', '1', '1'),
('124', '45', 'DPSQ_04', '审核开店申请', '0', 'admin/shops/handleApply', 'admin/shops/toHandleApply', '1', '1'),
('125', '46', 'DPGL_00', '查看店铺管理', '1', 'admin/shops/index', 'admin/shops/pageQuery', '1', '1'),
('126', '46', 'DPGL_01', '新增店铺', '0', 'admin/shops/add', 'admin/shops/toAddByApply', '1', '1'),
('127', '46', 'DPGL_02', '编辑店铺', '0', 'admin/shops/edit', 'admin/shops/toEdit', '1', '1'),
('128', '46', 'DPGL_03', '删除店铺', '0', 'admin/shops/del', '', '1', '1'),
('129', '41', 'SCXX_00', '查看商城消息', '1', 'admin/messages/index', 'admin/messages/showFullMsg,admin/messages/pageQuery', '1', '1'),
('130', '41', 'SCXX_01', '发送商城消息', '0', 'admin/messages/add', 'admin/messages/userQuery', '1', '1'),
('131', '41', 'SCXX_03', '删除商城消息', '0', 'admin/messages/del', '', '1', '1'),
('132', '47', 'TYDP_00', '查看停用店铺', '1', 'admin/shops/stopIndex', 'admin/shops/pageStopQuery', '1', '1'),
('133', '47', 'TYDP_04', '启用店铺', '0', 'admin/shops/start', '', '-1', '1'),
('134', '32', 'SPGG_00', '查看商品规格', '1', 'admin/speccats/index', 'admin/speccats/pageQuery', '1', '1'),
('135', '32', 'SPGG_01', '新增商品规格', '0', 'admni/speccats/add', 'admni/speccats/toEdit', '1', '1'),
('136', '32', 'SPGG_02', '编辑商品规格', '0', 'admni/speccats/edit', 'admni/speccats/toEdit,admni/speccats/setToggle', '1', '1'),
('137', '32', 'SPGG_03', '删除商品规格', '0', 'admni/speccats/del', '', '1', '1'),
('138', '48', 'SPSX_00', '查看商品属性', '1', 'admin/attributes/index', 'admin/attributes/pageQuery', '1', '1'),
('139', '50', 'DDLB_00', '查看订单', '1', 'admin/orders/index', 'admin/orders/pageQuery', '1', '1'),
('140', '51', 'TSDD_00', '查看投诉订单', '1', 'admin/ordercomplains/index', 'admin/ordercomplains/view,admin/ordercomplains/pageQuery', '1', '1'),
('141', '52', 'TKDD_00', '查看退款订单', '1', 'admin/orderrefunds/refund', 'admin/orderrefunds/refundPageQuery,admin/orders/view', '1', '1'),
('142', '53', 'KDGL_00', '查看快递管理', '1', 'admin/express/index', 'admin/express/pageQuery', '1', '1'),
('143', '53', 'KDGL_01', '新增快递', '0', 'admin/express/add', '', '1', '1'),
('144', '53', 'KDGL_02', '编辑快递', '0', 'admin/express/edit', '', '1', '1'),
('145', '53', 'KDGL_03', '删除快递', '0', 'admin/express/del', '', '1', '1'),
('146', '33', 'SJSP_00', '查看已上架商品', '1', 'admin/goods/index', 'admin/goods/saleByPage', '1', '1'),
('147', '33', 'SJSP_04', '商品操作', '0', 'admin/goods/illegal', '', '1', '1'),
('148', '33', 'SJSP_03', '删除商品', '0', 'admin/goods/del', '', '1', '1'),
('149', '54', 'DSHSP_00', '查看待审核商品', '1', 'admin/goods/auditIndex', 'admin/goods/auditByPage', '1', '1'),
('150', '54', 'DSHSP_04', '商品审核', '0', 'admin/goods/allow', '', '1', '1'),
('151', '55', 'WGSP_00', '查看违规商品', '1', 'admin/goods/illegalIndex', '', '1', '1'),
('152', '58', 'SPTJ_00', '查看商品推荐', '1', 'admin/recommends/goods', 'admin/recommends/editgoods', '1', '1'),
('153', '59', 'DPTJ_00', '查看店铺推荐', '1', 'admin/recommends/shops', 'admin/recommends/editshops', '1', '1'),
('154', '59', 'DPTJ_04', '推荐操作', '0', 'admin/recommends/editshops', '', '1', '1'),
('155', '58', 'SPTJ_04', '推荐操作', '0', 'admin/recommends/editgoods', '', '1', '1'),
('156', '60', 'PPTJ_00', '查看品牌推荐', '1', 'admin/recommends/brands', 'admin/recommends/editbrands', '1', '1'),
('157', '60', 'PPTJ_04', '推荐操作', '0', 'admin/recommends/editbrands', '', '1', '1'),
('158', '36', 'TPKJ_00', '查看图片空间', '1', 'admin/images/index', 'admin/images/summary,admin/images/lists,admin/images/pageQuery,admin/images/checkImages', '1', '1'),
('159', '36', 'TPKJ_04', '图片管理', '0', 'admin/images/del', '', '1', '1'),
('160', '56', 'YYGL_00', '查看运营', '0', '', '', '1', '1'),
('161', '57', 'TJGL_00', '查看推荐管理', '0', '', '', '1', '1'),
('162', '49', 'DDSZ_00', '查看订单管理', '0', '', '', '1', '1'),
('163', '51', 'TSDD_04', '处理订单投诉', '0', 'admin/orderComplains/toHandle', 'admin/orderComplains/finalHandle,admin/orderComplains/deliverRespond', '1', '1'),
('164', '52', 'TKDD_04', '处理退款订单', '0', 'admin/orders/toRefund', 'admin/orders/orderRefund', '1', '1'),
('165', '55', 'WGSP_04', '商品审核', '0', 'admin/goods/allow', '', '1', '1'),
('166', '48', 'SPSX_01', '新增商品属性', '0', 'admin/attributes/add', '', '1', '1'),
('167', '48', 'SPSX_02', '编辑商品属性', '0', 'admin/attributes/edit', 'admin/attributes/setToggle', '1', '1'),
('168', '48', 'SPSX_03', '删除商品属性', '0', 'admin/attributes/del', '', '1', '1'),
('169', '2', 'HHQL_04', '清理缓存', '0', 'admin/index/clearcache', '', '1', '1'),
('170', '54', 'DSHSP_03', '删除商品', '0', 'admin/goods/del', '', '1', '1'),
('171', '55', 'WGSP_03', '删除商品', '0', 'admin/goods/del', '', '1', '1'),
('172', '2', 'ZYDP_00', '登录自营店铺', '0', 'admin/shops/inself', '', '1', '1'),
('173', '61', 'FGGL_00', '查看风格管理', '1', 'admin/styles/index', '', '1', '1'),
('174', '61', 'FGGL_04', '风格管理', '0', 'admin/styles/edit', '', '1', '1'),
('175', '62', 'CWGL_00', '查看财务管理', '0', '', '', '1', '1'),
('176', '63', 'TXSQ_00', '查看提现申请', '1', 'admin/cashdraws/index', 'admin/cashdraws/pageQuery,admin/cashdraws/toView', '1', '1'),
('177', '63', 'TXSQ_04', '处理提现申请', '0', 'admin/cashdraws/handle', 'admin/cashdraws/toHandle', '1', '1'),
('178', '64', 'JSSQ_00', '查看结算申请', '1', 'admin/settlements/index', 'admin/settlements/pageQuery,admin/settlements/toView,admin/settlements/pageGoodsQuery', '1', '1'),
('179', '64', 'JSSQ_04', '处理结算申请', '0', 'admin/settlements/handle', 'admin/settlements/toHandle', '1', '1'),
('180', '65', 'SJJS_00', '查看商家结算', '1', 'admin/settlements/toShopIndex', 'admin/settlements/pageShopQuery,admin/settlements/pageShopOrderQuery,admin/settlements/toOrders', '1', '1'),
('181', '65', 'SJJS_04', '生成结算单', '0', 'admin/settlements/generateSettleByShop', '', '1', '1'),
('190', '71', 'TJBB_00', '查看统计报表', '1', '', '', '1', '1'),
('191', '72', 'REPORTS_01', '查看销售排行', '1', 'admin/reports/toTopSaleGoods', 'admin/reports/topSaleGoodsByPage', '1', '1'),
('192', '74', 'REPORTS_03', '查看销售统计', '1', 'admin/reports/toStatSales', 'admin/reports/statSales', '1', '1'),
('193', '73', 'REPORTS_05', '查看会员统计', '1', 'admin/reports/toStatNewUser', 'admin/reports/statNewUser', '1', '1'),
('194', '75', 'REPORTS_06', '查看登录统计', '1', 'admin/reports/toStatUserLogin', 'admin/reports/statUserLogin', '1', '1'),
('195', '77', 'REPORTS_02', '店铺销售统计', '1', 'admin/reports/toTopShopSales', 'admin/reports/topShopSalesByPage', '1', '1'),
('196', '78', 'REPORTS_07', '查看客户端登录统计', '0', 'admin/reports/toStatLoginSrc', 'admin/reports/statLoginSrc', '1', '1'),
('197', '76', 'REPORTS_04', '查看销售订单统计', '1', 'admin/reports/toStatOrders', 'admin/reports/statOrders', '1', '1'),
('209', '82', 'KZGL_00', '查看扩展管理', '0', '', '', '1', '1'),
('210', '81', 'KZXX_00', '查看扩展', '0', '', '', '1', '1'),
('211', '83', 'CJGL_00', '查看插件', '1', 'admin/addons/index', 'admin/addons/pageQuery', '1', '1'),
('212', '83', 'CJGL_01', '设置插件', '0', 'admin/addons/add', '', '1', '1'),
('213', '83', 'CJGL_02', '安装插件', '0', 'admin/addons/install', '', '1', '1'),
('214', '83', 'CJGL_03', '卸载插件', '0', 'admin/addons/uninstall', '', '1', '1'),
('215', '83', 'CJGL_04', '启用插件', '0', 'admin/addons/enable', '', '1', '1'),
('216', '83', 'CJGL_05', '禁用插件', '0', 'admin/addons/disable', '', '1', '1'),
('217', '84', 'GZGL_00', '查看钩子', '1', 'admin/hooks/index', '', '1', '1'),
('218', '85', 'ZJGL_00', '查看资金管理', '1', 'admin/logmoneys/index', 'admin/logmoneys/pageQuery,admin/logmoneys/pageQueryByUser,admin/logmoneys/pageQueryByShop,admin/logmoneys/tologmoneys,admin/logmoneys/pageQuery', '1', '1'),
('219', '86', 'XXMB_00', '查看消息模板', '1', 'admin/templatemsgs/index', 'admin/templatemsgs/pageMsgQuery,admin/templatemsgs/pageEmailQuery,admin/templatemsgs/pageSMSQuery', '1', '1'),
('221', '86', 'XXMB_02', '编辑消息模板', '0', 'admin/templatemsgs/edit', 'admin/templatemsgs/toEdit', '1', '1'),
('229', '93', 'CXGL_00', '查看促销管理', '0', '', '', '1', '1'),
('230', '168', 'SPZX_00', '查看商品咨询', '1', 'admin/goodsconsult/index', 'admin/goodsconsult/pagequery', '1', '1'),
('231', '168', 'SPZX_02', '编辑商品咨询', '0', 'admin/goodsconsult/edit', 'admin/goodsconsult/toEdit', '1', '1'),
('232', '168', 'SPZX_03', '商品商品咨询', '0', 'admin/goodsconsult/del', '', '1', '1'),
('233', '169', 'SJGL_00', '查看系统数据管理', '1', 'admin/datas/index', 'admin/datacats/listquery', '1', '1'),
('234', '169', 'SJFL_01', '新增系统数据分类', '0', 'admin/datacats/add', '', '1', '1'),
('235', '169', 'SJFL_02', '修改系统数据分类', '0', 'admin/datacats/edit', '', '1', '1'),
('236', '169', 'SJFL_03', '删除系统数据分类', '0', 'admin/datacats/del', '', '1', '1'),
('237', '169', 'SJGL_01', '新增系统数据', '0', 'admin/datas/add', '', '1', '1'),
('238', '169', 'SJGL_02', '修改系统数据', '0', 'admin/datas/edit', '', '1', '1'),
('239', '169', 'SJGL_03', '删除系统数据', '0', 'admin/datas/del', '', '1', '1'),
('255', '170', 'ANGL_00', '移动端按钮列表', '1', 'admin/mobilebtns/index', 'admin/mobilebtns/pagequery', '1', '1'),
('256', '170', 'ANGL_01', '移动端按钮新增', '0', 'admin/mobileBtns/add', '', '1', '1'),
('257', '170', 'ANGL_02', '移动端按钮修改', '0', 'admin/mobileBtns/edit', '', '1', '1'),
('258', '170', 'ANGL_03', '移动端按钮删除', '0', 'admin/mobileBtns/del', '', '1', '1'),
('259', '172', 'CZGL_00', '查看充值管理', '1', 'admin/chargeitems/index', 'admin/chargeitems/pageQuery', '1', '1'),
('260', '172', 'CZGL_01', '新增充值项', '0', 'admin/chargeitems/add', 'admin/chargeitems/toEdit', '1', '1'),
('261', '172', 'CZGL_02', '修改充值项', '0', 'admin/chargeitems/edit', 'admin/chargeitems/toEdit', '1', '1'),
('262', '172', 'CZGL_03', '删除充值项', '0', 'admin/chargeitems/del', '', '1', '1'),
('263', '185', 'DXRZ_00', '查看短信日志', '1', 'admin/Logsms/index', 'admin/LogSms/pageQuery', '1', '1'),
('264', '188', 'JBSP_00', '举报商品管理', '1', 'admin/Informs/index', 'admin/Informs/pageQuery,admin/Informs/detail', '1', '1'),
('265', '195', 'YMQH_00', '查看页面转换', '1', 'admin/switchs/index', 'admin/switchs/pageQuery', '1', '1'),
('266', '195', 'YMQH_01', '新增页面转换', '0', 'admin/switchs/add', '', '1', '1'),
('267', '195', 'YMQH_02', '编辑页面转换', '0', 'admin/switchs/edit', 'admin/switchs/editUrl', '1', '1'),
('268', '195', 'YMQH_03', '删除页面转换', '0', 'admin/switchs/del', '', '1', '1');
