<?php
namespace shangtao\home\controller;
use shangtao\home\model\Goods as M;
use shangtao\common\model\Goods as CM;
use shangtao\home\model\Attributes as AT;
/**
 * 商品控制器
 */
class Goods extends Base{
    protected $beforeActionList = [
          'checkShopAuth' =>  ['except'=>'search,lists,detail,historybygoods,contrastgoods,contrastdel,contrast']
    ];
    /**
      * 批量删除商品
      */
     public function batchDel(){
        $m = new M();
        return $m->batchDel();
     }
    /**
     * 修改商品库存/价格
     */
    public function editGoodsBase(){
        $m = new M();
        return $m->editGoodsBase();
    }

    /**
    * 修改商品状态
    */
    public function changSaleStatus(){
        $m = new M();
        return $m->changSaleStatus();
    }
    /**
    * 批量修改商品状态 新品/精品/热销/推荐
    */
    public function changeGoodsStatus(){
         $m = new M();
        return $m->changeGoodsStatus();
    }
    /**
    *   批量上(下)架
    */
    public function changeSale(){
        $m = new M();
        return $m->changeSale();
    }
   /**
    *  上架商品列表
    */
	public function sale(){
		return $this->fetch('shops/goods/list_sale');
	}
	/**
	 * 获取上架商品列表
	 */
	public function saleByPage(){
		$m = new M();
		$rs = $m->saleByPage();
		$rs['status'] = 1;
		return $rs;
	}
	/**
	 * 仓库中商品
	 */
    public function store(){
		return $this->fetch('shops/goods/list_store');
	}
    /**
	 * 审核中的商品
	 */
    public function audit(){
		return $this->fetch('shops/goods/list_audit');
	}
	/**
	 * 获取审核中的商品
	 */
    public function auditByPage(){
		$m = new M();
		$rs = $m->auditByPage();
		$rs['status'] = 1;
		return $rs;
	}
	/**
	 * 获取仓库中的商品
	 */
    public function storeByPage(){
		$m = new M();
		$rs = $m->storeByPage();
		$rs['status'] = 1;
		return $rs;
	}
	/**
	 * 违规商品
	 */
    public function illegal(){
		return $this->fetch('shops/goods/list_illegal');
	}
	/**
	 * 获取违规的商品
	 */
	public function illegalByPage(){
		$m = new M();
		$rs = $m->illegalByPage();
		$rs['status'] = 1;
		return $rs;
	}
	
	/**
	 * 跳去新增页面
	 */
    public function add(){
    	$m = new M();
    	$object = $m->getEModel('goods');
    	$object['goodsSn'] = WSTGoodsNo();
    	$object['productNo'] = WSTGoodsNo();
    	$data = ['object'=>$object,'src'=>'add'];
    	return $this->fetch('shops/goods/edit',$data);
    } 
    
    /**
     * 新增商品
     */
    public function toAdd(){
    	$m = new M();
    	return $m->add();
    }
    
    /**
     * 跳去编辑页面
     */
    public function edit(){
    	$m = new M();
    	$object = $m->getById(input('get.id'));
    	$data = ['object'=>$object,'src'=>input('src')];
    	return $this->fetch('shops/goods/edit',$data);
    }
    
    /**
     * 编辑商品
     */
    public function toEdit(){
    	$m = new M();
    	return $m->edit();
    }
    /**
     * 删除商品
     */
    public function del(){
    	$m = new M();
    	return $m->del();
    }
    /**
     * 获取商品规格属性
     */
    public function getSpecAttrs(){
    	$m = new M();
    	return $m->getSpecAttrs();
    }
    /**
     * 进行商品搜索
     */
    public function search(){
    	//获取商品记录
    	$m = new M();
    	$data = [];
    	$data['isStock'] = Input('isStock/d');
    	$data['isNew'] = Input('isNew/d');
        $data['isFreeShipping'] = input('isFreeShipping/d');
    	$data['orderBy'] = Input('orderBy/d');
    	$data['order'] = Input('order/d',1);
    	$data['keyword'] = input('keyword');
    	$data['sprice'] = Input('sprice/d');
    	$data['eprice'] = Input('eprice/d');

        $data['areaId'] = (int)Input('areaId');
        $aModel = model('home/areas');

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
        

    	$data['goodsPage'] = $m->pageQuery();
    	return $this->fetch("goods_search",$data);
    }
    
