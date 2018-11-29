<?php
namespace addons\webchinese\model;
use think\addons\BaseModel as Base;
use think\Db;
/**
 * 中国网建短信接口
 */
class Webchinese extends Base{
	public function getConfigs(){
		$data = cache('webchinese_sms');
		if(!$data){
			$rs = Db::name('addons')->where('name','Webchinese')->field('config')->find();
		    $data =  json_decode($rs['config'],true);
		    cache('webchinese_sms',$data,31622400);
		}
		return $data;
	}
 
    public function install(){
		Db::startTrans();
		try{
			$hooks = ['sendSMS'];
			$this->bindHoods("Webchinese", $hooks);
			Db::commit();
			return true;
		}catch (\Exception $e) {
	 		Db::rollback();
	  		return false;
	   	}
	}
	public function uninstall(){
		Db::startTrans();
		try{
			$hooks = ['sendSMS'];
			$this->unbindHoods("Webchinese", $hooks);
			Db::commit();
			return true;
		}catch (\Exception $e) {
	 		Db::rollback();
	  		return false;
	   	}
	}
	/**
	 * 发送短信接口
	 */
	public function http($params){
		$data = '-1001708';
		try{
			$smsConf = $this->getConfigs();
			$url = 'http://utf8.sms.webchinese.cn/?Uid='.$smsConf['smsKey'].'&Key='.$smsConf['smsPass'].'&smsMob='.$params['phoneNumber'].'&smsText='.$params['content'];
	        $ch=curl_init($url);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置否输出到页面
	        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30 ); //设置连接等待时间
	        curl_setopt($ch, CURLOPT_ENCODING, "gzip" );
	        $data=curl_exec($ch);
	        curl_close($ch);
	        return $data;
	    }catch (\Exception $e) {
			$data = '-1001708';
	    }
        return $data;
	}
	public function sendSMS($params){
		$smsConf = $this->getConfigs();
		$tpl = $params['params']['tpl']['tplContent'];
		foreach($params['params']['params'] as $key =>$v){
			$tpl = str_replace('${'.$key."}",$v,$tpl);
		}

		$params['content'] = $tpl."【".$smsConf["signature"]."】";
		$code = $this->http($params);
		$log = model('common/logSms')->get($params['smsId']);
		$log->smsReturnCode = $code;
		$log->smsContent = $params['content'];
		$log->save();
		if(intval($code)>0){
	        $params['status']['msg'] = '短信发送成功!';
	        $params['status']['status'] = 1;
		}else{
			$params['status']['msg'] = '短信发送失败!';
	        $params['status']['status'] = -1;
		}
	}
}
