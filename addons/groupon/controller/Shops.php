<?php
namespace addons\groupon\controller;

use think\addons\Controller;
use addons\groupon\model\Groupons as M;
/**
 * 团购插件
 */
class Shops extends Controller{
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
	/**
	 * 团购列表
	 */
	public function groupon(){
    	return $this->fetch("/home/shops/list");
	}
	/**
	 * 加载团购数据
	 */
	public function pageQuery(){
		$m = new M();
		return $m->pageQueryByShop();
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
			$object = $m->getEModel('groupons');
			$object['marketPrice'] = '';
			$object['goodsName'] = '请选择团购商品';
			$object['startTime'] = date('Y-m-d H:00:00',strtotime("+2 hours"));
			$object['endTime'] = date('Y-m-d H:00:00',strtotime("+1 month"));
		}
		$this->assign("object",$object);
		return $this->fetch("/home/shops/edit");
	}

	/**
	 * 保存团购信息
	 */
	public function toEdit(){
		$id = (int)input('post.grouponId');
		$m = new M();
		if($id==0){
            return $m->add();
		}else{
            return $m->edit();
		}
	}

	/**
	 * 删除团购
	 */
	public function del(){
		$m = new M();
		return $m->del();
	}

	/**
	 * 查看团购订单列表
	 */
    public function orders(){
    	$this->assign("grouponId",(int)input('grouponId'));
    	return $this->fetch("/home/shops/list_orders");
    }
    /**
     * 查询订单列表
     */ 
    public function pageQueryByGoods(){
    	$m = new M();
		return $m->pageQueryByGoods();
    }
}