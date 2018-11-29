<?php
namespace addons\pintuan\controller;

use think\addons\Controller;
use shangtao\common\model\UserAddress as M;
use addons\pintuan\model\Pintuans as PM;
/**
 * 用户地址控制器
 */
class UserAddress extends Controller{
	protected $addonStyle = 'default';
	public function __construct(){
		parent::__construct();
		$m = new PM();
        $data = $m->getConf('Pintuan');
        $this->addonStyle = ($data['addonsStyle']=='')?'default':$data['addonsStyle'];
        $this->assign("addonStyle",$this->addonStyle);
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
		return $this->fetch($this->addonStyle.'/wechat/index/useraddress/list');
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
		return $this->fetch($this->addonStyle.'/mobile/index/useraddress/list');
	}
}
