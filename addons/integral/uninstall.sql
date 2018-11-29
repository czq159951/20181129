DROP TABLE IF EXISTS `wst_integral_goods`;
delete from `wst_navs` where navUrl='addon/integral-goods-lists.html';
delete from `wst_ad_positions` where positionCode='ads-integral';


delete from `wst_switchs` where urlMark='integral';