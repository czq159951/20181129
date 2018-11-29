<?php
namespace shangtao\common\model;
use think\Db;
/**
 * 消息队列
 */
class MessageQueues extends Base{
   /**
	* 新增
 	*/
	public function add($param){
		$shopId = $param["shopId"];
		$tplCode = $param["tplCode"];
		$msgcat = Db::name("shop_message_cats")->where(["msgCode"=>$tplCode])->find();
		if(!empty($msgcat)){
			$msgDataId = $msgcat["msgDataId"];
			$msgType = $param['msgType'];
			$dbo = Db::name("shop_roles sr")
					->join("__SHOP_USERS__ su","sr.id=su.roleId");
			if($msgType==4){
				$dbo = $dbo->join("__USERS__ u","su.userId=u.userId")
						   ->where("u.wxOpenId!=''");
			}
			$list = $dbo->where("su.dataFlag=1 and FIND_IN_SET(".$msgDataId.",sr.privilegeMsgs)")->field("su.userId")->select();
			$suser = array();
			if($msgType==4){
				$suser = Db::name("shop_users su")->join("__USERS__ u","su.userId=u.userId")
					->where(["su.shopId"=>$shopId,"su.roleId"=>0])
					->where("u.wxOpenId!=''")
					->field("su.userId")->find();
			}else{
				$suser = Db::name("shop_users")->where(["shopId"=>$shopId,"roleId"=>0])->field("userId")->find();
			}
			if(!empty($suser)){
				$list[] = $suser;
			}
			if($msgType==1){
				foreach ($list as $key => $user) {
					WSTSendMsg($user['userId'],$param['content'],$param['msgJson'],$msgType);
				}
			}else{
				$dataAll = [];
				$paramJson = $param["paramJson"];
				foreach ($list as $key => $user) {
					$data = [];
					$paramJson["userId"] = $user["userId"];
					$data['userId'] = $user["userId"];
					$data['msgType'] = $msgType;
					$data['msgCode'] = $param['tplCode'];
					$data['paramJson'] = json_encode($paramJson);
					$data['msgJson'] = $param['msgJson'];
					$data['createTime'] = date('Y-m-d H:i:s');
					$data['sendStatus'] = 0;
					$dataAll[] = $data;
				}
				Db::name("message_queues")->insertAll($dataAll);
			}
		}
		
		
	}
	/**
	 * 发送成功修改状态
	 */
	public function edit($id){
		$data = [];
		$data['sendStatus'] = 1;
		$result = $this->where(["id"=>$id])->save($data);
       	return $result;
	}

}
