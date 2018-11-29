<?php
namespace addons\bargain\controller;

use think\addons\Controller;
use shangtao\common\model\UserAddress as M;
/**
 * 用户地址控制器
 */
class UserAddress extends Controller{
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
	/**
	 * 微信地址管理
	 */
	public function index(){
		$m = new M();
		$userId = session('WST_USER.userId');
		$addressList = $m->listQuery($userId);
		//获取省级地区信息
		$area = model('WeChat/Areas')->listQuery(0);
		$this->assign('area',$area);
		$this->assign('list', $addressList);
		$this->assign('type', (int)input('type'));
		$this->assign('addressId', (int)input('addressId'));//结算选中的地址
		return $this->fetch('/wechat/users/useraddress/list');
	}
	/**
	 * 手机地址管理
	 */
	public function moindex(){
		$m = new M();
		$userId = session('WST_USER.userId');
		$addressList = $m->listQuery($userId);
		//获取省级地区信息
		$area = model('WeChat/Areas')->listQuery(0);
		$this->assign('area',$area);
		$this->assign('list', $addressList);
		$this->assign('type', (int)input('type'));
		$this->assign('addressId', (int)input('addressId'));//结算选中的地址
		return $this->fetch('/mobile/users/useraddress/list');
	}
}
