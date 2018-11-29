<?php
namespace addons\reward\model;
use think\addons\BaseModel as Base;
use addons\reward\validate\Rewards as Validate;
use think\Db;
use think\Loader;
/**
 * 满就送接口
 */
class Rewards extends Base{
	protected $pk = 'rewardId';
	/***
     * 安装插件
     */
    public function installMenu(){
    	Db::startTrans();
		try{
			$hooks = ['afterQueryGoods','homeDocumentGoodsPromotionDetail','afterQueryCarts','afterUserReceive',
                     'homeDocumentCartGoodsPromotion','homeDocumentSettlementGoodsPromotion',
                     'mobileDocumentCartGoodsPromotion','mobileDocumentSettlementGoodsPromotion','mobileDocumentGoodsPromotionDetail','mobileDocumentOrderViewGoodsPromotion',
                     'wechatDocumentCartGoodsPromotion','wechatDocumentSettlementGoodsPromotion','wechatDocumentGoodsPromotionDetail','wechatDocumentOrderViewGoodsPromotion',
                     'beforeInsertOrder','beforeInsertOrderGoods','homeDocumentOrderViewGoodsPromotion','adminDocumentOrderViewGoodsPromotion'
                     ];
			$this->bindHoods("Reward", $hooks);
			$now = date("Y-m-d H:i:s");
			//商家中心
			Db::name('home_menus')->insert(["parentId"=>77,"menuName"=>"满就送活动","menuUrl"=>"addon/reward-shops-index","menuOtherUrl"=>"addon/reward-shops-edit,addon/reward-shops-pageQuery,addon/reward-shops-toEdit,addon/reward-shops-del","menuType"=>1,"isShow"=>1,"menuSort"=>1,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"reward"]);
			installSql("reward");
			Db::commit();
			return true;
		}catch (\Exception $e) {
	 		Db::rollback();
	  		return false;
	   	}
    }

    /**
	 * 删除菜单
	 */
	public function uninstallMenu(){
		Db::startTrans();
		try{
			$hooks = ['afterQueryGoods','homeDocumentGoodsPromotionDetail','afterQueryCarts','afterUserReceive',
                     'homeDocumentCartGoodsPromotion','homeDocumentSettlementGoodsPromotion',
                     'mobileDocumentCartGoodsPromotion','mobileDocumentSettlementGoodsPromotion','mobileDocumentGoodsPromotionDetail','mobileDocumentOrderViewGoodsPromotion',
                     'wechatDocumentCartGoodsPromotion','wechatDocumentSettlementGoodsPromotion','wechatDocumentGoodsPromotionDetail','wechatDocumentOrderViewGoodsPromotion',
                     'beforeInsertOrder','beforeInsertOrderGoods','homeDocumentOrderViewGoodsPromotion','adminDocumentOrderViewGoodsPromotion'
                     ];
            $this->unbindHoods("Reward", $hooks);
			Db::name('home_menus')->where(["menuMark"=>"reward"])->delete();
			uninstallSql("reward");//传入插件名
			Db::commit();
			return true;
		}catch (\Exception $e) {
	 		Db::rollback();
	  		return false;
	   	}
	}

	/**
	 * 菜单显示隐藏
	 */
	public function toggleShow($isShow = 1){
		Db::startTrans();
		try{
			Db::name('home_menus')->where(["menuMark"=>"reward"])->update(["isShow"=>$isShow]);
			if($isShow==1){
				$this->addMobileBtn();
			}else{
				$this->delMobileBtn();
			}
			Db::commit();
			return true;
		}catch (\Exception $e) {
	 		Db::rollback();
	  		return false;
	   	}
	}

	/**
     * 商家-满就送列表
     */
    public function pageQueryByShop(){
    	$rewardTitle = (int)input('rewardTitle');
        $shopId = (int)session('WST_USER.shopId');
        $where = ['dataFlag'=>1,'shopId'=>$shopId];
        if($rewardTitle!='')$where[] = ['rewardTitle','like','%'.$rewardTitle.'%'];
    	$page =  $this->where($where)
                     ->order('createTime desc')
                     ->paginate(input('pagesize/d'))->toArray();
        $page['status'] = 1;
        if(count($page['data'])>0){
            $time = time();
            foreach ($page['data'] as $key => $v) {
                if(strtotime($v['startDate']." 00:00:00")>$time){
                    $page['data'][$key]['rewardStatus'] = -1;
                }else if((WSTStrToTime($v['startDate']." 00:00:00")<=$time) && (WSTStrToTime($v['endDate']." 23:59:59")>=$time)){
                    $page['data'][$key]['rewardStatus'] = 0;
                }else{
                    $page['data'][$key]['rewardStatus'] = 1;
                }
            }
        }
        return $page;
    }

