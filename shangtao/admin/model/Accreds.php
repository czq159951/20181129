<?php
namespace shangtao\admin\model;
use shangtao\admin\validate\Accreds as validate;
use think\Db;
/**
 * 商家认证业务处理
 */
class Accreds extends Base{
	protected $pk = 'accredId';
	/**
	 * 分页
	 */
	public function pageQuery(){
		return $this->where('dataFlag',1)->field(true)->order('accredId desc')->paginate(input('limit/d'));
	}
	/**
	 * 列表
	 */
    public function listQuery(){
		return $this->where('dataFlag',1)->field(true)->select();
	}
	public function getById($id){
		return $this->get(['accredId'=>$id,'dataFlag'=>1]);
	}
	/**
	 * 新增
	 */
	public function add(){
		$data = input('post.');
		$data['createTime'] = date('Y-m-d H:i:s');
		WSTUnset($data,'accredId');
		Db::startTrans();
		try{
			$validate = new validate();
		    if(!$validate->scene('add')->check($data))return WSTReturn($validate->getError());
			$result = $this->allowField(true)->save($data);
			if(false !==$result){
				$id = $this->accredId;
				//启用上传图片
				WSTUseImages(1, $id, $data['accredImg']);
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
		WSTUnset($data,'createTime');
		Db::startTrans();
		try{
			WSTUseImages(1, (int)$data['accredId'], $data['accredImg'], 'accreds', 'accredImg');
			$validate = new validate();
		    if(!$validate->scene('edit')->check($data))return WSTReturn($validate->getError());
		    $result = $this->allowField(true)->save($data,['accredId'=>(int)$data['accredId']]);
	        if(false !== $result){
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
		    $result = $this->setField(['dataFlag'=>-1,'accredId'=>$id]);
		    WSTUnuseImage('accreds','accredImg',$id);	
	        if(false !== $result){
	        	Db::commit();
	        	return WSTReturn("删除成功", 1);
	        }
		}catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('删除失败',-1); 
	}
	
}
