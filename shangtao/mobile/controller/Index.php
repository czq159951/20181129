<?php
namespace shangtao\mobile\controller;
use shangtao\mobile\model\Index as M;
/**
 * 默认控制器
 */
class Index extends Base{
	/**
     * 首页
     */
    public function index(){
    	$m = new M();
    	hook('mobileControllerIndexIndex',['getParams'=>input()]);
    	$news = $m->getSysMsg('msg');
    	$this->assign('news',$news);
    	$ads['count'] =  count(model("common/Tags")->listAds("mo-ads-index",99,86400));
    	$ads['width'] = 'width:'.$ads['count'].'00%';
    	$this->assign("ads", $ads);
    	return $this->fetch('index');
    }
    /**
     * 楼层
     */
    public function pageQuery(){
    	$m = new M();
    	$rs = $m->pageQuery();
    	if(isset($rs['goods'])){
    		foreach ($rs['goods'] as $key =>$v){
    			$rs['goods'][$key]['goodsImg'] = WSTImg($v['goodsImg'],3,'goodsLogo');
    		}
    	}
        return $rs;
    }

    /**
     * 转换url
     */
    public function transfor(){
        $data = input('param.');
        $url = $data['url'];
        unset($data['url']);
        echo Url($url,$data);
    }
    /**
     * 跳去登录之前的地址
     */
    public function sessionAddress(){
    	session('WST_MO_WlADDRESS',input('url'));
    	return WSTReturn("", 1);
    }
}
