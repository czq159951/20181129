<?php
namespace addons\bargain\model;
use think\addons\BaseModel as Base;
use think\Db;
/**
 * 全民砍价活动插件
 */
class Shops extends Base{
	/**
     * 商家获取砍价商品列表
     */
	public function pageQuery(){
		$goodsName = input('goodsName');
		$shopId = (int)session('WST_USER.shopId');
		$where = ['b.shopId'=>$shopId,'b.dataFlag'=>1];
		if($goodsName !='')$where[] = ['goodsName','like','%'.$goodsName.'%'];
        $page =  Db::name('bargains')->alias('b')->join('__GOODS__ g','g.goodsId=b.goodsId and g.isSale=1 and g.dataFlag=1')
                      ->where($where)
                      ->field('b.*,g.goodsImg,g.goodsName')
                      ->order('updateTime desc')
                      ->paginate(input('pagesize/d'))->toArray();
        if(count($page['data'])>0){
        	$time = time();
        	foreach($page['data'] as $key =>$v){
        		$page['data'][$key]['goodsImg'] = WSTImg($v['goodsImg']); 
        		if(strtotime($v['startTime'])<=$time && strtotime($v['endTime'])>=$time){
        			$page['data'][$key]['status'] = 1; 
        		}else if(strtotime($v['startTime'])>$time){
                    $page['data'][$key]['status'] = 0; 
        		}else{
        			$page['data'][$key]['status'] = -1; 
        		}
        	}
        }
        $page['status'] = 1;
        return $page;
	}

    /**
     * 搜索商品
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
    	$where['shopId'] = $shopId;
    	if($shopCatId1>0)$where['shopCatId1'] = $shopCatId1;
    	if($shopCatId2>0)$where['shopCatId2'] = $shopCatId2;
    	if($goodsName!='')$where[] = ['goodsName','like','%'.$goodsName.'%'];
    	$rs = Db::name('goods')->where($where)->field('goodsName,goodsId,marketPrice,shopPrice')->select();
        return WSTReturn('',1,$rs);
    }

	/**
	 *  获取砍价商品
	 */
	public function getById($id){
		$where = [];
		$where['b.shopId'] = (int)session('WST_USER.shopId');
		$where['b.bargainId'] = $id;
		$where['b.dataFlag'] = 1;
		$where['b.dataFlag'] = 1;
		return Db::name('bargains')->alias('b')
		         ->join('__GOODS__ g','g.goodsId=b.goodsId','left')
		         ->where($where)->field('g.goodsName,g.goodsImg,b.*')
		         ->find();
	}

	/**
	 * 新增砍价
	 */
	public function add(){
		$data = input('post.');
		$shopId = (int)session('WST_USER.shopId');
		$goods = model('common/Goods')->get((int)$data['goodsId']);
		if(empty($goods))return WSTReturn('商品不存在');
		if((float)$data['floorPrice']<=0)return WSTReturn('商品底价必须大于0');
		if((int)$data['goodsStock']<=0)return WSTReturn('商品数量必须大于0');
		if((int)$data['minusNum']<=0)return WSTReturn('砍价刀数必须大于0');
		
		if($goods->goodsStatus!=1 || $goods->isSale!=1 || $goods->dataFlag!=1 || $goods->shopId != $shopId)return WSTReturn('无效的商品');
		if($data['startTime']=='' || $data['endTime']=='')return WSTReturn('请选择有效活动时间');
		if(strtotime($data['startTime']) >= strtotime($data['endTime']))return WSTReturn('活动开始时间必须比活动结束时间早');
		//判断是否已经存在同时间的砍价商品
		$where = [];
		$where['goodsId'] = (int)$data['goodsId'];
		$where['dataFlag'] = 1;
		$whereOr = ' ( ("'.date('Y-m-d H:i:s',strtotime($data['startTime'])).'" between startTime and endTime) or ( "'.date('Y-m-d H:i:s',strtotime($data['endTime'])).'" between startTime and endTime) ) ';
		$rn = Db::name('bargains')->where($where)->where($whereOr)->Count();
		if($rn>0)return WSTReturn('该商品已存在另外一个相同时段的活动中');
		WSTUnset($data,'bargainId,bargainStatus,dataFlag,illegalRemarks');
		$data['shopId'] = $shopId;
		$data['updateTime'] = date('Y-m-d H:i:s'); 
		$data['createTime'] = date('Y-m-d H:i:s'); 
		$result = Db::name('bargains')->insert($data);
		if(false !== $result){
			return WSTReturn('新增成功',1);
		}
		return WSTReturn('新增失败');
	}

