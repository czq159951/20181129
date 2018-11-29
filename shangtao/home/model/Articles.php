<?php
namespace shangtao\home\model;
/**
 * 文章类
 */
use think\Db;
class Articles extends Base{
	/**
	 * 获取帮助左侧列表
	 */
	public function helpList(){
		$arts = cache('arts');
		if(!$arts){
			$rs = $this->alias('a')->join('article_cats ac','a.catId=ac.catId','inner')
				  ->field('a.articleId,a.catId,a.articleTitle,ac.catName')
				  ->where(['a.dataFlag'=>1,
				  	       'a.isshow'=>1,
				  		   'ac.dataFlag'=>1,
				  		   'ac.isShow'=>1,
				  		   'ac.parentId'=>7])
				  ->order('a.catSort asc')
				  ->cache(true)
				  ->select();
			//同一分类下的文章放一起
			$catName = [];
			$arts = [];
			foreach($rs as $k=>$v){
				if(in_array($v['catName'],$catName)){
					$arts[$v['catName'].'-'.$v['catId']][] = $v;
				}else{
					$catName[] = $v['catName'];
					$arts[$v['catName'].'-'.$v['catId']][] = $v;
				}
			}
			cache('arts',$arts,86400);
		}
		return $arts;
	}
	/**
	*  根据id获取帮助文章
	*/
	public function getHelpById(){
		$id = (int)input('id');
		WSTArticleVisitorNum($id);// 统计文章访问量
		return $this->alias('a')->join('__ARTICLE_CATS__ ac','a.catId=ac.catId','inner')->where('ac.parentId=7 and  a.dataFlag=1 and a.isShow=1 and a.articleId='.$id)->cache(true)->find();
	}
	/**
	*  根据id获取资讯文章
	*/
	public function getNewsById($id = 0){
		$id = $id>0?$id:(int)input('id');
		WSTArticleVisitorNum($id);// 统计文章访问量
	    return Db::name("articles")->alias('a')
					->field('a.*,ac.catName')
					->join('__ARTICLE_CATS__ ac','a.catId=ac.catId','inner')
					->where('a.catId<>7 and ac.parentId<>7 and a.dataFlag=1 and a.isShow=1')->order('a.catSort asc')
					->cache(true)
					->find($id);
	}

	/**
	* 获取资讯列表【左侧分类】
	*/
	public function NewsList(){
		$list =  $this->getTree();
		foreach($list as $k=>$v){
			if(!empty($v['children'])){
				foreach($v['children'] as $k1=>$v1){
					// 二级分类下的文章总条数
					$list[$k]['children'][$k1]['newsCount'] = $this->where(['catId'=>$v1['catId'],
																	'dataFlag'=>1,'isShow'=>1])->cache(true)->count();
				}
			}
		}
		return $list;
	}

	public function getTree(){
		$artTree = cache('artTree');
		if(!$artTree){
			$data = Db::name('article_cats')->field('catName,catId,parentId')->where('parentId <> 7 and catId <> 7 and dataFlag=1 and isShow=1')->cache(true)->select();
			$artTree = $this->_getTree($data, 0);
			cache('artTree',$artTree,86400);
		}
		return $artTree;
	}
	public function _getTree($data,$parentId){
		$tree = [];
		foreach($data as $k=>$v){
			if($v['parentId']==$parentId){
				// 再找其下级分类
				$v['children'] = $this->_getTree($data,$v['catId']);
				$tree[] = $v;
			}
		}
		return $tree;
	}
	/**
	*	根据分类id获取文章列表
	*/
	public function nList(){
		$catId = (int)input('catId');
		$rs = $this->alias('a')
			  ->join('__ARTICLE_CATS__ ac','a.catId=ac.catId','inner')
			  ->field('a.*')
			  ->where(['a.catId'=>$catId,'a.dataFlag'=>1,'a.isShow'=>1,'ac.dataFlag'=>1,'ac.isShow'=>1])->order('a.catSort asc,a.createTime desc')
			  ->where('ac.parentId','<>',7)
			  ->cache(true)
			  ->paginate();
		return $rs;
	}
	/**
	* 面包屑导航
	*/
	public function bcNav(){
		$catId = (int)input('catId'); //分类id
		$artId = (int)input('id'); 	//文章id
		$data = Db::name('article_cats')->field('catId,parentId,catName')->cache(true)->select();
		if($artId){
			$catId = $this->where('articleId',$artId)->value('catId');
		}
		$bcNav = $this->getParents($data,$catId,$isClear=true);
		return $bcNav;

	}
	/**
	* 获取父级分类
	*/
	public function getParents($data, $catId,$isClear=false){
		static $bcNav = [];
		if($isClear)
			$bcNav = [];
		foreach($data as $k=>$v){
			if($catId == $v['catId']){
				if($catId!=0){
					$this->getParents($data, $v['parentId']);
					$bcNav[] = $v;
				}
			}
		}
		return $bcNav;
	}

	/**
	*  记录解决情况
	*/
	public function recordSolve(){
		$articleId =  (int)input('id');
		$status =  (int)input('status');
		if($status==1){
			$rs = $this->where('articleId',$articleId)->setInc('solve');
		}else{
			$rs = $this->where('articleId',$articleId)->setInc('unsolve');
		}
		if($rs!==false){
			return WSTReturn('操作成功',1);
		}else{
			return WSTReturn('操作失败',-1);
		}
	}

	/**
	* 获取资讯中心的子集分类id
	*/
	public function getChildIds(){
		$ids = [];
		$data = Db::name('article_cats')->cache(true)->select();
			foreach($data as $k=>$v){
				if($v['parentId']!=7 && $v['catId']!=7 && $v['parentId']!=0 ){
					$ids[] = $v['catId'];
				}
			}
		return $ids;
	}

	/**
	* 获取咨询中中心所有文章
	*/
	public function getArticles(){
		// 获取咨询中心下的所有分类id
		$ids = $this->getChildIds();
		$rs = $this->alias('a')
			  ->field('a.*')
			  ->join('__ARTICLE_CATS__ ac','a.catId=ac.catId','inner')
			  ->where(['a.dataFlag'=>1,'a.isShow'=>1,'ac.dataFlag'=>1,'ac.isShow'=>1])
			  ->where([['a.catId','in',$ids],['ac.parentId','<>',7]])->order('a.catSort asc')
			  ->distinct(true)
			  ->cache(true)
			  ->paginate(15);
		return $rs;
	}

	/**
	 * 获取指定分类下的文章
	 */
	public function getArticlesByCat($catId){
        $ids = $this->getChildIds();
		$rs = $this->alias('a')
			  ->field('a.*')
			  ->join('__ARTICLE_CATS__ ac','a.catId=ac.catId','inner')
			  ->where(['a.dataFlag'=>1,'a.isShow'=>1,'ac.dataFlag'=>1,'ac.isShow'=>1])
			  ->where([['a.catId','in',$ids],['ac.parentId','<>',7]])->order('a.catSort asc')
			  ->distinct(true)
			  ->cache(true)
			  ->select();
	    $data = [];
		if(!empty($rs)){
			foreach($rs as $key =>$v){
                $data[$v['articleId']] = $v;
			}
		}
		return $data;
	}
}
