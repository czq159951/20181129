<?php
namespace shangtao\admin\model;
use think\Db;
/**
 * 登录日志业务处理
 */
class LogStaffLogins extends Base{
    /**
	 * 分页
	 */
	public function pageQuery(){
		$startDate = input('startDate');
		$endDate = input('endDate');
		$where = [];
		if($startDate!='')$where[] = ['l.loginTime','>=',$startDate." 00:00:00"];
		if($endDate!='')$where[] = [' l.loginTime','<=',$endDate." 23:59:59"];
		return $mrs = Db::name('log_staff_logins')->alias('l')->join('__STAFFS__ s',' l.staffId=s.staffId','left')
			->where($where)
			->field('l.*,s.staffName')
			->order('l.loginId', 'desc')->paginate(input('limit/d'));
			
	}
}
