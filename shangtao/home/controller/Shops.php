<?php
namespace shangtao\home\controller;
use shangtao\home\model\Goods;
use shangtao\common\model\GoodsCats;
use shangtao\home\validate\Shops as Validate;
use think\Loader;
/**
 * 门店控制器
 */

class Shops extends Base{
    protected $beforeActionList = [
          'checkShopAuth' =>  ['only'=>'editinfo,getshopmoney'],
          'checkAuth'=>['only'=>'join,joinstep1,joinstep2,savestep2,joinstep3,savestep3,joinstep4,savestep4,joinstep5,savestep5,joinsuccess']
    ];
    /**
    * 店铺公告页
    */
    public function notice(){
        $notice = model('shops')->getNotice();
        $this->assign('notice',$notice);
        return $this->fetch('shops/shops/notice');
    }
    /**
    * 修改店铺公告
    */
    public function editNotice(){
        $s = model('shops');
        return $s->editNotice();
    }
	/**
	 * 商家登录
	 */
	public function login(){
		$USER = session('WST_USER');
		if(!empty($USER) && isset($USER['shopId'])){
			$this->redirect("shops/index");
		}
		$loginName = cookie("loginName");
		if(!empty($loginName)){
			$this->assign('loginName',cookie("loginName"));
		}else{
			$this->assign('loginName','');
		}
		return $this->fetch('shop_login');
	}
	/**
	 * 商家中心
	 */
	public function index(){
		session('WST_MENID1',null);
		session('WST_MENUID31',null);
		$s = model('shops');
		$data = $s->getShopSummary((int)session('WST_USER.shopId'));
		$this->assign('data',$data);
		return $this->fetch('shops/index');
	}
    /**
     * 店铺街
     */
    public function shopStreet(){
    	$g = new GoodsCats();
    	$goodsCats = $g->listQuery(0);
    	$this->assign('goodscats',$goodsCats);
    	//店铺街列表
    	$s = model('shops');
    	$pagesize = 10;
    	$selectedId = input("get.id/d");
    	$this->assign('selectedId',$selectedId);
    	$list = $s->pageQuery($pagesize);
    	$this->assign('list',$list);
    	$this->assign('keyword',input('keyword'));
    	$this->assign('keytype',1);
    	return $this->fetch('shop_street');
    }
    /**
     * 店铺详情
     */
    public function home(){
    	$shopId = (int)input("param.shopId/d");
    	hook("homeBeforeGoShopHome",["shopId"=>$shopId]);
    	
    	$s = model('shops');
    	$data['shop'] = $s->getShopInfo($shopId);
        $ct1 = input("param.ct1/d",0);
        $ct2 = input("param.ct2/d",0);
        $goodsName = input("param.goodsName");
        if(($data['shop']['shopId']==1 || $shopId==0) && $ct1==0 && !isset($goodsName)){
        	$params = input();
        	unset($params["shopId"]);
            $this->redirect(Url('home/shops/selfShop'),$params);
        }
    	if(empty($data['shop']))return $this->fetch('error_lost');
    	$data['shopcats'] = $f = model('ShopCats','model')->getShopCats($shopId);
    	$g = model('goods');
    	$data['list'] = $g->shopGoods($shopId);
    	$this->assign('msort',input("param.msort/d",0));//筛选条件
    	$this->assign('mdesc',input("param.mdesc/d",1));//升降序
    	$this->assign('sprice',input("param.sprice"));//价格范围
    	$this->assign('eprice',input("param.eprice"));
    	$this->assign('ct1',$ct1);//一级分类
    	$this->assign('ct2',$ct2);//二级分类
    	$this->assign('goodsName',urldecode($goodsName));//搜索
    	$this->assign('data',$data);
    	return $this->fetch('shop_home');
    }
    
