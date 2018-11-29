DROP TABLE IF EXISTS `wst_bargains`;
DROP TABLE IF EXISTS `wst_bargain_users`;
DROP TABLE IF EXISTS `wst_bargain_helps`;
delete from `wst_datas` where dataVal='BARGAIN_GOODS_ALLOW';
delete from `wst_datas` where dataVal='BARGAIN_GOODS_REJECT';
delete from `wst_datas` where dataVal='WX_BARGAIN_GOODS_ALLOW';
delete from `wst_datas` where dataVal='WX_BARGAIN_GOODS_REJECT';

delete from `wst_template_msgs` where tplCode='BARGAIN_GOODS_ALLOW';
delete from `wst_template_msgs` where tplCode='BARGAIN_GOODS_REJECT';
delete from `wst_template_msgs` where tplCode='WX_BARGAIN_GOODS_ALLOW';
delete from `wst_template_msgs` where tplCode='WX_BARGAIN_GOODS_REJECT';

delete from `wst_ad_positions` where positionCode='wx-ads-bargain';
delete a from `wst_ads` a left join `wst_ad_positions` ap on ap.positionId=a.adPositionId where ap.positionCode='wx-ads-bargain';

delete from `wst_shop_message_cats` where msgCode = 'BARGAIN_GOODS_REJECT';
delete from `wst_shop_message_cats` where msgCode = 'WX_BARGAIN_GOODS_REJECT';
delete from `wst_shop_message_cats` where msgCode = 'BARGAIN_GOODS_ALLOW';
delete from `wst_shop_message_cats` where msgCode = 'WX_BARGAIN_GOODS_ALLOW';
