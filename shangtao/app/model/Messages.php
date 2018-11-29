<?php
namespace shangtao\app\model;
use shangtao\app\model\Shops;
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
         	$page['data'][$key]['msgContent'] = str_replace(['&nbsp;',"\n","\r","\t"],' ',trim(strip_tags(htmlspecialchars_decode($v['msgContent']))));
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
        if(!empty($data)){
          $data['msgContent'] = htmlspecialchars_decode($data['msgContent']);
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
        $result = $this->update($data,[['id','in',$ids],['receiveUserId','=',$userId]]);
        if(false !== $result){
            return WSTReturn("删除成功", 1);
        }else{
            return WSTReturn($this->getError(),-1);
        }
    }

    
}
