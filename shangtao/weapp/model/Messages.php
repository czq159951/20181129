<?php
namespace shangtao\weapp\model;
use shangtao\weapp\model\Shops;
/**
 * 商城消息
 */
class Messages extends Base{
   /**
    * 获取列表
    */
    public function pageQuery(){
         $userId = $this->getUserId();
         $where = ['receiveUserId'=>(int)$userId,'dataFlag'=>1];
         $page = model('Messages')->where($where)
                                  ->field('id,msgContent,msgStatus')
                                  ->order('msgStatus asc,id desc')
                                  ->paginate()
                                  ->toArray();
         foreach ($page['data'] as $key => $v){
         	$page['data'][$key]['msgContent'] = WSTMSubstr(strip_tags(htmlspecialchars_decode($v['msgContent'])),0,140);
         	$page['data'][$key]['status'] = 0;
         }
         return $page;
    }
   /**
    *  获取某一条消息详情
    */
    public function getById(){
    	$userId = $this->getUserId();
        $id = (int)input('msgId');
        $data = $this->field('createTime,msgContent,msgStatus')->where(['id'=>$id,'receiveUserId'=>$userId,'dataFlag'=>1])->find();
        $data['msgContent'] = str_replace("/shangtao/upload","http://localhost/shangtao/upload",htmlspecialchars_decode($data['msgContent']));
        if(!empty($data)){
          if($data['msgStatus']==0)
            model('Messages')->where('id',$id)->setField('msgStatus',1);
        }
        return $data;
    }
    /**
    * 批量删除
    */
    public function batchDel(){
    	$userId = $this->getUserId();
        $ids = input('ids');
        $data = [];
        $data['dataFlag'] = -1;
        $where[] = ['id','in',$ids];
        $where[] = ['receiveUserId','=',$userId];
        $result = $this->where($where)->update($data);
        if(false !== $result){
            return jsonReturn("删除成功", 1);
        }else{
            return jsonReturn($this->getError(),-1);
        }
    }

    
}
