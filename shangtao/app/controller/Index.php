<?php
namespace shangtao\app\controller;
use shangtao\app\model\Index as M;
use shangtao\common\model\Tags;
use shangtao\app\controller\Base;
/**
 * 默认控制器
 */
class Index extends Base{
     /**
     * 首页楼层数据
     */
    public function pageQuery(){
        $m = new M();
        $rs = $m->pageQuery();
        if(isset($rs['goods'])){
            foreach ($rs['goods'] as $key =>$v){
                $rs['goods'][$key]['goodsImg'] = WSTImg($v['goodsImg'],2);
            }
        }
        return json_encode(WSTReturn('success',1,$rs));
    }
    /**
     * 首页数据
     */
    public function getIndexData(){
        $rs = [];
        $rs = WSTReturn('success',1,$rs);
        // 获取域名
        $rs['domain'] = $this->domain();
        // 获取轮播图
        $model = new Tags();
        $rs['swiper'] = $this->transitionImg($model->listAds('app-ads-index',99));
        // 4张可横向循环滚动广告图
        $rs['ads'] = $this->transitionImg($model->listAds('app-index-small',4));
        // 获取4张广告图
        $rs['indexAds'] = $this->transitionImg($model->listAds('app-index-large',4));
        // 获取最新资讯
        $rs['news'] = $model->listByNewArticle(4, 86400);
        return json_encode($rs);
    }
    /**
    * 转换图片即删除无用字段
    */
    public function transitionImg($img){
        if(empty($img))return [];
        // 图片转换及删除无用字段
        $_img = [];
        foreach ($img as $k => $v) {
            $_img[$k]['adId'] = $v['adId'];
            $_img[$k]['adURL'] = $v['adURL'];
            $_img[$k]['adFile'] = WSTImg($v['adFile'],2);
        }
        return $_img;
    }
    /**
     * 配置信息
     */
    public function confInfo(){
        $data['smsOpen'] = WSTConf('CONF.smsOpen');//开启短信验证
    	$data['smsVerfy'] = WSTConf('CONF.smsVerfy');//发送短信前是否需要输入验证码
    	$data['userLogo'] = WSTConf('CONF.userLogo');//会员默认头像
    	$data['shopLogo'] = WSTConf('CONF.shopLogo');//店铺默认头像
        $data['goodsLogo'] = WSTConf('CONF.goodsLogo');//商品默认图片
    	$data['hotWordsSearch'] = WSTConf('CONF.hotWordsSearch');//商品热搜词
    	$data['mallName'] = WSTConf('CONF.mallName');//商城名称
		$data['serviceTel'] = WSTConf('CONF.serviceTel');//联系电话
		$data['serviceQQ'] = WSTConf('CONF.serviceQQ');//客服QQ
		$data['serviceEmail'] = WSTConf('CONF.serviceEmail');//联系邮箱
		$data['copyRight'] = WSTConf('CONF.copyRight');//版权所有
    	return json_encode(WSTReturn('success',1,$data));
    }
    /**
    * 获取用户未读消息数
    */
    public function getSysMsgs(){
        $rs = model('index')->getSysMsg('msg');
        $num = $rs['message']['num'];
        return json_encode(['num'=>$num]);
    }
}
