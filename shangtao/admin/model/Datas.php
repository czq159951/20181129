<?php
namespace shangtao\admin\model;
use shangtao\admin\validate\Datas as validate;
/**
 * 经营范围业务处理
 */
use think\Db;
class Datas extends Base{
	protected $insert = ['dataFlag'=>1]; 
	/**
	 * 获取指定分类的列表
	 */
	public function listQuery($catId){
		return Db::name('datas')->where('catId',$catId)->field('dataName,dataVal')->select();
	}
	/**
	* 根据catId获取子数据
	*/
	public function childQuery(){
		$catId = (int)input('post.id');
		return $this->where(['dataFlag'=>1,'catId'=>$catId])->paginate(input('limit/d'));
	}
	/**
	 * 获取菜单列表
	 */
	public function dataQuery($catId = -1){
		if($catId==-1)return ['id'=>0,'name'=>'系统数据','isParent'=>true,'open'=>true];
		$rs = Db::name('data_cats')->where(['dataFlag'=>1])->field('catId id,catName name')->select();
		return $rs;
	}
	/**
	 * 获取菜单
	 */
	public function getById($id){
		return $this->get(['dataFlag'=>1,'id'=>$id]);
	}
	
	/**
	 * 新增菜单
	 */
	public function add(){
		$data = input('post.');
		$validate = new validate();
		if(!$validate->scene('add')->check($data))return WSTReturn($validate->getError());
		$result = $this->save($data);
        if(false !== $result){
        	cache('WST_DATAS',null);
        	return WSTReturn("新增成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}
    /**
	 * 编辑菜单
	 */
	public function edit(){
		$id = input('post.id/d');
		$data = input('post.');
		$validate = new validate();
		if(!$validate->scene('edit')->check($data))return WSTReturn($validate->getError());
	    $result = $this->allowField(['dataName','dataVal','dataSort'])->save(($data),['id'=>$id]);
        if(false !== $result){
        	cache('WST_DATAS',null);
        	return WSTReturn("编辑成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}
	/**
	 * 删除菜单
	 */
	public function del(){
	    $id = input('post.id/d');
		$data = [];
		$data['dataFlag'] = -1;
	    $result = $this->update($data,['id'=>$id]);
        if(false !== $result){
        	cache('WST_DATAS',null);
        	return WSTReturn("删除成功", 1);
        }else{
        	return WSTReturn($this->getError(),-1);
        }
	}

}
