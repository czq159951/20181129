<?php
namespace shangtao\admin\model;
use shangtao\admin\validate\Banks as validate;
/**
 * 页面转换业务处理
 */
class Switchs extends Base{
	/**
	 * 分页
	 */
	public function pageQuery(){
		return $this->order('id desc')->paginate(input('limit/d'));
	}
	public function getById($id){
		return $this->get(['id'=>$id]);
	}
	/**
	 * 列表
	 */
	public function listQuery(){
		return $this->select();
	}
	/**
	 * 新增
	 */
	public function add(){
		$data = ['homeURL'=>input('post.homeURL'),'mobileURL'=>input('post.mobileURL'),'wechatURL'=>input('post.wechatURL')];
		if(($data['homeURL'] == '' && $data['mobileURL'] == '') || ($data['homeURL'] == '' && $data['wechatURL'] == '') || ($data['mobileURL'] == '' && $data['wechatURL'] == ''))return WSTReturn('请至少输入两个要转换的网址');
		$result = $this->save($data);
        if(false !== $result){
        	cache('WST_SWITCHS',null);
        	return WSTReturn("新增成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}
    /**
	 * 编辑
	 */
	public function edit(){
		$id = input('post.id/d',0);
		$data = ['homeURL'=>input('post.homeURL'),'mobileURL'=>input('post.mobileURL'),'wechatURL'=>input('post.wechatURL')];
		if(($data['homeURL'] == '' && $data['mobileURL'] == '') || ($data['homeURL'] == '' && $data['wechatURL'] == '') || ($data['mobileURL'] == '' && $data['wechatURL'] == ''))return WSTReturn('请至少输入两个要转换的网址');
		$result = $this->save($data,['id'=>$id]);
        if(false !== $result){
        	cache('WST_SWITCHS',null);
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
	    $result = $this->where('id',$id)->delete();
        if(false !== $result){
        	cache('WST_SWITCHS',null);
        	return WSTReturn("删除成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}
	
}
