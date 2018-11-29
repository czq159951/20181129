<?php
namespace shangtao\app\model;
use think\Db;
/**
 * 提现流水业务处理器
 */
use shangtao\common\model\CashDraws as M;
class CashDraws extends Base{
     protected $pk = 'cashId';
     /**
      * 获取列表
      */
      public function pageQuery($targetType,$targetId){
      	  $type = (int)input('post.type',-1);
          $where = [];
          $where['targetType'] = (int)$targetType;
          $where['targetId'] = (int)$targetId;
          if(in_array($type,[0,1]))$where['moneyType'] = $type;
          return $this->field(['createTime'],true)
                      ->where($where)
                      ->order('cashId desc')
                      ->paginate()
                      ->toArray();
      }

      /**
       * 申请提现
       */
      public function drawMoney(){
          $userId = $this->getUserId();
          $m = new M();
          return $m->drawMoney($userId);
      }
}
