<?php
namespace shangtao\mobile\controller;
use shangtao\common\model\UserAddress as M;
/**
 * 用户地址控制器
 */
class UserAddress extends Base{
	// 前置方法执行列表
    protected $beforeActionList = [
        'checkAuth'
    ];
	/**
	 * 地址管理
	 */
	public function index(){
		$m = new M();
		$userId = session('WST_USER.userId');
		$addressList = $m->listQuery($userId);
		//获取省级地区信息
		$area = model('areas')->listQuery(0);
		$this->assign('area',$area);
		$this->assign('list', $addressList);
		$this->assign('type', (int)input('type'));
		$this->assign('addressId', (int)input('addressId'));//结算选中的地址
		return $this->fetch('users/useraddress/list');
	}
	/**
	 * 获取地址信息
	 */
	public function getById(){
		$m = new M();
		return $m->getById(input('post.addressId/d'));
	}
	/**
	 * 设置为默认地址
	 */
	public function setDefault(){
		$m = new M();
		return $m->setDefault();
	}
	/**
     * 新增/编辑地址
     */
    public function edits(){
        $m = new M();
        if((int)input('addressId')>0){
        	$rs = $m->edit();
        }else{
        	$rs = $m->add();
        } 
        return $rs;
    }
    /**
     * 删除地址
     */
    public function del(){
    	$m = new M();
    	return $m->del();
    }
}
