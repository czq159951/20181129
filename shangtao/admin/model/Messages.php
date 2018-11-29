<?php
namespace shangtao\admin\model;
use think\Db;
/**
 * 商城消息业务处理
 */
class Messages extends Base{
	/**
	 * 分页
	 */
	public function pageQuery(){
		$where[] = ['m.dataFlag','=','1'];
		$msgType = (int)input('msgType');  
		if($msgType >= 0)$where[] = ['msgType','=',$msgType];
		$msgContent = input('msgContent');
		if(!empty($msgContent))$where[] = ['msgContent','like',"%$msgContent%"];
		$rs = $this->alias('m')
		->field('m.*,u.loginName,s.shopName,st.loginName stName')
		->join('__USERS__ u','m.receiveUserId=u.userId','left')
		->join('__SHOPS__ s','m.receiveUserId=s.shopId','left')
		->join('__STAFFS__ st','m.sendUserId=st.staffId','left')
		->order('id desc')
		->where($where)
		->paginate(input('limit/d'))->toArray();
	    foreach ($rs['data'] as $key => $v){
         	$rs['data'][$key]['msgContent'] = WSTMSubstr(strip_tags($v['msgContent']),0,140);
        }
		return $rs;
	}
	public function getById($id){
		return $this->get(['id'=>$id,'dataFlag'=>1]);
	}
	/**
	 * 新增
	 */
	public function add(){
		$data = input('post.');
		// 图片记录
		$rule = '/src="\/(upload.*?)"/';
        preg_match_all($rule,$data['msgContent'],$result);
        // 获取src数组
        $imgs = $result[1];

		$data['createTime'] = date('Y-m-d H:i:s');
		$data['sendUserId'] = session('WST_STAFF.staffId');
		//判断发送对象
		if($data['sendType']=='theUser'){
			$ids = explode(',',$data['htarget']);
		}
		elseif($data['sendType']=='shop'){
			//获取所有店铺的id
			$ids = model('Shops')->getAllShopId();
		}elseif($data['sendType']=='users'){
			//获取所有用户id
			$ids = model('users')->getAllUserId();
		}
		WSTUnset($data,'id,sendType,htarget');//删除多余字段
		$list = [];
		//去重
		array_unique($ids);
		foreach($ids as $v)
		{
			$data['receiveUserId'] = $v;
			$data['msgType'] = 0;//后台手工发送消息
			$list[] = $data;
		}

		Db::startTrans();
		try{
			$result = $this->allowField(true)->saveAll($list);
			$id = $result[0]['id'];//新增的第一条消息id
        	if(false !== $result){
        	    //启用上传图片
			    WSTUseImages(1, $id, $imgs);
        		Db::commit();
        	    return WSTReturn("新增成功", 1);
        	}
		}catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('新增失败',-1);
        
	}
	/**
	 * 删除
	 */
    public function del(){
	    $id = input('post.id/d');
		$data = [];
		$data['dataFlag'] = -1;
	    $result = $this->update($data,['id'=>$id]);
        if(false !== $result){
        	return WSTReturn("删除成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}
	/**
	 * 批量删除
	 */
	public function batchDel(){
		$ids = input('ids');
		$ids = explode(',',WSTFormatIn(',',$ids));
		$data = [];
		$data['dataFlag'] = -1;
		$result = $this->where('id','in',$ids)->update($data);
		if(false !== $result){
			return WSTReturn("删除成功", 1);
		}else{
			return WSTReturn($this->getError(),-1);
		}
	}
}
