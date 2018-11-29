<?php
namespace shangtao\home\controller;
/**
 */
class Helpcenter extends Base{
    public function index(){
    	return $this->view();
    }

	public function view(){
		//获取左侧列表
		$m = model('home/Articles');
		$list = $m->helpList();
		$data = $m->getHelpById();
		$this->assign('data',$data);
		$this->assign('list',$list);
		//面包屑导航
		$bcNav = [];
		if(!empty($data)){
			$bcNav = $this->bcNav();
		}
		$this->assign('bcNav',$bcNav);
		return $this->fetch('articles/help');
	}
	/**
	*  记录解决情况
	*/
	public function recordSolve(){
		$m = model('home/Articles');
		return $m->recordSolve();
	}
	/**
	* 面包屑导航
	*/
	public function bcNav(){
		$m = model('home/Articles');
		return $m->bcNav();
	}
}