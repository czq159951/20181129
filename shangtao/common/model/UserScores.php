<?php
namespace shangtao\common\model;
use think\Db;
/**
 * 积分业务处理器
 */
class UserScores extends Base{
	protected $pk = 'scoreId';
     /**
      * 获取列表
      */
      public function pageQuery($userId){
      	  $type = (int)input('post.type');
          $where = ['userId'=>(int)$userId];
          if($type!=-1)$where['scoreType'] = $type;
          $page = $this->where($where)->order('scoreId desc')->paginate()->toArray();
          foreach ($page['data'] as $key => $v){
          	  $page['data'][$key]['dataSrc'] = WSTLangScore($v['dataSrc']);
          }
          return $page;
      }

      /**
       * 新增记录
       */
      public function add($score,$isAddTotal = false){
      	$score['createTime'] = date('Y-m-d H:i:s');
      	$this->create($score);
      	$user = model('common/users')->get($score['userId']);
      	if($score['scoreType']==1){
      		$user->userScore = $user->userScore + $score['score'];
      		if($isAddTotal)$user->userTotalScore = $user->userTotalScore+$score['score'];
      	}else{
      		$user->userScore = $user->userScore - $score['score'];
      	}
      	$userinfo = session('WST_USER');
      	$userinfo['userScore'] = $user->userScore;
      	session('WST_USER',$userinfo);
      	$user->save();
      }
      
      /**
       *签到获得积分
       */
      public function signScore($userId){
      	$time = date('Y-m-d');
      	$frontTime = date("Y-m-d",strtotime("-1 day"));
      	if(WSTConf('CONF.signScoreSwitch')==0)return WSTReturn("签到失败");
      	$userscores = $this->where(["userId"=>$userId,"dataSrc"=>5,])->order('createTime desc')->find();
      	if(!$userscores || date("Y-m-d",strtotime($userscores['createTime']))!=$time){
      		$rs = Db::name('users')->where(["userId"=>$userId])->field('userScore')->find();
      		$days = $score = 0;
      		$days = (date("Y-m-d",strtotime($userscores['createTime']))==$frontTime)?($userscores['dataId']==30)?$userscores['dataId']:$userscores['dataId']+1:1;
      		$signScore = explode(",",WSTConf('CONF.signScore'));
      		if($signScore[0]!=0){
      			$score = $signScore[$days-1];
      		}
      		$data['totalScore'] = $rs['userScore'] + $score;
      		$data['score'] = $score;
      		if($score>0){
      			//添加
      			$userinfo = session('WST_USER');
      			$userinfo['signScoreTime'] = $time;
      			session('WST_USER',$userinfo);
      			$uscore = [];
      			$uscore['userId'] = $userId;
      			$uscore['score'] = $score;
      			$uscore['dataSrc'] = 5;
      			$uscore['dataId'] = $days;
      			$uscore['dataRemarks'] = "连续".$days."天签到，获得积分".$score."个";
      			$uscore['scoreType'] = 1;
      			$this->add($uscore,true);
      			return WSTReturn("签到第".$days."天，获得".$score."个积分",1,$data);
      		}else{
      			return WSTReturn("签到失败");
      		}
      	}else{
      		return WSTReturn("已签到，明天再来");
      	}
      }
}
