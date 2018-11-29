<?php
namespace shangtao\common\model;
/**
 * 地区类
 */
class Areas extends Base{
	protected $pk = 'areaId';
 	/**
	   * 获取所有城市-根据字母分类
	   */
	public function getCityGroupByKey(){
		$rs = array();
	  	$rslist = $this->where('isShow=1 AND dataFlag = 1 AND areaType=1')->field('areaId,areaName,areaKey')->order('areaKey, areaSort')->select();
	  	foreach ($rslist as $key =>$row){
	  		$rs[$row["areaKey"]][] = $row;
	  	}
	  	return $rs;
	}
	/**
	 * 获取城市列表
	 */
	public function getCitys(){
        return $this->where('isShow=1 AND dataFlag = 1 AND areaType=1')->field('areaId,areaName')->order('areaKey, areaSort')->select();
	}
	
	public function getArea($areaId2){
		$rs = $this->where(["isShow"=>1,"dataFlag"=>1,"areaType"=>1,"areaId"=>$areaId2])->field('areaId,areaName,areaKey')->find();
		return $rs;
	}
	/**
	 *  获取地区列表
	 */
	public function listQuery($parentId = 0){
		$parentId = ($parentId>0)?$parentId:(int)input('parentId');
		return $this->where(['isShow'=>1,'dataFlag'=>1,'parentId'=>$parentId])->field('areaId,areaName,parentId')->order('areaSort desc')->select();
	}
	/**
	 *  获取指定对象
	 */
    public function getById($id){
		return $this->where(["areaId"=>(int)$id])->find()->toArray();
	}
    /**
	 * 根据子分类获取其父级分类
	 */
	public function getParentIs($id,$data = array()){
		$data[] = $id;
		$parentId = $this->where('areaId',$id)->value('parentId');
		if($parentId==0){
			krsort($data);
			return $data;
		}else{
			return $this->getParentIs($parentId, $data);
		}
	}
	/**
	 * 获取自己以及父级的地区名称
	 */
	public function getParentNames($id,$data = array()){
		$areas = $this->where('areaId',$id)->field('parentId,areaName')->find();
		$data[] = $areas['areaName'];
		if((int)$areas['parentId']==0){
			krsort($data);
			return $data;
		}else{
			return $this->getParentNames((int)$areas['parentId'], $data);
		}
	}
	/**
	* 检测是否还存在下级
	*/
	public function hasChild($areaId){
		return $this->where(['parentId'=>(int)$areaId,'dataFlag'=>1,'isShow'=>1])->find();
	}
}