    /**
     * 获取商品列表
     */
    public function lists(){
    	$catId = Input('cat/d');
    	$goodsCatIds = model('GoodsCats')->getParentIs($catId);
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
        $aModel = model('home/areas');

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

    	$brandIds = Input('brand');


        $bgIds = [];// 品牌下的商品Id
        if(!empty($vs)){
            // 存在筛选条件,取出符合该条件的商品id,根据商品id获取可选品牌
            $goodsId = model('goods')->filterByAttributes();
            $data['brandFilter'] = model('Brands')->canChoseBrands($goodsId);
        }else{
           // 取出分类下包含商品的品牌
           $data['brandFilter'] = model('Brands')->goodsListQuery((int)current($goodsCatIds));
        }
        if(!empty($brandIds))$bgIds = model('Brands')->getGoodsIds($brandIds);


    	$data['price'] = Input('price');
    	//封装当前选中的值
    	$selector = [];
    	//处理品牌
        $brandIds = explode(',',$brandIds);
        $bIds = $brandNames = [];
        foreach($brandIds as $bId){
        	if($bId>0){
        		foreach ($data['brandFilter'] as $key =>$v){
        			if($v['brandId']==$bId){
                        array_push($bIds, $v['brandId']);
                        array_push($brandNames, $v['brandName']);
                    }
        		}
                $selector[] = ['id'=>join(',',$bIds),'type'=>'brand','label'=>"品牌","val"=>join('、',$brandNames)];
            }
        }
        // 当前是否有品牌筛选
        if(!empty($selector)){
            $_s[] = $selector[count($selector)-1];
            $selector = $_s;
            unset($data['brandFilter']);
        }
        $data['brandId'] = Input('brand');

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
        $at = new AT();
    	$goodsFilter = $at->listQueryByFilter($catId);
    	$ngoodsFilter = [];
        if(!empty($vs)){
            // 存在筛选条件,取出符合该条件的商品id,根据商品id获取可选属性进行拼凑
            $goodsId = model('goods')->filterByAttributes();
                // 如果同时有筛选品牌,则与品牌下的商品Id取交集
            if(!empty($bgIds))$goodsId = array_intersect($bgIds,$goodsId);


            $attrs = model('Attributes')->getAttribute($goodsId);
            // 去除已选择属性
            foreach ($attrs as $key =>$v){
                if(!in_array($v['attrId'],$vs))$ngoodsFilter[] = $v;
            }
        }else{
            if(!empty($bgIds))$goodsFilter = model('Attributes')->getAttribute($bgIds);// 存在品牌筛选
            // 当前无筛选条件,取出分类下所有属性
        	foreach ($goodsFilter as $key =>$v){
        		if(!in_array($v['attrId'],$vs))$ngoodsFilter[] = $v;
            }
        }
        if(count($vs)>0){
            $_vv = [];
            $_attrArr = [];
    		foreach ($goodsFilter as $key =>$v){
    			if(in_array($v['attrId'],$vs)){
    				foreach ($v['attrVal'] as $key2 =>$vv){
    					if(strstr(input('v_'.$v['attrId']),$vv)!==false){
                            array_push($_vv, $vv);
                            $_attrArr[$v['attrId']]['attrName'] = $v['attrName'];
                            $_attrArr[$v['attrId']]['val'] = $_vv;
                        }
    				}
                    $_vv = [];
    			}
    		}
            foreach($_attrArr as $k1=>$v1){
                $selector[] = ['id'=>$k1,'type'=>'v_'.$k1,'label'=>$v1['attrName'],"val"=>join('、',$v1['val'])];
            }
    	}
    	$data['selector'] = $selector;
        $attrs = [];
        foreach ($ngoodsFilter as $k => $val) {
           $result = array_unique($ngoodsFilter[$k]['attrVal']);
           $ngoodsFilter[$k]['attrVal'] = $result;
        }
    	$data['goodsFilter'] = $ngoodsFilter;
    	//获取商品记录
    	$m = new M();
    	$data['priceGrade'] = $m->getPriceGrade($goodsCatIds);
    	$data['goodsPage'] = $m->pageQuery($goodsCatIds);
        $catPaths = model('goodsCats')->getParentNames($catId);

        $data['catNamePath'] = '全部商品分类';
        if(!empty($catPaths))$data['catNamePath'] = implode(' - ',$catPaths);
    	return $this->fetch("goods_list",$data);
    }
    
    /**
     * 查看商品详情
     */
    public function detail(){
    	$m = new M();
    	$goods = $m->getBySale(input('goodsId/d',0));
    	if(!empty($goods)){
    	    $history = cookie("history_goods");
    	    $history = is_array($history)?$history:[];
            array_unshift($history, (string)$goods['goodsId']);
            $history = array_values(array_unique($history));
            
			if(!empty($history)){
				cookie("history_goods",$history,25920000);
			}
            // 商品详情延迟加载
            $goods['goodsDesc']=htmlspecialchars_decode($goods['goodsDesc']);
            $rule = '/<img src="\/(upload.*?)"/';
            preg_match_all($rule, $goods['goodsDesc'], $images);
            foreach($images[0] as $k=>$v){
                $goods['goodsDesc'] = str_replace($v, "<img class='goodsImg' data-original=\"".str_replace('/index.php','',request()->root())."/".WSTImg($images[1][$k],3)."\"", $goods['goodsDesc']);
            }
	    	$this->assign('goods',$goods);
            $this->assign('shop',$goods['shop']);
	    	return $this->fetch("goods_detail");
    	}else{
    		return $this->fetch("error_lost");
    	}
    }
    /**
     * 预警库存
     */
    public function stockwarnbypage(){
    	return $this->fetch("shops/stockwarn/list");
    }
    /**
     * 获取预警库存列表
     */
    public function stockByPage(){
    	$m = new M();
    	$rs = $m->stockByPage();
    	$rs['status'] = 1;
    	return $rs;
    }
    /**
     * 修改预警库存
     */
    public function editwarnStock(){
    	$m = new M();
    	return $m->editwarnStock();
    }
    
