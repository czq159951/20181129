<?php
namespace addons\bargain\model;
use think\addons\BaseModel as Base;
use addons\bargain\model\Bargains;
use think\Db;
use shangtao\common\model\LogSms;
/**
 * 全民砍价活动插件
 */
class Carts extends Base{
	/**
	 * 下单
	 */
	public function addCart(){
		$userId = (int)session('WST_USER.userId');
		if($userId==0)return WSTReturn('您尚未登录系统，请先登录系统');
		$id = (int)input('post.id');
		$cartNum = (int)input('post.buyNum',1);
		$cartNum = ($cartNum>0)?$cartNum:1;
		//验证传过来的商品是否合法
		$m = new Bargains();
		$chk = $m->checkGoodsSaleSpec($id);
		if($chk['status']==-1)return $chk;
		//检测库存是否足够
		if($chk['data']['stock']<$cartNum)return WSTReturn("下单失败，商品库存不足", -1);
		$carts = [];
		$carts['bargainId'] = $id;
		$carts['cartNum'] = $cartNum;
		session('BARGAIN_CARTS',$carts);
		return WSTReturn("下单商品成功", 1);
	}
	/**
	 * 获取session中购物车列表
	 */
	public function getCarts(){
		$userId = (int)session('WST_USER.userId');
		$tmp_carts = session('BARGAIN_CARTS');
		$where = [];
		$where['b.bargainId'] = $tmp_carts['bargainId'];
		$where['b.dataFlag'] = 1;
		$where['b.bargainStatus'] = 1;
		$where['g.goodsStatus'] = 1;
		$where['g.dataFlag'] = 1;
		$where['g.isSale'] = 1;
		$where['bs.userId'] = $userId;
		$rs = Db::name('bargains')->alias('b')->join('__GOODS__ g','b.goodsId=g.goodsId','inner')
		->join('__SHOPS__ s','s.shopId=b.shopId','left')
		->join('__GOODS_SPECS__ gs','g.goodsId=gs.goodsId and gs.isDefault','left')
		->join('__BARGAIN_USERS__ bs','bs.bargainId = b.bargainId','left')
		->where($where)
		->field('s.userId,s.shopId,s.shopName,g.goodsId,s.shopQQ,shopWangWang,g.goodsName,bs.currPrice shopPrice,b.floorPrice,b.goodsStock,b.orderNum,g.goodsImg,g.goodsCatId,g.goodsType,gs.specIds,gs.id goodsSpecId,b.startTime,b.endTime,g.isFreeShipping')
		->find();
		if(empty($rs))return ['carts'=>[],'goodsTotalMoney'=>0,'goodsTotalNum'=>0];
		// 确保goodsSpecId不为null.
		$rs['goodsSpecId'] = (int)$rs['goodsSpecId'];
		$rs['cartNum'] = $tmp_carts['cartNum'];
		$carts = [];
		$goodsTotalNum = 0;
		$goodsTotalMoney = 0;
		if(!isset($carts['goodsMoney']))$carts['goodsMoney'] = 0;
		$carts['isFreeShipping'] = ($rs['isFreeShipping']==1)?true:false;
		$carts['bargainId'] = $tmp_carts['bargainId'];
		$carts['shopId'] = $rs['shopId'];
		$carts['shopName'] = $rs['shopName'];
		$carts['shopQQ'] = $rs['shopQQ'];
		$carts['userId'] = $rs['userId'];
		$carts['shopWangWang'] = $rs['shopWangWang'];
		//判断能否购买，预设allowBuy值为10，为将来的各种情况预留10个情况值，从0到9
		$rs['allowBuy'] = 10;
		if($rs['goodsStock']<0){
			$rs['allowBuy'] = 0;//库存不足
		}else if($rs['goodsStock']<$tmp_carts['cartNum']){
			$rs['allowBuy'] = 1;//库存比购买数小
		}
		$carts['goodsMoney'] = $carts['goodsMoney'] + $rs['shopPrice'] * $rs['cartNum'];
		$goodsTotalMoney = $goodsTotalMoney + $rs['shopPrice'] * $rs['cartNum'];
		$goodsTotalNum = $rs['cartNum'];
		if($rs['specIds']!=''){
			//加载规格值
			$specs = DB::name('spec_items')->alias('s')->join('__SPEC_CATS__ sc','s.catId=sc.catId','left')
			->where(['s.goodsId'=>$rs['goodsId'],'s.dataFlag'=>1])
			->field('catName,itemId,itemName')
			->select();
			if(count($specs)>0){
				$specMap = [];
				foreach ($specs as $key =>$v){
					$specMap[$v['itemId']] = $v;
				}
				$strName = [];
				if($rs['specIds']!=''){
					$str = explode(':',$rs['specIds']);
					foreach ($str as $vv){
						if(isset($specMap[$vv]))$strName[] = $specMap[$vv];
					}
				}
				$rs['specNames'] = $strName;
			}
		}
		unset($rs['shopName']);
		$carts['goods'] = $rs;
		return ['carts'=>$carts,'goodsType'=>$rs['goodsType'],'goodsTotalMoney'=>$goodsTotalMoney,'goodsTotalNum'=>$goodsTotalNum];
	}
	/**
	 * 计算订单金额
	 */
	public function getCartMoney(){
		$data = ['shops'=>[],'totalMoney'=>0,'totalGoodsMoney'=>0];
		$areaId = input('post.areaId2/d',-1);
		//计算各店铺运费及金额
		$deliverType = (int)input('deliverType');
		$carts = $this->getCarts();
		$deliverType = ($carts['goodsType']==1)?1:$deliverType;
		$shopFreight = 0;
		//判断是否包邮
		if($carts['carts']['isFreeShipping']){
			$shopFreight = 0;
		}else{
			$shopFreight = ($deliverType==1)?0:WSTOrderFreight($carts['carts']['shopId'],$areaId);
		}
	
		$data['shops']['freight'] = $shopFreight;
		$data['shops']['shopId'] = $carts['carts']['shopId'];
		$data['shops']['goodsMoney'] = $carts['carts']['goodsMoney'];
		$data['totalGoodsMoney'] = $carts['carts']['goodsMoney'];
		$data['totalMoney'] += $carts['carts']['goodsMoney'] + $shopFreight;
		$data['useScore'] = 0;
		$data['scoreMoney'] = 0;
		//计算积分
		$isUseScore = (int)input('isUseScore');
		if($isUseScore==1){
			$userId = (int)session('WST_USER.userId');
			$useScore = (int)input('useScore');
			$user = model('common/users')->getFieldsById($userId,'userScore');
			if($useScore>$user['userScore'])$useScore = $user['userScore'];
			$moneyToScore = WSTScoreToMoney($data['totalGoodsMoney'],true);
			if($useScore>$moneyToScore)$useScore = $moneyToScore;
			$money = WSTScoreToMoney($useScore);
			$data['useScore'] = $useScore;
			$data['scoreMoney'] = $money;
		}
		$data['realTotalMoney'] = $data['totalMoney'] - $data['scoreMoney'];
		return WSTReturn('',1,$data);
	}
	/**
	 * 下单
	 */
	public function submit($orderSrc = 0){
		//检测购物车
		$carts = $this->getCarts();
		$userId = (int)session('WST_USER.userId');
		if($userId==0)return WSTReturn('您尚未登录系统，请先登录系统');
		if(empty($carts['carts']))return WSTReturn("请选择要购买的商品");
		//检测时间过了没有
		$time = time();
		if(strtotime($carts['carts']['goods']['startTime']) > $time)return WSTReturn('对不起，砍价活动尚未开始');
		if(strtotime($carts['carts']['goods']['endTime']) < $time)return WSTReturn('很抱歉，您来晚了，砍价活动已结束');
		$checkNum = $carts['carts']['goods']['goodsStock']-$carts['carts']['goods']['orderNum'];
		if($checkNum<$carts['goodsTotalNum'])return WSTReturn("下单商品失败，商品剩余库存为".$checkNum);
		//是否已下过单
		$m = new Bargains();
		$rs = $m->checkBargain($userId,$carts['carts']['bargainId']);
		if(!empty($rs['orderId']))return WSTReturn("下单失败，你已经购买过了", -1);
		if($carts['goodsType']==1){
			return $this->submitByVirtual($carts,$orderSrc);
		}else{
			return $this->submitByEntity($carts,$orderSrc);
		}
	}
	/**
	 * 虚拟商品下单
	 */
	public function submitByVirtual($carts,$orderSrc = 0){
		$addressId = 0;
		$isInvoice = ((int)input('post.isInvoice')!=0)?1:0;
		$invoiceClient = ($isInvoice==1)?input('post.invoiceClient'):'';
		$payType = 1;
		$userId = (int)session('WST_USER.userId');
		$isUseScore = (int)input('isUseScore');
		$useScore = (int)input('useScore');
		//计算出订单应该分配的金额和积分
		$scoreMoney = model('common/orders')->getOrderScoreMoney($isUseScore,$useScore);
		//生成订单
		Db::startTrans();
		try{
			$goods = $carts['carts']['goods'];
			$carts = $carts['carts'];
			//给用户分配卡券
			$cards = model('common/GoodsVirtuals')->where(['goodsId'=>$goods['goodsId'],'dataFlag'=>1,'shopId'=>$goods['shopId'],'isUse'=>0])->lock(true)->limit($goods['cartNum'])->select();
			if(count($cards)<$goods['cartNum'])return WSTReturn("下单失败，砍价商品库存不足");
			//修改库存
			Db::name('goods')->where('goodsId',$goods['goodsId'])->update([
				'goodsStock'=>['exp','goodsStock-'.$goods['cartNum']],
				'saleNum'=>['exp','saleNum+'.$goods['cartNum']]
			]);
			$orderunique = WSTOrderQnique();
				
			$orderNo = WSTOrderNo();
			$orderScore = 0;
			//创建订单
			$order = [];
			$order['orderNo'] = $orderNo;
			$order['userId'] = $userId;
			$order['orderType'] = 1;
			$order['areaId'] = 0;
			$order['userName'] = '';
			$order['userAddress'] = '';
			$order['shopId'] = $carts['shopId'];
			$order['payType'] = $payType;
			$order['goodsMoney'] = $carts['goodsMoney'];
			//计算运费和总金额
			$order['deliverType'] = 1;
			$order['deliverMoney'] = 0;
			$order['totalMoney'] = $order['goodsMoney'];
			//积分支付-计算分配积分和金额
			$order['scoreMoney'] = 0;
			$order['useScore'] = 0;
			if($scoreMoney['useMoney']>0){
				$order['scoreMoney'] = $scoreMoney['useMoney'];
				$order['useScore'] = $scoreMoney['useScore'];
			}
			//实付金额要减去积分兑换的金额
			$order['realTotalMoney'] = $order['totalMoney'] - $order['scoreMoney'];
			$order['needPay'] = $order['realTotalMoney'];
			$order['orderCode'] = 'bargain';
			$order['orderCodeTargetId'] = $carts['bargainId'];
			$order['extraJson'] = json_encode(['bargainId'=>$carts['bargainId']]);
			if($order['needPay']>0){
				$order['orderStatus'] = -2;//待付款
				$order['isPay'] = 0;
			}else{
				$order['orderStatus'] = 0;//待发货
				$order['isPay'] = 1;
			}
			//积分
			$orderScore = 0;
			//如果开启下单获取积分则有积分
			if(WSTConf('CONF.isOrderScore')==1){
				$orderScore = WSTMoneyGiftScore($order['goodsMoney']);
			}
			$order['orderScore'] = $orderScore;
			$order['isInvoice'] = $isInvoice;
			$order['invoiceJson'] = model('common/invoices')->getInviceInfo((int)input('param.invoiceId'));// 发票信息
			$order['invoiceClient'] = $invoiceClient;
			$order['orderRemarks'] = input('post.remark_'.$carts['shopId']);
			$order['orderunique'] = $orderunique;
			$order['orderSrc'] = $orderSrc;
			$order['dataFlag'] = 1;
			$order['payRand'] = 1;
			$order['createTime'] = date('Y-m-d H:i:s');
			$m = model('common/orders');
			$result = $m->data($order,true)->isUpdate(false)->allowField(true)->save($order);
			if(false !== $result){
				$orderId = $m->orderId;
				//标记虚拟卡券为占用状态
				$goodsCards = [];
				foreach ($cards as $key => $card) {
					$card->isUse = 1;
					$card->orderId = $orderId;
					$card->orderNo = $orderNo;
					$card->save();
					$goodsCards[] = ['cardId'=>$card->id];
				}
				$goods = $carts['goods'];
				//创建订单商品记录
				$orderGgoods = [];
				$orderGoods['orderId'] = $orderId;
				$orderGoods['goodsType'] = 1;
				$orderGoods['goodsId'] = $goods['goodsId'];
				$orderGoods['goodsNum'] = $goods['cartNum'];
				$orderGoods['goodsPrice'] = $goods['shopPrice'];
				$orderGoods['goodsSpecId'] = $goods['goodsSpecId'];
				if(!empty($goods['specNames'])){
					$specNams = [];
					foreach ($goods['specNames'] as $pkey =>$spec){
						$specNams[] = $spec['catName'].'：'.$spec['itemName'];
					}
					$orderGoods['goodsSpecNames'] = implode('@@_@@',$specNams);
				}else{
					$orderGoods['goodsSpecNames'] = '';
				}
				$orderGoods['goodsName'] = $goods['goodsName'];
				$orderGoods['goodsImg'] = $goods['goodsImg'];
				$orderGoods['commissionRate'] = WSTGoodsCommissionRate($goods['goodsCatId']);
				$orderGoods['extraJson'] = json_encode($goodsCards);
				Db::name('order_goods')->insert($orderGoods);
                //计算订单佣金
				$commissionFee = 0;
				if((float)$orderGoods['commissionRate']>0){
					$commissionFee += round($orderGoods['goodsPrice']*1*$orderGoods['commissionRate']/100,2);
				}
				model('common/orders')->where('orderId',$orderId)->update(['commissionFee'=>$commissionFee]);

				//修改砍价数量
				Db::name('bargains')->where('bargainId',$carts['bargainId'])->update([
					'orderNum'=>['exp','orderNum+'.$goods['cartNum']],
					'goodsStock'=>['exp','goodsStock-'.$goods['cartNum']]
				]);
				//添加订单号
				Db::name('bargain_users')->where(['bargainId'=>$carts['bargainId'],'userId'=>$userId])->update(['orderId'=>$orderId,'orderNo'=>$orderNo]);
				//创建积分流水--如果有抵扣积分就肯定是开启了支付支付
				if($order['useScore']>0){
					$score = [];
					$score['userId'] = $userId;
					$score['score'] = $order['useScore'];
					$score['dataSrc'] = 1;
					$score['dataId'] = $orderId;
					$score['dataRemarks'] = "交易订单【".$orderNo."】使用积分".$order['useScore']."个";
					$score['scoreType'] = 0;
					model('common/UserScores')->add($score);
				}
	
				//建立订单记录
				$logOrder = [];
				$logOrder['orderId'] = $orderId;
				$logOrder['orderStatus'] = -2;
				$logOrder['logContent'] = "下单成功，等待用户支付";
				$logOrder['logUserId'] = $userId;
				$logOrder['logType'] = 0;
				$logOrder['logTime'] = date('Y-m-d H:i:s');
				Db::name('log_orders')->insert($logOrder);
				if($payType==1 && $order['needPay']==0){
					$logOrder = [];
					$logOrder['orderId'] = $orderId;
					$logOrder['orderStatus'] = 0;
					$logOrder['logContent'] = "订单已支付，下单成功";
					$logOrder['logUserId'] = $userId;
					$logOrder['logType'] = 0;
					$logOrder['logTime'] = date('Y-m-d H:i:s');
					Db::name('log_orders')->insert($logOrder);
				}
				$tpl = WSTMsgTemplates('ORDER_SUBMIT');
				if($tpl['tplContent']!='' && $tpl['status']=='1'){
					$find = ['${ORDER_NO}'];
					$replace = [$orderNo];
					
					$msg = array();
		            $msg["shopId"] = $carts['shopId'];
		            $msg["tplCode"] = $tpl["tplCode"];
		            $msg["msgType"] = 1;
		            $msg["content"] = str_replace($find,$replace,$tpl['tplContent']);
		            $msg["msgJson"] = ['from'=>1,'dataId'=>$orderId];
		            model("common/MessageQueues")->add($msg);
				}
				//判断是否需要发送管理员短信
	            $tpl = WSTMsgTemplates('PHONE_ADMIN_SUBMIT_ORDER');
	            if((int)WSTConf('CONF.smsOpen')==1 && (int)WSTConf('CONF.smsSubmitOrderTip')==1 && $tpl['tplContent']!='' && $tpl['status']=='1'){
					$params = ['tpl'=>$tpl,'params'=>['ORDER_NO'=>$orderNo]];
					$staffs = Db::name('staffs')->where([['staffId','in',explode(',',WSTConf('CONF.submitOrderTipUsers'))],['staffStatus','=',1],['dataFlag','=',1]])->field('staffPhone')->select();
					for($i=0;$i<count($staffs);$i++){
						if($staffs[$i]['staffPhone']=='')continue;
						$m = new LogSms();
				        $rv = $m->sendAdminSMS(0,$staffs[$i]['staffPhone'],$params,'submitByVirtual','');
				    }
	            }
				//微信消息
				if((int)WSTConf('CONF.wxenabled')==1){
					$params = [];
					$params['ORDER_NO'] = $orderNo;
					$params['ORDER_TIME'] = date('Y-m-d H:i:s');
					$goodsNames = $goods['goodsName']."*".$goods['cartNum'];
					$params['GOODS'] = $goodsNames;
					$params['MONEY'] = $order['realTotalMoney'] + $order['scoreMoney'];
					$params['ADDRESS'] = $order['userAddress']." ".$order['userName'];
					$params['PAY_TYPE'] = WSTLangPayType($order['payType']);
					
				    $msg = array();
					$tplCode = "WX_ORDER_SUBMIT";
					$msg["shopId"] = $carts['shopId'];
		            $msg["tplCode"] = $tplCode;
		            $msg["msgType"] = 4;
		            $msg["paramJson"] = ['CODE'=>$tplCode,'URL'=>Url('wechat/orders/sellerorder','',true,true),'params'=>$params];
		            $msg["msgJson"] = "";
		            model("common/MessageQueues")->add($msg);
				    //判断是否需要发送给管理员消息
		            if((int)WSTConf('CONF.wxSubmitOrderTip')==1){
		                $params = [];
						$params['ORDER_NO'] = $orderNo;
						$params['ORDER_TIME'] = date('Y-m-d H:i:s');
						$goodsNames = $goods['goodsName']."*".$goods['cartNum'];
						$params['GOODS'] = $goodsNames;
						$params['MONEY'] = $order['realTotalMoney'] + $order['scoreMoney'];
						$params['ADDRESS'] = $order['userAddress']." ".$order['userName'];
						$params['PAY_TYPE'] = WSTLangPayType($order['payType']);
			            WSTWxBatchMessage(['CODE'=>'WX_ADMIN_ORDER_SUBMIT','userType'=>3,'userId'=>explode(',',WSTConf('CONF.submitOrderTipUsers')),'params'=>$params]);
		            }
				}
				//已付款的虚拟商品
				if($order['needPay']==0){
					model('common/orders')->handleVirtualGoods($orderId);
				}
			}
			Db::commit();
			//删除session的购物车商品
			session('BARGAIN_CARTS',null);
			return WSTReturn("提交订单成功", 1,$orderunique);
		}catch (\Exception $e) {
			Db::rollback();
			return WSTReturn('提交订单失败',-1);
		}
	}
	/**
	 * 实物商品下单
	 */
	public function submitByEntity($carts,$orderSrc = 0){
		$addressId = (int)input('post.s_addressId');
		$deliverType = ((int)input('post.deliverType')!=0)?1:0;
		$isInvoice = ((int)input('post.isInvoice')!=0)?1:0;
		$invoiceClient = ($isInvoice==1)?input('post.invoiceClient'):'';
		$payType = ((int)input('post.payType')!=0)?1:0;
		$userId = (int)session('WST_USER.userId');
		$isUseScore = (int)input('isUseScore');
		$useScore = (int)input('useScore');

		if($deliverType==0){// 配送方式为快递，必须有用户地址
			//检测地址是否有效
			$address = Db::name('user_address')->where(['userId'=>$userId,'addressId'=>$addressId,'dataFlag'=>1])->find();
			if(empty($address)){
				return WSTReturn("无效的用户地址");
			}
			$areaIds = [];
			$areaMaps = [];
			$tmp = explode('_',$address['areaIdPath']);
			$address['areaId2'] = $tmp[1];//记录配送城市
			foreach ($tmp as $vv){
				if($vv=='')continue;
				if(!in_array($vv,$areaIds))$areaIds[] = $vv;
			}
			if(!empty($areaIds)){
				$areas = Db::name('areas')->where([['areaId','in',$areaIds],['dataFlag','=',1]])->field('areaId,areaName')->select();
				foreach ($areas as $v){
					$areaMaps[$v['areaId']] = $v['areaName'];
				}
				$tmp = explode('_',$address['areaIdPath']);
				$areaNames = [];
				foreach ($tmp as $vv){
					if($vv=='')continue;
					$areaNames[] = $areaMaps[$vv];
					$address['areaName'] = implode('',$areaNames);
				}
			}
			$address['userAddress'] = $address['areaName'].$address['userAddress'];
			WSTUnset($address, 'isDefault,dataFlag,createTime,userId');
		}else{
			$address = [];
			$address['areaId'] = 0;
			$address['userName'] = '';
			$address['userAddress'] = '';
		}
		//计算出订单应该分配的金额和积分
		$scoreMoney = model('common/orders')->getOrderScoreMoney($isUseScore,$useScore);
		//生成订单
		Db::startTrans();
		try{
			$orderunique = WSTOrderQnique();
			$carts = $carts['carts'];
			$orderNo = WSTOrderNo();
			$orderScore = 0;
			//创建订单
			$order = [];
			$order = array_merge($order,$address);
			$order['orderNo'] = $orderNo;
			$order['userId'] = $userId;
			$order['shopId'] = $carts['shopId'];
			$order['payType'] = $payType;
			$order['goodsMoney'] = $carts['goodsMoney'];
			//计算运费和总金额
			$order['deliverType'] = $deliverType;
			if($carts['isFreeShipping']){
				$order['deliverMoney'] = 0;
			}else{
				$order['deliverMoney'] = ($deliverType==1)?0:WSTOrderFreight($carts['shopId'],$order['areaId2']);
			}
			$order['totalMoney'] = $order['goodsMoney']+$order['deliverMoney'];
			//积分支付-计算分配积分和金额
			$order['scoreMoney'] = 0;
			$order['useScore'] = 0;
			if($scoreMoney['useMoney']>0){
				$order['scoreMoney'] = $scoreMoney['useMoney'];
				$order['useScore'] = $scoreMoney['useScore'];
			}
			//实付金额要减去积分兑换的金额
			$order['realTotalMoney'] = $order['totalMoney'] - $order['scoreMoney'];
			$order['needPay'] = $order['realTotalMoney'];
			$order['orderCode'] = 'bargain';
			$order['orderCodeTargetId'] = $carts['bargainId'];
			$order['extraJson'] = json_encode(['bargainId'=>$carts['bargainId']]);
			if($payType==1){
				if($order['needPay']>0){
					$order['orderStatus'] = -2;//待付款
					$order['isPay'] = 0;
				}else{
					$order['orderStatus'] = 0;//待发货
					$order['isPay'] = 1;
				}
			}else{
				$order['orderStatus'] = 0;//待发货
				if($order['needPay']==0)$order['isPay'] = 1;
			}
			//积分
			$orderScore = 0;
			//如果开启下单获取积分则有积分
			if(WSTConf('CONF.isOrderScore')==1){
				$orderScore = WSTMoneyGiftScore($order['goodsMoney']);
			}
			$order['orderScore'] = $orderScore;
			$order['isInvoice'] = $isInvoice;
			$order['invoiceJson'] = model('common/invoices')->getInviceInfo((int)input('param.invoiceId'));// 发票信息
			$order['invoiceClient'] = $invoiceClient;
			$order['orderRemarks'] = input('post.remark_'.$carts['shopId']);
			$order['orderunique'] = $orderunique;
			$order['orderSrc'] = $orderSrc;
			$order['dataFlag'] = 1;
			$order['payRand'] = 1;
			$order['createTime'] = date('Y-m-d H:i:s');
			$m = model('common/orders');
			$result = $m->data($order,true)->isUpdate(false)->allowField(true)->save($order);
			if(false !== $result){
				$orderId = $m->orderId;
				$goods = $carts['goods'];
				//创建订单商品记录
				$orderGgoods = [];
				$orderGoods['orderId'] = $orderId;
				$orderGoods['goodsId'] = $goods['goodsId'];
				$orderGoods['goodsNum'] = $goods['cartNum'];
				$orderGoods['goodsPrice'] = $goods['shopPrice'];
				$orderGoods['goodsSpecId'] = $goods['goodsSpecId'];
				if(!empty($goods['specNames'])){
					$specNams = [];
					foreach ($goods['specNames'] as $pkey =>$spec){
						$specNams[] = $spec['catName'].'：'.$spec['itemName'];
					}
					$orderGoods['goodsSpecNames'] = implode('@@_@@',$specNams);
				}else{
					$orderGoods['goodsSpecNames'] = '';
				}
				$orderGoods['goodsName'] = $goods['goodsName'];
				$orderGoods['goodsImg'] = $goods['goodsImg'];
				$orderGoods['commissionRate'] = WSTGoodsCommissionRate($goods['goodsCatId']);
				Db::name('order_goods')->insert($orderGoods);
                //计算订单佣金
				$commissionFee = 0;
				if((float)$orderGoods['commissionRate']>0){
					$commissionFee += round($orderGoods['goodsPrice']*1*$orderGoods['commissionRate']/100,2);
				}
				model('common/orders')->where('orderId',$orderId)->update(['commissionFee'=>$commissionFee]);

				//修改砍价数量
				Db::name('bargains')->where('bargainId',$carts['bargainId'])->update([
					'orderNum'=>['exp','orderNum+'.$goods['cartNum']],
					'goodsStock'=>['exp','goodsStock-'.$goods['cartNum']]
				]);
				//添加订单号
				Db::name('bargain_users')->where(['bargainId'=>$carts['bargainId'],'userId'=>$userId])->update(['orderId'=>$orderId,'orderNo'=>$orderNo]);
				//创建积分流水--如果有抵扣积分就肯定是开启了支付支付
				if($order['useScore']>0){
					$score = [];
					$score['userId'] = $userId;
					$score['score'] = $order['useScore'];
					$score['dataSrc'] = 1;
					$score['dataId'] = $orderId;
					$score['dataRemarks'] = "交易订单【".$orderNo."】使用积分".$order['useScore']."个";
					$score['scoreType'] = 0;
					model('common/UserScores')->add($score);
				}
	
				//建立订单记录
				$logOrder = [];
				$logOrder['orderId'] = $orderId;
				$logOrder['orderStatus'] = ($payType==1 && $order['needPay']==0)?-2:$order['orderStatus'];
				$logOrder['logContent'] = ($payType==1)?"下单成功，等待用户支付":"下单成功";
				$logOrder['logUserId'] = $userId;
				$logOrder['logType'] = 0;
				$logOrder['logTime'] = date('Y-m-d H:i:s');
				Db::name('log_orders')->insert($logOrder);
				if($payType==1 && $order['needPay']==0){
					$logOrder = [];
					$logOrder['orderId'] = $orderId;
					$logOrder['orderStatus'] = 0;
					$logOrder['logContent'] = "订单已支付，下单成功";
					$logOrder['logUserId'] = $userId;
					$logOrder['logType'] = 0;
					$logOrder['logTime'] = date('Y-m-d H:i:s');
					Db::name('log_orders')->insert($logOrder);
				}
				//给店铺增加提示消息
				$tpl = WSTMsgTemplates('ORDER_SUBMIT');
				if($tpl['tplContent']!='' && $tpl['status']=='1'){
					$find = ['${ORDER_NO}'];
					$replace = [$orderNo];
					//WSTSendMsg($carts['userId'],str_replace($find,$replace,$tpl['tplContent']),['from'=>1,'dataId'=>$orderId]);
					$msg = array();
		            $msg["shopId"] = $carts['shopId'];
		            $msg["tplCode"] = $tpl["tplCode"];
		            $msg["msgType"] = 1;
		            $msg["content"] = str_replace($find,$replace,$tpl['tplContent']);
		            $msg["msgJson"] = ['from'=>1,'dataId'=>$orderId];
		            model("common/MessageQueues")->add($msg);
				}
				//判断是否需要发送管理员短信
	            $tpl = WSTMsgTemplates('PHONE_ADMIN_SUBMIT_ORDER');
	            if((int)WSTConf('CONF.smsOpen')==1 && (int)WSTConf('CONF.smsSubmitOrderTip')==1 && $tpl['tplContent']!='' && $tpl['status']=='1'){
					$params = ['tpl'=>$tpl,'params'=>['ORDER_NO'=>$orderNo]];
					$staffs = Db::name('staffs')->where([['staffId','in',explode(',',WSTConf('CONF.submitOrderTipUsers'))],['staffStatus','=',1],['dataFlag','=',1]])->field('staffPhone')->select();
					for($i=0;$i<count($staffs);$i++){
						if($staffs[$i]['staffPhone']=='')continue;
						$m = new LogSms();
				        $rv = $m->sendAdminSMS(0,$staffs[$i]['staffPhone'],$params,'submitByVirtual','');
				    }
	            }
				//微信消息
				if((int)WSTConf('CONF.wxenabled')==1){
					$params = [];
					$params['ORDER_NO'] = $orderNo;
					$params['ORDER_TIME'] = date('Y-m-d H:i:s');
					$goodsNames = $goods['goodsName']."*".$goods['cartNum'];
					$params['GOODS'] = $goodsNames;
					$params['MONEY'] = $order['realTotalMoney'] + $order['scoreMoney'];
					$params['ADDRESS'] = $order['userAddress']." ".$order['userName'];
					$params['PAY_TYPE'] = WSTLangPayType($order['payType']);
					
				    $msg = array();
					$tplCode = "WX_ORDER_SUBMIT";
					$msg["shopId"] = $carts['shopId'];
		            $msg["tplCode"] = $tplCode;
		            $msg["msgType"] = 4;
		            $msg["paramJson"] = ['CODE'=>$tplCode,'URL'=>Url('wechat/orders/sellerorder','',true,true),'params'=>$params];
		            $msg["msgJson"] = "";
		            model("common/MessageQueues")->add($msg);
				    //判断是否需要发送给管理员消息
		            if((int)WSTConf('CONF.wxSubmitOrderTip')==1){
		                $params = [];
						$params['ORDER_NO'] = $orderNo;
						$params['ORDER_TIME'] = date('Y-m-d H:i:s');
						$goodsNames = $goods['goodsName']."*".$goods['cartNum'];
						$params['GOODS'] = $goodsNames;
						$params['MONEY'] = $order['realTotalMoney'] + $order['scoreMoney'];
						$params['ADDRESS'] = $order['userAddress']." ".$order['userName'];
						$params['PAY_TYPE'] = WSTLangPayType($order['payType']);
			            WSTWxBatchMessage(['CODE'=>'WX_ADMIN_ORDER_SUBMIT','userType'=>3,'userId'=>explode(',',WSTConf('CONF.submitOrderTipUsers')),'params'=>$params]);
		            }
				}
			}
			Db::commit();
			//删除session的购物车商品
			session('BARGAIN_CARTS',null);
			return WSTReturn("提交订单成功", 1,$orderunique);
		}catch (\Exception $e) {
			Db::rollback();
			return WSTReturn('提交订单失败',-1);
		}
	}
}
