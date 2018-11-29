<?php
namespace shangtao\admin\model;
use think\Db;
/**
 * 积分流水日志业务处理
 */
class UserScores extends Base{
    protected $pk = 'scoreId';

	/**
	 * 获取用户信息
	 */
	public function getUserInfo(){
		$id = (int)input('id');
        return model('users')->where('userId',$id)->field('loginName,userId,userName')->find();
	}

    /**
	 * 分页
	 */
	public function pageQuery(){
		$userId = input('id');
		$startDate = input('startDate');
		$endDate = input('endDate');
		if($startDate!='')$where[] = ['createTime','>=',$startDate." 00:00:00"];
		if($endDate!='')$where[] = [' createTime','<=',$endDate." 23:59:59"];
		$where[] = ['userId','=',$userId];
		$page = $this->where($where)->order('scoreId', 'desc')->paginate(input('limit/d'))->toArray();
		if(count($page['data'])>0){
			foreach ($page['data'] as $key => $v) {
				$page['data'][$key]['dataSrc'] = WSTLangScore($v['dataSrc']);
			}
		}
		return $page;
	}

	/**
     * 新增记录
     */
    public function addByAdmin(){
    	$data = [];
    	$data['userId'] = (int)input('userId');
    	$data['score'] = (int)input('score');
        $data['dataSrc'] = 10001;
        $data['dataId'] = 0;
        $data['scoreType'] = (int)input('scoreType');
        $data['dataRemarks'] = input('dataRemarks');
        $data['createTime'] = date('Y-m-d H:i:s');
        //判断用户身份
        $user = model('users')->where(['userId'=>$data['userId'],'dataFlag'=>1])->find();
        if(empty($user))return WSTReturn('无效的会员');
        if(!in_array($data['scoreType'],[0,1]))return WSTReturn('无效的调节类型');
        if($data['score']<=0)return WSTReturn('调节积分必须大于0');
        Db::startTrans();
		try{
            $result = $this->insert($data);
            if(false !== $result){
            	if($data['scoreType']==1){
                    $user->userScore = $user->userScore+$data['score'];
                    $user->userTotalScore = $user->userTotalScore+$data['score'];
            	}else{
            		$user->userScore = $user->userScore-$data['score'];
            	}
            	$user->save();
            }
            Db::commit();
			return WSTReturn('操作成功',1);
		}catch (\Exception $e) {
			Db::rollback();
			return WSTReturn('操作失败',-1);
		}
    }
}
