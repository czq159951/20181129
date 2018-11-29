<?php
namespace addons\distribut\controller;

use think\addons\Controller;
use addons\test\model\Banks as BM;
use addons\distribut\model\Distribut as M;

use shangtao\common\model\GoodsCats as CatM;
use shangtao\common\model\Areas as AreaM;
use shangtao\common\model\Brands as BrandM;
use shangtao\home\model\Attributes as AttM;
use addons\distribut\model\Goods as GM;

class Goods extends Controller{
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
   

    /**
     * 获取商品列表【home】
     */
    public function glist(){
    	$catId = Input('cat/d');
    	$m = new CatM();
    	$goodsCatIds = $m->getParentIs($catId);
    	reset($goodsCatIds);
    	//填充参数
    	$data = [];
    	$data['catId'] = $catId;
    	$data['isStock'] = Input('isStock/d');
    	$data['isNew'] = Input('isNew/d');
        $data['isFreeShipping'] = input('isFreeShipping/d');
    	$data['orderBy'] = Input('orderBy/d');
    	$data['order'] = Input('order/d',1);
    	$data['sprice'] = Input('sprice');
    	$data['eprice'] = Input('eprice');
    	$data['attrs'] = [];
    
    	$data['areaId'] = (int)Input('areaId');
    	$aModel = new AreaM();
    
    	// 获取地区
    	$data['area1'] = $data['area2'] = $data['area3'] = $aModel->listQuery(); // 省级
    
    	// 如果有筛选地区 获取上级地区信息
    	if($data['areaId']!==0){
    		$areaIds = $aModel->getParentIs($data['areaId']);
    		/*
    		 2 => int 440000
    		 1 => int 440100
    		 0 => int 440106
    		*/
    		$selectArea = [];
    		$areaName = '';
    		foreach($areaIds as $k=>$v){
    			$a = $aModel->getById($v);
    			$areaName .=$a['areaName'];
    			$selectArea[] = $a;
    		}
    		// 地区完整名称
    		$selectArea['areaName'] = $areaName;
    		// 当前选择的地区
    		$data['areaInfo'] = $selectArea;
    
    		$data['area2'] = $aModel->listQuery($areaIds[2]); // 广东的下级
    
    		$data['area3'] = $aModel->listQuery($areaIds[1]); // 广州的下级
    	}
    
    	$vs = input('vs');
    	$vs = ($vs!='')?explode(',',$vs):[];
    	foreach ($vs as $key => $v){
    		if($v=='' || $v==0)continue;
    		$v = (int)$v;
    		$data['attrs']['v_'.$v] = input('v_'.$v);
    	}
    	$data['vs'] = $vs;
    	$m = new BrandM();
    	$data['brandFilter'] = $m->listQuery((int)current($goodsCatIds));
    	$data['brandId'] = Input('brand/d');
    	$data['price'] = Input('price');
    	//封装当前选中的值
    	$selector = [];
    	//处理品牌
    	if($data['brandId']>0){
    		foreach ($data['brandFilter'] as $key =>$v){
    			if($v['brandId']==$data['brandId'])$selector[] = ['id'=>$v['brandId'],'type'=>'brand','label'=>"品牌","val"=>$v['brandName']];
    		}
    		unset($data['brandFilter']);
    	}
    	//处理价格
    	if($data['sprice']!='' && $data['eprice']!=''){
    		$selector[] = ['id'=>0,'type'=>'price','label'=>"价格","val"=>$data['sprice']."-".$data['eprice']];
    	}
    	if($data['sprice']!='' && $data['eprice']==''){
    		$selector[] = ['id'=>0,'type'=>'price','label'=>"价格","val"=>$data['sprice']."以上"];
    	}
    	if($data['sprice']=='' && $data['eprice']!=''){
    		$selector[] = ['id'=>0,'type'=>'price','label'=>"价格","val"=>"0-".$data['eprice']];
    	}
    	//处理已选属性
    	$m = new AttM();
    	$goodsFilter = $m->listQueryByFilter($catId);
    	$ngoodsFilter = [];
    	foreach ($goodsFilter as $key =>$v){
    		if(!in_array($v['attrId'],$vs))$ngoodsFilter[] = $v;
    	}
    	if(count($vs)>0){
    		foreach ($goodsFilter as $key =>$v){
    			if(in_array($v['attrId'],$vs)){
    				foreach ($v['attrVal'] as $key2 =>$vv){
    					if($vv==input('v_'.$v['attrId']))$selector[] = ['id'=>$v['attrId'],'type'=>'v_'.$v['attrId'],'label'=>$v['attrName'],"val"=>$vv];;
    				}
    			}
    		}
    	}
    	$data['selector'] = $selector;
    	$data['goodsFilter'] = $ngoodsFilter;
    	
    	
    	
    	//获取商品记录
    	$m = new GM();
    	$data['priceGrade'] = $m->getPriceGrade($goodsCatIds);
    	$data['goodsPage'] = $m->pageQuery($goodsCatIds);
    	
    	$m = new M();
    	$goodsCat = $m->WSTGoodsCat();
    	$this->assign('goodsCat', $goodsCat);
    	$catPaths = model('common/goodsCats')->getParentNames($catId);
        $data['catNamePath'] = '全部商品分类';
        if(!empty($catPaths))$data['catNamePath'] = implode(' - ',$catPaths);
    	return $this->fetch("/home/index/goods_list",$data);
    }
   
    public function mwGoodsList(){
    	$m = new GM();
    	$rs = $m->pageQuery();
    	foreach ($rs['data'] as $key =>$v){
    		$rs['data'][$key]['goodsImg'] = WSTImg($v['goodsImg'],2);
    	}
    	return $rs;
    }
    
}