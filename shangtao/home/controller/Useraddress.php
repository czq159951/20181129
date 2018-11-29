<?php
namespace shangtao\home\controller;
use shangtao\common\model\LogSms;
/**
 * 用户地址控制器
 */

class Useraddress extends Base{
    protected $beforeActionList = ['checkAuth'];
    /**
    * 设置为默认地址
    */
    public function setDefault(){
        return model('userAddress')->setDefault();
    }
	public function index(){
		return $this->fetch('users/useraddress/list');
	}
    /**
    * 获取地址信息
    * 1.购物车结算有引用
    */
    public function listQuery(){
        //获取用户信息
        $userId = (int)session('WST_USER.userId');
        if(!$userId){
            return WSTReturn('未登录', -1);
        }
        $list = model('Home/userAddress')->listQuery($userId);
        return WSTReturn('', 1,$list);
    }
	
	/**
	* 跳去修改地址
	*/
	public function edit(){
		$m = model('userAddress');
		$id=(int)input('id');
        $data = $m->getById($id);
        $data = empty($data)?$m->getEModel('user_address'):$data;
        //获取省级地区信息
        $area1 = model('Areas')->listQuery(0);
        $this->assign(['data'=>$data,
        			   'area1'=>$area1]);
		return $this->fetch('users/useraddress/edit');
	}
	/**
     * 新增
     */
    public function add(){
        $m = model('userAddress');
        $rs = $m->add();
        return $rs;
    }
	/**
    * 修改
    */
    public function toEdit(){
        $m = model('userAddress');
        $rs = $m->edit();
        return $rs;
    }
    /**
    * 删除
    */
    public function del(){
    	$m = model('userAddress');
        $rs = $m->del();
        return $rs;
    }
    
    /**
     * 获取用户地址
     */
    public function getById(){
    	$m = model('userAddress');
        $id=(int)input('id');
        $data = $m->getById($id);
        return WSTReturn('', 1,$data);
    }
}
