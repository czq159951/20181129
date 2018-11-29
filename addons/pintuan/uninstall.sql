
DROP TABLE IF EXISTS `wst_pintuans`;
DROP TABLE IF EXISTS `wst_pintuan_users`;
delete from `wst_datas` where dataVal='PINTUAN_GOODS_ALLOW';
delete from `wst_datas` where dataVal='PINTUAN_GOODS_REJECT';
delete from `wst_datas` where dataVal='WX_PINTUAN_GOODS_ALLOW';
delete from `wst_datas` where dataVal='WX_PINTUAN_GOODS_REJECT';
delete from `wst_template_msgs` where tplCode='PINTUAN_GOODS_ALLOW';
delete from `wst_template_msgs` where tplCode='PINTUAN_GOODS_REJECT';
delete from `wst_template_msgs` where tplCode='WX_PINTUAN_GOODS_ALLOW';
delete from `wst_template_msgs` where tplCode='WX_PINTUAN_GOODS_REJECT';
delete from `wst_template_msgs` where tplCode='WX_PINTUAN_REFUND';
delete from `wst_template_msgs` where tplCode='WX_PINTUAN_SUCCESS';
delete from `wst_crons` where croncode='autoPintuanRefund';

delete from `wst_shop_message_cats` where msgCode = 'PINTUAN_GOODS_REJECT';
delete from `wst_shop_message_cats` where msgCode = 'WX_PINTUAN_GOODS_REJECT';
delete from `wst_shop_message_cats` where msgCode = 'PINTUAN_GOODS_ALLOW';
delete from `wst_shop_message_cats` where msgCode = 'WX_PINTUAN_GOODS_ALLOW';