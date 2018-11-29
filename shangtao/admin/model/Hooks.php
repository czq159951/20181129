<?php
namespace shangtao\admin\model;
use think\Db;
/**
 * 钩子业务处理
 */
class Hooks extends Base{
	
	/**
	 * 获取插件列表
	 * @param string $addon_dir
	 */
	public function pageQuery(){
		
		$keyWords = input("keyWords");
		$parentId = input('parentId/d',0);
		$where[] = ["name","like","%$keyWords%"];
		$page = $this->where($where)->order('`name` asc')->paginate(input('post.limit/d'))->toArray();
		
		return $page;

	}
	
	/**
	 * 保存插件设置
	 */
	public function saveConfig(){
		$id = input("id/d",0);
		$config =   $_POST['config'];
		$flag = $this->where(["addonId"=>$id])->setField('config',json_encode($config));
		if($flag !== false){
			return WSTReturn("保存成功", 1);
		}else{
			return WSTReturn('保存失败',-1);
		}
	}
	
	
    
	
}
