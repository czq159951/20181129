<?php
namespace shangtao\admin\model;
use shangtao\admin\validate\Payments as validate;
/**
 * 支付管理业务处理
 */
class Payments extends Base{
	/**
	 * 分页
	 */
	public function pageQuery(){
		return $this->field(true)->order('id desc')->paginate(input('limit/d'));
	}
	public function getById($id){
		return $this->get(['id'=>$id]);
	}
	
    /**
	 * 编辑
	 */
	public function edit(){
		$Id = input('post.id/d',0);
		//获取数据
		$data = input('post.');
		$data["payConfig"] = isset($data['payConfig'])?json_encode($data['payConfig']):"";
		$data['enabled']=1;
		$validate = new validate();
		if(!$validate->scene('edit')->check($data))return WSTReturn($validate->getError());
	    $result = $this->allowField(true)->save($data,['id'=>$Id]);
        if(false !== $result){
        	cache('WST_PAY_SRC',null);
        	return WSTReturn("编辑成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}
	/**
	 * 删除
	 */
    public function del(){
	    $id = input('post.id/d',0);
		$data = [];
		$data['enabled'] = 0;
	    $result = $this->update($data,['id'=>$id]);
        if(false !== $result){
        	cache('WST_PAY_SRC',null);
        	return WSTReturn("卸载成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}
	
}
