DROP TABLE IF EXISTS `wst_auctions`;
DROP TABLE IF EXISTS `wst_auction_logs`;
DROP TABLE IF EXISTS `wst_auction_moneys`;
delete from `wst_datas` where dataVal='AUCTION_GOODS_ALLOW';
delete from `wst_datas` where dataVal='AUCTION_GOODS_REJECT';
delete from `wst_datas` where dataVal='WX_AUCTION_GOODS_ALLOW';
delete from `wst_datas` where dataVal='WX_AUCTION_GOODS_REJECT';
delete from `wst_datas` where dataVal='AUCTION_USER_RESULT';
delete from `wst_datas` where dataVal='AUCTION_SHOP_RESULT';
delete from `wst_datas` where dataVal='WX_AUCTION_USER_RESULT';
delete from `wst_datas` where dataVal='WX_AUCTION_SHOP_RESULT';

delete from `wst_template_msgs` where tplCode='AUCTION_GOODS_ALLOW';
delete from `wst_template_msgs` where tplCode='AUCTION_GOODS_REJECT';
delete from `wst_template_msgs` where tplCode='WX_AUCTION_GOODS_ALLOW';
delete from `wst_template_msgs` where tplCode='WX_AUCTION_GOODS_REJECT';
delete from `wst_template_msgs` where tplCode='AUCTION_USER_RESULT';
delete from `wst_template_msgs` where tplCode='AUCTION_SHOP_RESULT';
delete from `wst_template_msgs` where tplCode='WX_AUCTION_USER_RESULT';
delete from `wst_template_msgs` where tplCode='WX_AUCTION_SHOP_RESULT';

delete from `wst_crons` where croncode='autoAuctionEnd';
delete from `wst_navs` where navUrl='index.php/addon/auction-goods-lists.html';

delete from `wst_shop_message_cats` where msgCode = 'AUCTION_GOODS_REJECT';
delete from `wst_shop_message_cats` where msgCode = 'WX_AUCTION_GOODS_REJECT';
delete from `wst_shop_message_cats` where msgCode = 'AUCTION_GOODS_ALLOW';
delete from `wst_shop_message_cats` where msgCode = 'WX_AUCTION_GOODS_ALLOW';
delete from `wst_shop_message_cats` where msgCode = 'AUCTION_SHOP_RESULT';
delete from `wst_shop_message_cats` where msgCode = 'WX_AUCTION_SHOP_RESULT';

delete from `wst_switchs` where urlMark='auction';