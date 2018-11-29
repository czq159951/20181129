DROP TABLE IF EXISTS `wst_groupons`;
delete from `wst_datas` where dataVal='GROUPON_GOODS_ALLOW';
delete from `wst_datas` where dataVal='GROUPON_GOODS_REJECT';
delete from `wst_datas` where dataVal='WX_GROUPON_GOODS_ALLOW';
delete from `wst_datas` where dataVal='WX_GROUPON_GOODS_REJECT';
delete from `wst_template_msgs` where tplCode='GROUPON_GOODS_ALLOW';
delete from `wst_template_msgs` where tplCode='GROUPON_GOODS_REJECT';
delete from `wst_template_msgs` where tplCode='WX_GROUPON_GOODS_ALLOW';
delete from `wst_template_msgs` where tplCode='WX_GROUPON_GOODS_REJECT';
delete from `wst_navs` where navUrl='index.php/addon/groupon-goods-lists.html';

delete from `wst_shop_message_cats` where msgCode = 'GROUPON_GOODS_REJECT';
delete from `wst_shop_message_cats` where msgCode = 'WX_GROUPON_GOODS_REJECT';
delete from `wst_shop_message_cats` where msgCode = 'GROUPON_GOODS_ALLOW';
delete from `wst_shop_message_cats` where msgCode = 'WX_GROUPON_GOODS_ALLOW';

delete from `wst_switchs` where urlMark='groupon';