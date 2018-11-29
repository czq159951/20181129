<?php
namespace shangtao\home\controller;
use shangtao\common\model\Brands as M;
/**
 * 品牌控制器
 */
class Brands extends Base{
	/**
	 * 品牌街
	 */
	public function index(){
		$m = new M();
		$pagesize = 66;
		$selectedId = (int)input("id");
		$g = model('goodsCats');
		$goodsCats = $g->listQuery(0);
    	$this->assign('goodscats',$goodsCats);
         if(empty($goodsCats))$goodsCats =[['catId'=>0]];
    	$selectedId = ($selectedId>0)?$selectedId:$goodsCats[0]['catId'];
		$brandsList = $m->pageQuery($pagesize,$selectedId);
		$this->assign('list',$brandsList);

		
        
        
    	
    	$this->assign('selectedId',$selectedId);
		return $this->fetch('brands_list');
	}
	/**
	 * 获取品牌列表
	 */
    public function listQuery(){
        $m = new M();
        return ['status'=>1,'list'=>$m->listQuery(input('post.catId/d'))];
    }
}
