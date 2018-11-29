<?php
namespace shangtao\admin\controller;
use shangtao\admin\model\Shops as M;
/**
 * 店铺控制器
 */
class Shops extends Base{
    public function index(){
        $this->assign("areaList",model('areas')->listQuery(0));
    	return $this->fetch("list");
    }
    public function stopIndex(){
        $this->assign("areaList",model('areas')->listQuery(0));
    	return $this->fetch("list_stop");
    }
    /**
     * 获取分页
     */
    public function pageQuery(){
    	$m = new M();
    	return WSTGrid($m->pageQuery(1));
    }
    /**
     * 停用店铺列表
     */
    public function pageStopQuery(){
    	$m = new M();
    	return WSTGrid($m->pageQuery(-1));
    }
    /**
     * 获取菜单
     */
    public function get(){
    	$m = new M();
    	return $m->get((int)Input("post.id"));
    }
    /**
     * 跳去编辑页面
     */
    public function toEdit(){
    	$m = new M();
    	$id = (int)Input("get.id");
    	if($id>0){
    	    $object = $m->getById((int)Input("get.id"));
    	    $data['object']=$object;
    	}else{
    		$object = $m->getEModel('shops');
    		$object['catshops'] = [];
    		$object['accreds'] = [];
    		$object['loginName'] = '';
    		$data['object']=$object;
    	}
    	$data['goodsCatList'] = model('goodsCats')->listQuery(0);
    	$data['accredList'] = model('accreds')->listQuery(0);
    	$data['bankList'] = model('banks')->listQuery();
    	$data['areaList'] = model('areas')->listQuery(0);  
        if($id>0){
        	return $this->fetch("edit",$data);
        }else{
            return $this->fetch("add",$data);
        }
    }
    
    /**
     * 新增菜单
     */
    public function add(){
    	$m = new M();
    	return $m->add();
    }
    /**
     * 编辑菜单
     */
    public function edit(){
    	$m = new M();
    	return $m->edit();
    }
    /**
     * 删除菜单
     */
    public function del(){
    	$m = new M();
    	return $m->del();
    }
    
    /**
     * 检测店铺编号是否存在
     */
    public function checkShopSn(){
    	$m = new M();
    	$isChk = $m->checkShopSn(input('post.shopSn'),input('shopId/d'));
        if(!$isChk){
    		return ['ok'=>'该店铺编号可用'];
    	}else{
    		return ['error'=>'对不起，该店铺编号已存在'];
    	}
    }
    
    /**
     * 自营店铺后台
     */
    public function inself(){
    	$staffId=session("WST_STAFF");
    	if(!empty($staffId)){
    		$id=1;
    		$s = new M();
    		$r = $s->selfLogin($id);
    		if($r['status']==1){
    			header("Location: ".Url('home/shops/index'));
    			exit();
    		}
    	}
    	header("Location: ".Url('home/shops/selfShop'));
    	exit();
    }

    /**
     * 跳去店铺申请列表
     */
    public function apply(){
        $this->assign("areaList",model('areas')->listQuery(0));
        return $this->fetch("list_apply");
    }
    /**
     * 获取分页
     */
    public function pageQueryByApply(){
        $m = new M();
        return WSTGrid($m->pageQueryByApply());
    }
    /**
     * 去处理开店申请
     */
    public function toHandleApply(){
        $data = [];
        $data['object'] = model('shops')->getShopApply((int)input("get.id"));
        $data['goodsCatList'] = model('goodsCats')->listQuery(0);
        $data['accredList'] = model('accreds')->listQuery(0);
        $data['bankList'] = model('banks')->listQuery();
        $data['areaList'] = model('areas')->listQuery(0); 
        return $this->fetch("edit_apply",$data);
    }

    public function delApply(){
        $m = new M();
        return $m->delApply();
    }

    /**
     * 开店申请处理
     */
    public function handleApply(){
        $m = new M();
        return $m->handleApply();
    }
}
