<?php
namespace addons\bargain\model;
use think\addons\BaseModel as Base;
use think\Db;
/**
 * 全民砍价活动插件-管理员端
 */
class Admin extends Base{
	/**
	 * 管理员查看砍价活动列表
	 */
	public function pageQuery($grouponStatus){
		$goodsName = input('goodsName');
		$shopName = input('shopName');
		$areaIdPath = input('areaIdPath');
		$goodsCatIdPath = input('goodsCatIdPath');
		$where[] = ['b.dataFlag','=',1];
		$where[] = ['bargainStatus','=',$grouponStatus];
		if($goodsName !='')$where[] = ['g.goodsName','like','%'.$goodsName.'%'];
		if($shopName !='')$where[] = ['s.shopName|s.shopSn','like','%'.$shopName.'%'];
		if($areaIdPath !='')$where[] = ['s.areaIdPath','like',$areaIdPath."%"];
		if($goodsCatIdPath !='')$where[] = ['g.goodsCatIdPath','like',$goodsCatIdPath."%"];
        $page =  Db::name('bargains')->alias('b')
                   ->join('__GOODS__ g','g.goodsId=b.goodsId and g.isSale=1 and g.dataFlag=1','inner')
                   ->join('__SHOPS__ s','s.shopId=b.shopId','left')
                   ->where($where)
                   ->field('g.goodsName,b.*,g.goodsImg,s.shopId,s.shopName')
                   ->order('b.updateTime desc')
                   ->paginate(input('limit/d'))->toArray();
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
        return $page;
	}

