
alter table `wst_goods` drop column isDistribut;
alter table `wst_goods` drop column commission;

alter table `wst_shop_configs` drop column isDistribut;
alter table `wst_shop_configs` drop column distributType;
alter table `wst_shop_configs` drop column distributOrderRate;

alter table `wst_orders` drop column distributType;
alter table `wst_orders` drop column distributOrderRate;
alter table `wst_orders` drop column distributRate;
alter table `wst_orders` drop column totalCommission;

alter table `wst_order_goods` drop column commission;

alter table `wst_users` drop column distributMoney;
alter table `wst_users` drop column isBuyer;


DROP TABLE IF EXISTS `wst_distribut_moneys`;
DROP TABLE IF EXISTS `wst_distribut_users`;

delete from `wst_navs` where navUrl='index.php/addon/distribut-goods-glist';

delete from `wst_switchs` where urlMark='distribut';