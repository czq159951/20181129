<?php
namespace shangtao\app\controller;
use shangtao\app\model\Articles as M;
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
    	}
    	echo(json_encode(WSTReturn('success',1,$data)));die;
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
        $data['domain'] = $this->domain();
        echo(json_encode(WSTReturn('success',1,$data)));die;
    }
    public function geturlNews(){
    	$m = new M();
    	$data = $m->getNewsById();
    	$data['articleContent']=htmlspecialchars_decode($data['articleContent']);
    	echo '<!DOCTYPE html><html><body><style>img{width:100%}</style>'.$data['articleContent'].'<script>window.onload=function(){window.location.hash = 1;document.title = document.body.clientHeight;}</script></body></html>';
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
         echo(json_encode(WSTReturn('success','1',$data)));die;
    }
}
