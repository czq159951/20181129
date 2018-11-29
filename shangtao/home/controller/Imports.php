<?php
namespace shangtao\home\controller;
use shangtao\home\model\Imports as M;
/**

 * 默认控制器
 */
class Imports extends Base{
	protected $beforeActionList = ['checkShopAuth'];
	/**
	 * 数据导入首页
	 */
	public function index(){
		return $this->fetch('shops/import');
	}
	
    /**
     * 上传商品数据
     */
    public function importGoods(){
    	$rs = WSTUploadFile();	
    	if(json_decode($rs)->status==1){
			$m = new M();
    	    $rss = $m->importGoods($rs);
    	    return $rss;
		}
    	return $rs;
    }
}
