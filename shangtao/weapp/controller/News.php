<?php
namespace shangtao\weapp\controller;
use shangtao\weapp\model\Articles as M;
/**
 * 新闻控制器
 */
class News extends Base{
    /**
    * 获取商城快讯列表
    */
    public function getNewsList(){
    	$m = new M();
    	$data = $m->getArticles();
    	foreach($data['data'] as $k=>$v){
    		$data['data'][$k]['articleContent'] = strip_tags(html_entity_decode($v['articleContent']));
            $data['data'][$k]['createTime'] = date('Y-m-d',strtotime($data['data'][$k]['createTime']));
            $data['data'][$k]['coverImg'] = WSTImg($data['data'][$k]['coverImg'],2);
    	}
    	return  jsonReturn('success',1,$data);
    }
    /**
    * 查看详情
    */
    public function getNews(){
    	$m = new M();
    	$data = $m->getNewsById();
         if(empty($data)){
            die('文章不存在');
        }
        unset($data['articleContent']);
        $data['createTime'] = date('Y-m-d',strtotime($data['createTime']));
        echo jsonReturn('success',1,$data);die;
    }
    public function geturlNews(){
    	$m = new M();
        $data = $m->getNewsById();
        $data['articleContent']=htmlspecialchars_decode($data['articleContent']);
        $request = request();
        $root = $request->root()?str_replace('/','',$request->root()).'\/':'';
        $rule = '/<img src="\/('.$root.'upload.*?)"/';
        preg_match_all($rule, $data['articleContent'],$images);
        $domain = $this->domain();
        foreach($images[0] as $k=>$v){
            $data['articleContent'] = str_replace($v, "<img src='".$request->domain().'/'.$images[1][$k]."'", $data['articleContent']);
        }
        return jsonReturn('success',1,$data);
    }
    /**
     * 点赞
     */
    public function like(){
        $m = new M();
        $data = $m->like();
        echo(json_encode($data));
    }

    public function getChild(){
         $m = new M();
         $data = $m->getChildInfos();
         return  jsonReturn('success','1',$data);
    }
}
