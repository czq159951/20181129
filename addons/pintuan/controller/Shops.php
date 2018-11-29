<?php
namespace addons\pintuan\controller;

use think\addons\Controller;
use addons\pintuan\model\Pintuans as M;
/**
 * 拼团插件
 */
class Shops extends Controller{
	protected $addonStyle = 'default';
	public function __construct(){
		parent::__construct();
		$m = new M();
        $data = $m->getConf('Pintuan');
        $this->addonStyle = ($data['addonsStyle']=='')?'default':$data['addonsStyle'];
        $this->assign("addonStyle",$this->addonStyle);
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
	/**
	 * 拼团列表
	 */
	public function pintuan(){
    	return $this->fetch($this->addonStyle."/home/shops/list");
	}
	/**
	 * 加载拼团数据
	 */
	public function pageQuery(){
		$m = new M();
		return $m->pageQueryByShop();
	}

	/**
	 * 拼团开团列表
	 */
	public function opentuan(){
		$this->assign('tuanId',(int)input("tuanId"));
		$this->assign('tuanStatus',(int)input("tuanStatus"));
    	return $this->fetch($this->addonStyle."/home/shops/open_list");
	}
	/**
	 * 加载开团列表
	 */
	public function openPageQuery(){
		$m = new M();
		return $m->pageQueryByTuan();
	}

	/**
	 * 搜索商品
	 */
	public function searchGoods(){
		$m = new M();
		return $m->searchGoods();
	}

	/**
	 * 跳去编辑页面
	 */
	public function edit(){
		$id = (int)input('id');
		$object = [];
		$m = new M();
		if($id>0){
            $object = $m->getById($id);
		}else{
			$object = $m->getEModel('pintuans');
			$object['marketPrice'] = '';
			$object['goodsName'] = '请选择拼团商品';
		}
		$this->assign("object",$object);
		return $this->fetch($this->addonStyle."/home/shops/edit");
	}

	/**
	 * 保存拼团信息
	 */
	public function toEdit(){
		$id = (int)input('post.tuanId');
		$m = new M();
		if($id==0){
            return $m->add();
		}else{
            return $m->edit();
		}
	}

	/**
	 * 删除拼团
	 */
	public function del(){
		$m = new M();
		return $m->del();
	}

	/**
	 * 下架拼团
	 */
	public function unSale(){
		$m = new M();
		return $m->unSale();
	}
	
	/**
	 * 查看拼团订单列表
	 */
    public function orders(){
    	$this->assign("tuanId",(int)input('tuanId'));
    	return $this->fetch($this->addonStyle."/home/shops/list_orders");
    }
    /**
     * 查询订单列表
     */ 
    public function pageQueryByGoods(){
    	$m = new M();
		return $m->pageQueryByGoods();
    }
}