	/**
	 * 获取商品浏览记录
	 */
	public function historyByGoods(){
		$rs = model('Tags')->historyByGoods(8);
		return WSTReturn('',1,$rs);
	}
	/**
	 *  记录对比商品
	 */
	public function contrastGoods(){
		$id = (int)input('post.id');
		$contras = cookie("contras_goods");
		if($id>0){
			$m = new M();
			$goods = $m->getBySale($id);
			$catId = explode('_',$goods['goodsCatIdPath']);
			$catId = $catId[0];
			if(isset($contras['catId']) && $catId!=$contras['catId'])return WSTReturn('请选择同分类对比',-1);
			if(isset($contras['list']) && count($contras['list'])>3)return WSTReturn('对比栏已满',-1);
			if(!isset($contras['catId']))$contras['catId'] = $catId;
			$contras['list'][$id] = $id;
			cookie("contras_goods",$contras,25920000);
		}
		if(isset($contras['list'])){
			$m = new M();
			$list = [];
			foreach($contras['list'] as $k=>$v){
				$list[] = $m->getBySale($v);
			}
			return WSTReturn('',1,$list);
		}else{
			return WSTReturn('',1);
		}
	}
	/**
	 *  删除对比商品
	 */
	public function contrastDel(){
		$id = (int)input('post.id');
		$contras = cookie("contras_goods");
		if($id>0 && isset($contras['list'])){
			unset($contras['list'][$id]);
			cookie("contras_goods",$contras,25920000);
		}else{
			cookie("contras_goods", null);
		}
		return WSTReturn('删除成功',1);
	}
	/**
	 *  商品对比
	 */
	public function contrast(){
		$contras = cookie("contras_goods");
		$list = [];
		$list = $lists= $saleSpec = $shop = $score = $brand = $spec = [];
		if(isset($contras['list'])){
			$m = new M();
			foreach($contras['list'] as $key=>$value){
				$dara = $m->getBySale($value);
				if(isset($dara['saleSpec'])){
					foreach($dara['saleSpec'] as $ks=>$vs){
						if($vs['isDefault']==1){
							$dara['defaultSpec'] = $vs;
							$dara['defaultSpec']['ids'] = explode(':',$ks);
						}
					}
					$saleSpec[$value] = $dara['saleSpec'];
				}
				$list[] = $dara;
			}
			//第一个商品信息
			$goods = $list[0];
			//对比处理
			$shops['identical'] = $scores['identical'] = $brands['identical'] = 1;
			foreach($list as $k=>$v){
				$shop[$v['goodsId']] = $v['shop']['shopName'];
				if($goods['shop']['shopId']!=$v['shop']['shopId'])$shops['identical'] = 0;
				$score[$v['goodsId']] = $v['scores']['totalScores'];
				if($goods['scores']['totalScores']!=$v['scores']['totalScores'])$scores['identical'] = 0;
				$brand[$v['goodsId']] = $v['brandName'];
				if($goods['brandId']!=$v['brandId'])$brands['identical'] = 0;
				if(isset($v['spec'])){
					foreach($v['spec'] as $k2=>$v2){
						$spec[$k2]['identical'] = 0;
						$spec[$k2]['type'] = 'spec';
						$spec[$k2]['name'] = $v2['name'];
						$spec[$k2]['catId'] = $k2;
						foreach($v2['list'] as $ks22=>$vs22){
							$v['spec'][$k2]['list'][$ks22]['isDefault'] = (in_array($vs22['itemId'],$v['defaultSpec']['ids']))?1:0;
						}
						$spec[$k2]['info'][$v['goodsId']] = $v['spec'][$k2];
					}
				}
			}
			$shops['name'] = '店铺';
			$shops['type'] = 'shop';
			$shops['info'] =  $shop;
			$lists[] = $shops;
			$scores['name'] = '商品评分';
			$scores['type'] = 'score';
			$scores['info'] =  $score;
			$lists[] = $scores;
			$brands['name'] = '品牌';
			$brands['type'] = 'brand';
			$brands['info'] =  $brand;
			$lists[] = $brands;
			foreach($spec as $k3=>$v3){
				$lists[] = $v3;
			}
		}
		$data['list'] = $list;
		$data['lists'] = $lists;
		$data['saleSpec'] = $saleSpec;
		$this->assign('data',$data);
		return $this->fetch("goods_contrast");
	}
}
