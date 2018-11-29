<?php
namespace shangtao\weapp\model;
use think\Model;
use think\Db;
/**
 * 基础模型器
 */
class Base extends Model{
	/**
	 * 获取登录用户的id
	 */
	public function getUserId(){
		$tokenId = input('tokenId');
		if($tokenId=='')return 0;
		return (int)Db::name('weapp_session')->where("tokenId='{$tokenId}'")->value('userId');
	}
	/**
	 * 获取用户对应的shopId
	 */
	public function getShopId($userId){
		return (int)Db::name('shop_users')->where(['userId'=>$userId])->value('shopId');
	}
}