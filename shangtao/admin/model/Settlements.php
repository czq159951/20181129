<?php 
namespace shangtao\admin\model;
use think\Db;
use think\Loader;
use Env;
/**
 * 结算业务处理
 */
class Settlements extends Base{
    protected $pk = 'settlementId';
    /**
	 * 获取结算列表
	 */
	public function pageQuery(){
        $where = [];
        $startDate = input('startDate');
		$endDate = input('endDate');
		$shopName = input('shopName');
        $settlementNo = input('settlementNo');
		$settlementStatus = (int)input('settlementStatus',-1);
		$sort = input('sort');
        $where = [];
        if($startDate!='')$where[] = ['st.createTime','>=',$startDate.' 00:00:00'];
        if($endDate!='')$where[] = ['st.createTime','<=',$endDate.' 23:59:59'];
		if($settlementNo!='')$where[] = ['settlementNo','like','%'.$settlementNo.'%'];
        if($shopName!='')$where[] = ['shopName|shopSn','like','%'.$shopName.'%']; 
        if($settlementStatus>=0)$where[] = ['settlementStatus','=',$settlementStatus];
        $order = 'st.settlementId desc';
        if($sort){
        	$sortArr = explode('.',$sort);
        	$order = $sortArr[0].' '.$sortArr[1];
        	if($sortArr[0]=='settlementNo'){
        		$order = $sortArr[0].'+0 '.$sortArr[1];
        	}
        }
		return Db::name('settlements')->alias('st')->join('__SHOPS__ s','s.shopId=st.shopId','left')->where($where)->field('s.shopName,settlementNo,settlementId,settlementMoney,commissionFee,backMoney,settlementStatus,settlementTime,st.createTime')->order($order)
			->paginate(input('limit/d'))->toArray();
	}

	/**
	 * 获取结算订单详情
	 */
	public function getById(){
        $settlementId = (int)input('id');
        $object =  Db::name('settlements')->alias('st')->where('settlementId',$settlementId)->join('__SHOPS__ s','s.shopId=st.shopId','left')->field('s.shopName,st.*')->find();
        if(!empty($object)){
        	$object['list'] = Db::name('orders')->where(['settlementId'=>$settlementId])
        	          ->field('orderId,orderNo,payType,goodsMoney,deliverMoney,realTotalMoney,totalMoney,commissionFee,scoreMoney,createTime')
        	          ->order('payType desc,orderId desc')->select();
        }
        return $object;
	}
	/**
	 * 处理订单
	 */
	public function handle(){
		$id = (int)input('settlementId');
		$remarks = input('remarks');
		Db::startTrans();
        try{
			$object = $this->get($id);
			$object->settlementStatus = 1;
			$object->settlementTime = date('Y-m-d H:i:s');
			if($remarks!='')$object->remarks = $remarks;
			$rs = $object->save();
			if(false !== $rs){
				$shop = model('Shops')->get($object->shopId);
				WSTSendMsg($shop['userId'],"您的结算申请【".$object->settlementNo."】已处理，请留意到账户息哦~",['from'=>4,'dataId'=>$id]);
				$shop->shopMoney = $shop->shopMoney+$object->backMoney;
				$shop->paymentMoney = $shop->paymentMoney + $object->commissionFee;
				$shop->save();
                $lmarr = [];
				//增加资金变动信息
                if($object->settlementMoney>0){
                    $lm = [];
                    $lm['targetType'] = 1;
                    $lm['targetId'] = $object->shopId;
                    $lm['dataId'] = $id;
                    $lm['dataSrc'] = 2;
                    $lm['remark'] = '结算订单申请【'.$object->settlementNo.'】收入订单金额¥'.$object->settlementMoney;
                    $lm['moneyType'] = 1;
                    $lm['money'] = $object->settlementMoney;
                    $lm['payType'] = 0;
                    $lm['createTime'] = date('Y-m-d H:i:s');
                    $lmarr[] = $lm;
                }
                if($object->commissionFee>0){
                    //要对有积分支付的佣金记录进行处理
                    $commissionFee = $object->commissionFee;
                    //如果backMoney小于0则说明平台收到的钱不足以支付佣金，这个backMoney已经减去了积分支付，所以直接显示backMoney为应付的佣金就好
                    if($object->backMoney<0){
                        $commissionFee = $object->backMoney;
                    }
                    $lm = [];
                    $lm['targetType'] = 1;
                    $lm['targetId'] = $object->shopId;
                    $lm['dataId'] = $id;
                    $lm['dataSrc'] = 2;
                    $lm['remark'] = '结算订单申请【'.$object->settlementNo.'】支出订单佣金¥'.$commissionFee."。".(($object->remarks!='')?"【操作备注】：".$object->remarks:'');
                    $lm['moneyType'] = 0;
                    $lm['money'] = $commissionFee;
                    $lm['payType'] = 0;
                    $lm['createTime'] = date('Y-m-d H:i:s');
                    $lmarr[] = $lm;
                }
				if(count($lmarr)>0)model('LogMoneys')->saveAll($lmarr);
				Db::commit();
				return WSTReturn('操作成功!',1);
			}
		}catch (\Exception $e) {
            Db::rollback();
        }
		return WSTReturn('操作失败!',-1);
	}