	/**
	* 设置商品违规状态
	*/
	public function illegal(){
		$illegalRemarks = input('post.illegalRemarks');		
		$id = (int)input('post.id');
		if($illegalRemarks=='')return WSTReturn("请输入违规原因");
		//判断商品状态
		$rs = Db::name('bargains')->alias('b')
		           ->join('__SHOPS__ s','b.shopId=s.shopId','left')
		           ->join('__GOODS__ g','g.goodsId=b.goodsId')
		           ->where('bargainId',$id)
		           ->field('b.bargainId,b.shopId,s.userId,g.goodsName,b.bargainStatus,b.goodsId')->find();
		if((int)$rs['bargainId']==0)return WSTReturn("无效的商品");
		if((int)$rs['bargainStatus']==-1)return WSTReturn("操作失败，商品状态已发生改变，请刷新后再尝试");
		Db::startTrans();
		try{
			$res = Db::name('bargains')->where('bargainId',$id)->update(['bargainStatus'=>-1,'illegalRemarks'=>$illegalRemarks]);
			if($res!==false){
				//发送一条商家信息
				$tpl = WSTMsgTemplates('BARGAIN_GOODS_REJECT');
		        if($tpl['tplContent']!='' && $tpl['status']=='1'){
		            $find = ['${GOODS}','${TIME}','${REASON}'];
		            $replace = [$rs['goodsName'],date('Y-m-d H:i:s'),$illegalRemarks];
		           
		            $msg = array();
		            $msg["shopId"] = $rs['shopId'];
		            $msg["tplCode"] = $tpl["tplCode"];
		            $msg["msgType"] = 1;
		            $msg["content"] = str_replace($find,$replace,$tpl['tplContent']);
		            $msg["msgJson"] = ['from'=>7,'dataId'=>$id];
		            model("common/MessageQueues")->add($msg);
		        }
		        if((int)WSTConf('CONF.wxenabled')==1){
					$params = [];
					$params['GOODS'] = $rs['goodsName'];
					$params['TIME'] = date('Y-m-d H:i:s'); 
					$params['REASON'] = $illegalRemarks;          
					
					$msg = array();
					$tplCode = "WX_BARGAIN_GOODS_REJECT";
					$msg["shopId"] = $rs['shopId'];
		            $msg["tplCode"] = $tplCode;
		            $msg["msgType"] = 4;
		            $msg["paramJson"] = ['CODE'=>$tplCode,'params'=>$params];
		            $msg["msgJson"] = "";
		            model("common/MessageQueues")->add($msg);
				}
				Db::commit();
				return WSTReturn('操作成功',1);
			}
		}catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('操作失败',-1);
	}
   /**
	* 通过商品审核
	*/
	public function allow(){	
		$id = (int)input('post.id');
		//判断商品状态
		$rs = Db::name('bargains')->alias('b')
		           ->join('__SHOPS__ s','b.shopId=s.shopId','left')
		           ->join('__GOODS__ g','g.goodsId=b.goodsId')
		           ->where('bargainId',$id)
		           ->field('b.bargainId,b.shopId,s.userId,g.goodsName,b.bargainStatus,b.goodsId')->find();
		if((int)$rs['bargainId']==0)return WSTReturn("无效的商品");
		if((int)$rs['bargainStatus']!=0)return WSTReturn("操作失败，商品状态已发生改变，请刷新后再尝试");
		Db::startTrans();
		try{
			$res = Db::name('bargains')->where('bargainId',$id)->update(['bargainStatus'=>1]);
			if($res!==false){
				//发送一条商家信息
				$tpl = WSTMsgTemplates('BARGAIN_GOODS_ALLOW');
		        if($tpl['tplContent']!='' && $tpl['status']=='1'){
		            $find = ['${GOODS}','${TIME}'];
		            $replace = [$rs['goodsName'],date('Y-m-d H:i:s')];
		            
		            $msg = array();
		            $msg["shopId"] = $rs['shopId'];
		            $msg["tplCode"] = $tpl["tplCode"];
		            $msg["msgType"] = 1;
		            $msg["content"] = str_replace($find,$replace,$tpl['tplContent']);
		            $msg["msgJson"] = ['from'=>7,'dataId'=>$id];
		            model("common/MessageQueues")->add($msg);
		        } 
		        if((int)WSTConf('CONF.wxenabled')==1){
					$params = [];
					$params['GOODS'] = $rs['goodsName'];
					$params['TIME'] = date('Y-m-d H:i:s');          
					
					$msg = array();
					$tplCode = "WX_BARGAIN_GOODS_ALLOW";
					$msg["shopId"] = $rs['shopId'];
		            $msg["tplCode"] = $tplCode;
		            $msg["msgType"] = 4;
		            $msg["paramJson"] = ['CODE'=>$tplCode,'params'=>$params];
		            $msg["msgJson"] = "";
		            model("common/MessageQueues")->add($msg);
				}
				Db::commit();
				return WSTReturn('操作成功',1);
			}
		}catch (\Exception $e) {
            Db::rollback();
        }
        return WSTReturn('操作失败',-1);
	}

    /**
	 * 删除砍价
	 */
	public function del(){
		$id = (int)input('id');
        Db::startTrans();
        try{
        	Db::name('bargains')->where('bargainId',$id)->update(['dataFlag'=>-1]);
            Db::commit();
        }catch (\Exception $e) {
            Db::rollback();
        }
		
        return WSTReturn('删除成功',1);
	}

	/**
	 * 获取参与者记录
	 */
	public function pageyByJoins(){
		$key = input('key');
		$where = [];
		if($key!='')$where[] = ['u.loginName','like','%'.$key.'%'];
		$where[] = ['bu.bargainId','=',(int)input('bargainId')];
		return Db::name('bargain_users')->alias('bu')
		         ->join('__BARGAINS__ b','b.bargainId=bu.bargainId','inner')
		         ->join('__USERS__ u','u.userId=bu.userId')
                 ->where($where)
                 ->field('bu.*,u.userName,u.userPhoto,b.startPrice,u.loginName')
                 ->order('bu.createTime desc')
                 ->paginate(input('limit/d'))->toArray();
	}

	/**
	 * 获取亲友团列表
	 */
    public function pageByHelps(){
		$where = [];
		$where['bargainJoinId'] = (int)input('bargainJoinId');
		return Db::name('bargain_helps')
                 ->where($where)
                 ->order('createTime desc')
                 ->paginate(input('limit/d'))->toArray();
	}
}
