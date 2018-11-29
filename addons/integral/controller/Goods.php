<?php
namespace addons\integral\controller;

use think\addons\Controller;
use addons\integral\model\Integrals as M;
use Request;
/**
 * 积分商城插件
 */
class Goods extends Controller{
	public function __construct(){
		parent::__construct();
        $m = new M();
        $data = $m->getConf('Integral');
        $this->assign("seoIntegralKeywords",$data['seoIntegralKeywords']);
        $this->assign("seoIntegralDesc",$data['seoIntegralDesc']);
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}

    /**
     * 保存商品信息
     */
    public function edit(){
        $id = (int)input('post.id');
        $m = new M();
        if($id==0){
            return $m->add();
        }else{
            return $m->edit();
        }
    }

    /**
     * 跳去编辑页面
     */
    public function toEdit(){
        $id = (int)input('id');
        $object = [];
        $m = new M();
        $shopCats = $m->getShopCats(0);
        $this->assign("shopCats",$shopCats);
        if($id>0){
            $object = $m->getById($id);
        }else{
            $object = $m->getEModel('integral_goods');
            $object['marketPrice'] = '';
            $object['goodsName'] = '请选择积分商城商品';
            $object['startTime'] = date('Y-m-d H:00:00',strtotime("+2 hours"));
            $object['endTime'] = date('Y-m-d H:00:00',strtotime("+1 month"));
        }
        $this->assign("object",$object);
        return $this->fetch("/admin/edit");
    }

	/**
	 * 积分商城列表
	 */
	public function lists(){
        $catId = (int)input('catId');
        $orderBy = (int)input('orderBy');
        $order = (int)input('order');
        $data = [];
        $data['integralCatId'] = $catId;
        $m = new M();
        $data['goodsPage'] = $m->pageQuery();
        $cats = WSTGoodsCats(0);
        $catName = '全部商品分类';
        foreach($cats as $k => $v){
            if($catId==$v['catId'])$catName = $v['catName'];
        }
        $data['catName'] = $catName;
        $data['catList'] = $cats;
        $userId = (int)session('WST_USER.userId');
        $user = model('common/users')->getFieldsById($userId,["userScore","userMoney","userId"]);
        $this->assign("user",$user);
		return $this->fetch("/home/index/list",$data);
	}

    /**
     * 商品详情
     */
    public function detail(){
        $m = new M();
        $goodsId = input('id/d',0);
        $goods = $m->getBySale($goodsId);
        if(!empty($goods)){
            $history = cookie("history_goods");
            $history = is_array($history)?$history:[];
            array_unshift($history, (string)$goods['goodsId']);
            $history = array_values(array_unique($history));
            
            if(!empty($history)){
                cookie("history_goods",$history,25920000);
            }
            $this->assign('goods',$goods);
            $this->assign('shop',$goods['shop']);
            
            //分享信息
            $conf = $m->getConf('Integral');
            $shareInfo['link'] = addon_url('integral://goods/detail',array('id'=>$goodsId,'shareUserId'=>base64_encode((int)session('WST_USER.userId'))),true,true);
            $shareInfo['title'] = $goods['goodsName'];
            $shareInfo['desc'] = (isset($conf["goodsShareTitle"]) && $conf["goodsShareTitle"]!="")?$conf["goodsShareTitle"]:WSTConf("CONF.mallSlogan");
            $shareInfo['imgUrl'] = WSTDomain()."/".$goods['goodsImg'];
            $this->assign('shareInfo', $shareInfo);
            
            return $this->fetch("/home/index/detail");
        }else{
            $this->redirect('home/error/goods');
        }
    }


    /**
     * 查看积分商城商品列表
     */
    public function pageByAdmin(){
        $this->checkAdminPrivileges();
        $this->assign("areaList",model('common/areas')->listQuery(0));
        return $this->fetch("/admin/list");
    }

    /**
     * 查询积分商城商品
     */
    public function pageQueryByAdmin(){
        $this->checkAdminPrivileges();
        $m = new M();
        return WSTGrid($m->pageQueryByAdmin());
    }
  

    /**
    * 设置违规商品
    */
    public function changeSale(){
        $this->checkAdminPrivileges();
        $m = new M();
        return $m->changeSale();
    }
   

    /**
     * 删除
     */
    public function delByAdmin(){
        $this->checkAdminPrivileges();
        $m = new M();
        return $m->delByAdmin();
    }
    
