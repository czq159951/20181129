<?php
namespace shangtao\admin\model;
use shangtao\admin\validate\MobileBtns as validate;
use think\Db;
/**
 * 商家认证业务处理
 */
class MobileBtns extends Base{
	/**
	 * 分页
	 */
	public function pageQuery(){
		$btnSrc = (int)input('btnSrc1',-1);
		$btnName = input('btnName1');
		$where = [];
		if($btnSrc>-1){
			$where[] = ['btnSrc','=',$btnSrc];
		}
		if($btnName!=''){
			$where[] = ['btnName','like',"%$btnName%"];
		}
		return $this->field(true)
					->where($where)
					->order('btnSrc asc,btnSort asc')
					->paginate(input('limit/d'));
	}
	public function getById($id){
		return $this->get(['id'=>$id]);
	}
	/**
	 * 新增
	 */
	public function add(){
		$data = input('post.');
		$data['btnSort'] = (int)$data['btnSort'];
		WSTUnset($data,'id');
		Db::startTrans();
		try{
			$validate = new validate();
			if(!$validate->scene('add')->check($data))return WSTReturn($validate->getError());
			$result = $this->allowField(true)->save($data);
			if(false !==$result){
				cache('WST_MOBILE_BTN',null);
				$id = $this->id;
				//启用上传图片
				WSTUseImages(1, $id, $data['btnImg']);
		        if(false !== $result){
		        	Db::commit();
		        	return WSTReturn("新增成功", 1);
		        }
			}
		}catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('新增失败',-1);	
	}
    /**
	 * 编辑
	 */
	public function edit(){
		$data = input('post.');
		$data['btnSort'] = (int)$data['btnSort'];
		WSTUnset($data,'createTime');
		Db::startTrans();
		try{
			WSTUseImages(1, (int)$data['id'], $data['btnImg'], 'mobile_btns', 'btnImg');
		    $validate = new validate();
		    if(!$validate->scene('edit')->check($data))return WSTReturn($validate->getError());
		    $result = $this->allowField(true)->save($data,['id'=>(int)$data['id']]);
	        if(false !== $result){
	        	cache('WST_MOBILE_BTN',null);
	        	Db::commit();
	        	return WSTReturn("编辑成功", 1);
	        }
	    }catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('编辑失败',-1);  
	}
	/**
	 * 删除
	 */
    public function del(){
	    $id = (int)input('post.id/d');
	    Db::startTrans();
		try{
		    WSTUnuseImage('mobile_btns','btnImg',$id);	
		    $result = $this->where(['id'=>$id])->delete();
	        if(false !== $result){
	        	cache('WST_MOBILE_BTN',null);
	        	Db::commit();
	        	return WSTReturn("删除成功", 1);
	        }
		}catch (\Exception $e) {
			echo $e->getMessage();
            Db::rollback();
        }
        return WSTReturn('删除失败',-1); 
	}
	
}