	/**
	 * 获取订单商品
	 */
	public function pageGoodsQuery(){
        $id = (int)input('id');
        return Db::name('orders')->alias('o')->join('__ORDER_GOODS__ og','o.orderId=og.orderId')->where('o.settlementId',$id)
        ->field('orderNo,og.goodsPrice,og.goodsName,og.goodsSpecNames,og.goodsNum,og.commissionRate')->order('o.payType desc,o.orderId desc')->paginate(input('limit/d'))->toArray();
    }

    /**
     * 获取待结算商家
     */
    public function pageShopQuery(){
    	$areaIdPath = input('areaIdPath');
    	$shopName = input('shopName');
    	if($shopName!='')$where[] = ['s.shopName|s.shopSn','like','%'.$shopName.'%'];
    	if($areaIdPath !='')$where[] = ['s.areaIdPath','like',$areaIdPath."%"];
    	$where[] = ['s.dataFlag','=',1];
    	$where[] = ['s.noSettledOrderNum','>',0];
		return Db::table('__SHOPS__')->alias('s')->join('__AREAS__ a2','s.areaId=a2.areaId')
		       ->where($where)
		       ->field('shopId,shopSn,shopName,a2.areaName,shopkeeper,telephone,abs(noSettledOrderFee) noSettledOrderFee,noSettledOrderNum')
		       ->order('noSettledOrderFee desc')->paginate(input('limit/d'));

	}

   /**
    * 获取商家未结算的订单
    */
   public function pageShopOrderQuery(){
   	     $orderNo = input('orderNo');
   	     $payType = (int)input('payType',-1);
         $where[] = ['settlementId','=',0];
         $where[] = ['orderStatus','=',2];
         $where[] = ['shopId','=',(int)input('id')];
         $where[] = ['dataFlag','=',1];
         if($orderNo!='')$where[] = ['orderNo','like','%'.$orderNo.'%'];
         if(in_array($payType,[0,1]))$where[] = ['payType','=',$payType];
   	     $page = Db::name('orders')->where($where)
        	          ->field('orderId,orderNo,payType,goodsMoney,deliverMoney,realTotalMoney,totalMoney,commissionFee,createTime')
        	          ->order('payType desc,orderId desc')->paginate(input('limit/d'))->toArray();
        if(count($page['data'])>0){
        	foreach ($page['data'] as $key => $v) {
        		$page['data'][$key]['payTypeName'] = WSTLangPayType($v['payType']);
        	}
        }
        return $page;
   }

