<?php
namespace shangtao\admin\model;
use think\Db;
/**
 * 微信被动回复业务处理
 */
class WxPassiveReplys extends Base{
	/**
     * 文本/消息分页
     */
    public function pageQuery($msgType = '',$dataSrc = 0){
        $where = ['dataFlag'=>1,'dataSrc'=>$dataSrc];
        if($msgType!='')$where['msgType'] =$msgType;
        return $this->where($where)->field(true)->order('id desc')->paginate(input('limit/d'));
    }

    public function pagSubscribeQuery($isSubscribe = 1){
        $where = ['dataFlag'=>1,'isSubscribe'=>$isSubscribe];
        return $this->where($where)->field(true)->order('subscribeSort asc,id desc')->paginate(input('limit/d'));
    }

    public function getById($id){
        return $this->get(['id'=>$id,'dataFlag'=>1]);
    }



    /**
     * 新增
     */
    public function add(){
        $data = input('post.');
        $data['createTime'] = date('Y-m-d H:i:s');
        WSTUnset($data,'id');
       
        $result = $this->allowField(true)->save($data);
        if(false !== $result){
            return WSTReturn("新增成功", 1);
        }
        return WSTReturn($this->getError(), -1);

    }
    /**
     * 编辑
     */
    public function edit(){
        $Id = (int)input('post.id');
        $data = input('post.');
        WSTUnset($data,'createTime,id');
        $result = $this->allowField(true)->save($data,['id'=>$Id]);
        if(false !== $result){
            return WSTReturn("编辑成功", 1);
        }
        return WSTReturn('编辑失败',-1);
    }
    /**
     * 删除
     */
    public function del(){
        $id = (int)input('post.id/d');
        $data = [];
        $data['dataFlag'] = -1;
        $result = $this->update($data,['id'=>$id]);
        if(false !== $result){
            return WSTReturn("删除成功", 1);
        }
        return WSTReturn('删除失败',-1);
    }

    /**
     * 删除关注回复
     */
    public function delSubscribe(){
        $id = (int)input('post.id/d');
        $obj = $this->get($id);
        if($obj->dataSrc==0){
            $obj->isSubscribe = 0;
        }else{
            $obj->dataFlag = -1;
        }
        $result = $obj->save();
        if(false !== $result){
            return WSTReturn("删除成功", 1);
        }
        return WSTReturn('删除失败',-1);
    }

    /**
     * 选择素材
     */
    public function selectSubscribe(){
        $id = (int)input('post.id/d');
        $data = [];
        $data['isSubscribe'] = 1;
        $result = $this->update($data,['id'=>$id]);
        if(false !== $result){
            return WSTReturn("操作成功", 1);
        }
        return WSTReturn('操作失败');
    }
}
