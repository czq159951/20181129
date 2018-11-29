<?php
namespace shangtao\admin\model;
use shangtao\admin\validate\Navs as validate;
use think\Db;
/**
 * 导航管理业务处理
 */
class Navs extends Base{
	/**
	 * 分页
	 */
	public function pageQuery(){
		return $this->field(true)->order('id desc')->paginate(input('limit/d'));
	}
	public function getById($id){
		return $this->get($id);
	}
	/**
	 * 新增
	 */
	public function add(){
		$data = input('post.');
		$data['createTime'] = date('Y-m-d H:i:s');
		WSTUnset($data,'id');
		$validate = new validate();
		if(!$validate->scene('add')->check($data))return WSTReturn($validate->getError());
		$result = $this->allowField(true)->save($data);
        if(false !== $result){
        	cache('WST_NAVS',null);
        	return WSTReturn("新增成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}
    /**
	 * 编辑
	 */
	public function edit(){
		$Id = input('post.id/d',0);
		//获取数据
		$data = input('post.');
		WSTUnset($data,'createTime');
		$validate = new validate();
		if(!$validate->scene('edit')->check($data))return WSTReturn($validate->getError());
	    $result = $this->allowField(true)->save($data,['id'=>$Id]);
        if(false !== $result){
        	cache('WST_NAVS',null);
        	return WSTReturn("编辑成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}
	/**
	 * 删除
	 */
    public function del(){
	    $id = input('post.id/d');
	    $result = $this->destroy($id);
        if(false !== $result){
        	cache('WST_NAVS',null);
        	return WSTReturn("删除成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}
	/**
	 * 设置显示隐藏 
	 */
    public function editiIsShow(){
        $id = input('post.id/d',0);
        $field = input('post.field');
        $val = input('post.val/d',0);
        if(!in_array($field,['isShow','isOpen']))return WSTReturn("非法的操作内容",-1);
        $result = Db::name('navs')->where('id','eq',$id)->setField($field, $val);
        if(false !== $result){
        	cache('WST_NAVS',null);
            return WSTReturn("设置成功", 1);
        }else{
            return WSTReturn($this->getError(),-1);
        }
    }
	
}
