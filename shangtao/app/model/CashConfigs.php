<?php
namespace shangtao\app\model;
use shangtao\common\validate\CashConfigs as Validate;
/**
 * 提现账号业务处理器
 */
class CashConfigs extends Base{
     /**
      * 获取列表
      */
      public function pageQuery($targetType,$targetId){
      	  $type = (int)input('post.type',-1);
          $where = [];
          $where['targetType'] = (int)$targetType;
          $where['targetId'] = (int)$targetId;
          $where['c.dataFlag'] = 1;
          if(in_array($type,[0,1]))$where['moneyType'] = $type;
          $page = $this->alias('c')->join('__BANKS__ b','c.accTargetId=b.bankId')->where($where)->field('b.bankName,c.*')->order('c.id desc')->select();
          if(count($page)>0){
              foreach($page as $key => $v){
                  // 删除无用字段
                  unset($v['dataFlag']);
                  unset($v['createTime']);
              }
          }
          return $page;
      }
      /**
       * 获取列表
       */
      public function listQuery($targetType,$targetId){
          $where = [];
          $where['targetType'] = (int)$targetType;
          $where['targetId'] = (int)$targetId;
          $where['dataFlag'] = 1;
          return $this->where($where)->field('id,accNo,accUser')->select();
      }
      /**
       * 获取资料
       */
      public function getById($id){
          $config = $this->get($id);
          $areas = model('areas')->getParentIs($config['accAreaId']);
          $config['accAreaIdPath'] = implode('_',$areas)."_";
          return $config;
      }
      /**
       * 新增卡号
       */
      public function add(){
          $data = input('post.');
          $data['targetType'] = 0;
          $data['targetId'] = $this->getUserId();
          $data['accType'] = 3; 
          $data['userId'] = $this->getUserId();
          $data['createTime'] = date('Y-m-d H:i:s');
          WSTUnset($data,'id');
          $validate = new validate();
         if(!$validate->scene('add')->check($data))return WSTReturn($validate->getError());
          $result = $this->allowField(true)->save($data);
          if(false !== $result){
              return WSTReturn("新增成功", 1,['id'=>$this->id]);
          }else{
              return WSTReturn($this->getError(),-1);
          }
      }
      /**
       * 编辑卡号
       */
      public function edit(){
          $id = (int)input('id');
          $data = input('post.');
          $userId = $this->getUserId();
          WSTUnset($data,'id,targetType,targetId,accType,createTime');
          $validate = new validate();
         if(!$validate->scene('edit')->check($data))return WSTReturn($validate->getError());
          $result = $this->allowField(true)->save($data,['id'=>$id,'targetId'=>$userId]);
          if(false !== $result){
              return WSTReturn("编辑成功", 1);
          }else{
              return WSTReturn($this->getError(),-1);
          }
      }
      /**
       *  删除提现账号
       */
      public function del(){
         $userId = $this->getUserId();
         $object = $this->get(['id'=>(int)input('id'),'targetId'=>$userId]);
         if($object==null)return WSTReturn('操作失败',-1);
         $object->dataFlag = -1;
         $result = $object->save();
         if(false !== $result){
            return WSTReturn('操作成功',1);
         }
         return WSTReturn('操作失败',-1);
      }
}