    /**
     * 查询商品
     */
    public function searchGoods(){
    	$shopId = (int)session('WST_USER.shopId');
    	$shopCatId1 = (int)input('post.shopCatId1');
    	$shopCatId2 = (int)input('post.shopCatId2');
    	$goodsName = input('post.goodsName');
    	$where = [];
    	$where['goodsStatus'] = 1;
    	$where['dataFlag'] = 1;
    	$where['isSale'] = 1;
        $where['goodsType'] = 0;
    	$where['shopId'] = $shopId;
    	if($shopCatId1>0)$where['shopCatId1'] = $shopCatId1;
    	if($shopCatId2>0)$where['shopCatId2'] = $shopCatId2;
    	if($goodsName!='')$where[] = ['goodsName|goodsSn','like','%'.$goodsName.'%'];
    	$rs = Db::name('goods')->where($where)->field('goodsImg,goodsName,goodsId,marketPrice,shopPrice,goodsType')->order('goodsName asc')->select();
        return WSTReturn('',1,$rs);
    }
    /**
     * 获取在售商品列表
     */
    public function getSaleGoods(){
    	$shopId = (int)session('WST_USER.shopId');
    	$where = [];
    	$where['goodsStatus'] = 1;
    	$where['dataFlag'] = 1;
        $where['goodsType'] = 0;
    	$where['isSale'] = 1;
    	$where['shopId'] = $shopId;
    	$rs = Db::name('goods')->where($where)->field('goodsName,goodsId')->order('goodsName asc')->select();
        return WSTReturn('',1,$rs);
    }

    /**
     * 获取优惠券
     */
    public function getCoupons(){
        $shopId = (int)session('WST_USER.shopId');
    	$rs = Db::name('coupons')->where('endDate>"'.date('Y-m-d').'" and dataFlag=1 and shopId='.$shopId)
    	        ->field('couponId,couponValue,useCondition,useMoney')
    	        ->order('couponId asc')
    	        ->select();
        return WSTReturn('',1,$rs);
    }