    /**
     * 店铺分类
     */
    public function cat(){
    	$s = model('shops');
    	$shopId = (int)input("param.shopId/d");
    	$data['shop'] = $s->getShopInfo($shopId);
    
    	$ct1 = input("param.ct1/d",0);
    	$ct2 = input("param.ct2/d",0);
    	$goodsName = input("param.goodsName");
    	if(($data['shop']['shopId']==1 || $shopId==0) && $ct1==0 && !isset($goodsName)){
	    	 $params = input();
	    	 unset($params["shopId"]);
	    	 $this->redirect('shops/selfShop',$params);
    	}
    	if(empty($data['shop']))return $this->fetch('error_lost');
    	$data['shopcats'] = $f = model('ShopCats','model')->getShopCats($shopId);
    	$g = model('goods');
    	$data['list'] = $g->shopGoods($shopId);
    	$this->assign('msort',input("param.msort/d",0));//筛选条件
    	$this->assign('mdesc',input("param.mdesc/d",1));//升降序
    	$this->assign('sprice',input("param.sprice"));//价格范围
    	$this->assign('eprice',input("param.eprice"));
    	$this->assign('ct1',$ct1);//一级分类
    	$this->assign('ct2',$ct2);//二级分类
    	$this->assign('goodsName',urldecode($goodsName));//搜索
    	$this->assign('data',$data);
    	return $this->fetch('shop_home');
    }
    
    /**
     * 查看店铺设置
     */
    public function info(){
    	$s = model('shops');
    	$object = $s->getByView((int)session('WST_USER.shopId'));
    	$this->assign('object',$object);
    	return $this->fetch('shops/shops/view');
    }
    /**
    * 自营店铺
    */
    public function selfShop(){
    	hook("homeBeforeGoSelfShop",["shopId"=>1]);
        $s = model('shops');
        $data['shop'] = $s->getShopInfo(1);
        if(empty($data['shop']))return $this->fetch('error_lost');
        $this->assign('selfShop',1);
	    $data['shopcats'] = model('ShopCats')->getShopCats(1);
	    $this->assign('goodsName',urldecode(input("param.goodsName")));//搜索
	    // 店长推荐
	    $data['rec'] = $s->getRecGoods('rec',6);
	    // 热销商品
	    $data['hot'] = $s->getRecGoods('hot',6);
	    $this->assign('data',$data);
	    return $this->fetch('self_shop');
    }

    /**
     * 编辑店铺资料
     */
    public function editInfo(){
        $rs = model('shops')->editInfo();
        return $rs;
    }

    /**
     * 获取店铺金额
     */
    public function getShopMoney(){
        $rs = model('shops')->getFieldsById((int)session('WST_USER.shopId'),'shopMoney,lockMoney,rechargeMoney');
        $urs = model('users')->getFieldsById((int)session('WST_USER.userId'),'payPwd');
        $rs['isSetPayPwd'] = ($urs['payPwd']=='')?0:1;
        $rs['isDraw'] = ((float)WSTConf('CONF.drawCashShopLimit')<=$rs['shopMoney'])?1:0;
        unset($urs);
        return WSTReturn('',1,$rs);
    }


    /**
     * 跳去商家入驻
     */
    public function join(){
        $rs = model('shops')->checkApply();
        $this->assign('isApply',(!empty($rs) && $rs['applyStatus']>=1)?1:0);
        $this->assign('applyStep',empty($rs)?1:$rs['applyStep']);
        $articles = model('Articles')->getArticlesByCat(53);
        // 防止不存在入驻文章时报错
        if(!isset($articles['105']))$articles['105']['articleContent'] = '无相关说明,请咨询商城客服~';
        if(!isset($articles['106']))$articles['106']['articleContent'] = '无相关说明,请咨询商城客服~';
        if(!isset($articles['107']))$articles['107']['articleContent'] = '无相关说明,请咨询商城客服~';
        if(!isset($articles['108']))$articles['108']['articleContent'] = '无相关说明,请咨询商城客服~';
        $this->assign('artiles',$articles);
        return $this->fetch('shop_join');
    }