	/**
	 * 编辑砍价 
	 */
	public function edit(){
		$data = input('post.');
		$shopId = (int)session('WST_USER.shopId');
		$goods = model('common/Goods')->get((int)$data['goodsId']);
		if(empty($goods))return WSTReturn('商品不存在');
		if((float)$data['floorPrice']<=0)return WSTReturn('商品底价必须大于0');
		if((int)$data['goodsStock']<=0)return WSTReturn('商品数量必须大于0');
		if((int)$data['minusNum']<=0)return WSTReturn('砍价刀数必须大于0');
		
		if($data['startTime']=='' || $data['endTime']=='')return WSTReturn('请选择有效活动时间');
		if(strtotime($data['startTime']) >= strtotime($data['endTime']))return WSTReturn('活动开始时间必须比活动结束时间早');
		//判断是否已经存在同时间的砍价
		$where = [];
		$where['goodsId'] = $data['goodsId'];
		$where[] = ['bargainId','<>',$data['bargainId']];
		$where['dataFlag'] = 1;
		$whereOr = ' ( ("'.date('Y-m-d H:i:s',strtotime($data['startTime'])).'" between startTime and endTime) or ( "'.date('Y-m-d H:i:s',strtotime($data['endTime'])).'" between startTime and endTime) ) ';
		$rn = Db::name('bargains')->where($where)->where($whereOr)->Count();
		if($rn>0)return WSTReturn('该商品已存在另外一个相同时段的活动中');
		WSTUnset($data,'bargainStatus,dataFlag,illegalRemarks');
		$data['bargainStatus'] = 0;
		$result = Db::name('bargains')->where(['bargainId'=>(int)$data['bargainId'],'shopId'=>$shopId])->update($data);
		if(false !== $result){
			return WSTReturn('编辑成功',1);
		}
		return WSTReturn('编辑失败');
	}

	/**
	 * 删除砍价活动
	 */
	public function del(){
		$id = (int)input('id');
		$shopId = (int)session('WST_USER.shopId');
		$result = Db::name('bargains')->where(['shopId'=>$shopId,'bargainId'=>$id])->update(['dataFlag'=>-1]);
        if(false !== $result){
			return WSTReturn('删除成功',1);
		}
		return WSTReturn('删除失败');
	}

	/**
	 * 获取参与者记录
	 */
	public function pageByJoins(){
		$where = [];
		$where['b.shopId'] = (int)session('WST_USER.shopId');
		$where['bu.bargainId'] = (int)input('bargainId');
		$page = Db::name('bargain_users')->alias('bu')
		         ->join('__BARGAINS__ b','b.bargainId=bu.bargainId','inner')
		         ->join('__USERS__ u','u.userId=bu.userId')
                 ->where($where)
                 ->field('bu.*,u.userName,u.userPhoto,b.startPrice')
                 ->order('bu.createTime desc')
                 ->paginate(input('pagesize/d'))->toArray();
        return WSTReturn('',1,$page);
	}
	/**
	 * 获取亲友团列表
	 */
    public function pageByHelps(){
		$where = [];
		$where['bargainJoinId'] = (int)input('bargainJoinId');
		$page = Db::name('bargain_helps')
                 ->where($where)
                 ->order('createTime desc')
                 ->paginate(input('pagesize/d'))->toArray();
        return WSTReturn('',1,$page);
	}
    /**
	 * 获取订单列表
	 */
    public function pageByOrders(){
		$where = [];
		$orderNo = input('post.orderNo');
		$payType = (int)input('post.payType');
		$deliverType = (int)input('post.deliverType');
		$shopId = (int)session('WST_USER.shopId');
		$where = ['shopId'=>$shopId,'dataFlag'=>1,'orderCode'=>'bargain','orderCodeTargetId'=>(int)input('bargainId')];
		if($orderNo!=''){
			$where[] = ['orderNo','like',"%$orderNo%"];
		}
		if($payType > -1){
			$where['payType'] =  $payType;
		}
		if($deliverType > -1){
			$where['deliverType'] =  $deliverType;
		}
		$page = Db::name('orders')->alias('o')->where($where)
		      ->join('__ORDER_REFUNDS__ orf','orf.orderId=o.orderId and refundStatus=0','left')
		      ->field('o.orderId,orderNo,goodsMoney,totalMoney,realTotalMoney,orderStatus,deliverType,deliverMoney,isAppraise,isRefund
		              ,payType,payFrom,userAddress,orderStatus,isPay,isAppraise,userName,orderSrc,o.createTime,orf.id refundId')
			  ->order('o.createTime', 'desc')
			  ->paginate()->toArray();
	    if(count($page['data'])>0){
	    	 $orderIds = [];
	    	 foreach ($page['data'] as $v){
	    	 	 $orderIds[] = $v['orderId'];
	    	 }
	    	 $goods = Db::name('order_goods')->where([['orderId','in',$orderIds]])->select();
	    	 $goodsMap = [];
	    	 foreach ($goods as $v){
	    	 	 $v['goodsSpecNames'] = str_replace('@@_@@','、',$v['goodsSpecNames']);
	    	 	 $goodsMap[$v['orderId']][] = $v;
	    	 }
	    	 foreach ($page['data'] as $key => $v){
	    	 	 $page['data'][$key]['list'] = $goodsMap[$v['orderId']];
	    	 	 $page['data'][$key]['payTypeName'] = WSTLangPayType($v['payType']);
	    	 	 $page['data'][$key]['deliverType'] = WSTLangDeliverType($v['deliverType']==1);
	    	 	 $page['data'][$key]['status'] = WSTLangOrderStatus($v['orderStatus']);
	    	 }
	    }
	    return WSTReturn('',1,$page);
	}
}
