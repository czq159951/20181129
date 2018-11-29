<?php
namespace shangtao\admin\model;
use shangtao\admin\validate\ChargeItems as validate;
use think\Db;
/**
 * 充值项业务处理
 */
class ChargeItems extends Base{
	/**
	 * 分页
	 */
	public function pageQuery(){
		$where = [];
		$where['dataFlag'] = 1;
		return $this->where($where)->field(true)->order('itemSort asc,id asc')->paginate(input('limit/d'));
	}
	public function getById($id){
		return $this->get(['id'=>$id,'dataFlag'=>1]);
	}
	/**
	 * 新增
	 */
	public function add(){
		$data = input('post.');
		$data["createTime"] = date("Y-m-d H:i:s");
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
		$Id = (int)input('post.id');
		$data = input('post.');
		$validate = new validate();
		if(!$validate->scene('edit')->check($data))return WSTReturn($validate->getError());
	    $result = $this->allowField(true)->save($data,['id'=>$Id]);
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
	    $result = $this->setField(['id'=>$id,'dataFlag'=>-1]);
        if(false !== $result){
        	return WSTReturn("删除成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}
	
	
}