    public function joinStep1(){
        session('apply_step',1);
        $rs = model('shops')->checkApply();
        $articles = model('Articles')->getArticlesByCat(53);
        // 防止不存在入驻文章时报错
        if(!isset($articles['109']))$articles['109']['articleContent'] = '无相关说明,请咨询商城客服~';
        $this->assign('artiles',$articles);
        return $this->fetch('shop_join_step1');
    }
    public function joinStep2(){
        $step = (int)session('apply_step');
        if($step<1){
            $this->redirect(Url('home/shops/joinStep1'));
            exit();
        }
        session('apply_step',2);
        $apply = model('shops')->getShopApply();
        $this->assign('apply',$apply);
        return $this->fetch('shop_join_step2');
    }
    public function saveStep2(){
        $step = (int)session('apply_step');
        if($step<2){
            return WSTReturn('请勿跳过申请步骤');
        }
        $data = input('post.');
        $validate = new Validate;
        if(!$validate->check($data,[],'applyStep1')){
            return WSTReturn($validate->getError());
        }else{
            return model('shops')->saveStep2($data);
        }
    }
    public function joinStep3(){
        $step = (int)session('apply_step');
        if($step<2){
            $this->redirect(Url('home/shops/joinStep1'));
            exit();
        }
        session('apply_step',3);
        $areas = model('Areas')->listQuery();
        $this->assign('areaList',$areas);
        $apply = model('shops')->getShopApply();
        $this->assign('apply',$apply);
        return $this->fetch('shop_join_step3');
    }
    public function saveStep3(){
        $step = (int)session('apply_step');
        if($step<3){
            return WSTReturn('请勿跳过申请步骤');
        }
        $data = input('post.');
        $validate = new Validate;
        if(!$validate->check($data,[],'applyStep2')){
            return WSTReturn($validate->getError());
        }else{
            return model('shops')->saveStep3($data);
        }
    }
    public function joinStep4(){
        $step = (int)session('apply_step');
        if($step<3){
            $this->redirect(Url('home/shops/joinStep4'));
            exit();
        }
        session('apply_step',4);
        $areas = model('Areas')->listQuery();
        $this->assign('areaList',$areas);
        $banks = model('banks')->listQuery();
        $this->assign('bankList',$banks);
        $apply = model('shops')->getShopApply();
        $this->assign('apply',$apply);
        return $this->fetch('shop_join_step4');
    }
    public function saveStep4(){
        $step = (int)session('apply_step');
        if($step<4){
            return WSTReturn('请勿跳过申请步骤');
        }
        $data = input('post.');
        $validate = new Validate;
        if(!$validate->check($data,[],'applyStep3')){
            return WSTReturn($validate->getError());
        }else{
            return model('shops')->saveStep4($data);
        }
    }
    public function joinStep5(){
        $step = (int)session('apply_step');
        if($step<4){
            $this->redirect(Url('home/shops/joinStep1'));
            exit();
        }
        session('apply_step',5);
        $goodsCatList = model('goodsCats')->listQuery(0);
        $this->assign('goodsCatList',$goodsCatList);
        $apply = model('shops')->getShopApply();
        $this->assign('apply',$apply);
        return $this->fetch('shop_join_step5');
    }
    public function saveStep5(){
        $step = (int)session('apply_step');
        if($step<5){
            return WSTReturn('请勿跳过申请步骤');
        }
        $data = input('post.');
        $validate = new Validate;
        if(!$validate->check($data,[],'applyStep4')){
            return WSTReturn($validate->getError());
        }else{
            return model('shops')->saveStep5($data);
        }
    }
    public function joinSuccess(){
        $step = (int)session('apply_step');
        if($step<5){
            $this->redirect(Url('home/shops/joinStep1'));
        }
        session('apply_step',5);
        $apply = model('shops')->getShopApply();
        $this->assign('apply',$apply);
        return $this->fetch('shop_join_success');
    }
    /**
     * 入驻进度查询
     */
    public function checkapplystatus(){
        $apply = model('shops')->getShopApply();
        if(empty($apply)){
            $this->redirect(Url('home/shops/joinStep1'));
            exit();
        }else{
            if($apply['applyStatus']==0){
                session('apply_step',$apply['applyStep']);
                $this->redirect(Url('home/shops/joinStep'.$apply['applyStep']));
                exit();
            }else{
                $this->assign('apply',$apply);
                return $this->fetch('shop_join_success');
            }
        }
    }
}
