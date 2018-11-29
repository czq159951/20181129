<?php
namespace shangtao\weapp\controller;
use shangtao\common\model\GoodsCats;
use shangtao\common\model\Attributes as AT;
use think\Cache;
/**
 * 商品控制器
 */
class Goods extends Base{
    protected $beforeActionList = [
          'checkAuth' => ['only'=>'historyquery']
    ];
	/**
	 * 商品主页
	 */
	public function index(){
		$m = model('goods');
        $goods = $m->getBySale(input('goodsId/d'));
        // 找不到商品记录
        if(empty($goods))return jsonReturn('未找到商品记录',-1);
        // 删除无用字段
        WSTUnset($goods,'goodsSn,productNo,isSale,isBest,isHot,isNew,isRecom,goodsCatIdPath,goodsCatId,shopCatId1,shopCatId2,brandId,goodsStatus,saleTime,goodsSeoKeywords,illegalRemarks,dataFlag,createTime,read');
        // 猜你喜欢6件商品
        $like = model('Tags')->listByGoods('best',$goods['shop']['catId'],6);
        foreach($like as $k=>$v){
            // 删除无用字段
            unset($like[$k]['shopName']);
            unset($like[$k]['shopId']);
            unset($like[$k]['goodsSn']);
            unset($like[$k]['goodsStock']);
            unset($like[$k]['saleNum']);
            unset($like[$k]['marketPrice']);
            unset($like[$k]['isSpec']);
            unset($like[$k]['appraiseNum']);
            unset($like[$k]['visitNum']);
            // 替换商品图片
            $like[$k]['goodsImg'] = WSTImg($v['goodsImg'],3,'goodsLogo');
        }
        $goods['like'] = $like;
        $goods['carts'] = model('weapp/carts')->cartNum();
        $goods['consult'] = model('GoodsConsult')->firstQuery($goods['goodsId']);
        return jsonReturn('success',1,$goods);
	}
    // 获取商品详情
    public function goodsDetail(){
        $detail = model('goods')->getGoodsDetail((int)input('goodsId'));
        if($detail){
        	$detail['goodsDesc'] = htmlspecialchars_decode($detail['goodsDesc']);
        	$rule = '/<img src="\/(upload.*?)"/';
        	preg_match_all($rule, $detail['goodsDesc'], $images);
        	foreach($images[0] as $k=>$v){
        		$detail['goodsDesc'] = str_replace('/'.$images[1][$k], url('/','','',true).WSTImg($images[1][$k],3), $detail['goodsDesc']);
        	}
        }
        return jsonReturn('success',1,$detail);
    }
    
    /**
     * 获取商品列表
     */
    public function pageQuery(){
    	$m = model('goods');
    	$gc = new GoodsCats();
    	$catId = (int)input('catId');
    	if($catId>0){
    		$goodsCatIds = $gc->getParentIs($catId);
    	}else{
    		$goodsCatIds = [];
    	}

         //处理已选属性
        $vs = input('vs');
        $vs = ($vs!='')?explode(',',$vs):[];
        $at = new AT();
        $goodsFilter = $at->listQueryByFilter((int)input('catId/d'));
        $ngoodsFilter = [];
        if(!empty($vs)){
            // 存在筛选条件,取出符合该条件的商品id,根据商品id获取可选属性进行拼凑
            $goodsId = model('goods')->filterByAttributes();

            $attrs = model('Attributes')->getAttribute($goodsId);
            // 去除已选择属性
            foreach ($attrs as $key =>$v){
                if(!in_array($v['attrId'],$vs)){$ngoodsFilter[] = $v;}
            }
        }else{
            // 当前无筛选条件,取出分类下所有属性
            foreach ($goodsFilter as $key =>$v){
                if(!in_array($v['attrId'],$vs))$ngoodsFilter[] = $v;
            }
        }

    	$rs['goodsPage'] = $m->pageQuery($goodsCatIds);

        foreach ($ngoodsFilter as $k => $val) {
           $result = array_values(array_unique($ngoodsFilter[$k]['attrVal']));

           $ngoodsFilter[$k]['attrVal'] = $result;
        }
        $rs['goodsFilter'] = $ngoodsFilter;
    	foreach ($rs['goodsPage']['data'] as $key =>$v){
    		$rs['goodsPage']['data'][$key]['goodsImg'] = WSTImg($v['goodsImg'],3,'goodsLogo');
    		$rs['goodsPage']['data'][$key]['praiseRate'] = ($v['totalScore']>0)?(sprintf("%.2f",$v['totalScore']/($v['totalUsers']*15))*100).'%':'100%';
    	}
    	return jsonReturn('success',1,$rs);
    }
    /**
    * 商品列表热卖推荐
    */
    public function getCatRecom(){
        $catId = (int)input('catId');
        $rs = model('Tags')->listGoods('recom',$catId,8);
        if(!empty($rs)){
            $_rs = [];
            foreach($rs as $k=>$v){
                $_rs['row'][$k]['goodsId'] = $v['goodsId'];
                $_rs['row'][$k]['goodsName'] = $v['goodsName'];
                $_rs['row'][$k]['shopPrice'] = $v['shopPrice'];
                $_rs['row'][$k]['goodsImg'] = $v['goodsImg'];
            }
            return jsonReturn('success',1,$_rs);
        }else{
        	return jsonReturn('暂无热卖推荐',-1);
        }

    }
    /**
    * 获取浏览历史
    */
    public function historyQuery(){
        $data = model('weapp/carts')->cartNum();
        return jsonReturn('success',1,$data);
    }
}
