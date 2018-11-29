<?php

namespace think\addons;
use think\Db;

/**
 * 插件基类服务器
 * Class Model
 * @package think\addons
 */
class BaseModel extends \think\Model{
	/**
	 * 获取插件配置内容
	 */
	public function getConf($addonsName){
		$data = cache('ADDONS_'.$addonsName);
		if(!$data){
			$rs = Db::name('addons')->where('name',$addonsName)->field('config')->find();
		    $data =  json_decode($rs['config'],true);
		    cache('ADDONS_'.$addonsName,$data,31622400);
		}
		return $data;
	}
	
	public function getAddonStatus($addonsName){
		$rs = Db::name('addons')->where('name',$addonsName)->field('status')->find();
		return (int)$rs["status"];
	}
	/**
	 * 获取空模型
	 */
	public function getEModel($tables){
		$rs =  Db::query('show columns FROM `'.config('database.prefix').$tables."`");
		$obj = [];
		if($rs){
			foreach($rs as $key => $v) {
				$obj[$v['Field']] = $v['Default'];
				if($v['Key'] == 'PRI')$obj[$v['Field']] = 0;
			}
		}
		return $obj;
	}
	
	/**
	 * 绑定勾子
	 * @param 插件名 $addonName
	 * @param 勾子数组 $hooks
	 */
	public function bindHoods($addonName,$hooks){
		$list = Db::name('hooks')->where("name","in",$hooks)->field(["hookId","name","addons"])->select();
		for($i=0,$k=count($list);$i<$k;$i++){
			$hook = $list[$i];
			$objs = explode(",",$hook["addons"]);
			$objs = array_filter($objs);
			if(!in_array($addonName,$objs)){
				$addons = $addonName;
				if(!empty($objs)){
					$objs[] = $addonName;
					$addons = implode(",",$objs);
				}
				Db::name('hooks')->where(["hookId"=>$hook["hookId"]])->update(["addons"=>$addons]);
			}
		}
	}
	
	/**
	 * 解绑勾子
	 * @param 插件名 $addonName
	 * @param 勾子数组 $hooks
	 */
	public function unbindHoods($addonName,$hooks){
	
		$list = Db::name('hooks')->where("name","in",$hooks)->field(["hookId","name","addons"])->select();
		for($i=0,$k=count($list);$i<$k;$i++){
			$hook = $list[$i];
			$objs = explode(",",$hook["addons"]);
			$temps = array();
			for($m=0,$n=count($objs);$m<$n;$m++){
				if($objs[$m]!=$addonName){
					$temps[] = $objs[$m];
				}
			}
			$addons = implode(",",$temps);
			Db::name('hooks')->where(["hookId"=>$hook["hookId"]])->update(["addons"=>$addons]);
		}
	}
    
}
