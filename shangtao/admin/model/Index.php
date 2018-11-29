<?php 
namespace shangtao\admin\model;
use think\Db;
/**
 * 系统业务处理
 */
class Index extends Base{
	/**
	 * 获取基础统计信息
	 */
	public function summary(){
		$data = [];
		//今日统计
		$data['tody'] = ['userType0'=>0,'userType1'=>0];
		$rs = Db::name('users')->where([['createTime','like',date('Y-m-d').'%'],['dataFlag','=',1]])->group('userType')->field('userType,count(userId) counts')->select();
		$tmp = [];
		if(!empty($rs)){
			foreach ($rs as $key => $v){
				$tmp[$v['userType']] = $v['counts'];
			}
		}
		if(isset($tmp['0']))$data['tody']['userType0'] = $tmp['0'];
		if(isset($tmp['1']))$data['tody']['userType1'] = $tmp['1'];
		$data['tody']['shopApplys'] = Db::name('shops')->where([['applyTime','like',date('Y-m-d').'%'],['dataFlag','=',1],['applyStatus','=',1]])->count();
		$data['tody']['compalins'] = Db::name('order_complains')->where('complainTime','like',date('Y-m-d').'%')->count();
		$data['tody']['saleGoods'] = Db::name('goods')->where([['dataFlag','=',1],['goodsStatus','=',1],['isSale','=',1],['createTime','like',date('Y-m-d').'%']])->count();
		$data['tody']['auditGoods'] = Db::name('goods')->where([['dataFlag','=',1],['goodsStatus','=',0],['isSale','=',1],['createTime','like',date('Y-m-d').'%']])->count();
		$data['tody']['order'] = Db::name('orders')->where([['dataFlag','=',1],['createTime','like',date('Y-m-d').'%']])->count();
		//商城统计
		$data['mall'] = ['userType0'=>1,'userType1'=>0];
		$rs = Db::name('users')->where('dataFlag','=',1)->group('userType')->field('userType,count(userId) counts')->select();
		$tmp = [];
		if(!empty($rs)){
			foreach ($rs as $key => $v){
				$tmp[$v['userType']] = $v['counts'];
			}
		}
		if(isset($tmp['0']))$data['mall']['userType0'] = $tmp['0'];
		if(isset($tmp['1']))$data['mall']['userType1'] = $tmp['1'];
		$data['mall']['saleGoods'] = Db::name('goods')->where([['dataFlag','=',1],['goodsStatus','=',1],['isSale','=',1]])->count();
		$data['mall']['auditGoods'] = Db::name('goods')->where([['dataFlag','=',1],['goodsStatus','=',0],['isSale','=',1]])->count();
		$data['mall']['order'] = Db::name('orders')->where('dataFlag','=',1)->count();
		$data['mall']['brands'] = Db::name('brands')->where('dataFlag','=',1)->count();
		$data['mall']['appraise'] = Db::name('goods_appraises')->where('dataFlag','=',1)->count();
		$rs = Db::query('select VERSION() as sqlversion');
		$data['MySQL_Version'] = $rs[0]['sqlversion'];
		$data['time']['startDate'] = date('Y-m-d',strtotime("-1month"));
        $data['time']['endDate'] = date('Y-m-d');
		return $data;
	}
	
    /**
	 * 保存授权码
	 */
	public function saveLicense(){
		$data = [];
		$data['fieldValue'] = input('license');
	    $result = model('SysConfigs')->where('fieldCode','mallLicense')->update($data);
		if(false !== $result){
			cache('WST_CONF',null);
			return WSTReturn("操作成功",1);
		}
		return WSTReturn("操作失败");
	}
}