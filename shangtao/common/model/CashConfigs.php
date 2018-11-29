<?php
namespace shangtao\common\model;
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
          $page = $this->alias('c')->join('__BANKS__ b','c.accTargetId=b.bankId')->where($where)->field('b.bankName,c.*')->order('c.id desc')->paginate()->toArray();
          if(count($page['data'])>0){
              foreach($page['data'] as $key => $v){
                  $areas = model('areas')->getParentNames($v['accAreaId']);
                  $page['data'][$key]['areaName'] = implode('',$areas);
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
          $userId = (int)session('WST_USER.userId');
          $config = $this->where([['id','=',(int)$id],['dataFlag','=',1],['targetId','=',$userId]])->find();
          $areas = model('areas')->getParentIs($config['accAreaId']);
          $config['accAreaIdPath'] = implode('_',$areas)."_";
          return $config;
      }
      /**
       * 新增卡号
       */
      public function add(){
          $data = input('post.');
          unset($data['id']);
          $data['targetType'] = 0;
          $data['targetId'] = (int)session('WST_USER.userId');
          $data['accType'] = 3; 
          $data['createTime'] = date('Y-m-d H:i:s');
          WSTUnset($data,'id');
          $validate = new Validate;
          if (!$validate->scene('add')->check($data)) {
          	  return WSTReturn($validate->getError());
          }else{
          	  $result = $this->allowField(true)->save($data);
          }
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
          $userId = (int)session('WST_USER.userId');
          WSTUnset($data,'id,targetType,dataFlag,targetId,accType,createTime');
          $validate = new Validate;
          if (!$validate->scene('edit')->check($data)) {
          	return WSTReturn($validate->getError());
          }else{
          	$result = $this->allowField(true)->save($data,['id'=>$id,'targetId'=>$userId]);
          }
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
         $userId = (int)session('WST_USER.userId');
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
