<?php
namespace addons\pintuan\controller;

use think\addons\Controller;
use addons\pintuan\model\Pintuans as M;
use Request;
/**
 * 拼团商品插件
 */
class Goods extends Controller{
    protected $addonStyle = 'default';
	public function __construct(){
		parent::__construct();
        $m = new M();
        $data = $m->getConf('Pintuan');
        $this->addonStyle = ($data['addonsStyle']=='')?'default':$data['addonsStyle'];
        $this->assign("addonStyle",$this->addonStyle);
        $this->assign("seoPintuanKeywords",$data['seoPintuanKeywords']);
        $this->assign("seoPintuanDesc",$data['seoPintuanDesc']);
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}

	
    /**
     * 查看拼团商品列表
     */
    public function pageByAdmin(){
        $this->checkAdminPrivileges();
        $this->assign("areaList",model('common/areas')->listQuery(0));
        return $this->fetch("/admin/list");
    }

    /**
     * 查询拼团商品
     */
    public function pageQueryByAdmin(){
        $this->checkAdminPrivileges();
        $m = new M();
        return WSTGrid($m->pageQueryByAdmin(1));
    }
    /**
     * 查询待审核拼团商品
     */
    public function pageAuditQueryByAdmin(){
        $this->checkAdminPrivileges();
        $m = new M();
        return WSTGrid($m->pageQueryByAdmin(0));
    }

    /**
     * 查看拼团用户列表
     */
    public function openTuanByAdmin(){
        $this->checkAdminPrivileges();
        $this->assign("tuanId",(int)input("tuanId"));
        $this->assign("tuanStatus",(int)input("tuanStatus"));
        return $this->fetch("/admin/open_list");
    }

    /**
     * 查看拼团用户列表
     */
    public function tuanByAdminPageQuery(){
        $this->checkAdminPrivileges();
        $m = new M();
        return WSTGrid($m->tuanByAdminPageQuery());
    }

    /**
    * 设置违规商品
    */
    public function illegal(){
        $this->checkAdminPrivileges();
        $m = new M();
        return $m->illegal();
    }
    /**
     * 通过商品审核
     */
    public function allow(){
        $this->checkAdminPrivileges();
        $m = new M();
        return $m->allow();
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
     * 微信拼团列表页
     */
    public function wxlists(){
    	$gModel = model('wechat/GoodsCats');
    	$data['goodscats'] = $gModel->getGoodsCats();
        $m = new M();
        $maxPuId = $m->getMaxPuId();
        $this->assign("maxPuId", $maxPuId);
    	$this->assign("keyword", input('keyword'));
    	$this->assign("goodsCatId", input('goodsCatId/d'));
    	$this->assign("data", $data);
    	return $this->fetch($this->addonStyle."/wechat/index/list");
    }
    /**
     * 拼团列表
     */
    public function wxGrouplists(){
    	$m = new M();
    	$rs = $m->pageQuery();
    	if(!empty($rs['data'])){
    		foreach ($rs['data'] as $key =>$v){
    			$rs['data'][$key]['goodsImg'] = WSTImg($v['goodsImg'],2);
    		}
    	}
    	return $rs;
    }
    /**
     * 微信商品详情
     */
    public function wxdetail(){
    	$m = new M();
    	$tuanId = input('id/d',0);
    	$goods = $m->getBySale($tuanId);

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
    	    if(WSTConf('CONF.wxenabled')==1){
		        $we = WSTWechat();
		        $datawx = $we->getJsSignature(request()->scheme().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		        $this->assign("datawx", $datawx);
        	}
    		//分享信息
    		$conf = $m->getConfigs();
    		$shareInfo['link'] = addon_url('pintuan://goods/wxdetail',array('id'=>$tuanId,'shareUserId'=>base64_encode((int)session('WST_USER.userId'))),true,true);
    		$shareInfo['title'] = $goods['goodsName'];
    		$shareInfo['desc'] = (isset($conf["goodsShareTitle"]) && $conf["goodsShareTitle"]!="")?$conf["goodsShareTitle"]:WSTConf("CONF.mallSlogan");
    		$shareInfo['imgUrl'] = WSTDomain()."/".$goods['goodsImg'];
    		$this->assign('shareInfo', $shareInfo);
            $pulist = $m->getPulist();
            $this->assign('pulist',$pulist);
    		
    		return $this->fetch($this->addonStyle."/wechat/index/goods_detail");
    	}else{
    		session('wxdetail','对不起你要找的拼团商品已下架~~o(>_<)o~~');
    		$this->redirect('wechat/error/message',['code'=>'wxdetail']);
    	}
    }
    
    


    /**
     * 查看拼团
     */
    public function wxTuanDetail(){
        $m = new M();
        $tuanNo = input('tuanNo/d',0);
        $tuan = $m->getTuanInfo($tuanNo);
        
        if(!empty($tuan)){
            $goods = $m->getBySale($tuan['tuanId']);
			if(empty($goods)){
                session('wxdetail','对不起你要找的拼团商品已下架~~o(>_<)o~~');
                $this->redirect('wechat/error/message',['code'=>'wxdetail']);
            }
            $history = cookie("history_goods");
            $history = is_array($history)?$history:[];
            array_unshift($history, (string)$tuan['goodsId']);
            $history = array_values(array_unique($history));
    
            if(!empty($history)){
                cookie("history_goods",$history,25920000);
            }
            $this->assign('tuan',$tuan);
            $this->assign('info',$goods);
            $we = WSTWechat();
            $datawx = $we->getJsSignature(request()->scheme().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
            $this->assign("datawx", $datawx);
            
            //分享信息
            $conf = $m->getConfigs();
            if($tuan['tuanStatus']>0){
                $shareInfo['link'] = addon_url('pintuan://goods/wxTuanDetail',array('tuanNo'=>$tuanNo,'shareUserId'=>base64_encode((int)session('WST_USER.userId'))),true,true);
            }else{
                $shareInfo['link'] = addon_url('pintuan://goods/wxTuanDetail',"",true,true);
            }
           
            $shareInfo['title'] = $tuan['goodsName'];
            $shareInfo['desc'] = (isset($conf["goodsShareTitle"]) && $conf["goodsShareTitle"]!="")?$conf["goodsShareTitle"]:WSTConf("CONF.mallSlogan");
            $shareInfo['imgUrl'] = WSTDomain()."/".$tuan['goodsImg'];
            $this->assign('shareInfo', $shareInfo);

            return $this->fetch($this->addonStyle."/wechat/index/tuan_detail");
        }else{
            session('wxdetail','对不起你要找的拼团商品已下架~~o(>_<)o~~');
            $this->redirect('wechat/error/message',['code'=>'wxdetail']);
        }
        
    }

    public function getLastTuan(){
        $m = new M();
        $rs = $m->getLastTuan();
        return $rs;
    }
}