   /**
    * 生成结算单
    */
	public function generateSettleByShop(){
		$shopId = (int)input('id');
		$where[] = ['shopId','=',$shopId];
		$where[] = ['dataFlag','=',1];
		$where[] = ['orderStatus','=',2];
		$where[] = ['settlementId','=',0];
		$orders = Db::name('orders')->where($where)->field('orderId,payType,realTotalMoney,scoreMoney,commissionFee')->select();
    	if(empty($orders))return WSTReturn('没有需要结算的订单，请刷新后再核对!');
    	$settlementMoney = 0;
        $commissionFee = 0;    //平台要收的佣金
        $ids = [];
    	foreach ($orders as $key => $v) {
            $ids[] = $v['orderId'];
    		if($v['payType']==1){
                $settlementMoney += $v['realTotalMoney']+$v['scoreMoney'];
            }else{
                $settlementMoney += $v['scoreMoney'];
            }
            $commissionFee += abs($v['commissionFee']);
    	}
    	$backMoney = $settlementMoney-$commissionFee;
    	$shops = model('shops')->get($shopId);
    	if(empty($shops))WSTReturn('无效的店铺结算账号!');
    	Db::startTrans();
		try{
            $data = [];
            $data['settlementType'] = 0;
            $data['shopId'] = $shopId;
            $data['settlementMoney'] = $settlementMoney;
            $data['commissionFee'] = $commissionFee;
            $data['backMoney'] = $settlementMoney-$commissionFee;
            $data['settlementStatus'] = 1;
            $data['settlementTime'] = date('Y-m-d H:i:s');
            $data['createTime'] = date('Y-m-d H:i:s');
            $data['settlementNo'] = '';
            $result = $this->save($data);
            if(false !==  $result){
            	$this->settlementNo = $this->settlementId.(fmod($this->settlementId,7));
            	$this->save();
            	//修改商家订单情况
                Db::name('orders')->where([['orderId','in',$ids]])->update(['settlementId'=>$this->settlementId]);
                $shops->shopMoney = $shops->shopMoney + $backMoney;
                $shops->noSettledOrderNum = 0;
                $shops->noSettledOrderFee = 0;
                $shops->paymentMoney = 0;
                //修改商家充值金额
                $lockCashMoney = (($shops->rechargeMoney - $commissionFee)>=0)?($shops->rechargeMoney - $commissionFee):0;
                $shops->rechargeMoney = $lockCashMoney;
                $shops->save();
                
                //发消息
                $tpl = WSTMsgTemplates('SHOP_SETTLEMENT');
                if( $tpl['tplContent']!='' && $tpl['status']=='1'){
                    $find = ['${SETTLEMENT_NO}'];
                    $replace = [$this->settlementNo];
                    
                    $msg = array();
                    $msg["shopId"] = $shopId;
                    $msg["tplCode"] = $tpl["tplCode"];
                    $msg["msgType"] = 1;
                    $msg["content"] = str_replace($find,$replace,$tpl['tplContent']) ;
                    $msg["msgJson"] = ['from'=>4,'dataId'=>$this->settlementId];
                    model("common/MessageQueues")->add($msg);
                }
                //增加资金变动信息
                $lmarr = [];
                if($settlementMoney>0){
                    $lm = [];
                    $lm['targetType'] = 1;
                    $lm['targetId'] = $shopId;
                    $lm['dataId'] = $this->settlementId;
                    $lm['dataSrc'] = 2;
                    $lm['remark'] = '结算订单申请【'.$this->settlementNo.'】收入订单金额¥'.$settlementMoney."。";
                    $lm['moneyType'] = 1;
                    $lm['money'] = $settlementMoney;
                    $lm['payType'] = 0;
                    $lm['createTime'] = date('Y-m-d H:i:s');
                    $lmarr[] = $lm;
                }
                if($commissionFee>0){
    				$lm = [];
    				$lm['targetType'] = 1;
    				$lm['targetId'] = $shopId;
    				$lm['dataId'] = $this->settlementId;
    				$lm['dataSrc'] = 2;
    				$lm['remark'] = '结算订单申请【'.$this->settlementNo.'】收取订单佣金¥'.$commissionFee."。";
    				$lm['moneyType'] = 0;
    				$lm['money'] = $commissionFee;
    				$lm['payType'] = 0;
    				$lm['createTime'] = date('Y-m-d H:i:s');
                    $lmarr[] = $lm;
                }
				if(count($lmarr)>0)model('LogMoneys')->saveAll($lmarr);
				Db::commit();
            	return WSTReturn('生成结算单成功',1);
            }
		}catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('生成结算单失败',-1);
    }
	/**
     * 导出
     */
    public function toExport(){
        $where = [];
        $name='结算申请表';
        $settlementNo = input('settlementNo');
        $startDate = input('startDate');
        $endDate = input('endDate');
        $shopName = input('shopName');
        $settlementStatus = (int)input('settlementStatus',-1);
        $sort = input('sort');
        if($startDate!='')$where[] = ['st.createTime','>=',$startDate.' 00:00:00'];
        if($endDate!='')$where[] = ['st.createTime','<=',$endDate.' 23:59:59'];
        if($settlementNo!='')$where[] = ['settlementNo','like','%'.$settlementNo.'%'];
        if($shopName!='')$where[] = ['shopName|shopSn','like','%'.$shopName.'%']; 
        if($settlementStatus>=0)$where[] = ['settlementStatus','=',$settlementStatus];
        $order = 'st.settlementId desc';
        if($sort){
            $sortArr = explode('.',$sort);
            $order = $sortArr[0].' '.$sortArr[1];
            if($sortArr[0]=='settlementNo'){
                $order = $sortArr[0].'+0 '.$sortArr[1];
            }
        }
        $page = Db::name('settlements')->alias('st')
                ->join('__SHOPS__ s','s.shopId=st.shopId','left')
                ->where($where)
                ->field('s.shopName,settlementNo,settlementId,settlementMoney,commissionFee,backMoney,settlementStatus,settlementTime,st.createTime')
                ->order($order)
                ->select();
       
        require Env::get('root_path') . 'extend/phpexcel/PHPExcel/IOFactory.php';
        $objPHPExcel = new \PHPExcel();
        // 设置excel文档的属性
        $objPHPExcel->getProperties()->setCreator("shangtao")//创建人
        ->setLastModifiedBy("shangtao")//最后修改人
        ->setTitle($name)//标题
        ->setSubject($name)//题目
        ->setDescription($name)//描述
        ->setKeywords("结算")//关键字
        ->setCategory("Test result file");//种类
    
        // 开始操作excel表
        $objPHPExcel->setActiveSheetIndex(0);
        // 设置工作薄名称
        $objPHPExcel->getActiveSheet()->setTitle(iconv('gbk', 'utf-8', 'Sheet'));
        // 设置默认字体和大小
        $objPHPExcel->getDefaultStyle()->getFont()->setName(iconv('gbk', 'utf-8', ''));
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(11);
        $styleArray = array(
                'font' => array(
                        'bold' => true,
                        'color'=>array(
                                'argb' => 'ffffffff',
                        )
                ),
                'borders' => array (
                        'outline' => array (
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,  //设置border样式
                                'color' => array ('argb' => 'FF000000'),     //设置border颜色
                        )
                )
        );
        //设置宽
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);


        $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->getStartColor()->setARGB('333399');
        
        $objPHPExcel->getActiveSheet()->setCellValue('A1', '结算单号')
        ->setCellValue('B1', '申请店铺')->setCellValue('C1', '结算金额')
        ->setCellValue('D1', '结算佣金')->setCellValue('E1', '返还金额')
        ->setCellValue('F1', '申请时间')->setCellValue('G1', '状态');
        $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->applyFromArray($styleArray);
    
        for ($row = 0; $row < count($page); $row++){
            $i = $row+2;
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $page[$row]['settlementNo'])
            ->setCellValue('B'.$i, $page[$row]['shopName'])->setCellValue('C'.$i, '￥'.$page[$row]['settlementMoney'])
            ->setCellValue('D'.$i, '￥'.$page[$row]['commissionFee'])->setCellValue('E'.$i, '￥'.$page[$row]['backMoney'])
            ->setCellValue('F'.$i, $page[$row]['createTime'])->setCellValue('G'.$i, $page[$row]['settlementStatus']==1?'已结算':'未结算');
        }
    
        //输出EXCEL格式
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        // 从浏览器直接输出$filename
        header('Content-Type:application/csv;charset=UTF-8');
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-excel;");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition: attachment;filename="'.$name.'.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
    }
}