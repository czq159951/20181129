<?php
namespace shangtao\wechat\model;
use think\Db;
/**
 *  文章类
 */
class Articles extends Base{

	/**
	* 获取咨询中中心所有文章
	*/
	public function getArticles(){
		// 获取咨询中心下的所有分类id
		$catId = input('catId');
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
			  ->paginate((int)input('pagesize'));
		return $rs;
	}


	/**
	*  根据id获取资讯文章
	*/
	public function getNewsById(){
		$id = (int)input('id');
		WSTArticleVisitorNum($id);// 统计文章访问量
		$article = $this->alias('a')
					->field('a.*')
					->join('__ARTICLE_CATS__ ac','a.catId=ac.catId','inner')
					->where('ac.catType=0 and a.dataFlag=1 and a.isShow=1 and a.articleId='.$id)
					->cache(true)
					->find();
        $articleId = cookie("like_articleId");
		$articleId = is_array($articleId)?$articleId:[];
		$rc = !empty($articleId)?in_array($id,$articleId):'';
		if($rc){
         $article['likeState'] = 1;
		}else{
         $article['likeState'] = 0;
		}
        return $article;
	}
	/**
	 * 点赞
	 */
	public function like(){
	    $id = input("param.id/d");
		//判断记录是否存在
		$articleId = cookie("wechat_like_articleId");
		$articleId = is_array($articleId)?$articleId:[];
		$rc = !empty($articleId)?in_array($id,$articleId):'';
		if($rc)return WSTReturn("已点赞成功", -1);
		$rs = $this->where(['isShow'=>1,'dataFlag'=>1,'articleId'=>$id])->setInc('likeNum',1);
		//判断是否点赞成功
		if(false !== $rs){
			array_push($articleId,$id);
			cookie("wechat_like_articleId",$articleId,25920000);
			return WSTReturn("点赞成功", 1);
		}else{
			return WSTReturn($this->getError(),-1);
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
