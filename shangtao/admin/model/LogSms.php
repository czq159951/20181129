<?php
namespace shangtao\admin\model;
use think\Db;
/**
 * 短信日志类
 */
class LogSms extends Base{
	protected $pk = 'smsId';

	/**
	 * 写入并发送短讯记录
	 */
	public function sendSMS($smsSrc,$userId,$phoneNumber,$params,$smsFunc){
		if((int)WSTConf('CONF.smsOpen')==0)return WSTReturn('未开启短信接口');
		$data = [];
		$data['smsSrc'] = $smsSrc;
		$data['smsUserId'] = $userId;
		$data['smsPhoneNumber'] = $phoneNumber;
		$data['smsContent'] = 'N/A';
		$data['smsReturnCode'] = '';
		$data['smsFunc'] = $smsFunc;
		$data['smsIP'] = request()->ip();
		$data['createTime'] = date('Y-m-d H:i:s');
		$this->data($data)->save();
		$rdata = ['msg'=>'短信发送失败!','status'=>-1];
		hook('sendSMS',['phoneNumber'=>$phoneNumber,"params"=>$params,'smsId'=>$this->smsId,'status'=>&$rdata]);
		return $rdata;
	}
	
	public function pageQuery(){
		$startDate = input('startDate');
		$endDate = input('endDate');
		$phone = input('phone');
		$where = [];
		if($startDate!='')$where[] = ['l.createTime','>=',$startDate." 00:00:00"];
		if($endDate!='')$where[] = [' l.createTime','<=',$endDate." 23:59:59"];
		if($phone!='')$where[] = [' l.smsPhoneNumber','like',"%".$phone."%"];
		return Db::name('log_sms')->alias('l')->join('__STAFFS__ s',' l.smsUserId=s.staffId','left')
			->where($where)
			->field('l.*,s.staffName')
			->order('l.smsId', 'desc')->paginate(input('limit/d'));
	}
}
