<?php 
namespace shangtao\app\controller;
use shangtao\app\model\ShopCats as M;
class Shopcats extends Base{
	protected $beforeActionList = ['checkAuth'];
	static $model = null;
	static $shopId = null;
	public function __construct(){
		parent::__construct();
		$userId = model('index')->getUserId();
		self::$shopId = model('app/shops')->getShopId($userId);
		self::$model = new M();
	}
	// 列表查询
	public function listQuery(){
		$rs = self::$model->listQuery(self::$shopId,(int)input('parentId'));
		return json_encode(WSTReturn('ok',1,$rs));
	}
	// 获取
	public function getById(){
		return json_encode(self::$model->getById((int)input('catId')));
	}
	// 保存
	public function save(){
		return json_encode(self::$model->saveData(self::$shopId));
	}
	// 删除
	public function del(){
		return json_encode(self::$model->del(self::$shopId));
	}
}