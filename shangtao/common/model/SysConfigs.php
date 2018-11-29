<?php
namespace shangtao\common\model;
/**
 * 系统配置类
 */
class SysConfigs extends Base{
	
	/**
	 * 获取商城配置文件
	 */
	public function loadConfigs(){
		
		$rs = $this->field('fieldCode,fieldValue')->order("parentId asc,fieldSort asc")->select();
		$configs = array();
		if(count($rs)>0){
			foreach ($rs as $key=>$v){
				if($v['fieldCode']=="hotSearchs"){
					$fieldValue = str_replace("，",",",$v['fieldValue']);
					$configs[$v['fieldCode']] = explode(",",$fieldValue);
				}else{
					$configs[$v['fieldCode']] = $v['fieldValue'];
				}
			}
		}
		unset($rs);
		return $configs;
	}
}
