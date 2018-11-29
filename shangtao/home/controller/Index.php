<?php
namespace shangtao\home\controller;
/**
 * 默认控制器
 */
class Index extends Base{
	protected $beforeActionList = [
          'checkAuth' =>  ['only'=>'getsysmessages']
    ];
    public function index(){
    	$categorys = model('GoodsCats')->getFloors();
    	$this->assign('floors',$categorys);
        $this->assign('hideCategory',1);

      //获取用户积分
      $rs = model('Users')->getFieldsById((int)session('WST_USER.userId'),'userScore');
      $this->assign('object',$rs);
        // 店铺街数据
      $shopStreet = model('shops')->indexShopQuery();
    	$this->assign('shopStreet',$shopStreet);
    	return $this->fetch('index');
    }
    /**
     * 保存目录ID
     */
    public function getMenuSession(){
    	$menuId = input("post.menuId");
    	$menuType = session('WST_USER.loginTarget');
    	session('WST_MENUID3'.$menuType,$menuId);
    } 
    /**
     * 获取用户信息
     */
    public function getSysMessages(){
    	$rs = model('Systems')->getSysMessages();
    	return $rs;
    }
    /**
     * 定位菜单以及跳转页面
     */
    public function position(){
    	$menuId = (int)input("post.menuId");
    	$menuType = ((int)input("post.menuType")==1)?1:0;
        $menus = model('HomeMenus')->getParentId($menuId);
        session('WST_MENID'.$menus['menuType'],$menus['parentId']);
    	session('WST_MENUID3'.$menuType,$menuId);
    }

    /**
     * 转换url
     */
    public function transfor(){
        $data = input('param.');
        $url = $data['url'];
        unset($data['url']);
        echo Url($url,$data);
    }
    /**
     * 保存url
     */
    public function currenturl(){
    	session('WST_HO_CURRENTURL',input('url'));
    	return WSTReturn("", 1);
    }
}
