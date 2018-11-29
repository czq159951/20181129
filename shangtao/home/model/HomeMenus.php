<?php
namespace shangtao\home\model;
use shangtao\common\model\HomeMenus as CHomeMenus;
use think\Db;
/**
 * 菜单业务处理
 */
class HomeMenus extends CHomeMenus{
	/**
	 * 获取菜单树
	 */
	public function getMenus(){
		$data = cache('WST_HOME_MENUS');
		if(!$data){
			$rs = $this->where(['isShow'=>1,'dataFlag'=>1])
			        ->field('menuId,parentId,menuName,menuUrl,menuType')->order('menuSort asc,menuId asc')->select();
			$m1 = ['0'=>[],'1'=>[]];
			$tmp = [];
			
			//获取第一级
			foreach ($rs as $key => $v){
				if($v['parentId']==0){
					$m1[$v['menuType']][$v['menuId']] = ['menuId'=>$v['menuId'],'parentId'=>$v['parentId'],'menuName'=>$v['menuName'],'menuUrl'=>$v['menuUrl']];
				}else{
					$tmp[$v['parentId']][] = ['menuId'=>$v['menuId'],'parentId'=>$v['parentId'],'menuName'=>$v['menuName'],'menuUrl'=>$v['menuUrl']];
				}
			}
			//获取第二级
			foreach ($m1 as $key => $v){
				foreach ($v as $key1 => $v1){
				    if(isset($tmp[$v1['menuId']]))$m1[$key][$key1]['list'] = $tmp[$v1['menuId']];
				}
			}
			//获取第三级
		    foreach ($m1 as $key => $v){
		    	foreach ($v as $key1 => $v1){
			    	if(isset($v1['list'])){
				    	foreach ($v1['list'] as $key2 => $v2){
						    if(isset($tmp[$v2['menuId']]))$m1[$key][$key1]['list'][$key2]['list'] = $tmp[$v2['menuId']];
				    	}
			    	}
		    	}
			}
			cache('WST_HOME_MENUS',$m1,31536000);
			return $m1;
		}
		return $data;
	}

	/**
	 * 获取店铺菜单树
	 */
	public function getShopMenus(){
		$m1 = $this->getMenus();
		$userType = (int)session('WST_USER.userType');
		$menuUrls = array();
		if($userType==1){
			$shopId = (int)session('WST_USER.shopId');
			$roleId = (int)session('WST_USER.roleId');
			if($roleId>0){
				$role = model("home/ShopRoles")->getById($roleId);
				$menuUrls = isset($role["privilegeUrls"])?json_decode($role["privilegeUrls"],true):[];
				foreach ($m1[1] as $k1 => $menus1) {
					if(!array_key_exists($menus1["menuId"],$menuUrls)){
						unset($m1[1][$k1]);
					}else{
						if(isset($menus1["list"])){
							if(count($menus1["list"])>0){
								foreach ($menus1["list"] as $k2 => $menus2) {
									if(!array_key_exists($menus2["menuId"],$menuUrls[$menus1["menuId"]])){
										unset($m1[1][$k1]["list"][$k2]);
									}else{
										if(isset($menus2["list"])){
											if(count($menus2["list"])>0){
												foreach ($menus2["list"] as $k3 => $menus3) {
													$purls = $menuUrls[$menus1["menuId"]][$menus2["menuId"]];
													$urls = $purls["urls"];
													if(!in_array(strtolower($menus3["menuUrl"]),$urls)){
														unset($m1[1][$k1]["list"][$k2]["list"][$k3]);
													}
												}
											}else{
												unset($m1[1][$k1]["list"][$k2]);
											}
										}else{
											unset($m1[1][$k1]["list"][$k2]);
										}
									}
								}
								if(count($m1[1][$k1]["list"])==0){
									unset($m1[1][$k1]);
								}
							}else{
								unset($m1[1][$k1]);
							}
						}else{
							unset($m1[1][$k1]);
						}
					}
				}
			}
		}
		return $m1;
	}
	
	/**
	 * 获取菜单URL
	 */
	public function getMenusUrl(){
		$wst_user = session('WST_USER');
		$data = array();
		if(!empty($wst_user)){
			$data = cache('WST_PRO_MENUS');
			if(!$data){
				$list = $this->where('dataFlag',1)->order('menuType asc')->select();
				$menus = [];
				foreach($list as $key => $v){
					$menus[strtolower($v['menuUrl'])] = $v['menuType'];
					if($v['menuOtherUrl']!=''){
						$str = explode(',',$v['menuOtherUrl']);
						foreach ($str as $vkey => $vv){
							if($vv=='')continue;
							$menus[strtolower($vv)] = $v['menuType'];
						}
					}
				}
				cache('WST_PRO_MENUS',$menus,31536000);
				return $menus;
			}
		}
		return $data;
	}

	/**
	 * 角色可访问url
	 */
	public function getShopMenusUrl(){
		$wst_user = session('WST_USER');
		
		if(!empty($wst_user)){
			$roleId = isset($wst_user["roleId"])?(int)$wst_user["roleId"]:0;
			if($roleId>0){
				$role = model("home/ShopRoles")->getById($roleId);
				if(!empty($role)){
					$menuUrls = $role["menuUrls"];
					$menuOtherUrls = $role["menuOtherUrls"];
					$shopUrls = array_merge($menuUrls,$menuOtherUrls);
				}
				
			}
		}
		$shopUrls[] = "home/shops/index";
		$shopUrls[] = "home/reports/getstatsales";
		return $shopUrls;
	}


	/**
	 * 获取菜单父ID
	 */
	public function getParentId($menuId){
		$data = cache('WST_HOME_MENUS_PARENT');
		if(!$data){
			$rs = $this->where(['isShow'=>1,'dataFlag'=>1])
			        ->field('menuId,parentId,menuType')->order('menuSort asc,menuId asc')->select();
			$tmp = [];
			foreach ($rs as $key => $v) {
			    $tmp[$v['menuId']] = $v;
			}
			$data = [];
            foreach ($tmp as $key => $v) {
            	if($v['parentId']==0){
                    $data[$v['menuId']] = $v;
            	}else{
                    $data[$v['menuId']] = $tmp[$v['parentId']];
            	}
			} 
            cache('WST_HOME_MENUS_PARENT',$data,31536000);
		}
		return $data[$menuId];	
	}
	/**
	 * 获取店铺角色菜单
	 */
	public function getRoleMenus(){
		$data = cache('WST_HOME_MENUS_SHOPROLE');
		if(!$data){
			$rs = $this->alias('m1')
				->join("__HOME_MENUS__ m2","m1.parentId=m2.menuId")
				->where([['m1.isShow','=',1],['m1.dataFlag','=',1],["m1.menuType",'=',1],["m2.parentId",'>',0]])
				->field('m1.menuId,m1.parentId,m2.parentId grandpaId,m1.menuName,m1.menuUrl,m1.menuOtherUrl,m1.menuType')
				->order('m1.menuSort asc,m1.menuId asc')
				->select();
			$m = array();
			//获取第一级
			foreach ($rs as $key => $v){
				$m[$v['menuId']] = ['menuId'=>$v['menuId'],'parentId'=>$v['parentId'],'grandpaId'=>$v['grandpaId'],'menuName'=>$v['menuName'],'menuUrl'=>$v['menuUrl'],'menuOtherUrl'=>$v['menuOtherUrl']];
			}
			cache('WST_HOME_MENUS_SHOPROLE',$m,31536000);
			return $m;
		}
		return $data;
	}



}
