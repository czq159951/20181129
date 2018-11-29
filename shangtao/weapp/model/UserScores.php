<?php
namespace shangtao\weapp\model;
use think\Db;
/**
 * 积分业务处理器
 */
class UserScores extends Base{
     /**
      * 获取列表
      */
      public function pageQuery($userId){
      	  $type = (int)input('post.type');
          $where = ['userId'=>(int)$userId];
          if($type!=-1)$where['scoreType'] = $type;
          $page = $this->where($where)->order('scoreId desc')->select();
          foreach ($page as $key => $v){
          	  $page[$key]['dataSrc'] = WSTLangScore($v['dataSrc']);
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
          $user->save();
      }
      public function isSign(){
        $userId = $this->getUserId();
        $createTime = Db::name('user_scores')->where(["userId"=>$userId,"dataSrc"=>5])->order('createTime desc')->value('createTime');
        if(empty($createTime))return false;
        if(date('Y-m-d',strtotime($createTime)) == date('Y-m-d')){
          // 签到获得积分的最后日期与当前日期相同
          return true;
        }
        return false;
      }
}