    /**
     * 新增
     */
    public function add(){
        $data = input('post.');
        unset($data['rewardId']);
        $shopId = (int)session('WST_USER.shopId');
        $goodsIds = explode(',',$data['useObjectIds']);
        $validate = new Validate;
        if(!$validate->check($data)){
            return WSTReturn($validate->getError());
        }
        //判断有没有同一时间的全店满送活动
        $where = [];
        $where['dataFlag'] = 1;
        if($data['useObjects']==1)$where['useObjects'] = 0;
        $where['shopId'] = $shopId;
        $whereOr = ' ( ("'.date('Y-m-d',strtotime($data['startDate'])).'" between startDate and endDate) or ( "'.date('Y-m-d',strtotime($data['endDate'])).'" between startDate and endDate) ) ';
        $rn = $this->where($where)->where($whereOr)->Count();
        if($rn>0)return WSTReturn('已存在相同时段的全店满就送活动');
        $goods = [];
        //判断商品是否存在
        if($data['useObjects']==1){
            $goods = Db::name('goods')->where([['goodsId','in',$goodsIds],['shopId','=',$shopId],['goodsType','=',0],['isSale','=',1],['goodsStatus','=',1],['dataFlag','=',1]])
                       ->field('goodsId,goodsName')->select();
            if(empty($goods))return WSTReturn('请选择活动适用的商品');
            foreach ($goods as $key => $gv) {
                $where = [];
                $where['r.useObjects'] = 1;
                $where['r.dataFlag'] = 1;
                $where['g.goodsId'] = $gv['goodsId'];
                $where['r.shopId'] = $shopId;
                $whereOr = ' ( ("'.date('Y-m-d',strtotime($data['startDate'])).'" between startDate and endDate) or ( "'.date('Y-m-d',strtotime($data['endDate'])).'" between startDate and endDate) ) ';
                $rn = $this->alias('r')->join('__REWARD_GOODS__ g','r.rewardId=g.rewardId')->where($where)->where($whereOr)->Count();
                if($rn>0)return WSTReturn('商品【'.$gv['goodsName'].'】已存在相同时段的满就送活动');
            }
        }
        $data['shopId'] = $shopId;
        $data['createTime'] = date('Y-m-d H:i:s');
        Db::startTrans();
        try{
            $result = $this->allowField(true)->save($data);
            if(false !== $result){
                //组装优惠内容
                $no = input('no/d',0);
                $favourables = [];
                for($i=0;$i<$no;$i++){
                    $json = [];
                    $json['rewardId'] = $this->rewardId;
                    $json['orderMoney'] = (int)input('money-'.$i);
                    $cjson = [];
                    $cjson['chk0'] = ((int)input('chk-0-'.$i)!=0)?true:false;
                    $cjson['chk0val'] = (int)input('j-reward-c-0-'.$i);
                    $cjson['chk1'] = ((int)input('chk-1-'.$i)!=0)?true:false;
                    $cjson['chk1val'] = (int)input('j-reward-c-1-'.$i);
                    $cjson['chk2'] = ((int)input('chk-2-'.$i)!=0)?true:false;
                    $cjson['chk3'] = ((int)input('chk-3-'.$i)!=0)?true:false;
                    $cjson['chk3val'] = (int)input('j-reward-c-3-'.$i);
                    $json['favourableJson'] = json_encode($cjson);
                    $favourables[] = $json;
                }
                Db::name('reward_favourables')->insertAll($favourables);
                if($data['useObjects']==1){
                    //保存优惠券适用的商品
                    $arr = [];
                    for($i=0;$i<count($goods);$i++){
                        $cgoods = [];
                        $cgoods['goodsId'] = $goods[$i]['goodsId'];
                        $cgoods['rewardId'] = $this->rewardId;
                        $arr[] = $cgoods;
                    }
                    Db::name('reward_goods')->insertAll($arr);
                }
            }
            Db::commit();
            return WSTReturn('新增成功',1);
        }catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('新增失败');
        }
    }

    /**
     * 获取活动信息
     */
    public function getById(){
        $id = (int)input('id/d',0);
        $shopId = (int)session('WST_USER.shopId');
        $reward = $this->where(['rewardId'=>$id,'dataFlag'=>1,'shopId'=>$shopId])->find();
        if($reward){
        	$reward['goods'] = [];
        	//获取活动优惠信息
        	$rewardJson = Db::name('reward_favourables')->where('rewardId',$id)->order('orderMoney asc')->select();
        	foreach ($rewardJson as $key => $v) {
        		$rewardJson[$key]['favourableJson'] = json_decode($v['favourableJson']);
        	}
        	$reward['rewardJson'] = $rewardJson;
        	//获取适用商品
        	if($reward['useObjects']==1){
        		$reward['goods'] = Db::name('reward_goods')->alias('cg')
        		->join('__GOODS__ g','g.goodsId=cg.goodsId and g.isSale=1 and g.dataFlag=1 and g.goodsStatus=1 and goodsType=0','inner')
        		->where('cg.rewardId',$id)
        		->field('goodsImg,goodsName,g.goodsId,marketPrice,shopPrice,goodsType')
        		->select();
        	}
        }
        return $reward;
    }
    /**
     * 编辑活动
     */
    public function edit(){
        $data = input('post.');
        $shopId = (int)session('WST_USER.shopId');
        $goodsIds = explode(',',$data['useObjectIds']);
        $validate = new Validate;
        if(!$validate->check($data)){
            return WSTReturn($validate->getError());
        }
        //判断有没有同一时间的全店满送活动
        $where = [];
        $where['dataFlag'] = 1;
        if($data['useObjects']==1)$where['useObjects'] = 0;
        $where[] = ['rewardId','<>',$data['rewardId']];
        $where['shopId'] = $shopId;
        $whereOr = ' ( ("'.date('Y-m-d',strtotime($data['startDate'])).'" between startDate and endDate) or ( "'.date('Y-m-d',strtotime($data['endDate'])).'" between startDate and endDate) ) ';
        $rn = $this->where($where)->where($whereOr)->Count();
        if($rn>0)return WSTReturn('已存在相同时段的满就送活动');
        $reward = $this->where(['rewardId'=>$data['rewardId'],'dataFlag'=>1,'shopId'=>$shopId])->find();
        if(false == $reward)return WSTReturn('编辑失败');
        $goods = [];
        if($data['useObjects']==1){
            $goods = Db::name('goods')->where([['goodsId','in',$goodsIds],['shopId','=',$shopId],['goodsType','=',0],['isSale','=',1],['goodsStatus','=',1],['dataFlag','=',1]])
                       ->field('goodsId,goodsName')->select();
            if(empty($goods))return WSTReturn('请选择活动适用的商品');
            foreach ($goods as $key => $gv) {
                $where = [];
                $where['r.useObjects'] = 1;
                $where['r.dataFlag'] = 1;
                $where[] = ['r.rewardId','<>',$data['rewardId']];
                $where['g.goodsId'] = $gv['goodsId'];
                $where['r.shopId'] = $shopId;
                $whereOr = ' ( ("'.date('Y-m-d',strtotime($data['startDate'])).'" between startDate and endDate) or ( "'.date('Y-m-d',strtotime($data['endDate'])).'" between startDate and endDate) ) ';
                $rn = $this->alias('r')->join('__REWARD_GOODS__ g','r.rewardId=g.rewardId')->where($where)->where($whereOr)->Count();
                if($rn>0)return WSTReturn('商品【'.$gv['goodsName'].'】已存在相同时段的满就送活动');
            }
        }else{
            $data['useObjectIds'] = '';
        }
        WSTAllow($data,'rewardId,rewardTitle,startDate,endDate,rewardType,useObjects,useObjectIds');
        Db::startTrans();
        try{
            $result = $this->update($data,['rewardId'=>$data['rewardId'],'shopId'=>$shopId]);
            if(false !== $result){
                Db::name('reward_goods')->where('rewardId',$data['rewardId'])->delete();
                Db::name('reward_favourables')->where('rewardId',$data['rewardId'])->delete();
                //组装优惠内容
                $no = input('no/d',0);
                $favourables = [];
                for($i=0;$i<$no;$i++){
                    $json = [];
                    $json['rewardId'] = $data['rewardId'];
                    $json['orderMoney'] = (int)input('money-'.$i);
                    $cjson = [];
                    $cjson['chk0'] = ((int)input('chk-0-'.$i)!=0)?true:false;
                    $cjson['chk0val'] = (int)input('j-reward-c-0-'.$i);
                    $cjson['chk1'] = ((int)input('chk-1-'.$i)!=0)?true:false;
                    $cjson['chk1val'] = (int)input('j-reward-c-1-'.$i);
                    $cjson['chk2'] = ((int)input('chk-2-'.$i)!=0)?true:false;
                    $cjson['chk3'] = ((int)input('chk-3-'.$i)!=0)?true:false;
                    $cjson['chk3val'] = (int)input('j-reward-c-3-'.$i);
                    $json['favourableJson'] = json_encode($cjson);
                    $favourables[] = $json;
                }
                Db::name('reward_favourables')->insertAll($favourables);
                if($data['useObjects']==1){
                    $goodsCatIds = [];
                    //保存活动适用的商品
                    $arr = [];
                    for($i=0;$i<count($goods);$i++){
                        $cgoods = [];
                        $cgoods['goodsId'] = $goods[$i]['goodsId'];
                        $cgoods['rewardId'] = $data['rewardId'];
                        $arr[] = $cgoods;
                    }
                    Db::name('reward_goods')->insertAll($arr);
                }
            }
            Db::commit();
            return WSTReturn('编辑成功',1);
        }catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('编辑失败');
        }
    }

    /**
     * 删除活动
     */
    public function del(){
        $shopId = (int)session('WST_USER.shopId');
        $id = (int)input('id/d',0);
        $this->where(['rewardId'=>$id,'shopId'=>$shopId])->update(['dataFlag'=>-1]);
        return WSTReturn('删除成功',1);
    }

    /**
     * 判断商品是否有参与满就送活动
     */
    public function getGoodsRewardTags($goodsId){
        $time = date('Y-m-d');
        //查询是否有针对该商品的活动
        $hasReward = Db::name('reward_goods')->alias('rg')
                       ->join('__REWARDS__ r','rg.rewardId=r.rewardId')
                       ->where('r.dataFlag=1 and goodsId='.$goodsId.' and startDate<="'.$time.'" and endDate>="'.$time.'"')->count();
        if($hasReward>0)return 1;
        //查询一下是否有针对全店分类的活动
        $goods = Db::name('goods')->where(['goodsId'=>$goodsId,'goodsType'=>0])->field('shopId')->find();
        if(empty($goods))return 0;
        $hasReward = Db::name('rewards')->where('dataFlag=1 and useObjects=0 and shopId='.$goods['shopId'].' and startDate<="'.$time.'" and endDate>="'.$time.'"')->count();
        if($hasReward>0)return 1;
        return 0;
    }

    /**
     * 获取可用的满减活动
     */
    public function getAvailableRewards($shopId,$goodsId){
        $goods = Db::name('goods')->where(['goodsId'=>$goodsId,'goodsType'=>0])->find();
        if(empty($goods))return [];
        $date = date('Y-m-d');
        //先查看有没有指定商品的满就送活动
        $reward = $this->alias('r')->join('__REWARD_GOODS__ rg','r.rewardId=rg.rewardId')
                  ->where([['dataFlag','=',1],['goodsId','=',$goodsId],['startDate','<=',$date],['endDate','>=',$date]])
                  ->order('r.rewardId asc')
                  ->find();
        if(empty($reward)){
            //查看是否有全店的满就送活动
            $reward = $this->where([['dataFlag','=',1],['useObjects','=',0],['shopId','=',$shopId],['startDate','<=',$date],['endDate','>=',$date]])
                      ->order('rewardId asc')
                      ->find();
        }
        if(empty($reward))return [];
        $reward = $reward->toArray();
        $favourables = Db::name('reward_favourables')->where('rewardId',$reward['rewardId'])->order('orderMoney asc')->select();
        foreach ($favourables as $key => $v) {
            $json = json_decode($v['favourableJson'],true);
            if($json['chk1'])$json['chk1val'] = $this->getGoods($json['chk1val']);
            if(WSTConf('WST_ADDONS.coupon')){
                if($json['chk3'])$json['chk3val'] = $this->getCouponById($json['chk3val']);
            }else{
                if($json['chk3'])$json['chk3val'] = [];
            }
            $favourables[$key]['favourableJson'] = $json;    
        }
        $reward['json'] = $favourables;
        return $reward;
    }

    /**
     * 获取指定的商品
     */
    public function getGoods($id){
        $rs = Db::name('goods')->where(['goodsId'=>$id,'goodsType'=>0])->field('goodsId,goodsName,goodsType,shopPrice,goodsImg,isSpec,isFreeShipping')->find();
        //如果有规格，则取默认规格
        if($rs['isSpec']==1){
            $spec = Db::name('goods_specs')->where(['goodsId'=>$rs['goodsId'],'dataFlag'=>1,'isDefault'=>1])->find();
            if($spec['specIds']!=''){
                $rs['goodsSpecId'] = $spec['id'];
                $specIds = explode(':',$spec['specIds']);
                $specItem = Db::name('spec_items')->alias('s')->join('__SPEC_CATS__ sc','s.catId=sc.catId')
                              ->where([['itemId','in',$specIds]])
                              ->field('catName,itemName')
                              ->order('sc.catSort asc,sc.catId asc')
                              ->select();
                $str = [];
                foreach ($specItem as $key => $v) {
                    $str[] = $v['catName']."：".$v['itemName'];
                }
                $rs['goodsSpecNames'] = implode('@@_@@',$str);
            }
        }
        if(!isset($rs['goodsSpecId'])){
            $rs['goodsSpecId'] = 0;
            $rs['goodsSpecNames'] = 0;
        }
        return ['text'=>$rs['goodsName'],'data'=>$rs];
    }
    /**
     * 获取优惠券
     */
    public function getCouponById($id){
        $rs = Db::name('coupons')->where('couponId',$id)->field('couponId,useCondition,couponValue,useMoney,shopId')->find();
        if($rs['useCondition']==1){
            return ['text'=>"满".$rs['useMoney']."减".$rs['couponValue'],'data'=>$rs];
        }else{
            return ['text'=>"￥".$rs['couponValue'],'data'=>$rs];
        }
    }

    /**
     * 【实物】满就送活动商品排序归类
     */
    public function afterQueryCarts($params){
        foreach ($params['carts']['carts'] as $skey => $shop) {
            foreach ($shop['list'] as $key => $v) {
                //如果存在商品优惠活动则不需要继续
                if(!empty($v['promotion']))continue;
                //获取符合条件的优惠活动
                $promotion = $this->getAvailableRewards($skey,$v['goodsId']);
                if(!empty($promotion)){
                    if($promotion['useObjects']==0 && empty($params['carts']['carts'][$skey]['promotion'])){
                        $params['carts']['carts'][$skey]['promotion']['data'] = $promotion;
                        $params['carts']['carts'][$skey]['promotion']['type'] = 'reward';
                    }
                    $params['carts']['carts'][$skey]['list'][$key]['promotion']['data'] = $promotion;
                    $params['carts']['carts'][$skey]['list'][$key]['promotion']['type'] = 'reward';
                }
            }
            //避免多个活动中有多个全店适用的活动
            if(!empty($params['carts']['carts'][$skey]['promotion'])){
                foreach ($shop['list'] as $key => $v) {
                    $params['carts']['carts'][$skey]['list'][$key]['promotion']['data'] = $params['carts']['carts'][$skey]['promotion']['data'];
                    $params['carts']['carts'][$skey]['list'][$key]['promotion']['type'] = 'reward';
                }
            }
            //对商品按活动进行归类排序
            usort($params['carts']['carts'][$skey]['list'],'self::sortRewardGoods');
            //对商品进行分类标记
            $rewardId = 0;//用于标记优惠活动的第一个商品ID
            $rewardAllGoodsIds = [];//用于标记优惠活动
            //以活动的第一个商品为key，收集和他同一个活动的其他商品的id
            foreach ($params['carts']['carts'][$skey]['list'] as $bkey => $bgoods) {
                if(!empty($bgoods['promotion']) && $bgoods['promotion']['type']=='reward'){
                    if($rewardId!=$bgoods['promotion']['data']['rewardId']){
                            $rewardId = $bgoods['promotion']['data']['rewardId'];
                    }
                    $rewardAllGoodsIds[$rewardId][] = $bgoods['cartId'];
                }else{
                    $rewardId = 0;
                }
            }
            //把收集到的同一个活动的商品ID集合放到第一个商品中
            $rewardId = 0;
            foreach ($params['carts']['carts'][$skey]['list'] as $bkey => $bgoods) {
                if(!empty($bgoods['promotion']) && $bgoods['promotion']['type']=='reward'){
                    if($rewardId!=$bgoods['promotion']['data']['rewardId']){
                        $rewardId = $bgoods['promotion']['data']['rewardId'];
                        $params['carts']['carts'][$skey]['list'][$bkey]['rewardCartIds'] = $rewardAllGoodsIds[$rewardId];
                    }
                }
            }
            //如果是结算的话则要对店铺金额进行处理了
            /**************************************************************************
             结算会改变原来的carts结构
             [
                'promotionMoney'=>'这次购物总共要优惠的金额',
                '1'=> [
                        'shopId'=>1,
                        .....
                        'promotionMoney'=>'店铺要优惠的金额',
                        'promotion'=>['type'=>'reward','data'=>'店铺参与的活动Json-备用']
                        'list'=>[
                             '0'=>[
                                  'cartId'=>'1',
                                  'goodsId'=>'1',
                                  ......
                                  'rewardCartIds'=>'参与活动的商品[cartId]列表--有多个商品参与同一个活动的话则只有第一个活动才有',
                                  'promotion'=>['type'=>'reward','data'=>'商品参与的活动Json'],
                                  'rewardResult'=>'这个商品应该享受的活动优惠Json数组--有多个商品参与同一个活动的话则只有第一个活动才有'
                                  'rewardGoodsMoney'=>'活动商品达到的金额--有多个商品参与同一个活动的话则只有第一个活动才有',
                                  'rewardMoney'=>'活动要减免的金额--有多个商品参与同一个活动的话则只有第一个活动才有',
                                  'rewardText'=>'商品文字描述--有多个商品参与同一个活动的话则只有第一个活动才有'
                             ]
                        ]
                 ]
             ]
             **************************************************************************/
            if($params['isSettlement']){
                foreach ($params['carts']['carts'][$skey]['list'] as $bkey => $bgoods) {
                    //没有优惠活动 或者 优惠活动不是满就送的跳过
                    if(empty($bgoods['promotion']) || $bgoods['promotion']['type']!='reward')continue;  
                    //把活动优惠的结果放到活动的第一个商品上
                    if(isset($bgoods['rewardCartIds'])){
                        $rewardMoney = 0;
                        foreach ($params['carts']['carts'][$skey]['list'] as $tkey => $tgoods){
                             if(in_array($tgoods['cartId'],$bgoods['rewardCartIds'])){
                                  $rewardMoney = $rewardMoney + $tgoods['shopPrice'] * $tgoods['cartNum'];
                             }
                        }
                        //看下计算出来的总金额落在哪个优惠范围内
                        $favourables = $bgoods['promotion']['data']['json'];
                        $params['carts']['carts'][$skey]['list'][$bkey]['rewardResult'] = [];
                        $params['carts']['carts'][$skey]['list'][$bkey]['rewardMoney'] = 0;
                        $params['carts']['carts'][$skey]['list'][$bkey]['rewardGoodsMoney'] = $rewardMoney;
                        for($fkey = count($favourables)-1;$fkey>=0;$fkey--) {
                            if($rewardMoney>=$favourables[$fkey]['orderMoney']){
                                //保存优惠内容-下单时用到
                                $favourableJson = $favourables[$fkey]['favourableJson'];
                                $params['carts']['carts'][$skey]['list'][$bkey]['rewardResult'] = $favourables[$fkey];
                                $params['carts']['carts'][$skey]['list'][$bkey]['rewardMoney'] = 0;
                                //获取优惠文字-用于显示
                                $favourableTxt = [];
                                if($favourableJson['chk0']){
                                    $favourableTxt[] = '减￥'.$favourableJson['chk0val'];
                                    $params['carts']['carts'][$skey]['list'][$bkey]['rewardMoney'] = $favourableJson['chk0val'];
                                    //记录到店铺里边，现在活动要优惠多少
                                    $params['carts']['carts'][$skey]['promotionMoney'] += $favourableJson['chk0val'];
                                    $params['carts']['promotionMoney'] += $favourableJson['chk0val'];
                                }
                                if($favourableJson['chk1'])$favourableTxt[] = '送赠品【'.$favourableJson['chk1val']['text']."】";
                                if($favourableJson['chk2']){
                                    $favourableTxt[] = '免邮费';
                                    //记录到店铺里要免邮费
                                    $params['carts']['carts'][$skey]['isFreeShipping'] = true;
                                }
                                if($favourableJson['chk3'] && !empty($favourableJson['chk3val']))$favourableTxt[] = '送'.$favourableJson['chk3val']['text']."优惠券";
                                $params['carts']['carts'][$skey]['list'][$bkey]['rewardText'] = implode('、',$favourableTxt);
                                break;
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * 排序
     */
    public function sortRewardGoods($a,$b){
        if(!isset($a['promotion']['data']))return 1;//没有优惠活动
        if(!isset($b['promotion']['data']))return -1;//没有优惠活动
        if(!isset($a['promotion']['data']['rewardId']))return 1;
        if(!isset($b['promotion']['data']['rewardId']))return -1;
        if($a['promotion']['data']['rewardId']>$b['promotion']['data']['rewardId']){
           return -1;
        }else{
           return 1;
        }
    }

    /**
     * 重新计算运费和订单金额
     */
    public function beforeInsertOrder($params){
        //修改订单价格和运费
        $carts = $params['carts']['carts'][$params['order']['shopId']];
        //记录参与的店铺活动-全店的活动才记录，商品的活动不记录在这里
        if(!empty($carts['promotion']) && $carts['promotion']['type']=='reward'){
            $params['order']['extraJson'] = json_encode(['orderCode'=>'reward','extraJson'=>json_encode($carts['promotion']['data'])]);
        }
    }

    /**
     * 重新计算订单商品
     */
    public function beforeInsertOrderGoods($params){
        $orderGoods = $params['orderGoods'];
        $order = model('orders')->get($params['orderId']);
        $carts = $params['carts']['carts'][$order['shopId']];
        $gifts = [];
        foreach ($carts['list'] as $key => $goods) {
            if(!empty($goods['promotion']) && $goods['promotion']['type']=='reward'){
                $rewardCartIds = [];//一同参与活动的商品
                $rewardResult = []; //应该享受的优惠
                $rewardText = '';   //优惠文字描述
                $rewardMoney = 0;//优惠应该减免的金额
                if(isset($goods['rewardCartIds'])){
                    $rewardCartIds = $goods['rewardCartIds'];
                    if(empty($goods['rewardResult']))continue;//没有获得优惠就跳过
                    $rewardResult = $goods['rewardResult'];
                    $rewardText = $goods['rewardText'];
                    $rewardMoney = $goods['rewardMoney'];

                    //将cartId转换成商品ID
                    $promotionGoodsIds = [];
                    foreach ($carts['list'] as $gkey => $gv) {
                        if(in_array($gv['cartId'],$rewardCartIds))$promotionGoodsIds[] = $gv['goodsId'];
                    }
                    //如果有送赠品则加入赠品
                    $favourable = $rewardResult['favourableJson'];
                    if($favourable['chk1']){
                        $gifts[] = [
                                      'orderId'=>$params['orderId'],
                                      'goodsId'=>$favourable['chk1val']['data']['goodsId'],
                                      'goodsNum'=>1,
                                      'goodsPrice'=>0,
                                      'goodsSpecId'=>$favourable['chk1val']['data']['goodsSpecId'],
                                      'goodsSpecNames'=>$favourable['chk1val']['data']['goodsSpecNames'],
                                      'goodsName'=>$favourable['chk1val']['text'],
                                      'goodsImg'=>$favourable['chk1val']['data']['goodsImg'],
                                      'commissionRate'=> 0,
                                      'goodsCode'=>'gift',
                                      'goodsType'=>$favourable['chk1val']['data']['goodsType'],
                                      'extraJson'=>'',
                                      'promotionJson'=>''
                                   ];
                    }
                    
                    //准备好数据，给每个相关的商品都填上
                    $extraJson = ['orderCode'=>'reward',
                                  'text'=>$rewardText,
                                  'promotionMoney'=>$rewardMoney,
                                  'promotionGoodsIds'=>$promotionGoodsIds,
                                  'extraJson'=>json_encode($rewardResult)
                                 ];
                    foreach ($params['orderGoods'] as $okey => $ov) {
                        if(in_array($ov['goodsId'],$promotionGoodsIds))$params['orderGoods'][$okey]['promotionJson'] = json_encode($extraJson);
                    }
                }
            }
        }
        foreach ($gifts as $key => $v) {
             $params['orderGoods'][] = $v;
        }
    }

    /**
     * 用户确认收货
     */
    public function afterUserReceive($params){
        $orderId = $params['orderId'];
        $order = Db::name('orders')->where('orderId',$orderId)->find();
        $orderGoods = Db::name('order_goods')->where('orderId',$orderId)->select();
        //把满就送的筛选出来
        $rewards = [];
        $favourableJson = [];
        foreach ($orderGoods as $key => $v) {
           if($v['promotionJson']!=''){
              $promotionJson = json_decode($v['promotionJson'],true);
              if($promotionJson['orderCode']=='reward'){
                  $promotionJson['extraJson'] = json_decode($promotionJson['extraJson'],true);
                  if(!in_array($promotionJson['extraJson']['rewardId'],$rewards)){
                      $rewards[] = $promotionJson['extraJson']['rewardId'];
                      if($promotionJson['extraJson']['favourableJson']['chk3']){
                          $favourableJson[] = $promotionJson['extraJson']['favourableJson'];
                      }
                  }
              }
           }
        }
        if(WSTConf('WST_ADDONS.coupon')){
            foreach ($favourableJson as $key => $favourable) {
                $coupon = [];
                $coupon['shopId'] = $favourable['chk3val']['data']['shopId'];
                $coupon['couponId'] = $favourable['chk3val']['data']['couponId'];
                $coupon['userId'] = $order['userId'];
                $coupon['createTime'] = date('Y-m-d H:i:s');
                Db::name('coupon_users')->insert($coupon);
                //发送一条用户信息
                $content = "恭喜您从订单【".$order['orderNo']."】中获得".$favourable['chk3val']['text']."优惠券一张";
                WSTSendMsg($order['userId'],$content,['from'=>1,'dataId'=>$orderId]);
            }
        }
    }
}
