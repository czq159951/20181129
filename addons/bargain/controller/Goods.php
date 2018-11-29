<?php
namespace addons\bargain\controller;
use think\addons\Controller;
use addons\bargain\model\Bargains;
use shangtao\wechat\model\Users;
use Request;
/**
 * 全民砍价插件
 */
class Goods extends Controller{
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
		$state = input('param.state');
		if($state==WSTConf('CONF.wxAppCode')){
			$this->weixinUser();
		}
	}
	/**
	 * 参与人
	 */
	public function weixinUser(){
		$we = WSTWechat();
		$wdata = $we->getUserInfo(input('param.code'));//获取openid和access_token
		$userinfo = session('WST_WX_USERINFOU');
		if(empty($userinfo['openid'])){
			$userinfo = $we->UserInfo($wdata);
			session('WST_WX_USERINFOU',$userinfo);
		}
		if($userinfo['openid']!=''){
			//获取标识
			$signinfo = $we->wxUserInfo($userinfo['openid']);
			session('WST_WX_SIGNINFOU',$signinfo);
		}
	}
	/**
	 * 微信砍价列表页
	 */
	public function wxlists(){
		$gModel = model('wechat/GoodsCats');
		$data['goodscats'] = $gModel->getGoodsCats();
		$this->assign("keyword", input('keyword'));
		$this->assign("goodsCatId", input('goodsCatId/d'));
		$this->assign("data", $data);
		$ads['count'] =  count(model("common/Tags")->listAds("wx-ads-bargain",99,86400));
		$ads['width'] = 'width:'.$ads['count'].'00%';
		$this->assign("ads", $ads);
		return $this->fetch("/wechat/index/list");
	}
	/**
	 * 砍价列表
	 */
	public function wxBargainlists(){
		$m = new Bargains();
		$rs = $m->pageQuery();
		if(!empty($rs['data'])){
			foreach ($rs['data'] as $key =>$v){
				$rs['data'][$key]['goodsImg'] = WSTImg($v['goodsImg'],2);
			}
		}
		return $rs;
	}
	/**
	 * 跳转
	 */
	public function shareBargain(){
		$bargainId = (int)input('id');
		$bargainUserId = (int)base64_decode(input('bargainUserId'));
		$url = urlencode(addon_url('bargain://goods/wxdetail',array('id'=>$bargainId,'bargainUserId'=>base64_encode($bargainUserId)),true,true));
		$url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.WSTConf('CONF.wxAppId').'&redirect_uri='.$url.'&response_type=code&scope=snsapi_userinfo&state='.WSTConf('CONF.wxAppCode').'#wechat_redirect';
		header("location:".$url);
        exit;
	}
	/**
	 * 微信商品详情
	 */
	public function wxdetail(){
		$m = new Bargains();
		$goods = $m->getBySale(input('id/d',0));
		$bargainId = (int)input('id');
		if(!empty($goods)){
			$goods['goodsDesc']=htmlspecialchars_decode($goods['goodsDesc']);
			$rule = '/<img src="\/(upload.*?)"/';
			preg_match_all($rule, $goods['goodsDesc'], $images);
	
			foreach($images[0] as $k=>$v){
				$goods['goodsDesc'] = str_replace('/'.$images[1][$k], Request::root().'/'.WSTConf("CONF.goodsLogo") . "\"  data-echo=\"".Request::root()."/".WSTImg($images[1][$k],3), $goods['goodsDesc']);
			}
	
			$history = cookie("history_goods");
			$history = is_array($history)?$history:[];
			array_unshift($history, (string)$goods['goodsId']);
			$history = array_values(array_unique($history));
	
			if(!empty($history)){
				cookie("history_goods",$history,25920000);
			}
			$goods['imgcount'] =  count($goods['gallery']);
			$goods['imgwidth'] = 'width:'.$goods['imgcount'].'00%';
			$this->assign('info',$goods);
			$userId = (int)session('WST_USER.userId');
			$bargainUserId = (int)base64_decode(input('bargainUserId'));
			$this->assign('bargainUserId',input('bargainUserId'));
			//砍价个人信息
			$user = ['userType'=>0];
			$user['bargainType'] = 0;
			if($userId>0 || $bargainUserId>0){
				$userIds = ($bargainUserId>0)?$bargainUserId:$userId;
				$u = new Users();
				$user = $u->getById($userIds);
				$user['bargain'] = $m->checkBargain($userIds,$bargainId);
				$user['bargainType']  = (empty($user['bargain']))?0:1;
				$user['userType'] = ($bargainUserId>0)?1:0;
				//标识
				$signinfo = session('WST_WX_SIGNINFOU');
				if($userId>0)$signinfo['subscribe']=1;
			}else{
				$signinfo['subscribe']=2;
				$userIds = 0;
			}
			$userinfo = session('WST_WX_USERINFOU');
			$this->assign('userinfo',$userinfo);
			$this->assign('signType',$signinfo['subscribe']);
			$this->assign('user',$user);
			//公众号二维码
			$this->assign('weixinCode',WSTConf('CONF.wxAppLogo'));
	        if(WSTConf('CONF.wxenabled')==1){
	        	$we = WSTWechat();
	        	$datawx = $we->getJsSignature(request()->scheme().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	        	$this->assign("datawx", $datawx);
	        }
	        //分享信息
	        $url = addon_url('bargain://goods/shareBargain',array('id'=>$bargainId,'bargainUserId'=>base64_encode($userIds)),true,true);
	        $shareInfo['url'] = $url;
	        $shareInfo['title'] = $goods['goodsName'];
	        $shareInfo['goodsName'] = $goods['shareExplain'];
	        $shareInfo['goodsImg'] = WSTDomain()."/".$goods['goodsImg'];
	        $this->assign('shareInfo', $shareInfo);
			return $this->fetch("/wechat/index/detail");
		}else{
			session('wxdetail','对不起你要找的商品不见了~~o(>_<)o~~');
			$this->redirect('wechat/error/message',['code'=>'wxdetail']);
		}
	}
}