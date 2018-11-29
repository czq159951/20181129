<?php
namespace shangtao\admin\model;
use shangtao\admin\validate\AdPositions as validate;
use think\Db;
/**
 * 广告位置业务处理
 */
class AdPositions extends Base{
	protected $pk = 'positionId';
	/**
	 * 分页
	 */
	public function pageQuery(){
		$positionType = (int)input('positionType');
		$key = input('key');
		$where[] = ['dataFlag','=',1];
        if($positionType>0)$where[] = ['positionType','=',$positionType];
        if($key !='')$where[] = ['positionCode','like','%'.$key.'%'];
		return $this->where($where)->field(true)->order('apSort asc,positionId asc')->paginate(input('limit/d'));
	}
	public function getById($id){
		return $this->get(['positionId'=>$id,'dataFlag'=>1]);
	}
	/**
	 * 新增
	 */
	public function add(){
		$data = input('post.');
		WSTUnset($data,'positionId');
		$validate = new validate();
		if(!$validate->scene('add')->check($data))return WSTReturn($validate->getError());
		$result = $this->allowField(true)->save($data);
        if(false !== $result){
        	return WSTReturn("新增成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}
    /**
	 * 编辑
	 */
	public function edit(){
		$data = input('post.');
		$Id = (int)input('post.positionId');
		$validate = new validate();
		if(!$validate->scene('edit')->check($data))return WSTReturn($validate->getError());
	    $result = $this->allowField(true)->save($data,['positionId'=>$Id]);
        if(false !== $result){
        	return WSTReturn("编辑成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}
	/**
	 * 删除
	 */
    public function del(){
	    $id = (int)input('post.id/d');
	    $result = $this->setField(['positionId'=>$id,'dataFlag'=>-1]);
        if(false !== $result){
        	return WSTReturn("删除成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}
	/**
	* 获取广告位置
	*/
	public function getPositon($typeId){
		return $this->where(['positionType'=>$typeId,'dataFlag'=>1])->order('apSort asc,positionId asc')->select();
	}
	
}