    /**
     * 微信积分商城列表页
     */
    public function wxlists(){
        $userId = (int)session('WST_USER.userId');
        $user = model('common/users')->getFieldsById($userId,["userScore","userId"]);
        $this->assign("user",$user);

    	$gModel = model('wechat/GoodsCats');
    	$data['goodscats'] = $gModel->getGoodsCats();
    	$this->assign("keyword", input('keyword'));
    	$this->assign("goodsCatId", input('goodsCatId/d'));
    	$this->assign("data", $data);
    	return $this->fetch("/wechat/index/list");
    }
    /**
     * 积分商城列表
     */
    public function glists(){
    	$m = new M();
    	$rs = $m->pageQuery();
    	if(!empty($rs['data'])){
    		foreach ($rs['data'] as $key =>$v){
    			$rs['data'][$key]['goodsImg'] = WSTImg($v['goodsImg'],2);
    		}
    	}
        $userId = (int)session('WST_USER.userId');
        $user = model('common/users')->getFieldsById($userId,["userScore","userMoney","userId"]);
        $rs['User'] = $user;
    	return $rs;
    }
    /**
     * 微信商品详情
     */
    public function wxdetail(){
        $root = WSTDomain();
    	$m = new M();
    	$goodsId = input('id/d',0);
    	$goods = $m->getBySale($goodsId);
    	
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
    		$this->assign('info',$goods);
    	    if(WSTConf('CONF.wxenabled')==1){
		        $we = WSTWechat();
		        $datawx = $we->getJsSignature(request()->scheme().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		        $this->assign("datawx", $datawx);
	        }
    		
    		//分享信息
    		$conf = $m->getConf('Integral');
    		$shareInfo['link'] = addon_url('integral://goods/wxdetail',array('id'=>$goodsId,'shareUserId'=>base64_encode((int)session('WST_USER.userId'))),true,true);
    		$shareInfo['title'] = $goods['goodsName'];
    		$shareInfo['desc'] = (isset($conf["goodsShareTitle"]) && $conf["goodsShareTitle"]!="")?$conf["goodsShareTitle"]:WSTConf("CONF.mallSlogan");
    		$shareInfo['imgUrl'] = WSTDomain()."/".$goods['goodsImg'];
    		$this->assign('shareInfo', $shareInfo);
    		
    		return $this->fetch("/wechat/index/detail");
    	}else{
    		session('wxdetail','对不起你要找的商品不见了~~o(>_<)o~~');
    		$this->redirect('wechat/error/message',['code'=>'wxdetail']);
    	}
    }
    
    /**
     * 手机积分商城列表页
     */
    public function molists(){
        $userId = (int)session('WST_USER.userId');
        $user = model('common/users')->getFieldsById($userId,["userScore","userId"]);
        $this->assign("user",$user);
        
    	$gModel = model('mobile/GoodsCats');
    	$data['goodscats'] = $gModel->getGoodsCats();
    	$this->assign("keyword", input('keyword'));
    	$this->assign("goodsCatId", input('goodsCatId/d'));
    	$this->assign("data", $data);
    	return $this->fetch("/mobile/index/list");
    }
    /**
     * 手机商品详情
     */
    public function modetail(){
        $root = WSTDomain();
    	$m = new M();
    	$goodsId = input('id/d',0);
    	$goods = $m->getBySale($goodsId);
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
    		$this->assign('info',$goods);
    		
    		//分享信息
    		$conf = $m->getConf('Integral');
    		$shareInfo['link'] = addon_url('integral://goods/modetail',array('id'=>$goodsId,'shareUserId'=>base64_encode((int)session('WST_USER.userId'))),true,true);
    		$shareInfo['title'] = $goods['goodsName'];
    		$shareInfo['desc'] = (isset($conf["goodsShareTitle"]) && $conf["goodsShareTitle"]!="")?$conf["goodsShareTitle"]:WSTConf("CONF.mallSlogan");
    		$shareInfo['imgUrl'] = WSTDomain()."/".$goods['goodsImg'];
    		$this->assign('shareInfo', $shareInfo);
    		
    		return $this->fetch("/mobile/index/detail");
    	}else{
    		session('modetail','对不起你要找的商品不见了~~o(>_<)o~~');
    		$this->redirect('mobile/error/message',['code'=>'modetail']);
    	}
    }
}