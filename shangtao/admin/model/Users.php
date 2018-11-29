<?php
namespace shangtao\admin\model;
use shangtao\admin\validate\Users as validate;
use think\Db;
/**
 * 会员业务处理
 */
class Users extends Base{
	protected $pk = 'userId';
	/**
	 * 分页
	 */
	public function pageQuery(){
		/******************** 查询 ************************/
		$where[] = ['u.dataFlag','=',1];
		$lName = input('loginName1');
		$phone = input('loginPhone');
		$email = input('loginEmail');
		$uType = input('userType');
		$uStatus = input('userStatus1');
		$sort = input('sort');
		if(!empty($lName))
			$where[] = ['loginName|s.shopName','like',"%$lName%"];
		if(!empty($phone))
			$where[] = ['userPhone','like',"%$phone%"];
		if(!empty($email))
			$where[] = ['userEmail','like',"%$email%"];
		if(is_numeric($uType))
			$where[] = ['userType','=',"$uType"];
		if(is_numeric($uStatus))
			$where[] = ['userStatus','=',"$uStatus"];
		$order = 'u.userId desc';
		if($sort){
			$sort =  str_replace('.',' ',$sort);
			$order = $sort;
		}
		/********************* 取数据 *************************/
		$rs = $this->alias('u')->join('__SHOPS__ s','u.userId=s.userId and s.dataFlag=1','left')->where($where)
					->field(['u.userId','u.rechargeMoney','loginName','userName','userType','userPhone','userEmail','userScore','u.createTime','userStatus','lastTime','s.shopId','userMoney','u.lockMoney'])
					->order($order)
					->paginate(input('limit/d'))
					->toArray();
	    foreach ($rs['data'] as $key => $v) {
	    	$r = WSTUserRank($v['userScore']);
	    	$rs['data'][$key]['rank'] = $r['rankName'];
	    }
		return $rs;
	}
	public function getById($id){
		return $this->get(['userId'=>$id]);
	}
	/**
	 * 新增
	 */
	public function add(){
		$data = input('post.');
		$data['createTime'] = date('Y-m-d H:i:s');
		$data["loginSecret"] = rand(1000,9999);
    	$data['loginPwd'] = md5($data['loginPwd'].$data['loginSecret']);
    	WSTUnset($data,'userId,userType,userScore,userTotalScore,lastIP,lastTime,userMoney,lockMoney,dataFlag,rechargeMoney');
    	Db::startTrans();
		try{
			$validate = new validate();
		    if(!$validate->scene('add')->check($data))return WSTReturn($validate->getError());
			$result = $this->allowField(true)->save($data);
			$id = $this->userId;
	        if(false !== $result){
	        	hook("adminAfterAddUser",["userId"=>$id]);
	        	WSTUseImages(1, $id, $data['userPhoto']);
	        	Db::commit();
	        	return WSTReturn("新增成功", 1);
	        }
		}catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('新增失败',-1);
        }	
	}
    /**
	 * 编辑
	 */
	public function edit(){
		$Id = (int)input('post.userId');
		$data = input('post.');
		$u = $this->where('userId',$Id)->field('loginSecret')->find();
		if(empty($u))return WSTReturn('无效的用户');
		//判断是否需要修改密码
		if(empty($data['loginPwd'])){
			unset($data['loginPwd']);
		}else{
    		$data['loginPwd'] = md5($data['loginPwd'].$u['loginSecret']);
		}
		Db::startTrans();
		try{
			if(isset($data['userPhoto'])){
			    WSTUseImages(1, $Id, $data['userPhoto'], 'users', 'userPhoto');
			}
			
			WSTUnset($data,'loginName,createTime,userId,userType,userScore,userTotalScore,lastIP,lastTime,userMoney,lockMoney,dataFlag,rechargeMoney');
		    $result = $this->allowField(true)->save($data,['userId'=>$Id]);
	        if(false !== $result){
	        	hook("adminAfterEditUser",["userId"=>$Id]);
	        	Db::commit();
	        	return WSTReturn("编辑成功", 1);
	        }
		}catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('编辑失败',-1);
        }
	}
	/**
	 * 删除
	 */
    public function del(){
	    $id = (int)input('post.id');
	    if($id==1){
	    	return WSTReturn('无法删除自营店铺账号',-1);
	    }
	    Db::startTrans();
	    try{
		    $data = [];
			$data['dataFlag'] = -1;
		    $result = $this->update($data,['userId'=>$id]);
	        if(false !== $result){
	        	//删除店铺信息
	        	model('shops')->delByUserId($id);
	        	hook("adminAfterDelUser",["userId"=>$id]);
	        	WSTUnuseImage('users','userPhoto',$id);
	        	// 删除app端、小程序端对应用户登录凭证
	        	delAppToken($id);
	        	Db::commit();
	        	return WSTReturn("删除成功", 1);
	        }
	    }catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('删除失败',-1);
        }
	}
	/**
	* 是否启用
	*/
	public function changeUserStatus($id, $status){
		$result = $this->update(['userStatus'=>(int)$status],['userId'=>(int)$id]);
		if(false !== $result){
        	return WSTReturn("删除成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}
	/**
	* 根据用户名查找用户
	*/
	public function getByName($name){
		return $this->field(['userId','loginName'])->where('loginName','like',"%$name%")->select();
	}
	/**
	* 获取所有用户id
	*/
	public function getAllUserId()
	{
		return $this->where('dataFlag',1)->column('userId');
	}
	/**
	* 重置支付密码
	*/
	public function resetPayPwd(){
		$Id = (int)input('post.userId');
		$loginSecret = $this->where('userId',$Id)->value('loginSecret');
		// 重置支付密码为6个6
		$payPwd = md5('666666'.$loginSecret);
		$result = $this->where('userId',$Id)->setField('payPwd',$payPwd);
		if(false !== $result){
        	return WSTReturn("重置成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}

	/**
	 * 根据用户账号查找用户信息
	 */
	public function getUserByKey(){
		$key = input('key');
		$user = $this->where([['loginName|userPhone|userEmail','=',$key],['dataFlag','=',1]])->find();
        if(empty($user))return WSTReturn('找不到用户',-1);
        $shop = model('shops')->where([['userId','=',$user->userId],['dataFlag','=',1]])->find();
        if(!empty($shop))return WSTReturn('该用户已存在关联的店铺信息',-1);
        return WSTReturn('',1,['loginName'=>$user->loginName,'userId'=>$user->userId]);
	}
	
}
