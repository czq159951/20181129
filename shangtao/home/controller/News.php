<?php
namespace shangtao\home\controller;
/**
 */
class News extends Base{
	/**
	*	根据分类id获取文章列表
	*/
	public function nList(){
		$m = model('home/articles');
		$pageObj = $m->nList();
		$news = $pageObj->toArray();
		// 分页页码
		$page = $pageObj->render();
		$this->assign('page',$page);
		//获取左侧列表
		$leftList = $m->NewsList();
		$this->assign('list',$leftList);
		$this->assign('newsList',$news['data']);
		$this->assign('catId',(int)input('catId'));
		//面包屑导航
		$bcNav = $this->bcNav();
		// 防止用户取出帮助中心分类
		foreach($bcNav as $k=>$v){
			if($v['catId']==7){
				$bcNav = [];
				break;
			}
		}
		// 获取title
		$currTitle = '';
		foreach($bcNav as $k=>$v){
			if($v['catId']==(int)input('catId'))$currTitle = $v['catName'];
		}
		$this->assign('title',$currTitle);
		$this->assign('bcNav',$bcNav);
		// 防止没有数据时报错
		if(empty($bcNav))$this->redirect('home/News/view');
		return $this->fetch('articles/news_list');
	}

	public function view(){
		//获取左侧列表
		$m = model('home/Articles');
		$list = $m->NewsList();
		//当前分类id
		$content = $m->getNewsById();
		$this->assign('catId',(int)$content['catId']);
		$this->assign('list',$list);
		$this->assign('content',$content);

		//面包屑导航
		$bcNav = [];
		if(!empty($content)){
			$bcNav = $this->bcNav();
		}
		$this->assign('bcNav',$bcNav);


		if((int)input('id')==0){
			// 资讯列表下的新闻
			$pageObj = $m->getArticles();
			$news = $pageObj->toArray();
			// 分页页码
			$page = $pageObj->render();
			$this->assign('page',$page);
			$this->assign('index',$news['data']);
		}

		return $this->fetch('articles/news_view');
	}
	public function bcNav(){
		$m = model('home/Articles');
		return $m->bcNav();
	}

	public function index(){
		return $this->view();
	}
}