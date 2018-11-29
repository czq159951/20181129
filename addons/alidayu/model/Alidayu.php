<?php
namespace addons\alidayu\model;
use think\addons\BaseModel as Base;
use think\Db;
/**
 * 阿里大于短信接口
 */
class Alidayu extends Base{
	public function getConfigs(){
		$data = cache('alidayu_sms');
		if(!$data){
			$rs = Db::name('addons')->where('name','Alidayu')->field('config')->find();
		    $data =  json_decode($rs['config'],true);
		    cache('alidayu_sms',$data,31622400);
		}
		return $data;
	}

	public function install(){
		Db::startTrans();
		try{
			$hooks = ['sendSMS'];
			$this->bindHoods("Alidayu", $hooks);
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
			$this->unbindHoods("Alidayu", $hooks);
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
		$resp ='{"code":"-1001708","msg":"短信发送失败"}';
		try{
			$smsConf = $this->getConfigs();
	        include WST_ADDON_PATH."alidayu/sdk/TopSdk.php";
	        $c = new \TopClient;
			$c->appkey = $smsConf['smsKey'];
			$c->secretKey = $smsConf['smsPass'];
			$req = new \AlibabaAliqinFcSmsNumSendRequest;
			$req->setSmsType("normal");
			$req->setSmsFreeSignName($smsConf["signature"]);
			$req->setSmsParam($params['content']);
			$req->setRecNum($params['phoneNumber']);
			$req->setSmsTemplateCode($smsConf[$params['params']['tpl']['tplCode']]);
			$resp = $c->execute($req);
	        return $resp;
        }catch (\Exception $e) {
        	$resp ='{"code":"-1001708","msg":"'.$e->getMessage().'"}';
	    }
        return json_decode($resp);
	}

	public function sendSMS($params){
		$smsConf = $this->getConfigs();
		$code = [];
		foreach($params['params']['params'] as $key =>$v){
			$code[] = '"'.$key.'":"'.$v.'"';
		}
        $codes = "{".implode(',',$code)."}";
        $params['content'] = $codes;
		$code = $this->http($params);
		$log = model('common/logSms')->get(['smsId'=>$params['smsId']]);
		$log->smsReturnCode = json_encode($code);
		$log->smsContent = $codes."||".$params['params']['tpl']['tplCode']."||".$smsConf[$params['params']['tpl']['tplCode']];
		$log->save();
		try{
			if($code->result->success){
	            $params['status']['msg'] = '短信发送成功!';
	            $params['status']['status'] = 1;
			}
		}catch (\Exception $e) {
            $params['status']['msg'] = '短信发送失败!';
	        $params['status']['status'] = -1;
		}
	}
}
