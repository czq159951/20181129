<?php
namespace shangtao\weapp\controller;
use shangtao\weapp\model\Index as M;
use shangtao\common\model\Tags;
use think\Db;
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
            	$rs['goods'][$key]['goodsImg'] = WSTImg($v['goodsImg'],3,'goodsLogo');
            }
        }
        if(isset($rs['catId'])){
        	return jsonReturn('success',1,$rs);
        }else{
        	return jsonReturn('',-1);
        }
        
    }
    /**
     * 首页数据
     */
    public function getIndexData(){
        $rs = [];
        $m = new M();
        // 获取轮播图
        $model = new Tags();
        $rs['swiper'] = $this->transitionImg($model->listAds('weapp-ads-index',5));
        // 4张可横向循环滚动广告图
        $rs['ads'] = $this->transitionImg($model->listAds('weapp-index-small',4));
        $rs['ads1'] = $this->transitionImg($model->listAds('weapp-index-1',1));
        $rs['ads2'] = $this->transitionImg($model->listAds('weapp-index-2',1));
        $rs['ads3'] = $this->transitionImg($model->listAds('weapp-index-3',1));
        // 获取4张广告图
        $rs['indexAds'] = $this->transitionImg($model->listAds('weapp-index-large',4));
        // 获取最新资讯
        $rs['news'] = $model->listByNewArticle(4, 86400);
        // 获取商城信息
        $rs['message'] = $m->getSysMsg('message');
        //按钮
        $rs['btns'] = WSTMobileBtns(2);
        return jsonReturn('success',1,$rs);
    }
    /**
     * 配置信息
     */
    public function confInfo(){
    	$data['mallName'] = WSTConf('CONF.mallName');//商城名称
        $data['smsOpen'] = WSTConf('CONF.smsOpen');//开启短信验证
    	$data['smsVerfy'] = WSTConf('CONF.smsVerfy');//发送短信前是否需要输入验证码
    	$data['userLogo'] = WSTConf('CONF.userLogo');//会员默认头像
    	$data['shopLogo'] = WSTConf('CONF.shopLogo');//店铺默认头像
    	$data['goodsLogo'] = WSTConf('CONF.goodsLogo');//商品默认图片
    	$data['isOrderScore'] = WSTConf('CONF.isOrderScore');//是否开启积分
    	$data['isCryptPwd'] = WSTConf('CONF.isCryptPwd');//是否密码加密
    	$data['pwdModulusKey'] = WSTConf('CONF.pwdModulusKey');//商城密匙
    	$addons = Db::name('addons')->where(['dataFlag'=>1])->select();
    	if($addons){
    		foreach ($addons as $key =>$v){
    			$data['addons'][$v['name']] = $v['status'];//插件
    		}
    	}
    	session('sessionId','1');
    	$data['sessionId'] = session_id();//sessionId
    	return jsonReturn('success',1,$data);
    }
    public function hots(){
        $rec = WSTConf("CONF.hotWordsSearch");
        return jsonReturn('请求成功',1,explode(',',$rec));
    }
}
