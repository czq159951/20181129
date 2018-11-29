<?php
namespace shangtao\admin\model;
/**
 * 商城配置业务处理
 */
use think\Db;
class Styles extends Base{
	/**
	 * 获取分类
	 */
	public function getCats(){
		return $this->distinct(true)->field('styleSys')->select();
	}
	/**
	 * 获取风格列表
	 */
	public function listQuery(){
		$styleSys = input('styleSys');
		$rs = $this->where('styleSys',$styleSys)->select();
		return ['sys'=>$styleSys,'list'=>$rs];
	}
	/**
	 * 初始化风格列表
	 */
	public function initStyles(){
		$styles = $this->field('styleSys,stylePath,id')->select();
        $sys = [];
		foreach ($styles as $key => $v) {
			$sys[$v['styleSys']][$v['stylePath']] = 1;
			//删除不存在的风格记录
			if(!is_dir(WSTRootPath(). DS .'shangtao'. DS .$v['styleSys']. DS .'view'. DS.$v['stylePath'])){
				$this->where('id',$v['id'])->delete();
			}
		}
		Db::startTrans();
        try{
        	//添加不存在的风格目录
        	$prefix = config('database.prefix');
			foreach ($sys as $key => $v) {
			    $dirs = array_map('basename',glob(WSTRootPath(). DS .'shangtao'.DS.$key.DS.'view'.DS.'*', GLOB_ONLYDIR));
		        foreach ($dirs as $dkey => $dv) {
		        	if(!isset($v[$dv])){
		        		$sqlPath = WSTRootPath(). DS .'shangtao'. DS .$key. DS .'view'. DS .$dv. DS.'sql'.DS.'install.sql';// sql路径
						$hasFile = file_exists($sqlPath);
						if(!$hasFile)continue;
						$sql = file_get_contents($sqlPath);
						$this->excute($sql,$prefix);
		        	}
		        }
		    }
		    Db::commit();
		}catch (\Exception $e) {
            Db::rollback();
        }
	}
	
    /**
	 * 编辑
	 */
	public function changeStyle(){
		 $id = (int)input('post.id');
		 $object = $this->get($id);
		 // styleSys   stylePath

		 $sqlPath = WSTRootPath(). DS .'shangtao'. DS .$object['styleSys']. DS .'view'. DS .$object['stylePath']. DS.'sql'.DS.'style.sql';// sql路径
		 $hasFile = file_exists($sqlPath);
		 if(!$hasFile)return WSTReturn('风格sql文件不存在,请联系管理员');
		 $sql = file_get_contents($sqlPath);

		 $positionArr = ['home'=>1,'wechat'=>2,'mobile'=>3];
		 if(!isset($positionArr[$object['styleSys']]))return WSTReturn('风格目录出错,请联系管理员');
		 $positionType = $positionArr[$object['styleSys']];// 1:PC版 2:微信 3:手机版
		 
		 Db::startTrans();
         try{
         	 $prefix = config('database.prefix');
         	 /* 删除与风格无关广告 */
			 $delAds = "delete from `".$prefix."ads` where positionType='".$positionType."';";
			 // 若为pc版,则保留商家入驻广告位置
			 if($positionType==1){
			 	// 获取当前商家入驻广告位置的主键id
			 	$positionId = Db::name('ad_positions')->where(['positionCode'=>'ads-shop-apply'])->value('positionId');
			 	$delAds = "delete from `".$prefix."ads` where positionType='".$positionType."' and adPositionId!=$positionId;";
			 }
			 Db::execute($delAds);

			 /*  删除无关广告位 */
			 $delAdPosition = "delete from `".$prefix."ad_positions` where positionType='".$positionType."';";
			 // 若为pc版,则保留商家入驻广告
			 if($positionType==1){
			 	$delAdPosition = "delete from `".$prefix."ad_positions` where positionType='".$positionType."' and positionCode not in ('ads-shop-apply');";
			 }
			 Db::execute($delAdPosition);
         	 $flag = $this->excute($sql,$prefix);
         	 if($flag===false)throw new \Exception("风格sql执行出错", 1);


		     $rs = $this->where('styleSys',$object['styleSys'])->update(['isUse'=>0]);
		     if(false !== $rs){
		         $object->isUse = 1;
		         $object->save();
		         cache('WST_CONF',null);
		         Db::commit();
		         return WSTReturn('操作成功',1);
		     }
		}catch (\Exception $e) {
            Db::rollback();
            return WSTReturn("操作失败");
        }
    }

    /**
    * 执行sql
    */
    private function excute($sql,$db_prefix=''){
		if(!isset($sql) || empty($sql)) return;
		$sql = str_replace("\r", " ", str_replace('`wst_', '`'.$db_prefix, $sql));// 替换表前缀
		$ret = array();
		foreach(explode(";\n", trim($sql)) as $query) {
            Db::execute(trim($query));
		}
	}
	
}
