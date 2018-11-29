<?php
namespace shangtao\app\model;
use think\Db;
/**
 *  文章类
 */
class Articles extends Base{
	protected $pk = 'articleId';

	/**
	* 获取咨询中中心所有文章
	*/
	public function getArticles(){
		// 获取咨询中心下的所有分类id
		$catId = input("catId");
		$rs = $this->alias('a')
			  ->field('a.*')
			  ->join('__ARTICLE_CATS__ ac','a.catId=ac.catId','inner')
			  ->where(['a.catId'=>$catId,
			  		   'a.isShow'=>1,
			  		   'a.dataFlag'=>1,
			  		   'ac.dataFlag'=>1,
			  		   'ac.isShow'=>1,
			  		   'ac.catType'=>0,
			  		   ])
			  ->order('a.catSort asc,a.createTime desc')
			  ->paginate()
			  ->toArray();
		return $rs;
	}

	/**
	*  根据id获取资讯文章
	*/
	public function getNewsById(){
		$id = (int)input('id');
		WSTArticleVisitorNum($id);// 统计文章访问量
		return $this->alias('a')
					->field('a.articleId,a.articleContent,a.createTime,a.articleTitle,a.likeNum')
					->join('__ARTICLE_CATS__ ac','a.catId=ac.catId','inner')
					->where('ac.catType=0 and a.dataFlag=1')
					->cache(true)
					->find($id);
	}

	/**
	 * 点赞
	 */
	public function like(){
	    $id = input("id/d");
		//判断记录是否存在
		$rs = $this->where(['isShow'=>1,'dataFlag'=>1,'articleId'=>$id])->setInc('likeNum',1);
		//判断是否点赞成功
		 if(false !== $rs){
             return WSTReturn("点赞成功", 1);
        }else{
             return WSTReturn("点赞失败", -1);
        }
	}
	/**
	* 获取资讯中心的子集分类
	*/
	public function getChildInfos(){
		$infos = cache('NEW_INFOS');
		$i = 0;
		if(!$infos){
			$data = Db::name('article_cats')->order('catSort asc')->cache(true)->select();
			foreach($data as $k=>$v){
				if($v['parentId']== 8){
					$infos[$i]['catId'] = $v['catId'];
					$infos[$i]['catName'] = $v['catName'];
					$i++;
				}
			}
            cache('NEW_INFOS',$infos);
		}
		return $infos;
	}
	
}
