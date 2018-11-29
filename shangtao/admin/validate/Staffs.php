<?php 
namespace shangtao\admin\validate;
use think\Validate;
use think\Db;
/**
 * 职员验证器
 */
class Staffs extends Validate{
	protected $rule = [
	    'loginName'  => 'require|max:20|checkLoginName:1',
	    'loginPwd'  => 'require|min:6',
        'staffName' => 'require|max:60',
        'workStatus' => 'require|in:0,1',
        'staffStatus' => 'require|in:0,1',
    ];
    
    protected $message = [
        'loginName.require' => '请输入登录账号',
        'loginName.max' => '登录账号不能超过20个字符',
        'loginPwd.require' => '请输入登录密码',
        'loginPwd.min' => '登录密码不能少于6个字符',
        'staffName.require' => '请输入职员名称',
        'staffName.max' => '职员名称不能超过20个字符',
        'workStatus.require' => '请选择工作状态',
        'workStatus.in' => '无效的工作状态值',
        'staffStatus.require' => '请选择账号状态',
        'staffStatus.in' => '无效的账号状态值',
    ];
    
    protected $scene = [
        'add'   =>  ['loginName','loginPwd','staffName','workStatus','staffStatus'],
        'edit'  =>  ['staffName','workStatus','staffStatus']
    ]; 
    
    protected function checkLoginName($value){
    	$where = [];
    	$where['dataFlag'] = 1;
    	$where['loginName'] = $value;
    	$rs = Db::name('staffs')->where($where)->count();
    	return ($rs==0)?true:'该登录账号已存在';
    }
}