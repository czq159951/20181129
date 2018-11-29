<?php
namespace shangtao\app\model;
use think\Model;
/**
 * 基础类
 */
use think\Db;
class Base extends Model{
	/**
	 * 获取登录用户的id
	 */
	public function getUserId(){
		$tokenId = input('tokenId');
		if($tokenId=='')return 0;
		return (int)Db::name('app_session')->where("tokenId='{$tokenId}'")->value('userId');
	}
}
