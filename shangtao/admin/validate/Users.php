<?php 
namespace shangtao\admin\validate;
use think\Validate;
use think\Db;
/**
 * 会员验证器
 */
class Users extends Validate{
	protected $rule = [
        'loginName'  =>'require|max:30|checkLoginName:1',
    ];
    
    protected $message = [
        'loginName.require' => '请输入账号',
        'loginName.max' => '账号不能超过10个字符',
    ];

    protected $scene = [
        'add'   =>  ['loginName'],
    ]; 

    protected function checkLoginName($value){
    	$where = [];
    	$where['dataFlag'] = 1;
    	$where['loginName'] = $value;
        if((int)input('userId')>0){
            $where['userId'] = ['<>',(int)input('post.userId')];
        }
    	$rs = Db::name('users')->where($where)->count();
    	return ($rs==0)?true:'该登录账号已存在';
    }

}